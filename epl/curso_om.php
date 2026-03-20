<?php
include '../system/system.php';
/*** 
 * ASP Carvalhoza
 * Correção EPL
 * 
 * Função para fazer uma validação básica nas veriárveis 
 * $parametro - conteudo a ser validado
 * $isArray - True para validar um Array
 * 
 * return boleano
 **/

$dados = $_POST;

function validar($parametro, $isArray = false, $isNumeric = false){
    if(isset($parametro) && !is_null($parametro)){
        if($isArray == true){
            if(is_array($parametro)) {
                return true;
            } else {
                return false;
            }
        } 
        if($isNumeric == true){
            if(is_numeric($parametro)) {
                return true;
            } else {
                return false;
            }
        } 
    }
    return false;
}



if(validar($dados, true)){
    
    if(validar($dados['om_id'],false,true) && validar($dados['curso_id'],false,true) &&  validar($dados['ativar'],false,true)){
        // verifica se está cadastrado
        $query = mysqli_query($con, "SELECT * FROM curso_local WHERE om_id = ".$dados['om_id']." and crs_id = ".$dados['curso_id']);
        $num = mysqli_num_rows($query);
        
        if($num == 0){
            $insert = mysqli_query($con, "INSERT INTO curso_local (om_id, crs_id, ativo) VALUES(".$dados['om_id'].",".$dados['curso_id'].",".$dados['ativar'].")");
            
            echo "Dados alterados com sucesso!";
        } else if ($num == 1){
            $result = mysqli_fetch_array($query);
            $update = mysqli_query($con, "UPDATE curso_local SET ativo =".$dados['ativar']." WHERE cl_id = ".$result["cl_id"]);
//        return var_dump("UPDATE curso_local SET ativo =".$dados['ativar']." WHERE cl_id = ".$result["cl_id"]);
            
            echo "Dados atualizados com sucesso!";
        }
        
    } else {
        echo "Não foi possível realizar a operação, entre em contato com o administrador do Sistema e informe o código - ERRO 001";
    }
} else {
    echo "Não foi possível realizar a operação, entre em contato com o administrador do Sistema e informe o código - ERRO 001";
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

