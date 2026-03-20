<?php
include '../system/system.php';
/*** 
 * Ten Carvalhoza
 * Correção EPL
 * 
 * Funções para dar baixa em pagamentos duplicados
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

$cc_id = $dados['cc_id'];
$cpf = $dados['cpf'];

if(validar($dados, true) && Token::check($dados['token']) && $dados['acao'] == "pagar"){
    
    if(validar($cc_id,false,true)){        
        $dadosExame = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM cadastro_curso WHERE cc_id = $cc_id and ccs_id in(0,11) ")); //altera para 0 (aguardando pagamento)
        $dadosPagamento = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM pagamento where pgt_cpf = $cpf AND pgt_duplicado = 'SIM' LIMIT 1")); //altera para 0 (aguardando pagamento)
        $pgt_id = $dadosPagamento['pgt_id'];
        if(count($dadosExame) != 0 && count($dadosPagamento) != 0){
            try {
                mysqli_begin_transaction($con);
                mysqli_query($con, "update cadastro_curso set ccs_id = 1 where cc_id = $cc_id"); //altera para 0 (aguardando pagamento)
                $strSQL = "update pagamento 
                                       set cc_id = $cc_id, 
                                           pgt_duplicado='NÃO',
                                           flagpagamentocorrigido = true,
                                           datacorrigido = current_date,
                                           horacorrigido = current_time,
                                           idusuariocorrecao = {$_SESSION["uid"]}
                                     where pgt_id = $pgt_id";
                mysqli_query($con, $strSQL); //remove pagamento da tabela pagamento
                mysqli_commit($con);
                echo "OK";                

            } catch (mysqli_sql_exception $e) {
                mysqli_rollback($con);
                throw $e;
                echo "ERRO"; 
            }
        } else {
            echo "ERRO";
        }
        
    } 
}