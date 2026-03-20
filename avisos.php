<div id="avisos" style="width:100%">
	<ul>
<?php
$i = 0;
$avisos = mysqli_query($con, "select * from avisos where avs_status = 1 order by avs_id desc");
while($lista_avisos = mysqli_fetch_array($avisos)){
	foreach($lista_avisos as $campo => $valor){$$campo = stripslashes($valor);}
	$avs_titulo = explode(";",$avs_titulo);
	$i++;
	$li = ($i%2 == 0) ? "li1" : "li2";
?>
		<li class="<?= $li ?>"><label><?= $avs_titulo[0] ?></label> <?= $avs_titulo[1] ?></li>
<?php } ?>
	</ul>
</div>