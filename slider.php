<link rel="stylesheet" href="style/bjqs.css"/>
<link rel="stylesheet" href="style/slider.css"/>
<script src="script/jquery-1.7.1.min.js"></script>
<script src="script/bjqs-1.3.min.js"></script>
<div id="container">
	<div id="banner-fade">
		<ul class="bjqs">
<?php
$capas = mysqli_query($con, "select * from capas where cps_status = 1 order by cps_id desc limit 10");
while($lista_capas = mysqli_fetch_array($capas)){
	foreach($lista_capas as $campo => $valor){$$campo = stripslashes($valor);}
?>
			<li><pre><?= $cps_titulo ?></pre><img src="capas/<?= $cps_id ?>.jpg" <?php if($cps_link){ ?> onclick="window.open('<?= $cps_link ?>','_blank','')" style="cursor:pointer" title="<?= $cps_titulo ?>"<?php } ?>/></li>
<?php } ?>
		</ul>
	</div>
<script class="secret-source">
jQuery(document).ready(function($){
	$('#banner-fade').bjqs({
		width		: 590,
		height		: 250,
		responsive	: false,
		nexttext	: '',
		prevtext	: '',
		hoverpause	: true
	});
});
</script>
</div>