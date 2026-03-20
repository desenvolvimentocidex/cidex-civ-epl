<?php
include '../system/system.php';
$i = 0;
//ini verifica ultimo periodo para epl
$cp = mysqli_fetch_array(mysqli_query($con, "select * from curso_periodo order by cp_id desc"));
$cp_nome = $cp["cp_nome"];
$last_cp_id = $cp["cp_id"];//ultimo periodo cadastrado
//fim verifica ultimo periodo para epl
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="br" lang="pt">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title><?= $sis_sigla ?> :: <?= $sis_nome ?></title>
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
	</head>
<body>
<div>
<br/><br/>
<table width="880">
	<tr>
		<td><b>EPLE/EPLO <?= $cp_nome ?> :: Exames por OMSE</b></td>
	</tr>
<table>
<br/>
<table width="880">
	<tr>
		<td colspan="2" class="tit2">Selecione a OMSE:
<select name="om_id" onchange="location='?id=<?= $id ?>&amp;oid='+ this.value">
	<option></option>
<?php
$om = mysqli_query($con, "select * from rm,om where rm.rm_id = om.rm_id order by rm.rm_id, om.om_nome, om_uf, om_municipio");
while($om_lista = mysqli_fetch_array($om)){
	foreach($om_lista as $campo => $valor){$$campo = addslashes($valor);}
?>
	<option value="<?= $om_id ?>" <?php if($om_id == $oid){ echo "selected"; } ?>><?= $rm_sigla ." - ". $om_nome . " - ". $om_municipio ." - ". $om_uf ?></option>
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
	
	$tn1 = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso cc,curso_local cl where cc.nivel_id = 1 and cc.crs_id = $crs_id and cc.idm_id = $idm_id and cc.ccs_id = 1 and cl.cl_id = cc.cl_id and cl.om_id = $oid and cc.cp_id = $last_cp_id"));
	$tn2 = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso cc,curso_local cl where cc.nivel_id = 2 and cc.crs_id = $crs_id and cc.idm_id = $idm_id and cc.ccs_id = 1 and cl.cl_id = cc.cl_id and cl.om_id = $oid and cc.cp_id = $last_cp_id"));
	$tn3 = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso cc,curso_local cl where cc.nivel_id = 3 and cc.crs_id = $crs_id and cc.idm_id = $idm_id and cc.ccs_id = 1 and cl.cl_id = cc.cl_id and cl.om_id = $oid and cc.cp_id = $last_cp_id"));
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
?>
</div>
</body>
</html>