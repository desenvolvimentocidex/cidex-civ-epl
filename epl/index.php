<?php
include '../system/system.php';
include 'action.php';
$i = 0;

//ini verifica ultimo periodo para epl
$cp = mysqli_fetch_array(mysqli_query($con, "select * from curso_periodo order by cp_id desc"));
$last_cp_id = $cp["cp_id"];//ultimo periodo cadastrado
$last_cp_nome = $cp["cp_nome"];//ultimo periodo cadastrado
//fim verifica ultimo periodo para epl
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="br" lang="pt">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>CIDEx - Centro de Idiomas do Exército</title>
		<link href="imagens/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon"/>
		<link rel="stylesheet" href="style/style.css"/>
                <script src="script/jquery-3.6.0.js" type="text/javascript"></script>
		<script type="text/javascript" src="script/script.js?v=1"></script>
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
	</head>
<body>
<div id="corpo">
<?php include ("top.php"); ?>

<?php if(@$_SESSION["loged"] == "adm_on"){ ?>
<?php include ("menu.php"); ?>
<div id="conteudo">
<?php
if($id == 1){//ini cadastrar OM
if($oid){
	$om = mysqli_fetch_array(mysqli_query($con, "select * from om,rm where rm.rm_id = om.rm_id and om_id = $oid"));
	if($om){
		foreach($om as $campo => $valor){$$campo = addslashes($valor);}
		$botao = "Atualizar";
		$act = "update_om";
	}
}else{
	$botao = "Cadastrar";
	$act = "add_om";
}
?>
<form method="post">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="acao" value="<?= $act ?>"/>
<input type="hidden" name="om_id" value="<?= $om_id ?>"/>
<table width="880">
	<tr>
		<td class="tit2" colspan="2">Dados da OMSE</td>
	</tr>
	<tr>
		<td class="w150px">RM:</td>
		<td>
		<select name="rm_id" class="w150px">
			<option value="<?= isset($rm_id) ? $rm_id : "<< Selecione >>"; ?>"><?= isset($rm_nome) ? $rm_nome : "<< Selecione >>"; ?></option>
		<?php
		$rm = mysqli_query($con, "select * from rm order by rm_id");
		while($rm_lista = mysqli_fetch_array($rm)){
			foreach($rm_lista as $campo => $valor){$$campo = addslashes($valor);}
		?>
			<option value="<?= $rm_id ?>"><?= $rm_nome ?></option>
		<?php } ?>
		</select>
		</td>
	</tr>
	<tr class="li1">
		<td>OM Sigla:</td>
		<td><input type="text" name="om_sigla" value="<?= isset($om_sigla) ? $om_sigla : ""; ?>" class="w150px"/></td>
	</tr>
	<tr>
		<td>OM Nome:</td>
		<td><input type="text" name="om_nome" value="<?= isset($om_nome) ? $om_nome : ""; ?>"/></td>
	</tr>
	<tr class="li1">
		<td>Estado:</td>
		<td>
		<select name="om_uf" class="w150px">
			<option value="<?= isset($om_uf) ? $om_uf : ""; ?>"><?= isset($om_uf) ? $om_uf : "<< Selecione >>"; ?></option>
			<option value="AC">AC</option> 
			<option value="AL">AL</option> 
			<option value="AM">AM</option> 
			<option value="AP">AP</option> 
			<option value="BA">BA</option> 
			<option value="CE">CE</option> 
			<option value="DF">DF</option> 
			<option value="ES">ES</option> 
			<option value="GO">GO</option> 
			<option value="MA">MA</option> 
			<option value="MT">MT</option> 
			<option value="MS">MS</option> 
			<option value="MG">MG</option> 
			<option value="PA">PA</option> 
			<option value="PB">PB</option> 
			<option value="PR">PR</option> 
			<option value="PE">PE</option> 
			<option value="PI">PI</option> 
			<option value="RJ">RJ</option> 
			<option value="RN">RN</option> 
			<option value="RO">RO</option> 
			<option value="RS">RS</option> 
			<option value="RR">RR</option> 
			<option value="SC">SC</option> 
			<option value="SE">SE</option> 
			<option value="SP">SP</option> 
			<option value="TO">TO</option> 
		</select>
		</td>
	</tr>
	<tr>
		<td>Municipio:</td>
		<td><input type="text" name="om_municipio" value="<?= isset($om_municipio) ? $om_municipio : ""; ?>" class="w150px"/></td>
	</tr>
        <tr>
		<td>Mostrar essa OMSE apenas para seus alunos? </td>
                <td><input type="checkbox" name="flagapenasparalunos" value="S" <?= isset($flagapenasparalunos) && $flagapenasparalunos != "N" ? "checked" : ""; ?> class="w150px"/></td>
	</tr>
	<tr>
		<td colspan="2" class="tdbotao"><input type="submit" class="botao" value="<?= $botao ?>"/></td>
	</tr>
</table>
</form>
<br/>
<table width="880">
	<tr>
		<td class="tit2">OMSEs cadastradas</td>
		<td class="tit2" width="50"></td>
		<td class="tit2" width="50"></td>
	</tr>
<?php
$i = 0;
$om = mysqli_query($con, "select * from rm,om where rm.rm_id = om.rm_id order by rm.rm_id, om.om_nome");
while($om_lista = mysqli_fetch_array($om)){
	foreach($om_lista as $campo => $valor){$$campo = addslashes($valor);}
	$i++;
?>
	<tr class="<?= linecolor($i) ?>">
		<td><?= $rm_sigla ." - ". $om_nome . " (". $om_sigla .") - ". $om_municipio ." - ". $om_uf ?></td>
		<td><input type="button" value="Editar" onclick="location='?id=<?= $id ?>&amp;oid=<?= $om_id ?>'"></td>
<form method="post">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="acao" value="delete_om"/>
<input type="hidden" name="om_id" value="<?= $om_id ?>"/>
		<td><input type="submit" value="Excluir" onclick="return confirmacao('excluir',' a OM <?= $om_nome ?>','')"/></td>
</form>
	</tr>
<?php  } ?>
</table>
<?php }//fim id = 1 ?>

<?php if($id == 2){//ini cadastrar local de prova ?>
<form method="post" id="om_curso_form" >
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="acao" value="add_cl"/>
<table width="880">
	<tr>
		<td colspan="3" class="tit2">OMSE:
                    <select name="om_id" id="om_id" onchange="location='?id=<?= $id ?>&amp;oid='+ this.value">
	<option></option>
<?php
$om = mysqli_query($con, "select * from rm,om where rm.rm_id = om.rm_id order by rm.rm_id, om.om_nome, om_uf, om_municipio");
while($om_lista = mysqli_fetch_array($om)){
	foreach($om_lista as $campo => $valor){$$campo = addslashes($valor);}
?>
	<option value="<?= $om_id ?>" <?php if($om_id == $oid){ echo "selected"; } ?>><?= $rm_sigla ." - ". $om_nome . " (".$om_sigla.") - ". $om_municipio ." - ". $om_uf ?></option>
<?php } ?>
</select>
		</td>
	</tr>

<?php
if($oid){
	$om = mysqli_num_rows(mysqli_query($con, "select * from om where om_id = $oid"));
	if($om == 0){//verifica se a om selecionada esta cadastrada no bd
		echo "<script>alert('Selecione uma OM.');location='?id=$id'</script>";
		exit;
	}
	$i = 0;
        $crs = mysqli_query($con, "SELECT * FROM curso");
        while($crs_lista = mysqli_fetch_array($crs)){
		foreach($crs_lista as $campo => $valor){$$campo = addslashes($valor);}
              	$num = mysqli_num_rows(mysqli_query($con, "select * from curso_local where om_id = $oid and crs_id = $crs_id"));
                
                if($num == 0){
                    $ativo = 0;
                } else {
                    $cl = mysqli_fetch_array(mysqli_query($con, "select * from curso_local where om_id = $oid and crs_id = $crs_id"));
                    foreach($cl as $key => $value){$$key = addslashes($value);}
                    
                }

                $i++;
?>
	<tr class="<?= linecolor($i) ?>">
                <td><!--<input type="checkbox" name="crs_id[]" value="<?php // $crs_id ?>" <?php // $check ?>/><input type="hidden" name="curso[]" value="<?php // $crs_id ?>"/>--></td>	
        	<td><?= $crs_nome ?></td>
                <td>
                    <?php if ($ativo == 0) { 
                        echo '<input type="button" class="alterarOmCurso" name="ativar" id="ativar'.$crs_id.'" style="width: 65px; background-color:#19882C; color: #fff; font-weight: bold;" value="Ativar" curso_id="'.$crs_id.'" /><input type="button" class="alterarOmCurso" name="desativar" id="desativar'.$crs_id.'" style="width: 65px; background-color:#bf0000; color: #fff; font-weight: bold; display: none" value="Desativar" curso_id="'.$crs_id.'" />'; 
                    } else { 
                        echo '<input type="button" class="alterarOmCurso" name="ativar" id="ativar'.$crs_id.'" style="width: 65px; background-color:#19882C; color: #fff; font-weight: bold; display: none" value="Ativar" curso_id="'.$crs_id.'" /><input type="button" class="alterarOmCurso" name="desativar" id="desativar'.$crs_id.'" style="width: 65px; background-color:#bf0000; color: #fff; font-weight: bold;" value="Desativar" curso_id="'.$crs_id.'" />';
                    } ?>
                </td>
	</tr>
<?php }//fim while ?>
	<tr>
<!--		<td colspan="3" class="tdbotao"><input type="hidden" name="crs_total" value="<?= $i ?>"/><input type="submit" class="botao" value="Enviar"/></td>-->
	</tr>
<?php }//fim oid ?>
</table>

</form>
<?php }//fim id = 2 ?>

<?php
if($id == 3){//ini cadastrar local de prova    
?>
<table width="880">
	<tr>
            <td class="tit2" colspan="6">Lista de cursos/exames</td>
	</tr>
	<tr class="tit">
            <td><b>Curso</b></td>
            <td class="w100px" style="width: 160px"><b style="cursor:help" title="Início das Inscrições">Início</b></td>
            <td class="w100px" style="width: 160px"><b style="cursor:help" title="Término das Inscrições">Término</b></td>
            <td class="w100px" style="width: 160px"><b style="cursor:help" title="Prazo para alterar OMSE">Prazo OMSE (?)</b></td>
            <td class="w50px"><b>Status</b></td>
            <td></td>
	</tr>
            <?php
        $crs = mysqli_query($con, "select * from curso where not crs_id = 1 order by crs_id");//diferente de civ
        while($crs_lista = mysqli_fetch_array($crs)){
                foreach($crs_lista as $campo => $valor){$$campo = addslashes($valor);}
                $i++;
        ?>
	<tr class="<?= linecolor($i) ?>">
            <td><?= $crs_cod ?> - <?= $crs_nome ?></td>
            <td><?php $inicio = new DateTime($crs_dtinicio); echo $inicio->format('d/m/Y H:i'); ?></td>
            <td><?php $termino = new DateTime($crs_dttermino); echo $termino->format('d/m/Y H:i'); ?></td>
            <td><?= formatdate($crs_dtlocal) ?></td>
            <td>
                <?php if($crs_status == 0){ echo "Off"; } ?>
                <?php if($crs_status == 1){ echo "On"; } ?>
            </td>
            <td></td>
	</tr>
        <?php } ?>
	<tr class="tit">		
		<td class="w100px" style="width: 190px"><b style="cursor:help" title="Início das Inscrições">Início</b></td>
		<td class="w100px" style="width: 190px"><b style="cursor:help" title="Término das Inscrições">Término</b></td>
		<td class="w100px" style="width: 190px"><b style="cursor:help" title="Prazo para alterar OMSE">Prazo OMSE (?)</b></td>		
		<td></td>
	</tr>
	<tr>
		<form method="post" name="form_todos">
		<input type="hidden" name="token" value="<?= $tok ?>"/>
		<input type="hidden" name="acao" value="update_crs">
			<tr class="<?= linecolor($i) ?>">				
						<td>
							<input type="datetime-local" name="crs_dtinicio" value="<?= formatdate($crs_dtinicio) ?>" style="width:145px"/>
							<span id="pop<?= $i ?>a" style="position:absolute"></span>
						</td>
				<td>
							<input type="datetime-local" name="crs_dttermino" value="<?= formatdate($crs_dttermino) ?>" style="width:145px"/>
							<span id="pop<?= $i ?>a" style="position:absolute"></span>
						</td>
				<td>
							<input type="date" name="crs_dtlocal" value="<?= formatdate($crs_dtlocal) ?>" style="width:115px"/>
							<span id="pop<?= $i ?>b" style="position:absolute"></span>
						</td>
			
				<td><input type="image" src="imagens/i_save.png" style="width:16px;padding:0px;"/></td>
			</tr>
		</form>
	</tr>	

</table>
<br/>
<table width="880">
    	<tr>
		<td class="tit2 w500px"><b>Curso</b></td>
		<td class="tit2 w100px"><b>Idioma</b></td>
                <td class="tit2 w150px" style="width: 160px"><b>Data Exame</b></td>		
		<td class="tit2 w50px"><b>Vagas</b></td>		
		<td class="tit2"></td>
	</tr>
<?php
$crs = mysqli_query($con, "select * from curso crs,idioma idm, curso_idioma ci where crs.crs_id = ci.crs_id and idm.idm_id = ci.idm_id and crs.crs_status = 1 and not crs.crs_id = 1 order by crs.crs_id,idm.idm_nome");//lista apenas cursos liberados diferentes de civ
while($crs_lista = mysqli_fetch_array($crs)){
	foreach($crs_lista as $campo => $valor){$$campo = addslashes($valor);}
	$i++;
?>
	<tr class="<?= linecolor($i) ?>">
		<td><?= $crs_nome ?></td>
		<td><?= $idm_nome ?></td>
		<td><?= formatdate($ci_dtinicio) ?></td>
		<td><?= $ci_vagas ?></td>		
		<td></td>
	</tr>
<?php }//fim while ?>
	<tr>
		<td class="tit2 w500px"><b>Curso</b></td>
		<td class="tit2 w100px"><b>Idioma</b></td>
                <td class="tit2 w150px" style="width: 160px"><b>Data Exame</b></td>		
		<td class="tit2 w50px"><b>Vagas</b></td>		
		<td class="tit2"></td>
	</tr>
<?php
$crs = mysqli_query($con, "select * from curso crs,idioma idm, curso_idioma ci where crs.crs_id = ci.crs_id and idm.idm_id = ci.idm_id and crs.crs_status = 1 and not crs.crs_id = 1 order by crs.crs_id,idm.idm_nome");//lista apenas cursos liberados diferentes de civ
while($crs_lista = mysqli_fetch_array($crs)){
	foreach($crs_lista as $campo => $valor){$$campo = addslashes($valor);}
	$i++;
?>
<form method="post" name="form<?= $i ?>">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="acao" value="update_ci">
<input type="hidden" name="ci_id" value="<?= $ci_id ?>">
	<tr class="<?= linecolor($i) ?>">
		<td><?= $crs_nome ?></td>
		<td><?= $idm_nome ?></td>
		<td><input type="date" name="ci_dtinicio" value="<?= formatdate($ci_dtinicio) ?>" style="width:110px"/><!--<input type="button" value="..." onclick="javascript:popdate('document.form<?php // $i ?>.ci_dtinicio','pop<?php // $i ?>a','150',document.form<?php // $i ?>.ci_dtinicio.value)" style="width:22px">--><span id="pop<?= $i ?>a" style="position:absolute"></span></td>
		<td><input type="text" name="ci_vagas" value="<?= $ci_vagas ?>"/></td>		
		<td><input type="image" src="imagens/i_save.png" style="width:16px;padding:0px;"/></td>
	</tr>
</form>
<?php }//fim while ?>
</table>
<?php }//fim id = 3 ?>

<?php
if($id == 4){//ini lista de vagas, inscritos e pagos
	$crs = "select * from curso where crs_status = 1 and not crs_id = 1 order by crs_id";//lista cursos
	$idm = "select * from idioma order by idm_nome";//lista idiomas
?>
<table width="880">
	<tr>
		<td colspan="2" class="tit2">Selecione o período:
		<select onchange="location='?id=<?= $id ?>&amp;cpid='+ this.value">
			<option></option>
<?php
$cpid = htmlspecialchars((int)$_GET["cpid"]);
if(empty($cpid)){
	$cp = mysqli_fetch_array(mysqli_query($con, "select * from curso_periodo order by cp_id desc limit 1"));
	$cpid = $cp["cp_id"];
}
$cp = mysqli_query($con, "select * from curso_periodo order by cp_id desc");
while($cp_lista = mysqli_fetch_array($cp)){
	foreach($cp_lista as $campo => $valor){$$campo = addslashes($valor);}
?>
			<option value="<?= $cp_id ?>" <?php if($cp_id == $cpid){ echo "selected"; } ?>><?= $cp_nome ?></option>
<?php } ?>
		</select>
		</td>
	</tr>
</table>
<br/><br/>
<table width="880">
	<tr>
		<td colspan="8" class="tit2"><b>Total de vagas</b></td>
	</tr>
	<tr class="tit">
		<td><b>Curso</b></td>
<?php
$idm_vagas = mysqli_query($con, $idm);
while($idm_lista = mysqli_fetch_array($idm_vagas)){
	foreach($idm_lista as $campo => $valor){$$campo = addslashes($valor);}
	@$t_idm = @$t_idm + 1;
?>
		<td class="w50px"><?= $idm_sigla ?></td>
<?php } ?>
		<td>Total</td>
	</tr>
<?php
$crs_vagas = mysqli_query($con, $crs);
while($crs_lista = mysqli_fetch_array($crs_vagas)){
	foreach($crs_lista as $campo => $valor){$$campo = addslashes($valor);}
	$t_vagas = 0;
	$i++;
?>
	<tr class="<?= linecolor($i) ?>">
		<td><?= $crs_cod ?> - <?= $crs_nome ?></td>
<?php
for($x = 1;$x <= $t_idm;$x++){//1 até o total de idiomas
	$ci = mysqli_fetch_array(mysqli_query($con, "select * from curso_idioma where crs_id = $crs_id and idm_id = $x"));
	foreach($ci as $campo => $valor){$$campo = addslashes($valor);}
	$t_vagas = $t_vagas + $ci_vagas;
?>
		<td><?= $ci_vagas ?></td>
<?php } ?>
		<td><?= $t_vagas ?></td>
	</tr>
<?php } ?>
</table>

<table width="880">
	<tr>
		<td colspan="8" class="tit2"><b>Total Inscritos</b></td>
	</tr>
	<tr class="tit">
		<td><b>Curso</b></td>
<?php
$idm_insc = mysqli_query($con, $idm);
while($idm_lista = mysqli_fetch_array($idm_insc)){
	foreach($idm_lista as $campo => $valor){$$campo = addslashes($valor);}
	@$t_idm = @$t_idm + 1;
?>
		<td class="w50px"><?= $idm_sigla ?></td>
<?php } ?>
		<td>Total</td>
	</tr>
<?php
$i = 0;
$crs_insc = mysqli_query($con, $crs);
while($crs_lista = mysqli_fetch_array($crs_insc)){
	foreach($crs_lista as $campo => $valor){$$campo = addslashes($valor);}
	$t_vagas = 0;
	$i++;
?>
	<tr class="<?= linecolor($i) ?>">
		<td><?= $crs_cod ?> - <?= $crs_nome ?></td>
<?php
$t_pg = 0;
$idm_insc = mysqli_query($con, $idm);
while($idm_lista = mysqli_fetch_array($idm_insc)){
	foreach($idm_lista as $campo => $valor){$$campo = addslashes($valor);}
	$rcc = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso where cp_id = $cpid and crs_id = $crs_id and idm_id = $idm_id"));
	$t_pg = $t_pg + $rcc;
?>
		<td><?= $rcc ?></td>
<?php }//fim while idioma ?>
		<td><?= $t_pg ?></td>
	</tr>
<?php }//fim while curso ?>
</table>

<table width="880">
	<tr>
		<td colspan="8" class="tit2"><b>Total Pagos</b></td>
	</tr>
	<tr class="tit">
		<td><b>Curso</b></td>
<?php
$idm_pagos = mysqli_query($con, $idm);
while($idm_lista = mysqli_fetch_array($idm_pagos)){
	foreach($idm_lista as $campo => $valor){$$campo = addslashes($valor);}
	@$t_idm = @$t_idm + 1;
?>
		<td class="w50px"><?= $idm_sigla ?></td>
<?php } ?>
		<td>Total</td>
	</tr>
<?php
$i = 0;
$crs_pagos = mysqli_query($con, $crs);
while($crs_lista = mysqli_fetch_array($crs_pagos)){
	foreach($crs_lista as $campo => $valor){$$campo = addslashes($valor);}
	$t_vagas = 0;
	$i++;
?>
	<tr class="<?= linecolor($i) ?>">
		<td><?= $crs_cod ?> - <?= $crs_nome ?></td>
<?php
$t_pg = 0;
$idm_pagos = mysqli_query($con, $idm);
while($idm_lista = mysqli_fetch_array($idm_pagos)){
	foreach($idm_lista as $campo => $valor){$$campo = addslashes($valor);}
	$rcc = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso where cp_id = $cpid and crs_id = $crs_id and idm_id = $idm_id and ccs_id in (1,2)"));
	$t_pg = $t_pg + $rcc;
?>
		<td><?= $rcc ?></td>
<?php }//fim while idioma ?>
		<td><?= $t_pg ?></td>
	</tr>
<?php }//fim while curso ?>
</table>

<!--ini total pagos por nivel-->
<table width="880">
	<tr>
		<td class="tit2"><b>Total Pago por Nível</b></td>
		<td class="tit2 w100px"><b>Idioma</b></td>
		<td class="tit2 w50px"><b>Nível 1</b></td>
		<td class="tit2 w50px"><b>Nível 2</b></td>
		<td class="tit2 w50px"><b>Nível 3</b></td>
		<td class="tit2 w50px"><b>Total</b></td>
	</tr>
<?php
$crs_nivel = mysqli_query($con, "select * from curso crs,idioma idm, curso_idioma ci where crs.crs_id = ci.crs_id and idm.idm_id = ci.idm_id and crs.crs_status = 1 and not crs.crs_id = 1 order by crs.crs_id,idm.idm_nome");//lista apenas cursos liberados
while($crs_lista = mysqli_fetch_array($crs_nivel)){
	foreach($crs_lista as $campo => $valor){$$campo = addslashes($valor);}
	$i++;
?>
	<tr class="<?= linecolor($i) ?>">
		<td><?= $crs_nome ?></td>
		<td><?= $idm_nome ?></td>
<?php
$total = 0;
for($x=1;$x<=3;$x++){
	$t_n = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso where cp_id = $cpid and crs_id = $crs_id and idm_id = $idm_id and nivel_id = $x and ccs_id in (1,2)"));
	$total = $total + $t_n;
?>
		<td align="right"><?= $t_n ?></td>
<?php }//fim for ?>
		<td align="right"><?= $total ?></td>
	</tr>
<?php }//fim while ?>
</table>
<!--fim total pagos por nivel-->

<!-- ini total nao pagos -->
<table width="880">
	<tr>
		<td colspan="9" class="tit2"><b>Total Não Pagos</b></td>
	</tr>
	<tr class="tit">
		<td><b>Curso</b></td>
<?php
$idm_naopagos = mysqli_query($con, $idm);
while($idm_lista = mysqli_fetch_array($idm_naopagos)){
	foreach($idm_lista as $campo => $valor){$$campo = addslashes($valor);}
	@$t_idm = @$t_idm + 1;
?>
		<td class="w50px"><b><?= $idm_sigla ?></b></td>
<?php } ?>
		<td><b>Total</b></td>
		<td></td>
	</tr>
<?php
$i = 0;
$crs_naopagos = mysqli_query($con, $crs);
while($crs_lista = mysqli_fetch_array($crs_naopagos)){
	foreach($crs_lista as $campo => $valor){$$campo = addslashes($valor);}
	$t_vagas = 0;
	$i++;
?>
	<tr class="<?= linecolor($i) ?>">
		<td><?= $crs_cod ?> - <?= $crs_nome ?></td>
<?php
$t_pg = 0;
$idm_naopagos = mysqli_query($con, $idm);
while($idm_lista = mysqli_fetch_array($idm_naopagos)){
	foreach($idm_lista as $campo => $valor){$$campo = addslashes($valor);}
	$rcc = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso where cp_id = $cpid and crs_id = $crs_id and idm_id = $idm_id and ccs_id in(0,11)"));
	$t_pg = $t_pg + $rcc;
?>
		<td><?= $rcc ?></td>
<?php }//fim while idioma ?>
		<td><?= $t_pg ?></td>
	</tr>
<?php }//fim while curso ?>
</table>
<!-- fim total nao pagos -->

<!-- ini total nao pagos dentro de numero de vagas -->
<table width="880">
	<tr>
		<td class="tit2"><b>Total não pago dentro do número de vaga</b></td>
		<td class="tit2 w100px"><b>Idioma</b></td>
		<td class="tit2 w50px"><b>Vagas</b></td>
		<td class="tit2 w50px"><b>Inscritos</b></td>
		<td class="tit2 w50px"><b>Pagos</b></td>
		<td class="tit2 w50px"><b>Não pagos</b></td>
		<td class="tit2 w50px"><b>Abrir</b></td>
		<td class="tit2 w50px"></td>
	</tr>
<?php

$crs = mysqli_query($con, "select * from curso crs,idioma idm, curso_idioma ci where crs.crs_id = ci.crs_id and idm.idm_id = ci.idm_id and crs.crs_status = 1 and not crs.crs_id = 1 order by crs.crs_id,idm.idm_nome");//lista apenas cursos liberados
while($crs_lista = mysqli_fetch_array($crs)){
	foreach($crs_lista as $campo => $valor){$$campo = addslashes($valor);}
	$i++;
?>
<form method="post">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="acao" value="clear_crs"/>
<input type="hidden" name="crs_id" value="<?= $crs_id ?>"/>
<input type="hidden" name="idm_id" value="<?= $idm_id ?>"/>
<input type="hidden" name="cp_id" value="<?= $last_cp_id ?>"/>
	<tr class="<?= linecolor($i) ?>">
		<td><?= $crs_nome ?></td>
		<td><?= $idm_nome ?></td>
<?php
$inscritos = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso where cp_id = $cpid and crs_id = $crs_id and idm_id = $idm_id"));// inscritos
$pagos = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso where cp_id = $cpid and crs_id = $crs_id and idm_id = $idm_id and ccs_id in (1,2)"));// pagos
$naopagos = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso where cp_id = $cpid and crs_id = $crs_id and idm_id = $idm_id and ccs_id in(0,11) "));// nao pagos
$vagas = mysqli_fetch_array(mysqli_query($con, "select * from curso_idioma where crs_id = $crs_id and idm_id = $idm_id"));
$ci_vagas = $vagas["ci_vagas"];
$npv = mysqli_query($con, "select * from cadastro_curso where cp_id = $cpid and crs_id = $crs_id and idm_id = $idm_id limit $ci_vagas");
$abrir = 0;
while($lista_npv = mysqli_fetch_array($npv)){
	foreach($lista_npv as $campo => $valor){$$campo = addslashes($valor);}
	if(in_array($ccs_id, [0,11] )){ $abrir++; }
}
$cnv = mysqli_fetch_array(mysqli_query($con, "select * from curso_novasvagas where crs_id = $crs_id and idm_id = $idm_id order by cnv_id desc"));
if($cnv){
	$user = mysqli_fetch_array(mysqli_query($con, "select * from user where u_id = ". $cnv["u_id"] .""));
	$cnv_msg = "?\\n\\nForam abertas ". $cnv["cnv_qtd"] ." vagas no dia ". $cnv["cnv_data"] ." pelo usuário ". $user["u_nome"] .".\\n\\nConfirma";
}else{
	$cnv_msg = "";
}
?>
		<td align="right"><?= $ci_vagas ?></td>
		<td align="right"><?= $inscritos ?></td>
		<td align="right"><?= $pagos ?></td>
		<td align="right"><?= $naopagos ?></td>
		<td align="right"><?= $abrir ?></td>
		<td><input class="botao" type="submit" value="Abrir" onclick="return confirmacao('abrir',' as inscrições não pagas dentro do número de vagas para o <?= $crs_cod ?> - <?= $idm_sigla ?><?= $cnv_msg ?>','')"/></td>
	</tr>
</form>
<?php }//fim while ?>
</table>
<!-- fim total nao pagos dentro de numero de vagas -->

<?php }//fim 4 ?>

<?php if($id == 5){//garregar grus pagas //Incrições realizadas ?>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="acao" value="gru">
<table width="880">
    <tr>
        <input type="file" name='arquivo' accept=".csv" >  
    </tr>
	<tr>
		<td class="tdbotao" colspan="3"><input type="submit" style="width:150px" class="botao" value="Carregar pagamentos"/></td>
	</tr>
</table>
</form>
CARREGAR PAGAMENTO<br/>
1. Abrir o arquivo EXCEL e selecionar apenas as colunas (nesta ordem):<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a. Número de Referência;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b. Valor do Documento;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c. Identificação do Contribuinte;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d. Número de Autenticação.<br/>
2. Gerar Arquivo CSV (registros separados por PONTO E VÍRGULA);<br/>
3. Enviar o arquivo.<br/>
<?php }//fim 5 ?>

<?php
if($id == 6){//configurar gru
?>
<table width="880">
	<tr>
		<td class="tit2" colspan="2">Lista de Cursos/Exames:</td>
	</tr>
<?php
$i = 0;
$crs = mysqli_query($con, "select * from curso where crs_status = 1 and not crs_id = 1 order by crs_id");
while($crs_lista = mysqli_fetch_array($crs)){
	foreach($crs_lista as $campo => $valor){$$campo = addslashes($valor);}
	$i++;
?>
	<tr class="<?= linecolor($i) ?>">
		<td class="w550px"><?= $crs_nome ?></td>
		<td class="w50px"><input type="submit" value="Editar" onclick="location='?id=6&amp;cid=<?= $crs_id ?>'"/></td>
	</tr>
<?php } ?>
</table>
<br/>
<?php
if($cid){
	$cg = mysqli_fetch_array(mysqli_query($con, "select * from curso crs,curso_gru cg where crs.crs_id = cg.crs_id and cg.crs_id = $cid"));
	if($cg){#se cid existir: dados da gru
		foreach($cg as $campo => $valor){$$campo = addslashes($valor);}
		$venc = explode("-",$cg_vencimento); $vencimento = $venc[2] ."/". $venc[1] ."/". $venc[0];
	}else{#se cid nao existir: redireciona para cursos
		echo "<script>location='?id=$id'</script>";
	}
?>
<form method="post">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="acao" value="update_gru">
<input type="hidden" name="cid_id" value="<?= $cid ?>">
<table width="880">
	<tr>
		<td colspan="2" class="tit2">Dados da GRU :: <?= $crs_nome ?></td>
	</tr>
	<tr>
		<td class="w150px">Nome do favorecido</td>
		<td><input type="text" name="nome" value="<?= $cg_nome ?>" readonly/></td>
	</tr>
	<tr class="li1">
		<td>Código Recolhimento</td>
		<td><input type="text" name="codrec" value="<?= $cg_codrec ?>" readonly class="w150px"/></td>
	</tr>
	<tr>
		<td>Vencimento</td>
		<td><input type="text" name="vencimento" value="<?= $vencimento ?>" class="w150px"/> 00/00/0000</td>
	</tr>
	<tr class="li1">
		<td>Código Favorecido</td>
		<td><input type="text" name="favorecido" value="<?= $cg_favorecido ?>" readonly class="w150px"/></td>
	</tr>
	<tr>
		<td>Gestão</td>
		<td><input type="text" name="gestao" value="<?= $cg_gestao ?>" readonly class="w150px"/></td>
	</tr>
	<tr>
		<td colspan="2" class="tdbotao"><input type="submit" class="botao" value="Atualizar"/>
	</tr>
</table>
</form>
<?php }//if cid ?>
<?php }//fim = 6 ?>

<?php
if($id == 7){//ini relatorio por omse    
?>
<table width="880">
	<tr>
	   <td colspan="1" class="tit2">Período:
                <select name="periodo_id" onchange="location='?id=<?= $id ?>&amp;pid='+ this.value">
                        <option></option>
                <?php
                $cp = mysqli_query($con, "select cp_id, cp_nome, DATE_FORMAT(cp_ini,'%d/%m/%Y') as cp_ini from curso_periodo ");
                while($periodos = mysqli_fetch_array($cp)){
                        foreach($periodos as $campo => $valor){$$campo = addslashes($valor);}
                ?>
                        <option value="<?= $cp_id ?>" <?php if($cp_id == @$_GET['pid']){ echo "selected"; } ?>><?= $cp_nome." - .".$cp_ini ?></option>
                <?php } ?>
                </select>
          </td>	
          <td colspan="1" class="tit2">OMSE:
<select name="om_id" onchange="location='?id=<?= $id ?>&amp;pid=<?= @$_GET['pid'] ?>&amp;oid='+ this.value">
	<option></option>
<?php
$om = mysqli_query($con, "select * from rm,om where rm.rm_id = om.rm_id order by rm.rm_id, om.om_nome, om_uf, om_municipio");
while($om_lista = mysqli_fetch_array($om)){
	foreach($om_lista as $campo => $valor){$$campo = addslashes($valor);}
?>
	<option value="<?= $om_id ?>" <?php if($om_id == $oid){ echo "selected"; } ?>><?= $rm_sigla ." - ".$om_sigla." - ". $om_nome . " - ". $om_municipio ." - ". $om_uf ?></option>
<?php } ?>
</select>
		</td>
            
	</tr>
</table>
<?php if($oid){//se oid selecionada ?>
<table width="880">
	<tr>
		<td class="w500px tit2">Curso/Exame</td>
		<td class="w100px tit2">Idioma</td>
		<td class="w50px tit2 tac">Nível 1</td>
		<td class="w50px tit2 tac">Nível 2</td>
		<td class="w50px tit2 tac">Nível 3</td>
		<td class="w50px tit2 tac">Total</td>
	</tr>
<?php
$i = 0;
$crs = mysqli_query($con, "select * from curso crs,idioma idm, curso_idioma ci where not crs.crs_id = 1 and crs.crs_id = ci.crs_id and idm.idm_id = ci.idm_id and crs.crs_status = 1 order by crs.crs_id,idm.idm_nome");
while($crs_lista = mysqli_fetch_array($crs)){
	foreach($crs_lista as $campo => $valor){$$campo = addslashes($valor);}
	$i++;
	$pid = md5($_GET['pid']);
        
	$tn1 = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso cc,curso_local cl where cc.nivel_id = 1 and cc.crs_id = $crs_id and cc.idm_id = $idm_id and cc.ccs_id = 1 and cl.cl_id = cc.cl_id and cl.om_id = $oid and md5(cc.cp_id) = '{$pid}'"));
	$tn2 = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso cc,curso_local cl where cc.nivel_id = 2 and cc.crs_id = $crs_id and cc.idm_id = $idm_id and cc.ccs_id = 1 and cl.cl_id = cc.cl_id and cl.om_id = $oid and md5(cc.cp_id) = '{$pid}'"));
	$tn3 = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso cc,curso_local cl where cc.nivel_id = 3 and cc.crs_id = $crs_id and cc.idm_id = $idm_id and cc.ccs_id = 1 and cl.cl_id = cc.cl_id and cl.om_id = $oid and md5(cc.cp_id) = '{$pid}'"));
	$total = $tn1 + $tn2 + $tn3;
?>
	<tr class="<?= linecolor($i) ?>">
		<td><?= $crs_nome ?></td>
		<td><?= $idm_nome ?></td>
		<td class="tac"><?= $tn1?></td>
		<td class="tac"><?= $tn2?></td>
		<td class="tac"><?= $tn3?></td>
		<td class="tac"><?= $total?></td>
	</tr>	
<?php }// fim while ?>
</table>
<?php
	}//se oid
}//fim id = 7
?>

<?php
if($id == 8){//ini consulta miliar
?>
<form method="post" action="?id=<?= $id ?>">
    <input type="hidden" name="token" value="<?= $tok ?>"/>
    <table width="880">
        <tr>
            <td colspan="3" class="tit2">Buscar Militar</td>
        <tr>
        <tr>
            <td class="w150px">
            <select name="searchby">
                <option value="cad_login" <?= (@$_POST['searchby'] == 'cad_login') ? 'selected': '' ?>>Identidade:</option>
                <option value="cad_cpf"   <?= (@$_POST['searchby'] == 'cad_cpf') ? 'selected': '' ?>>CPF:</option>
                <option value="cad_mail"  <?= (@$_POST['searchby'] == 'cad_mail') ? 'selected': '' ?>>E-mail:</option>
                <option value="num_ref"   <?= (@$_POST['searchby'] == 'num_ref') ? 'selected': '' ?>>Número de Referência:</option>
            </select><?php $_SESSION['searchby'] = ""; ?>
            </td>
            <td class="w150px"><input type="text" name="searchrg" value="<?= (isset($_POST['searchrg'])) ? $_POST['searchrg']: '' ?>"/></td>
            <td><input type="submit" class="botao" value="Buscar"/>
        </tr>
    </table>
</form>
<?php
if(isset($searchrg) && Token::check($token)){//ini dados
	$searchrg = $searchrg;
        if($searchby != 'num_ref'){
            $cad = mysqli_fetch_array(mysqli_query($con, "select * from cadastro where $searchby = '$searchrg'"));
        }else{
            $cad = mysqli_fetch_array(mysqli_query($con, "select * from epl_busca WHERE num_ref = '$searchrg'"));
        }
	if(empty($cad)){
		echo "<script>alert('Militar não localizado.');location='?id=$id'</script>";
        }
        
	foreach($cad as $campo => $valor){$$campo = stripslashes($valor);}
	
	if(@$cad_tel){
		$tel = explode(";",$cad_tel);//ddd;telefone
		$tel0 = $tel[0];
		$tel1 = $tel[1];
	}
	if(@$cad_cel){
		$cel = explode(";",$cad_cel);//ddd;celular
		$cel0 = $cel[0];
		$cel1 = $cel[1];
	}
?>
<table width="880">
	<tr>
		<td colspan="8" class="tit2">Informações em vigor</td>
	</tr>
	<tr style="tit2">
		<td class="tit" style="width:140px">Referência</td>
		<td class="tit" style="width:280px">Curso/Exame</td>
		<td class="tit w100px">Idioma</td>
		<td class="tit w50px">Nível</td>
		<td class="tit w100px">OMSE</td>
		<td class="tit w150px">Situação</td>
		<td class="tit w150px">Data</td>
		<td class="tit w50px">Cancelar Inscrição</td>
	</tr>
<?php

$cc = mysqli_query($con, "select * from cadastro_curso cc,curso crs,idioma idm,cadastro_curso_status ccs,curso_periodo cp where cc.crs_id = crs.crs_id and cc.idm_id = idm.idm_id and cc.ccs_id = ccs.ccs_id and cad_id = $cad_id and cc.cp_id = cp.cp_id order by cp.cp_id desc,crs.crs_id,idm.idm_id,cc_id desc");
while($cc_lista = mysqli_fetch_array($cc)){
	foreach($cc_lista as $campo => $valor){$$campo = stripslashes($valor);}
	$pos = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso where cc_id <= $cc_id and crs_id = $crs_id and idm_id = $idm_id and cp_id = $cp_id order by cc_id"));
	$bol_id = str_pad($cc_id, 6, "0", STR_PAD_LEFT);
	$referencia = $idm_id.$nivel_id.$crs_id.$bol_id;
	
	//omse
	$omse = mysqli_fetch_array(mysqli_query($con, "select * from curso_local cl,om where cl.om_id = om.om_id and cl.cl_id = $cl_id"));
	$om_sigla_on = $omse["om_sigla"];
	$cl_id_on = $omse["cl_id"];
        
          if (strpos($cp_nome, '2016') !== false)
            {
               $cor = 'style="background-color:  palegreen;"';
            }
            else if (strpos($cp_nome, '2017') !== false)
            {
                $cor = 'style="background-color:  yellow;"';
            }
            else if (strpos($cp_nome, '2018.1') !== false)
            {
                $cor = 'style="background-color:  coral;"';
            }
            else if (strpos($cp_nome, '2018.2') !== false)
            {
                $cor = 'style="background-color:  lightblue;"';
            }
            else if (strpos($cp_nome, '2019.1') !== false)
            {
                $cor = 'style="background-color:  #ff52004f;"';
            }
            else if (strpos($cp_nome, '2019.2') !== false)
            {
                $cor = 'style="background-color:  #00ffe74f;"';
            }else{
                  $cor = 'style="background-color:  '.$cp_cor.'"';
            }

        
?>
        <tr <?=$cor?> id="linha<?= $referencia ?>">
<?php 


if( in_array($ccs_id, [0,11] ) ){ 
    $tempagamento = false; ?>
            
                <td><input type="image" src="imagens/i_ok.png" title="Realizar pagamento" value="Realizar" style="width:10px;" nr="<?= $referencia ?>;0;<?= $cad_cpf ?>" ref-realizar="<?= $referencia ?>" referencia="<?= $referencia ?>" tok="<?= $tok ?>" cc_id="<?= $cc_id ?>" acao="del_gru" class="botao-realizar-pagamento"/>
		<input type="image" src="imagens/i_del.png" title="Excluir pagamento" value="Excluir" style="width:10px; display: none;" referencia="<?= $referencia ?>" ref-excluir="<?= $referencia ?>" tok="<?= $tok ?>" cc_id="<?= $cc_id ?>" acao="del_gru"  class="botao-excluir-pagamento" /> <?= $num_inscricao.'-'.$digito_verificador; ?></td>
<?php } else { 
        $tempagamento = true;    
     ?>
                <td><input type="image" src="imagens/i_ok.png" title="Realizar pagamento" value="Realizar" style="width:10px; display: none;" nr="<?= $referencia ?>;0;<?= $cad_cpf ?>" ref-realizar="<?= $referencia ?>" referencia="<?= $referencia ?>" tok="<?= $tok ?>" cc_id="<?= $cc_id ?>" acao="del_gru" class="botao-realizar-pagamento"/>
		<input type="image" src="imagens/i_del.png" title="Excluir pagamento" value="Excluir" style="width:10px;" referencia="<?= $referencia ?>" ref-excluir="<?= $referencia ?>" tok="<?= $tok ?>" cc_id="<?= $cc_id ?>" acao="del_gru"  class="botao-excluir-pagamento" /> <?= $num_inscricao.'-'.$digito_verificador; ?></td>
<?php } 

if($crs_id == 3){
    $crsTipoNome = "Videoconferência";
} else if($crs_id == 6){
    $crsTipoNome = "Presencial";
} else {
    $crsTipoNome = "";
}
?>
            
                <td ><?= $crs_cod ." ".$crsTipoNome." ". $cp_nome ?></td>
		<td><?= $idm_nome ?> (<?= $pos ?>)</td>
		<td><?= $nivel_id ?></td>
		<td>
		<iframe src="omse.php?cid=<?= $cc_id ?>" frameborder="0" width="100" height="22" scrolling="no"></iframe>
		</td>
                <td  ref-situacao="<?= $referencia ?>"><?= $ccs_nome ?></td>
		<td><?= $cc_date ?></td>
                <td>
                    <?php 
                       if( !$tempagamento ) { ?>
                         <input type="button"  title="Cancelar Inscrição" value="Cancelar" style="width:60px;" tok="<?= $tok ?>" cc_id="<?= $cc_id ?>"  referencia="<?= $referencia ?>" acao="canc_insc"  class="botao-cancelar-inscricao" />
                       <?php } ?>                            
                </td>
	</tr>
<?php } ?>
	<tr>
		<td colspan="8" class="tit2">Histórico</td>
	</tr>
	<tr style="tit2">
		<td class="tit" colspan="3">Curso/Exame</td>
		<td class="tit w100px">Idioma</td>
		<td class="tit w50px">Nível</td>
		<td class="tit w150px">OMSE</td>
		<td class="tit w150px">Situação</td>
		<td class="tit w150px">Data</td>
	</tr>
<?php
$cc = mysqli_query($con, "select * from cadastro_curso_lixo cc,curso crs,idioma idm,cadastro_curso_status ccs,curso_periodo cp where cc.crs_id = crs.crs_id and cc.idm_id = idm.idm_id and cc.ccs_id = ccs.ccs_id and cad_id = $cad_id and cc.cp_id = cp.cp_id order by cp.cp_id desc,crs.crs_id,idm.idm_id,ccl_id desc");
while($cc_lista = mysqli_fetch_array($cc)){
	foreach($cc_lista as $campo => $valor){$$campo = stripslashes($valor);}
	//omse
	$omse = mysqli_fetch_array(mysqli_query($con, "select * from curso_local cl,om where cl.om_id = om.om_id and cl.cl_id = $cl_id"));
	$om_sigla = $omse["om_sigla"];
?>
	<tr>
		<td colspan="2"><?= $crs_cod ." ". $cp_nome ?></td>
		<td><?= $idm_nome ?></td>
		<td><?= $nivel_id ?></td>
		<td><?= $om_sigla ?></td>
		<td><?= $ccs_nome ?></td>
		<td><?= $ccl_date ?></td>
	</tr>
<?php } ?>
	<tr>
		<td colspan="8" class="tit2">IPLs</td>
	</tr>
        <tr style="tit2">
		<td class="tit" colspan="4">Idioma</td>
		<td class="tit w100px">EPLO/CA:</td>
		<td class="tit w50px">EPLO/EO:</td>
		<td class="tit w150px">EPLE/CL:</td>
		<td class="tit w150px">EPLE/EE:</td>
	</tr>
	
    <?php 
        $sqlIpls = "SELECT PROF.PES_IDENTIFICADOR_COD,IDI.DESCRICAO as IDIOMA, MAX(PROF.nivel_compr_auditiva) as COMP_AUD,MAX(PROF.nivel_expr_oral) as EXP_ORAL,MAX(PROF.nivel_compr_leitora) as COMP_LEIT,MAX(PROF.nivel_expr_escrita) as EXP_ESC 
                    FROM RH_QUADRO.INDICE_PROF_LINGUISTICA PROF 
                    INNER JOIN RH_QUADRO.IDIOMA_IPL IDI ON PROF.codigo_idioma = IDI.codigo 
                    WHERE PROF.PES_IDENTIFICADOR_COD = '$searchrg' GROUP BY PROF.PES_IDENTIFICADOR_COD,IDI.DESCRICAO";

        $dados = ociparse($oci_connect,$sqlIpls);
        ociexecute($dados);
                                            
        while ($dado = oci_fetch_assoc($dados)) {
    ?>
    <tr>
                <td class="" colspan="4"><?= $dado["IDIOMA"] ?></td>
		<td class=" w100px"><?= ($dado["COMP_AUD"] != 0) ? $dado["COMP_AUD"] : 0; ?></td>
		<td class=" w100px"><?= ($dado["EXP_ORAL"] != 0) ? $dado["EXP_ORAL"] : 0; ?></td>
		<td class=" w100px"><?= ($dado["COMP_LEIT"] != 0) ? $dado["COMP_LEIT"] : 0; ?></td>
		<td class=" w100px"><?= ($dado["EXP_ESC"] != 0) ? $dado["EXP_ESC"] : 0; ?></td>

        </tr>    
    <?php } 
      oci_close($oci_connect); // Fecha a Conexão oracle
       //fim a = 5  ?>
    
	<tr>
		<td colspan="8" class="tit2">Informações do Militar</td>
	</tr>
</table>
	<ul id="lista_aluno">
		<li class="tit">Informações pessoais</li>
		<li class="li1"><label>Nome:</label> <?= $cad_nome ?></li>
		<li class="li2"><label>Identidade:</label> <?= mask($cad_login,'#########-#') ?></li>
		<li class="li1"><label>Pai:</label> <?= $cad_pai ?></li>
		<li class="li2"><label>Mãe:</label> <?= $cad_mae ?></li>
		<li class="li1"><label>Sexo:</label> <?= ($cad_sexo == 1)? "Masculino":"Feminino"; ?></li>
		<li class="li2"><label>Nascimento:</label> <?= $cad_nascimento ?></li>
		<li class="li1"><label>CPF:</label> <?= mask($cad_cpf,'###.###.###-##') ?></li>

		<li class="tit">Informações militares</li>
		<li class="li1"><label>Nome de guerra:</label> <?= $cad_nomeguerra ?></li>
		<li class="li2"><label>Posto/Graduação:</label> <?= $cad_postograd ?></li>
		<li class="li1"><label>QAS/QMS:</label> <?= $cad_qasqms ?></li>
		<li class="li2"><label>Prec-CP:</label> <?= mask($cad_preccp,'## #######') ?></li>
		<li class="li1"><label>Região Militar:</label> <?= $cad_rm ?></li>
		<li class="li2"><label>Comando Militar:</label> <?= $cad_cma ?></li>
		<li class="li1"><label>Organização militar:</label> <?= $cad_om ?></li>

		<li class="tit">Contatos</li>
		<form method="post" id="update_email">
		<input type="hidden" name="token" value="<?= $tok ?>"/>
                <input type="hidden" name="acao" value="update_email">
                <input type="hidden" name="identidade" value="<?= $cad_login ?>">
		<li class="li2"><label>Telefone Fixo:</label> (<?= @$tel0 ?>) <?= @$tel1 ?> <b>Celular:</b> (<?= @$cel0 ?>) <?= @$cel1 ?></li>
                <li class="li1"><label>E-mail:</label><input class="w200px" type="text" name="mail" value="<?= $cad_mail ?>"</input></li>
				<li>
					<button type="button" class="botao" id="gerarNovaSenhaMilitar" >Gerar nova senha</button>
					<label id="senha_gerada"></label>
				</li>
                <input type="submit" class="botao" value="Salvar"/>
                </form>
	</ul>
<?php }//fim dados ?>
<?php }//fim id = 8 ?>

<?php
if($id == 9){//ini consulta exames
?>
<form method="post" action="?id=<?= $id ?>">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<table width="880">
	<tr>
		<td colspan="2" class="tit2">Busca por Curso/Exame</td>
	</tr>
	<tr>
		<td>Curso/Exame</td>
		<td>
		<select name="cid">
			<option>-- Selecione --</option>
<?php
$crs = mysqli_query($con, "select * from curso where not crs_id = 1 and crs_status = 1");
while($crs_lista = mysqli_fetch_array($crs)){
	foreach($crs_lista as $campo => $valor){$$campo = stripslashes($valor);}
?>
			<option value="<?= $crs_id ?>" <?php if($crs_id == $cid){ echo "selected"; } ?>><?= $crs_nome ?></option>
<?php }//fim lista crs ?>
		</select>
		</td>
	</tr>
	<tr class="li1">
		<td>Idioma</td>
		<td>
		<select name="iid" class="w150px">
			<option>-- Selecione --</option>
<?php
$idm = mysqli_query($con, "select * from idioma order by idm_nome");
while($idm_lista = mysqli_fetch_array($idm)){
	foreach($idm_lista as $campo => $valor){$$campo = stripslashes($valor);}
?>
			<option value="<?= $idm_id ?>" <?php if($idm_id == $iid){ echo "selected"; } ?>><?= $idm_nome ?></option>

<?php }//fim lista idm ?>
		</select>
		</td>
	</tr>
	<tr>
		<td>Nível</td>
		<td>
		<select name="nid" class="w150px">
			<option>-- Selecione --</option>
<?php for($x=1;$x<=3;$x++){ ?>
			<option value="<?= $x ?>" <?php if($x == $nid){ echo "selected"; } ?>><?= $x ?></option>
<?php }//fim lista niveis ?>
		</select>
		</td>
	</tr>
	<tr class="li1">
		<td colspan="2" class="tdbotao"><input type="submit" class="botao" value="Buscar"/></td>
	</tr>
</table>
</form>

<?php if($cid  && Token::check($token)){//ini search ?>
<table width="880">
	<tr>
		<td class="tit2 w100px">Identidade</td>
		<td class="tit2">Militar</td>
		<td class="tit2 w100px">Posto/Grad</td>
		<td class="tit2 w50px">Curso</td>
		<td class="tit2 w50px">Idioma</td>
		<td class="tit2 w50px">Nível</td>
		<td class="tit2 w150px">OMSE</td>
	</tr>
<?php
	$i = 0;
	$idm = ($iid) ? "and idm.idm_id = ". $iid : "";
	$nivel = ($nid) ? "and nivel_id = ". $nid : "";
	$cc = mysqli_query($con, "select * from cadastro_curso cc,cadastro c,curso crs,idioma idm,curso_local cl,om,rm where cl.cl_id = cc.cl_id and cl.om_id = om.om_id and om.rm_id = rm.rm_id and crs.crs_id = cc.crs_id and cc.idm_id = idm.idm_id and cc.cad_id = c.cad_id and cc.crs_id = $cid and cc.ccs_id = 1 $idm $nivel and cc.cp_id = $last_cp_id order by nivel_id,rm.rm_id,om.om_id,cad_nome,idm.idm_id");
	while($cc_lista = mysqli_fetch_array($cc)){
		foreach($cc_lista as $campo => $valor){$$campo = stripslashes($valor);}
		$i++;
?>
	<tr class="<?= linecolor($i) ?>">
		<td><?= $cad_login ?></td>
		<td><?= $cad_nome ?></td>
		<td><?= $cad_postograd ?></td>
		<td><?= $crs_cod ?></td>
		<td><?= $idm_sigla ?></td>
		<td><?= $nivel_id ?></td>
		<td><?= $rm_sigla ." - ". $om_sigla ?></td>
	</tr>
<?php } ?>
</table>
<?php }//fim search ?>
<?php }//fim id = 9 ?>

<?php
if($id == 10){//ini militares por omse
?>
<table width="880">
	<tr>
           <td colspan="1" class="tit2">Período:
	  <select name="periodo_id" onchange="location='?id=<?= $id ?>&amp;pid='+ this.value">
                        <option></option>
                <?php
                $cp = mysqli_query($con, "select cp_id, cp_nome, DATE_FORMAT(cp_ini,'%d/%m/%Y') as cp_ini from curso_periodo ");
                while($periodos = mysqli_fetch_array($cp)){
                        foreach($periodos as $campo => $valor){$$campo = addslashes($valor);}
                ?>
                        <option value="<?= $cp_id ?>" <?php if($cp_id == @$_GET['pid']){ echo "selected"; } ?>><?= $cp_nome." - .".$cp_ini ?></option>
                <?php } ?>
                </select>
           </td>   
            <td colspan="1" class="tit2">OMSE:
<select name="om_id" onchange="location='?id=<?= $id ?>&amp;pid=<?= @$_GET['pid'] ?>&amp;oid='+ this.value">
	<option></option>
<?php
$om = mysqli_query($con, "select * from rm,om where rm.rm_id = om.rm_id order by rm.rm_id, om.om_nome, om_uf, om_municipio");
while($om_lista = mysqli_fetch_array($om)){
	foreach($om_lista as $campo => $valor){$$campo = addslashes($valor);}
?>
	<option value="<?= $om_id ?>" <?php if($om_id == $oid){ echo "selected"; } ?>><?= $rm_sigla ." - ".$om_sigla." - ".$om_nome . " - ". $om_municipio ." - ". $om_uf ?></option>
<?php } ?>
</select>
		</td>
	</tr>
</table>
<?php if($oid){//ini oid selecionada ?>
<table width="880">
	<tr>
		<td class="tit2">Curso/Exame</td>
		<td class="tit2">Idioma</td>
		<td class="tit2">Nível</td>
	</tr>
<?php
$crs = mysqli_query($con, "select * from curso crs,idioma idm,curso_idioma ci where crs.crs_id = ci.crs_id and idm.idm_id = ci.idm_id and crs_status = 1");
while($crs_lista = mysqli_fetch_array($crs)){
	$cid = $crs_lista["crs_id"];//curso_id
	$iid = $crs_lista["idm_id"];//idioma_id
	$crs_nome = $crs_lista["crs_nome"];
	$idm_nome = $crs_lista["idm_nome"];
	for($x=1;$x<=3;$x++){//ini for nivel: nivel_id
?>
	<tr>
		<td class="tit"><?= $crs_nome ?></td>
		<td class="tit"><?= $idm_nome ?></td>
		<td class="tit">Nível <?= $x ?></td>
	</tr>
	
<?php
$pid = md5($_GET['pid']);
$cad = mysqli_query($con, "select * from cadastro_curso cc,curso_local cl,cadastro cad where cc.cad_id = cad.cad_id and cc.cl_id = cl.cl_id and cl.om_id = $oid and cc.crs_id = $cid and cc.idm_id = $iid and cc.nivel_id = $x and cc.ccs_id = 1 and md5(cc.cp_id) = '{$pid}' ");
while($cad_lista = mysqli_fetch_array($cad)){
	foreach($cad_lista as $campo => $valor){$$campo = addslashes($valor);}
	$i++;
?>
	<tr class="<?= linecolor($i) ?>">
		<td colspan="2"><?= $cad_login ?><br/><?= $cad_nome ?><br/><?= $cad_postograd ?></td>
<form method="post" action="?id=8">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="searchby" value="cad_login"/>
<input type="hidden" name="searchrg" value="<?= $cad_login ?>"/>
		<td><input type="submit" value="Ver" class="botao"/></td>
</form>
	</tr>	
<?php }// fim lista candidatos ?>
	
<?php
	}//fim for nivel
}//fim lista crs
?>
</table>
<?php }//fim oid ?>
<?php }//fim id = 10 ?>

<?php
if($id == 11){//solicitacoes de cancelamento de inscricao
?>
<table width="880">
	<tr>
		<td class="tit2">Nome</td>
		<td class="tit2" width="100">Curso/Exame</td>
		<td class="tit2" width="100">Idioma</td>
		<td class="tit2" width="100">Período</td>
		<td class="tit2" width="50">Nível</td>
		<td class="tit2" width="100">Dt Solicitação</td>
		<td class="tit2" width="100">Usuário</td>
		<!--<td class="tit2" width="100"></td>-->
	</tr>
<?php
$cc = mysqli_query($con, "select * from cadastro_curso_lixo cc,cadastro cad,curso crs,idioma idm, curso_periodo cp, user where cc.cad_id = cad.cad_id and cc.crs_id = crs.crs_id and cc.idm_id = idm.idm_id and cc.user_id = user.u_id and cp.cp_id = cc.cp_id and cc.cp_id = $last_cp_id");
while($cc_lista = mysqli_fetch_array($cc)){
	foreach($cc_lista as $campo => $valor){$$campo = addslashes($valor);}
	$i++;
?>
<form method="post">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="acao" value="cancelhomolog">
<input type="hidden" name="cc_id" value="<?= $cc_id ?>">
	<tr class="<?= linecolor($i) ?>">
		<td><?= $cad_nome ?></td>
		<td><?= $crs_cod ?></td>
		<td><?= $idm_nome ?></td>
		<td><?= $cp_nome ?></td>
		<td><?= $nivel_id ?></td>
		<td><?= formatdate($cc_date) ?></td>
		<td><?= $u_nome ?></td>
		<!--<td><input type="submit" value="Homologar" onclick="return confirmacao('cancelar',' a inscrição <?php // $crs_cod ." ". $idm_sigla ." ". $nivel_id ?> do candidato <?php // $cad_nome ?>','')"/></td>-->
	</tr>
</form>
<?php }//fim lista ?>
</table>
<?php }//fim id = 11?>

<?php
if($id == 12){//solicitacoes de gratuidade de inscricao
	if(isset($codpg) && $codpg == "54,55,56,57"){//cadete
		$aman = "selected";
	}
	if(isset($codpg) && $codpg == "64"){//sgt
		$sgt = "selected";
	}
?>
<form method="post" action="?id=12">
<table width="880">
	<tr>
		<td colspan="2" class="tit2">Selecione o tipo de aluno:
                    <select name="codpg" class="w150px">
                            <option value="">-- Selecione --</option>
                            <option value="54,55,56,57" <?= isset($aman) ? $aman : ""; ?>>Cadetes da AMAN</option>
                            <option value="59,61,62,63">Alunos do IME</option>
                            <option value="64" <?= isset($sgt) ? $sgt : ""; ?>>Alunos CFS</option>
                    </select> 
                    <input type="submit" value="Listar" class="botao"/>
		</td>
	</tr>
</table>
</form>
<br/>
<?php if(isset($codpg)){ ?>
<form method="post"/>
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="acao" value="homologall"/>
<input type="hidden" name="cp_id" value="<?= $last_cp_id ?>"/>
<input type="hidden" name="codgp" value="<?= $codpg ?>"/>
<img src="imagens/i_ok.png" width="15"/> Liberar todas as gratuidades para o tipo de aluno selecionado? <input type="submit" value="Confirmar" onclick="return confirmacao('deferir',' a gratuidade de todas as inscrições para o tipo de aluno selecionado','')" class="botao"/><br/>
</form>
<br/>
<table width="880">
	<tr>
		<td class="tit2" width="100">Identidade</td>
		<td class="tit2">Nome</td>
		<td class="tit2" width="100">Curso/Exame</td>
		<td class="tit2" width="100">Idioma</td>
		<td class="tit2" width="50">Nível</td>
		<td class="tit2" width="100">Dt Solicitação</td>
		<td class="tit2" width="100"></td>
		<td class="tit2" width="100"></td>
	</tr>
<?php
$cc = mysqli_query($con, "select * from cadastro_curso cc,cadastro cad,curso crs,idioma idm,gratuidade gra where cad.cad_codpg in ($codpg) and gra.cc_id = cc.cc_id and cc.cad_id = cad.cad_id and cc.crs_id = crs.crs_id and cc.idm_id = idm.idm_id and ccs_id = 0 and cc.cp_id = $last_cp_id order by cad_nome");
while($cc_lista = mysqli_fetch_array($cc)){
	foreach($cc_lista as $campo => $valor){$$campo = addslashes($valor);}
	$i++;
?>
	<tr class="<?= linecolor($i) ?>">
		<td><?= $cad_login ?></td>
		<td>(<?= $cad_codpg ?>) <?= $cad_nome ?></td>
		<td><?= $crs_cod ?></td>
		<td><?= $idm_nome ?></td>
		<td><?= $nivel_id ?></td>
		<td><?= formatdate($cc_date) ?></td>
<form method="post">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="acao" value="gratishomolog">
<input type="hidden" name="cc_id" value="<?= $cc_id ?>">
		<td><input type="submit" value="Deferir" onclick="return confirmacao('deferir',' a gratuidade de inscrição <?= $crs_cod ." ". $idm_sigla ." ". $nivel_id ?> do candidato <?= $cad_nome ?>','')" class="botao"/></td>
</form>
<form method="post">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="acao" value="gratisnega">
<input type="hidden" name="cc_id" value="<?= $cc_id ?>">
		<td><input type="submit" value="Indeferir" onclick="return confirmacao('indeferir',' a gratuidade de inscrição <?= $crs_cod ." ". $idm_sigla ." ". $nivel_id ?> do candidato <?= $cad_nome ?>','')" class="botao"/></td>
</form>
	</tr>
<?php }//fim lista ?>
</table>
<?php }//fim codpg ?>
<?php }//fim id = 12 ?>

<?php if($id == 13){//ini inscrever ?>
<b>Lançamento manual dos Inscritos</b><br/><br/>
<form method="post">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="acao" value="add_manual">
Identidade:<br/>
<input type="text" name="rg" style="width:150px"><br/>
<br/>
<b>EPLO/EOExame de Proficiência Linguística Oral/Expressão Oral</b><br/>
<input type="hidden" name="crs_id" value="3"/>
<br/>
Idioma:<br/>
<select name="idm_id" style="width:150px">
	<option></option>
	<option value="3" >Francês</option>
	<option value="4" >Inglês</option>
	<option value="6" >Russo</option>
</select><br/>
<br/>
Nível:<br/>
<select id="nivel" name="nivel" style="width:150px">
	<option value=""></option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
</select><br/>
<input type="submit" value="Inscrever manualmente" style="width:150px"/>
</form>
<?php }//fim id = 13 ?>

<?php if($id == 14){//ini listar por nome ?>
<table width="880">
	<tr>
		<td colspan="3" class="tit2">Buscar Militar</td>
	<tr>
	<tr>
		<td class="w150px">
<form method="post">
Nome do Militar: <input type="text" name="nome" style="width:200px"/>
<input type="submit" value="Localizar" class="botao"/>
</form>
		</td>
	</tr>
</table>	
<br/>
<?php
if(isset($nome)){
	$total_cad = mysqli_num_rows(mysqli_query($con, "select * from cadastro where cad_nome like '%". $nome ."%' order by cad_nome"));
	echo "Foram encontradas <b>". $total_cad ."</b> ocorrências para <b>". $nome ."</b>.";
?>
<br/><br/>
<table width="880">
	<tr>
		<td class="tit2">Nome</td>
		<td class="tit2" width="100">Identidade</td>
		<td class="tit2" width="100">CPF</td>
		<td class="tit2" width="100"></td>		
	</tr>
<?php
	$cad = mysqli_query($con, "select * from cadastro where cad_nome like '%". $nome ."%' order by cad_nome");
	while($cad_lista = mysqli_fetch_array($cad)){//ini while
		foreach($cad_lista as $campo => $valor){$$campo = addslashes($valor);}
		$i++;
?>
<form method="post" action="?id=8">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="searchby" value="cad_login"/>
<input type="hidden" name="searchrg" value="<?= $cad_login ?>"/>
	<tr class="<?= linecolor($i) ?>">
		<td><?= $cad_nome ?></td>
		<td><?= $cad_login ?></td>
		<td><?= $cad_cpf ?></td>
		<td><input type="submit" value="Ver" class="botao"/></td>
	</tr>
</form>
<?php }//fim while
}//fim if
?>
</table>
<?php }//fim id = 14 ?>

<?php if($id == 15){//ini lista de espera EPLO/EO ?>
<table width="880">
	<tr>
		<td colspan="2" class="tit2">Selecione o Idioma:
		<select onchange="location='?id=<?= $id ?>&amp;cpid=<?= $last_cp_id ?>&amp;iid='+ this.value">
			<option>-- Selecione --</option>
<?php
$cpid = htmlspecialchars((int)$_GET["cpid"]);
$iid = htmlspecialchars((int)$_GET["iid"]);
$idm = mysqli_query($con, "select * from idioma order by idm_id");
while($idm_lista = mysqli_fetch_array($idm)){
	foreach($idm_lista as $campo => $valor){$$campo = addslashes($valor);}
?>
			<option value="<?= $idm_id ?>" <?php if($idm_id == $iid){ echo "selected"; } ?>><?= $idm_nome ?></option>
<?php } ?>
		</select>
		</td>
	</tr>
</table>
<br/>
<table width="880">
	<tr>
		<td class="tit2" colspan="8">Lista de militares EPLO/EO - Vídeoconferência:</td>
	</tr>
<?php
$cid = 3;//eplo/eo
$vagas = mysqli_fetch_array(mysqli_query($con, "select * from curso_idioma where crs_id = $cid and idm_id = $iid"));
$qtdvagas = $vagas["ci_vagas"];
?>
	<tr>
		<td colspan="8">Vagas para EPLO/EO: <?= $qtdvagas ?></td>
	</tr>
	<tr>
		<td class="tit2 w50px">Nr</td>
		<td class="tit2 w100px">CPF</td>
		<td class="tit2 w100px">Identidade</td>
		<td class="tit2 w100px">Nº Inscrição</td>
		<td class="tit2">Nome</td>
		<td class="tit2">E-mail</td>
		<td class="tit2 w50px">Posto/Grad</td>
		<td class="tit2 w100px">OMSE</td>
		<td class="tit2 w50px">Nível</td>
		<td class="tit2 w50px">Pago</td>
		<td class="tit2 w100px"></td>
	</tr>
<?php
$eoing = mysqli_query($con, "select * from cadastro_curso cc,cadastro cad where cad.cad_id = cc.cad_id and crs_id = $cid and idm_id = $iid and cp_id = $cpid order by cc_id");
while($eoing_lista = mysqli_fetch_array($eoing)){//ini lista eoing
	foreach($eoing_lista as $campo => $valor){$$campo = addslashes($valor);}
	if($ccs_id == 1){
		$pago = "S";
		$color = "green";
	}else{
		$pago = "N";
		$color = "red";
	}
	
	$i++;
	if($i > $qtdvagas){
		$color = "orange";
	}else{
		if(in_array($ccs_id, [0,11] )){
			$mail_naopago = @$mail_naopago .",<br/>". $cad_mail;
		}
	}
	
	//omse
	$omse = mysqli_fetch_array(mysqli_query($con, "select * from curso_local cl,om where cl.om_id = om.om_id and cl.cl_id = $cl_id"));
	$om_sigla = $omse["om_sigla"];
?>
	<tr class="<?= linecolor($i) ?>" style="color:<?= $color ?>">
		<td><?= $i ?></td>
		<td><?= $cad_cpf ?></td>
		<td><?= $cad_login ?></td>
		<td><?= $num_inscricao.'-'.$digito_verificador ?></td>
		<td><?= $cad_nome ?></td>
		<td><?= $cad_mail ?></td>
		<td><?= $cad_postograd ?></td>
		<td><?= $om_sigla ?></td>
		<td><?= $nivel_id ?></td>
		<td><?= $pago ?></td>
<form method="post" action="?id=8">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="searchby" value="cad_login"/>
<input type="hidden" name="searchrg" value="<?= $cad_login ?>"/>
		<td><input type="submit" value="Ver" class="botao"/></td>
</form>
	</tr>
<?php }//fim lista eo ?>
</table>
<br/>
<table width="880">
	<tr>
		<td class="tit2">Militares dentro da vaga e não pagos:</td>
	</tr>
	<tr>
		<td><?= isset($mail_naopago) ? $mail_naopago : ""; ?></td>
	</tr>
</table>
<?php }//fim lista de espera EPLO/EO ?>


<?php if($id == 16){ ?>
<table width="880">
	<tr>
            <td colspan="1" class="tit2">Período:
	  <select name="periodo_id" onchange="location='?id=<?= $id ?>&amp;pid='+ this.value">
                        <option></option>
                <?php
                $cp = mysqli_query($con, "select cp_id, cp_nome, DATE_FORMAT(cp_ini,'%d/%m/%Y') as cp_ini from curso_periodo ");
                while($periodos = mysqli_fetch_array($cp)){
                        foreach($periodos as $campo => $valor){$$campo = addslashes($valor);}
                ?>
                        <option value="<?= $cp_id ?>" <?php if($cp_id == @$_GET['pid']){ echo "selected"; } ?>><?= $cp_nome." - .".$cp_ini ?></option>
                <?php } ?>
                </select>
           </td> 
		<td colspan="1" class="tit2">OMSE:
<select name="om_id" onchange="location='?id=<?= $id ?>&amp;pid=<?= @$_GET['pid'] ?>&amp;oid='+ this.value">
	<option></option>
<?php
$om = mysqli_query($con, "select * from rm,om where rm.rm_id = om.rm_id order by rm.rm_id, om.om_nome, om_uf, om_municipio");
while($om_lista = mysqli_fetch_array($om)){
	foreach($om_lista as $campo => $valor){$$campo = addslashes($valor);}
?>
	<option value="<?= $om_id ?>" <?php if($om_id == $oid){ echo "selected"; } ?>><?= $rm_sigla ." - ".$om_sigla." - ". $om_nome . " - ". $om_municipio ." - ". $om_uf ?></option>
<?php } ?>
</select>
		</td>
	</tr>
</table>
<?php if($oid){//ini oid selecionada ?>
<table width="880">
	<tr>
		<td class="tit2">Curso/Exame</td>
		<td class="tit2">Idioma</td>
		<td class="tit2">Nível</td>
	</tr>
<?php
$crs = mysqli_query($con, "select * from curso crs,idioma idm,curso_idioma ci where crs.crs_id in (2,3,4,5) and crs.crs_id = ci.crs_id and idm.idm_id = ci.idm_id and crs_status = 1");
while($crs_lista = mysqli_fetch_array($crs)){
	$cid = $crs_lista["crs_id"];//curso_id
	$iid = $crs_lista["idm_id"];//idioma_id
	$crs_nome = $crs_lista["crs_nome"];
	$idm_nome = $crs_lista["idm_nome"];
	$ci_dtinicio = $crs_lista["ci_dtinicio"];
	for($x=1;$x<=3;$x++){//ini for nivel: nivel_id
?>
	<tr>
		<td class="tit"><?= $crs_nome ?> (<?= formatdate($ci_dtinicio) ?>)</td>
		<td class="tit"><?= $idm_nome ?></td>
		<td class="tit">Nível <?= $x ?></td>
	</tr>
	
<?php
$i = 0;
$pid = md5($_GET['pid']);
$cad = mysqli_query($con, "select * from cadastro_curso cc,curso_local cl,cadastro cad where cc.cad_id = cad.cad_id and cc.cl_id = cl.cl_id and cl.om_id = $oid and cc.crs_id = $cid and cc.idm_id = $iid and cc.nivel_id = $x and cc.ccs_id = 1 and md5(cc.cp_id) = '{$pid}' order by cad.cad_nome");
while($cad_lista = mysqli_fetch_array($cad)){
	foreach($cad_lista as $campo => $valor){$$campo = addslashes($valor);}
	$i++;
	@$tot++;
?>
	<tr class="<?= linecolor($i) ?>">
		<td colspan="3"><label style="width:150px;font-weight:normal"><?= $cad_postograd ?></label><?= $cad_nome ?> - <?= $cad_om ?> - <?= $cad_mail ?></td>
	</tr>	
<?php }// fim lista candidatos ?>
	<tr style="background:#666;color:#FFF"><td colspan="3"><b>Total:</b> <?= $i ?></td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
<?php
	}//fim for nivel
}//fim lista crs
?>
	<tr><td colspan="3" bgcolor="yellow"><b>TOTAL DE EXAMES NESTA OMSE:</b> <?= $tot ?></td></tr>
</table>
<?php }//fim oid ?>
<?php }//fim id = 16 ?>

<?php if($id == 17){//lista extratos ?>
<table width="880">
	<tr>
		<td colspan="2" class="tit2">Extrato:
		<select onchange="location='?id=<?= $id ?>&amp;pexid='+ this.value">
			<option></option>
<?php
$pexid = -1;
if (! empty( htmlspecialchars($_GET["pexid"] ))){
  $pexid = htmlspecialchars((int)$_GET["pexid"]);
}
$pex = mysqli_query($con, "select * from pagamento_extrato order by pex_id desc");
while($pex_lista = mysqli_fetch_array($pex)){
	foreach($pex_lista as $campo => $valor){$$campo = addslashes($valor);}
?>
			<option value="<?= $pex_id ?>" <?php if($pex_id == $pexid){ echo "selected"; } ?>><?= $pex_data ?></option>
<?php } ?>
		</select>
		</td>
	</tr>
</table>
<br/><br/>
<?php
if(!empty($pexid)){
	$pex = mysqli_fetch_array(mysqli_query($con, "select * from pagamento_extrato where pex_id = $pexid"));
	$pex_texto = $pex["pex_texto"];
	echo $pex_texto;
}
?>
<br/>
<?php }//fim id = 17 ?>

<?php if($id == 18){//lista extratos ?>
<table width="880">
	<tr>
		<td colspan="2" class="tit2">Selecione o período:
		<select onchange="location='?id=<?= $id ?>&amp;cpini='+ this.value">
			<option></option>
<?php
$cpini = htmlspecialchars((int)$_GET["cpini"]);
if(empty($cpini)){
	$cp = mysqli_fetch_array(mysqli_query($con, "select * from curso_periodo order by cp_id desc limit 1"));
	$cpid = $cp["cp_id"];
	$cpini = $cp["cp_ini"];
}
$cp = mysqli_query($con, "select * from curso_periodo order by cp_id desc");
while($cp_lista = mysqli_fetch_array($cp)){
	foreach($cp_lista as $campo => $valor){$$campo = addslashes($valor);}
?>
			<option value="<?= $cpini ?>" <?php if($cp_ini == $cpini){ echo "selected"; } ?>><?= $cp_nome ?></option>
<?php } ?>
		</select>
		</td>
	</tr>
</table>
<br/><br/>
<table width="880">
	<tr>
		<td class="tit2">Nome</td>
		<td class="tit2" width="150">Número Referência</td>
		<td class="tit2" width="150">Valor</td>
		<td class="tit2" width="150">Baixa do pgto</td>
		<td class="tit2"></td>
	</tr>
<?php
//$cc = mysqli_query($con, "select * from pagamento_erro pe,cadastro cad,cadastro_curso cc where (cc.cc_id = pe.cc_id or pe.cc_id = 0) and pe.cad_id = cad.cad_id and cc.cp_id = $cpid");
$cc = mysqli_query($con, "select * from pagamento_erro pe,cadastro cad where pe.cad_id = cad.cad_id and pe.pe_data >= '$cpini'");
while($cc_lista = mysqli_fetch_array($cc)){
	foreach($cc_lista as $campo => $valor){$$campo = addslashes($valor);}
	$i++;
?>
<form method="post" action="?id=8">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="searchby" value="cad_login"/>
<input type="hidden" name="searchrg" value="<?= $cad_login ?>"/>
	<tr class="<?= linecolor($i) ?>">
		<td><?= $cad_nome ?></td>
		<td><?= $cc_id ?></td>
		<td><?= $pe_valor ?></td>
		<td><?= $pe_data ?></td>
		<td><input type="submit" value="Ver" class="botao"/></td>
	</tr>
</form>
<?php } ?>
</table>
<?php }//fim id = 18 ?>

<?php if($id == 19){//ini lista de espera EPLO/EO ?>
<table width="880">
	<tr>
		<td colspan="2" class="tit2">Selecione o Idioma:
		<select onchange="location='?id=<?= $id ?>&amp;cpid=<?= $last_cp_id ?>&amp;iid='+ this.value">
			<option>-- Selecione --</option>
<?php
$cpid = htmlspecialchars((int)$_GET["cpid"]);
$iid = htmlspecialchars((int)$_GET["iid"]);
$idm = mysqli_query($con, "select * from idioma order by idm_id");
while($idm_lista = mysqli_fetch_array($idm)){
	foreach($idm_lista as $campo => $valor){$$campo = addslashes($valor);}
?>
			<option value="<?= $idm_id ?>" <?php if($idm_id == $iid){ echo "selected"; } ?>><?= $idm_nome ?></option>
<?php } ?>
		</select>
		</td>
	</tr>
</table>
<br/>
<table width="880">
	<tr>
		<td class="tit2" colspan="8">Lista de militares EPLO/EO - Presencial:</td>
	</tr>
<?php
$cid = 6;//eplo/eo
$vagas = mysqli_fetch_array(mysqli_query($con, "select * from curso_idioma where crs_id = $cid and idm_id = $iid"));
$qtdvagas = $vagas["ci_vagas"];
?>
	<tr>
		<td colspan="8">Vagas para EPLO/EO: <?= $qtdvagas ?></td>
	</tr>
	<tr>
		<td class="tit2 w50px">Nr</td>
		<td class="tit2 w100px">Identidade</td>
		<td class="tit2 w100px">Nº Inscrição</td>
		<td class="tit2">Nome</td>
		<td class="tit2">E-mail</td>
		<td class="tit2 w50px">Posto/Grad</td>
		<td class="tit2 w100px">OMSE</td>
		<td class="tit2 w50px">Nível</td>
		<td class="tit2 w50px">Pago</td>
		<td class="tit2 w100px"></td>
	</tr>
<?php
$eoing = mysqli_query($con, "select * from cadastro_curso cc,cadastro cad where cad.cad_id = cc.cad_id and crs_id = $cid and idm_id = $iid and cp_id = $cpid order by cc_id");
while($eoing_lista = mysqli_fetch_array($eoing)){//ini lista eoing
	foreach($eoing_lista as $campo => $valor){$$campo = addslashes($valor);}
	if($ccs_id == 1){
		$pago = "S";
		$color = "green";
	}else{
		$pago = "N";
		$color = "red";
	}
	
	$i++;
	if($i > $qtdvagas){
		$color = "orange";
	}else{
		if(in_array($ccs_id, [0,11] )){
			$mail_naopago = @$mail_naopago .",<br/>". $cad_mail;
		}
	}
	
	//omse
	$omse = mysqli_fetch_array(mysqli_query($con, "select * from curso_local cl,om where cl.om_id = om.om_id and cl.cl_id = $cl_id"));
	$om_sigla = $omse["om_sigla"];
?>
	<tr class="<?= linecolor($i) ?>" style="color:<?= $color ?>">
		<td><?= $i ?></td>
		<td><?= $cad_login ?></td>
		<td><?= $num_inscricao.'-'.$digito_verificador ?></td>
		<td><?= $cad_nome ?></td>
		<td><?= $cad_mail ?></td>
		<td><?= $cad_postograd ?></td>
		<td><?= $om_sigla ?></td>
		<td><?= $nivel_id ?></td>
		<td><?= $pago ?></td>
<form method="post" action="?id=8">
<input type="hidden" name="token" value="<?= $tok ?>"/>
<input type="hidden" name="searchby" value="cad_login"/>
<input type="hidden" name="searchrg" value="<?= $cad_login ?>"/>
		<td><input type="submit" value="Ver" class="botao"/></td>
</form>
	</tr>
<?php }//fim lista eo ?>
</table>
<br/>
<table width="880">
	<tr>
		<td class="tit2">Militares dentro da vaga e não pagos:</td>
	</tr>
	<tr>
		<td><?= isset($mail_naopago) ? $mail_naopago : ""; ?></td>
	</tr>
</table>
<?php }//fim lista de espera EPLO/EO ?>

  <?php if($id == 22){ ?>
                <?php
                   include 'cadastro.php';                   

                 ?>
 <?php   
  } ?> 

 <?php if($id == 23){ ?>
                <?php
                   include 'minhasinscricoes.php';                   

                 ?>
 <?php   
  } ?> 


<?php if($id == 25){ 
    
    include 'perfilusuario.php';    
}
?>

<?php if($id == 28){ 
    
    include './relinscricaolote.php';    
}
?>
<?php if($id == 29){ 
    
    include './relinscricaolotedetalhado.php';    
}
 if($id == 31){ 
    
    include './atualizacandidatos.php';    
}
?>

 
<?php }#fim adm_on ?>

