<?php
include '../system/system.php';
if(@$_SESSION["loged"] == "adm_on"){
	if($acao == "update_cl"){
		$clold = htmlspecialchars($_GET["cl_old"]);
		$clid = htmlspecialchars($_GET["cl_id"]);
		mysqli_query($con, "update cadastro_curso set cl_id = $clid where cc_id = $cid");
		mysqli_query($con, "insert into log_omse (cc_id,cl_id_old,cl_id_new) values($cid,$clold,$clid)");
		echo "<script>alert('OMSE alterada com sucesso.');location='?cid=$cid'</script>";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="br" lang="pt">
	<head>
		<style>
		body{
			margin:0px;
		}
		select{
			font:10px verdana;
			width:100px;
		}
		</style>
	</head>
<body>
<?php
$cc = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso where cc_id = $cid"));
foreach($cc as $campo => $valor){$$campo = stripslashes($valor);}
$cl_id_on = $cl_id;
?>
	<select onchange="location='?acao=update_cl&amp;cid=<?= $cid ?>&amp;cl_old=<?= $cl_id_on ?>&amp;cl_id='+ this.value">
		<option></option>
<?php
$cl = mysqli_query($con, "select * from curso_local cl, om where crs_id = $crs_id and cl.om_id = om.om_id order by om_sigla");
while($cl_lista = mysqli_fetch_array($cl)){
	foreach($cl_lista as $campo => $valor){$$campo = stripslashes($valor);}
?>
			<option value="<?= $cl_id ?>" <?php if($cl_id == $cl_id_on){ echo "selected"; } ?>><?= $om_sigla ?></option>
<?php } ?>
	</select>
</body>
</html>
<?php
}else{
		header('location:index.php');
}
?>