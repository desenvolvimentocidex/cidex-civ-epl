<div id="noticias_texto">
<?php
if(empty($nid)){
	$where = "";
	if(isset($search)){
		if(Token::check($token)){
			$where = "where not_status = 1 and (not_titulo like '%". $search ."%' or not_texto like '%". $search ."%')";
			$count = mysqli_fetch_array(mysqli_query($con, "select count(*) as total from noticias $where"));
			echo "Localizadas <b>". $count['total'] ."</b> ocorrências para <b>". stripslashes($search) ."</b><br/><br/>";
		}
	}else{
		$where = "where not_status = 1";
	}
	echo "<ul>";
	$not = mysqli_query($con, "select * from noticias $where order by not_id desc");
	while($lista_not = mysqli_fetch_array($not)){
		foreach($lista_not as $campo => $valor){$$campo = stripslashes($valor);}
		$not_id = (int)$not_id;
?>
	<li><a href="?id=17&amp;nid=<?= $not_id ?>"><img src="noticias/<?= $not_id ?>.jpg" class="not_img"/><div class="blue"><?= $not_titulo ?></div><div class="gray"><?= date("d/m/Y",strtotime($not_data)) ?></div><div id="noticia_resumo" class="black"><?= substr(nl2br($not_texto), 0, 250) ?></div></a></li>
<?php
	}
	echo "</ul>";
}else{//se noticia for selecionada
	$not = mysqli_fetch_array(mysqli_query($con, "select * from noticias where not_id = $nid"));
	if($not){
		foreach($not as $campo => $valor){$$campo = stripslashes($valor);}
		$not_id = (int)$not_id;
	}else{
		echo "<script>location='?id=17'</script>";
		exit;
	}
?>
<h2><?= $not_titulo ?></h2>
<h6>Notícia enviada em <?= date("d/m/Y",strtotime($not_data)) ?></h6>
<br/>
<img src="noticias/<?= $not_id ?>.jpg"/><?= nl2br($not_texto) ?>
<?php } ?>
</div>