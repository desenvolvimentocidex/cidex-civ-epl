<?php
if($id == 0 && empty($a)){
$alert = mysqli_fetch_array(mysqli_query($con, "select * from alert"));
	if($alert){
?>
	<script>alert('<?= $alert["alert_texto"] ?>')</script>
<?php
	}
}
?>