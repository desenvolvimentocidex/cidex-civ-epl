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

if(validar($dados, true) && Token::check($dados['token']) && $dados['valor'] == "Excluir"){
    
    if(validar($cc_id,false,true)){
        $dados_cad_log = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM cadastro_curso WHERE cc_id = $cc_id")); //altera para 0 (aguardando pagamento)
        $dados_pag_log = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM pagamento where cc_id = $cc_id")); //altera para 0 (aguardando pagamento)
        
        try {
            mysqli_begin_transaction($con);
            mysqli_query($con, "update cadastro_curso set ccs_id = 11 where cc_id = $cc_id"); //altera para 0 (aguardando pagamento)
            mysqli_query($con, "delete from pagamento where cc_id = $cc_id"); //remove pagamento da tabela pagamento
            mysqli_commit($con);
        } catch (mysqli_sql_exception $e) {
            mysqli_rollback($con);
            throw $e;
        }
//        logEpl($_SESSION['login'], 'cadastro_curso', 'ccs_id', 'Excluir Pagamento', $dados_cad_log['cad_id'], $dados_cad_log['ccs_id'], '0', $con);
//        foreach ($dados_pag_log as $k => $v) {
//            logEpl($_SESSION['login'], 'pagamento', $k, 'Excluir Pagamento', $dados_cad_log['cad_id'], $v, '', $con);
//        }
        
        echo "O pagamento foi excluído com sucesso.";
        
    } else {
        echo "Não foi possível realizar a operação, entre em contato com o administrador do Sistema e informe o código - ERRO 002";
    }
} else if(validar($dados, true) && Token::check($dados['token']) && $dados['valor'] == "Realizar"){
    $nr = $dados["nr"];
    $nr  = ltrim($nr);//limpa brancos a esquerda
    $nr  = rtrim($nr);//limpa brancos a direita
    $nr = preg_replace('/[ ]{2,}|[\t]/', ';', trim($nr));//replace "tab character" para ";"
    $lista = explode("\n", $nr);//separa nr ref por linha
    
    for($x = 0;$x < count($lista);$x++){//le todas as linhas separadamente

        $pgt = explode(";",$lista[$x]);
        $cc_id = substr($pgt[0],-6);//recupera os ultimos 6 carateres (x.x.x.xxxxxx - idioma.nivel.exame.num ref)
        if(empty($cc_id)){ $cc_id = "0"; }
        $valor = $pgt[1];
        $cpf = rtrim($pgt[2]);
        $exame = substr($pgt[0], -7,1);

        if($exame != 1){//se exame não for civ
            $cc = mysqli_num_rows(mysqli_query($con, "select * from cadastro c,cadastro_curso cc where cc.cc_id = $cc_id and c.cad_cpf = '$cpf' and c.cad_id = cc.cad_id"));//verifica se usuario pagou a propria gru diferente de civ
            if($cc > 0){//se pagamento foi realizado pelo usuario que gerou a gru
                $pgt_ok = mysqli_num_rows(mysqli_query($con, "select * from pagamento where cc_id = $cc_id"));
                if(empty($pgt_ok)){//se nao foi registrado pagamento
                    try {
                        mysqli_begin_transaction($con);
                        mysqli_query($con, "insert into pagamento (cc_id,pgt_valor) values($cc_id,'$valor')");
                        mysqli_query($con, "update cadastro_curso set ccs_id = 1 where cc_id = $cc_id");
                        mysqli_commit($con);
                    } catch (mysqli_sql_exception $e) {
                        mysqli_rollback($con);
                        throw $e;
                    }
                    $pgt_msg = "[v] ". $cpf ." gerou GRU Nr RF ". $cc_id ." - ". $valor ."<br/>";
                }else{//se ja registrou
                    $pgt_msg = "[x] ". $cpf ." gerou GRU Nr RF ". $cc_id ." - ". $valor ." - Pagamento já registrado anteriormente<br/>";
                }
            } else {//se pagamento nao foi realizado pelo usuario que gerou a gru
                $cad = mysqli_fetch_array(mysqli_query($con, "select * from cadastro where cad_cpf = '$cpf'"));
                $cad_id = $cad["cad_id"];
                $pgt_no = mysqli_num_rows(mysqli_query($con, "select * from pagamento_erro where cc_id = $cc_id and cad_id = $cad_id"));
                if(empty($pgt_no)){//se nao registrado o erro do pagamento do usuario
                    mysqli_query($con, "insert into pagamento_erro (cc_id,cad_id,pe_valor) values($cc_id,$cad_id,'$valor')");
                    $pgt_msg = "[x] ". $cpf ." NÃO gerou GRU Nr RF ". $cc_id ." - ". $valor ."<br/>";
                } else {//se ja registrou
                    $pgt_msg = "[x] ". $cpf ." NÃO gerou GRU Nr RF ". $cc_id ." - ". $valor ." - Erro no pagamento já registrado anteriormente<br/>";
                }
            }
            echo $pgt_msg;
            $pgt_msg_full = $pgt_msg_full .";". $pgt_msg;
        }
    }
    mysqli_query($con, "insert into pagamento_extrato (pex_texto) values('$pgt_msg_full')");
    echo "Pagamentos carregados com sucesso";
} else {
    echo "Não foi possível realizar a operação, entre em contato com o administrador do Sistema e informe o código - ERRO 002";
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

