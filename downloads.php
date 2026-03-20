<?php
$dwl = mysqli_query($con, "select * from downloads where dwl_status = 1 order by dwl_nome");
while($lista_dwl = mysqli_fetch_array($dwl)){
	foreach($lista_dwl as $campo => $valor){$$campo = stripslashes($valor);}
?>
	<img src="imagens/icon_downloads.png" width="16"/> <a href="file.php?id=<?= $dwl_id ?>"><?= $dwl_nome ?> - Nr de acessos: <?= $dwl_qtd ?></a><br/>
<?php } ?>