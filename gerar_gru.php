<?php
$ci = mysqli_fetch_array(mysqli_query($con, "select * from curso_idioma where crs_id = $crs_id and idm_id = $idm_id"));
$candidato = Candidato::getCandidato();
 
/*$val = ($cad_codpg <= 18)? $ci["ci_valof"] : $ci["ci_valpraca"];//se menor q 18: oficial, se não praca
$val = str_replace(".",",",$val);*/
 $val = $candidato->getValorCurso($crs_id);
if( $candidato->temGratuidade($crs_id) /*@$_SESSION["aluno"] == "on" && ($idm_id == 2 || $idm_id == 4) */){//se for aluno e idioma for espanhol(2) ou ingles(4)
	$val = "0,00";
	$gra = mysqli_num_rows(mysqli_query($con, "select * from gratuidade where cc_id = $cc_id"));//verifica se existe solicitacao de gratuidade
	if($gra == 0){//se nao solicitou gratuidade
		mysqli_query($con, "insert into gratuidade (cc_id) values($cc_id)");
	}
?>
        <input type="image" src="imagens/icon_a_gru.png" title="Gratuidade" 
               style="width: 14px; height: 14px; border: 0; padding: 0; padding-bottom: 7px; margin-top: -2px;" 
               align="absmiddle" 
               onclick="alert('Sr(a) candidato(a).<br> Não é gerada nenhuma cobrança.')" />
<?php
} else {

#dados da GRU
$cg = mysqli_fetch_array(mysqli_query($con, "select * from curso_gru where crs_id = $crs_id"));
foreach($cg as $campo => $valor){$$campo = addslashes($valor);}
    
$codigo_correlacao = "02349";
$boleto = "3";//no tesouro = 1
$impressao = "SA";
$pagamento = "1";
$campo = "CR";
$ind = "0";
$nome_contribuinte = $cad_nome;
$nome_favorecido = $cg_nome;
$codigo_recolhimento = $cg_codrec;
$bol_id = str_pad($cc_id, 6, "0", STR_PAD_LEFT);//999.999 registros
$referencia = $idm_id.$nivel_id.$crs_id.$bol_id;

if($crs_id == 1){
    $venc = explode("-",$cc_vencimento);//vencimento na tabela cadastro_curso
    
    /*** Verifica se a parcela é Inscrição ou Renovação, se for inscrição não permite que gere uma nova data. ***/    
    if (($cc_parcela % 12) == 1) {
        $inscricaoVencida = false;
    } else {
        $inscricaoVencida = true;
    }
    /** Fim **/
    /*** Verifica se a GRU está vencida, 
     * se estiver gera a GRU com prazo de 5 dias a partir da data atual, 
     * não gera nova data para Inscrição **/
    if(strtotime(date('Y-m-d')) > strtotime($cc_vencimento) && $inscricaoVencida){
        $vencimento = date('d/m/Y', strtotime('+5 days'));//$venc[2] ."/". $venc[1] ."/". $venc[0];//data de vencimento
    }else{
        $vencimento = $venc[2] ."/". $venc[1] ."/". $venc[0];//data de vencimento
    }
        $competencia = $venc[1]."/".$venc[0];
}else{
    $venc = explode("-",$cg_vencimento);//vencimento na tabela curso_gru
    $vencimento = $venc[2] ."/". $venc[1] ."/". $venc[0];//data de vencimento
    $competencia = $venc[1]."/".$venc[0];
}
    
$cnpj_cpf = mask($cad_cpf,'###.###.###-##');
$codigo_favorecido = $cg_favorecido;
$gestao = $cg_gestao;
$valorPrincipal = $val;
$descontos = "";
$deducoes = "";
$multa = "";
$juros = "";
$acrescimos = "";
$valorTotal = $val;
?>
<form METHOD="post" target="_blank" action="https://pagtesouro.tesouro.gov.br/api/gru/portal/boleto-gru">
	<input type="hidden" name="codigoUg" value="<?= $codigo_favorecido ?>" />	
	<input type="hidden" name="codigo_correlacao" value="<?= $codigo_correlacao ?>" />
	<input type="hidden" name="nomeContribuinte" value="<?= $nome_favorecido ?>" />
	<input type="hidden" name="codigoRecolhimento" value="<?= $codigo_recolhimento ?>" />
	<input type="hidden" name="nomeRecolhimento" value="EXER/FDO-SERV EDUC PROFISSIONAL" />
	<input type="hidden" name="numeroReferencia" value="<?= $bol_id ?>" />
	<input type="hidden" name="competencia" value="<?= $competencia ?>" />
	<input type="hidden" name="vencimento" value="<?= $vencimento ?>" />
	<input type="hidden" name="cpfCnpjContribuinte" value="<?= $cnpj_cpf ?>" />
	<input type="hidden" name="nome_contribuinte" value="<?= $nome_contribuinte ?>" />
	<input type="hidden" name="valorPrincipal" value="<?= $val ?>" />
	<input type="hidden" name="descontosAbatimentos" value="" />
	<input type="hidden" name="outrasDeducoes" value="" />
	<input type="hidden" name="moraMulta" value="" />
	<input type="hidden" name="jurosEncargos" value="" />
	<input type="hidden" name="outrosAcrescimos" value="" />
	<input type="hidden" name="valorTotal" value="<?= $val ?>" />
	
	<input type="image" id="<?= $bol_id ?>" src="imagens/icon_a_gru.png" title="Gerar GRU Nr Ref <?= $bol_id ?>" align="absmiddle" onclick="" style="display: none;" />
    <input type="button" class="visivel jnone" bolid="<?= $bol_id ?>" style="background: url(imagens/icon_a_gru2.png) no-repeat; width: 14px; height: 19px; border: 0; padding: 0" title="Gerar GRU Nr Ref <?= $bol_id ?>" align="absmiddle" />

</form>
<?php } ?>