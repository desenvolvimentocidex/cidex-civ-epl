<?php
include 'system/system.php';
$dwl = mysqli_fetch_array(mysqli_query($con, "select * from downloads where dwl_id = $id and dwl_status = 1"));
if($dwl){
	mysqli_query($con, "update downloads set dwl_qtd = dwl_qtd + 1 where dwl_id = $id ");//soma mais 1 download
	foreach($dwl as $campo => $valor){$$campo = stripslashes($valor);}
	if(file_exists("downloads/". $dwl_file)){
		header("Content-Type: application/save");
		header("Content-Length:".filesize("downloads/". $dwl_file));
		header("Content-Disposition: attachment; filename=CEADEx_". $dwl_file);
		header("Content-Transfer-Encoding: binary");
		header("Expires: 0");
		header("Pragma: no-cache");
		$fp = fopen("downloads/$dwl_file", "r");
		fpassthru($fp);
		fclose($fp);
	}else{
		echo "<script>alert('ERRO\\n\\nO arquivo $dql_file não foi localizado.');location='index.php';</script>";
		exit;
	}
}else{
	echo "<script>location='index.php';</script>";
	exit;
}
?>