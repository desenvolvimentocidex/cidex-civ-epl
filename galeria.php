<?php
if($fid){
	$atual_dir = $fid;
	$where = "where fts_id = ". $fid;
}else{
	$where = " where fts_status = 1";
}

$fts = mysqli_fetch_array(mysqli_query($con, "select * from fotos $where order by fts_id desc"));
if($fts){
	if(!$fid){ $atual_dir = $fts["fts_id"]; }
	$path = "fotos/". $atual_dir;
	$diretorio = dir($path);
	$files = scandir($path);
	$num_files = count($files)-2;
	$fts_titulo = stripslashes($fts["fts_titulo"]);
}else{
	echo "<script>location='?id=12'</script>";
	exit;
}
?>
<div id="fotos_left">
	<div id="fotos_tit"><?= $fts_titulo ?></div>
	<img id="fotos_view" src="<?= $path ?>/1.jpg"/><br/>
	<div id="arrow_left" onmouseover="scrollDivUp('fotos_scroll')" onmouseout="stopMe()"></div>
	<div id="fotos_scroll">
		<div id="fotos_lista" style="width:<?= 114 * $num_files ?>px">
<?php
while($arquivo = $diretorio -> read()){
	if($arquivo != ".." && $arquivo != "."){
		$file = $path ."/". $arquivo;
?>
			<img src="<?= $file ?>" onmouseover="TrocarFoto('<?= $file ?>')"/>
<?php
	}
}
?>
		</div>
	</div> 
	<div id="arrow_right" onmouseover="scrollDivDown('fotos_scroll')" onmouseout="stopMe()"></div>
</div>
<div id="fotos_right">
	<ul>
		<li class="user_tit">Galeria de imagens</li>
<?php
$fts = mysqli_query($con, "select * from fotos where fts_status = 1 order by fts_id desc");
while($lista_fts = mysqli_fetch_array($fts)){
	foreach($lista_fts as $campo => $valor){$$campo = stripslashes($valor);}
	$fts_id = (int)$fts_id;
?>
		<li><a href="?id=<?= $id ?>&amp;fid=<?= $fts_id ?>"><img src="fotos/<?= $fts_id ?>/1.jpg"><span><?= $fts_titulo ?></span></a></li>
<?php } ?>
	</ul>
</div>