<?php
$ytb = mysqli_query($con, "select * from youtube where ytb_status = 1 order by ytb_id desc");
$ytb_last = mysqli_fetch_array($ytb);
foreach($ytb_last as $campo => $valor){$$campo = stripslashes($valor);}
$ytb_link = explode("=",$ytb_link);
$ytb_link = $ytb_link[1];
?>
<div id="ytb_video"><iframe src="https://www.youtube.com/embed/<?= $ytb_link ?>" frameborder="0" name="youtube" allowfullscreen></iframe></div>
<div id="ytb_lista">
	<ul>
		<li class="user_tit">Galeria de vídeos</li>
<?php
$ytb = mysqli_query($con, "select * from youtube where ytb_status = 1 order by ytb_id desc");
while($lista_ytb = mysqli_fetch_array($ytb)){
	foreach($lista_ytb as $campo => $valor){$$campo = stripslashes($valor);}
	$ytb_link = explode("=",$ytb_link);
	$ytb_link = $ytb_link[1];
	$ytb_dados = explode(";",$ytb_titulo);
?>
		<li><a href="https://www.youtube.com/embed/<?= $ytb_link ?>" target="youtube"><div id="ytb_img" style="background:url('https://i.ytimg.com/vi/<?= $ytb_link ?>/default.jpg') no-repeat 0px -10px"></div><div id="ytb_texto"><b><?= $ytb_dados[1] ?></b><br/><?= $ytb_dados[0] ?></div></a></li>
<?php } ?>
	</ul>
</div>