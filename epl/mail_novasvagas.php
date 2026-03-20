<?php
include '../system/system.php';
$crs_id = 3;//eplo/eo
$idm_id = 4;//ingles

$vagas = mysqli_fetch_array(mysqli_query($con, "select * from curso_idioma where crs_id = $crs_id and idm_id = $idm_id"));
$ci_vagas = $vagas["ci_vagas"];
//$npv = mysqli_query($con, "select * from cadastro_curso where crs_id = $crs_id and idm_id = $idm_id order by cc_id  limit $ci_vagas");
$npv = mysqli_query($con, "select * from cadastro cad,cadastro_curso cc where cad.cad_id = cc.cad_id and crs_id = $crs_id and idm_id = $idm_id order by cc_id limit $ci_vagas");
echo $ci_vagas ."<p>";
$abrir = 0;
while($lista_npv = mysqli_fetch_array($npv)){
	foreach($lista_npv as $campo => $valor){$$campo = addslashes($valor);}
	if( in_array($ccs_id, [0,11] ) ){
		$abrir++;
		//echo $abrir ." - ". $cc_id." - ". $cad_id ." - ". $cad_mail ." - ". $ccs_id ."<br/>";
		echo $cad_mail .",";
		if($abrir == 20){ $abrir = 0; echo "<br/><br/>"; }
	}
}
?>