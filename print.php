<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="br" lang="pt">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>Exército Brasileiro</title>
		<link href="imagens/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon"/>
		<link rel="stylesheet" href="style/style.css"/>
		<script type="text/javascript" src="script/script.js"></script>
		<script src="https://www.google.com/recaptcha/api.js"></script>
		<style id="antiClickjack">body{display:none !important;}</style>
		<script type="text/javascript">
		if(self === top){
			var antiClickjack = document.getElementById("antiClickjack");
			antiClickjack.parentNode.removeChild(antiClickjack);
		}else{
			top.location = self.location;
		}
		</script>
		<style>
			label{font-weight:bold;width:140px;text-align:right;margin-right:10px}
		</style>
	</head>
<body onload="print()">
<?php
include 'system/system.php';
if(Token::check($token)){//se tiver token -> mostra ficha de inscricao
	$cc = mysqli_fetch_array(mysqli_query($con, "select * from curso_idioma ci,cadastro_curso cc,cadastro cad,curso crs,idioma idm where ci.crs_id = cc.crs_id and ci.idm_id = cc.idm_id and cc.cc_id = $cc_id and cc.cad_id = cad.cad_id and cc.crs_id = crs.crs_id and cc.idm_id = idm.idm_id"));
	if($cc){
		foreach($cc as $campo => $valor){$$campo = stripslashes($valor);}
		if($crs_id != 1){
			$cl = mysqli_fetch_array(mysqli_query($con, "select * from curso_local cl, om,rm where cl.om_id = om.om_id and om.rm_id = rm.rm_id and cl.cl_id = $cl_id and cl.crs_id = $crs_id"));
			foreach($cl as $campo => $valor){$$campo = stripslashes($valor);}
		}
		$homolog = ($cad_codpg == 57)? "homologação" : "pagamento";//se for cadete = homologacao
		$situacao = ( in_array($ccs_id,[0,11] ) )? "Aguardando ". $homolog ." da inscrição" : "INSCRITO REGULARMENTE (PAGO)";
		
		$bol_id = str_pad($cc_id, 6, "0", STR_PAD_LEFT);//999.999 registros
		$referencia = $idm_id.$nivel_id.$crs_id.$bol_id;
                
		
		if (! isset($last_cp_id)){
			$cp = mysqli_fetch_array(mysqli_query($con, "select * from curso_periodo order by cp_id desc"));
			$last_cp_id = $cp["cp_id"]; //ultimo periodo cadastrado
		}
		
		//verifica posicao do aluno		
		if($crs_id == 1){//se civ
			$pos_cad = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso where cc_id <= $cc_id and crs_id = $crs_id and idm_id = $idm_id and cc_parcela = 1 and ccs_id in(0,1,2,11)"));//verifica posicao do aluno civ
		}else{//se epl
			$pos_cad = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso where cc_id <= $cc_id and crs_id = $crs_id and idm_id = $idm_id and cp_id = $last_cp_id"));//verifica posicao do aluno epl
		}

		$vaga = mysqli_fetch_array(mysqli_query($con, "select * from curso_idioma where crs_id = $crs_id and idm_id = $idm_id"));//qtd de vaga para o idioma e curso
		$qtd_vagas = $vaga["ci_vagas"];
		if($pos_cad >= $qtd_vagas){
//			$posicao = $pos_cad - $qtd_vagas;
                        $situacao = "O(a) Sr(a) será incluído(a) na <u>2ª convocação</u>, prevista para o dia <span style='color:red'> {$_SESSION['DATA_SEGUNDA_CONVOCACAO']} </span>, quando receberá um e-mail e, então, deverá imprimir e pagar a GRU para confirmar sua inscrição.";
		}
?>
<fieldset style="width:800px;margin:auto">
	<center>
		<img src="imagens/cidex.png" width="70" /><br/>
		<b>CENTRO DE IDIOMAS DO EXÉRCITO</b>
		<br/>
		<br/>
		<b>FICHA DE INSCRIÇÃO</b>
	</center>
	<hr>
	<ul>
		<li><label>Data da Inscrição: </label> <?= date('d/m/Y H:i:s', strtotime($cc_date)) ?></li>
		<li><label>Inscrição nº: </label> <?= $num_inscricao.'-'.$digito_verificador ?></li>
		<li><label>Candidato: </label> <?= $cad_login ?> - <?= $cad_postograd ?> - <?= $cad_nome ?></li>
		<li><label>Curso:</label> <?= $crs_cod ?> - <?= $crs_nome ?></li>
		<li><label>Idioma:</label> <?= $idm_nome ?></li>
<?php if($crs_id != 1){//ini se EPL ?>
		<li><label>Nível:</label> <?= $nivel_id ?></li>
              <!--  <li><label>Data do exame:</label> <?php  if($crs_id == 3 || $crs_id == 6) { echo "Data a ser divulgada no endereço eletrônico www.cidex.eb.mil.br"; } else { echo formatdate($ci_dtinicio); } ?> <?php // ($ci_dtinicio == "0000-00-00")? "Data a ser divulgada no endereço eletrônico www.cep.ensino.eb.br" : formatdate($ci_dtinicio); ?></li> -->
		<li><label><?php if ($crs_id == 3 || $crs_id == 6){ ?>Guarnição de Exame: <?php } else { ?>Local: <?php } ?></label> <?= $rm_sigla ." - ". $om_nome ." (". $om_sigla .") - ". $om_municipio ." - ". $om_uf ?></li>
<?php }//fim se EPL ?>
		<li><label>Situação:</label> <b><?= $situacao ?></b></li>
                <?php 
                if($crs_id == 3){
                ?>
                <li style="height: 60px">
                    <div style="width: 140px; float: left; font-weight: bold; text-align: right">Observação:</div> 
                    <div style="font-weight: normal;width: 600px; text-align: left; float: left; padding-left: 13px;">A critério do Centro de Idiomas do Exército (CIdEx), os candidatos inscritos no nível 1, nos idiomas INGLÊS e/ou ESPANHOL no exame oral, poderão ter seus(s) exame(s) agendado(s) para o Colégio Militar da respectiva cidade ou cidade mais próxima da OMSE escolhida.</div></li>
                <?php } 
                if($crs_id == 6){
                ?>
                <li>
                    <div style="width: 140px; float: left; font-weight: bold; text-align: right">Observação:</div>  
                    <div style="font-weight: normal;width: 600px; text-align: left; float: left; padding-left: 13px;">A critério do Centro de Idiomas do Exército (CIdEx), os candidatos inscritos no nível 1, nos idiomas INGLÊS e/ou ESPANHOL no exame oral, poderão ter seus(s) exame(s) agendado(s) para o Colégio Militar(CMRJ).</div></li>
                <?php } ?>
					<li style="font-size: 10pt;font-weight: bolder;display: inline-block;">
					<ul>
						<?php
						if( in_array($idm_id,[2,4]) ):
							?>	
								<li>
									<p> 								
										<strong>Sua prova de CA, CL e EE/, inglês e espanhol,  N1, 2 e 3</strong> deve ser <strong>agendada através do site do CIDEx (<a href="https://www.cidex.eb.mil.br" target="_blank" style="
    color: #1313f0;
    font-style: italic;
    font-weight: 500;
"> www.cidex.eb.mil.br </a> </strong> no período de <strong>03 a 10 SET</strong>. 
									</p>	
									<p>
										Demais idiomas e provas de EO: conforme datas previstas no calendário anual.
									</p>						
								</li> 						
						
						<?php  else: ?>
								<li>
									<p>
										Seu exame será conforme as datas/períodos previstos no calendário anual (Port/ DECEx 844, de 17 DEZ 24).
									</p>	
								</li>	
						<?php endif; ?>	
					</ul>
					</li>	
	</ul>
        <span style="text-align: center; position: relative; display: block;  padding: 1%;">
               <?php if($ccs_id == 1){ echo "Importante: Este documento deve ser impresso e apresentado, junto a sua identidade militar, ao Fiscal de Prova no dia do Exame."; } ?>
        </span>
        <span style="left: 75%;font-size: x-small;position: relative;" > <?= md5($cc_id) ?> </span>
	
</fieldset>
<?php
	}
}else{//se não tiver token -> pagina principal
	header("location:index.php");
}
?>
</body>
</html>