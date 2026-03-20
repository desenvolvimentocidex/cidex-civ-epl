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
//trace(Token::check($dados['token']),false, false);
//trace($dados);

$cc_id = $dados['cc_id'];

if(validar($dados, true) && Token::check($dados['token']) && $dados['valor'] == "Cancelar"){
    
    if(validar($cc_id,false,true)){
        
        $cc = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso where cc_id = $cc_id"));
        if($cc){
            foreach($cc as $campo => $valor){$$campo = stripslashes($valor);}
            mysqli_query($con, "insert into cadastro_curso_lixo (cad_id,cc_id,cp_id,crs_id,idm_id,nivel_id,cl_id,cc_date,ccs_id,user_id) values($cad_id,$cc_id,$cp_id,$crs_id,$idm_id,$nivel_id,$cl_id,'$cc_date',3,".$_SESSION["uid"].")");
            mysqli_query($con, "delete from cadastro_curso where cc_id = $cc_id");
        }
        try {
            mysqli_begin_transaction($con);
            mysqli_query($con, "delete from cadastro_curso where cc_id = $cc_id"); //remove a inscricao da tabela 
            mysqli_query($con, "delete from pagamento where cc_id = $cc_id"); //remove pagamento da tabela pagamento
            mysqli_commit($con);
        } catch (mysqli_sql_exception $e) {
            mysqli_rollback($con);
            throw $e;
        }
        
        echo "O cancelamento foi efetuado com sucesso.";
        
    } else {
        echo "Não foi possível realizar a operação, entre em contato com o administrador do Sistema e informe o código - ERRO 003";
    }
} else {
    echo "Não foi possível realizar a operação, entre em contato com o administrador do Sistema e informe o código - ERRO 003";
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