<?php if($id == 21){//ini regras ?>
    <table width="880">
       <tr>
	  <td class="tit2">Regras</td>
       </tr>
       <tr>
           <td>
                <?php
                   include 'configregraposto.php';                   

                 ?>
          </td>      
       </tr> 
   </table>
  <?php   
  }//fim regras ?> 

<?php if($id == 32){//ini período ?>
    <table width="880">
       <tr>
	  <td class="tit2">Período</td>
       </tr>
       <tr>
           <td>
                <?php
                   include 'periodo.php';                   

                 ?>
          </td>      
       </tr> 
   </table>
  <?php   
  }//fim período ?> 

</div>
 <?php if($id == 20){//ini relatório de log ?>
 <!-- 
    <table width="880">
       <tr>
	  <td class="tit2">Relatório de log</td>
       </tr>
       <tr>
           <td>
                <?php
                   //include 'relatoriolog.php';                   

                 ?>
          </td>      
       </tr> 
   </table>
   -->
  <?php   
  }//fim relatório de log ?>   

 <?php if($id == 30){//ini baixa de pagamentos ?>
   <table width="880">
       <tr>
	  <td class="tit2">Baixa de pagamentos</td>
       </tr>
       <tr>
           <td>
               <form method="post" >
                   <input type="hidden" name="token" value="<?= $tok ?>"/>
                   <input type="hidden" name="acao" value="baixapagamento"/>
                   <label for="dataini">Data inicial</label>
                   <input type="date" name="dataini" >
                   <label for="datafim">Data final</label>
                   <input type="date" name="datafim" >                       
                   <input type="submit" value="Executar" name="btnExecPagamentos" />    
               </form>  
          </td>      
       </tr> 
   </table>
  
 <?php   
  }//fim baixa de pagamentos ?>   
 
</div>
</body>
</html>
<?php
if(empty($_SESSION["token"])){
	$_SESSION["token"] = $tok;
}
$_SESSION["token_safe"] = @$_SESSION["token"];
?>