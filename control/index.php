<?php include '../system/system.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="br" lang="pt">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title><?= $sis_sigla ?> :: <?= $sis_nome ?></title>
		<link href="../imagens/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon"/>
		<link rel="stylesheet" href="style/style.css"/>
		<script type="text/javascript" src="script/script.js"></script>
		<script src="https://www.google.com/recaptcha/api.js"></script>		
		<style id="antiClickjack">body{display:none !important;}</style>
		<script type="text/javascript">
		if(self === top){
			var antiClickjack = document.getElementById("antiClickjack");
			antiClickjack.parentNode.removeChild(antiClickjack);
		}else{
			top.location = self.location;
		}
		</script>
	</head>
<body>
<div id="corpo">
	<div id="top"><?= $sis_nome ?><br/><span>Exército Brasileiro</span></div>
	<div id="top_menu">
		<ul>
		
<?php
if(@$_SESSION["loged"] == "adm_on"){
	$menu = ["Avisos","Notícias","Capas","Galeria","Vídeos","Conteúdo","Downloads","Sair"];
	$t_menu = count($menu);
	for($x = 0; $x < $t_menu; $x++){
		if($x+1 == $t_menu){
			$mid = 100;
			$b0 = 'class="b0"';
		}else{
			$mid = $x+1;
			$b0 = "";
		}
?>
			<li <?= $b0 ?>><a href="?id=<?= $mid ?>"><?= $menu[$x] ?></a></li>
<?php
	}
}else{
?>
			<form method="post" onsubmit="return fix(1)">
			<input type="hidden" name="token" value="<?php echo $tok ?>"/>
			<input type="hidden" name="acao" value="adm_login"/>
			<li>Login: <input id="login" type="text" name="login" autocomplete="off"/></li>
			<li>Senha: <input id="pass" type="password" name="pass" autocomplete="off"/></li>
			<li><div id="captcha_box"><div id="captcha_content" class="g-recaptcha" data-sitekey="6LcMFw0TAAAAADG0Yx3TJvadPLdLS_RcNtB4umNI"></div></div></li>
			<li class="b0"><input class="botao" type="submit" value="Entrar"/></li>
			</form>
<?php } ?>
		</ul>
	</div>
<?php if(@$_SESSION["loged"] == "adm_on"){ ?>
	<div id="conteudo">
<?php if($id == 1){
	if(@$acao == "view"){//se view
	$avs = mysqli_fetch_array(mysqli_query($con, "select * from avisos where avs_id = $reg_id"));
	foreach($avs as $campo => $valor){$$campo = stripslashes($valor);}
?>
	<div id="view">
		<?= $avs_titulo ?></b><hr/><?= nl2br($avs_texto) ?>
	</div>
<?php
	}else{
	if(@$acao == "edit"){
		$avs = mysqli_fetch_array(mysqli_query($con, "select * from avisos where avs_id = $reg_id"));
		foreach($avs as $campo => $valor){$$campo = stripslashes($valor);}
		$avs_titulo = explode(";",$avs_titulo);
		$botao = "Atualizar";
		$act = "update";
		$alt = "<input type='hidden' name='reg_id' value='$avs_id'>";
	}else{
		$act = "add";
		$botao = "Enviar";
	}
?>

<ul>
	<li class="titulo">Enviar aviso</li>
	<form method="post" onsubmit="return fix(6)">
	<?php if (@alt){ echo @$alt; } ?>
	<input type="hidden" name="acao" value="<?= $act ?>"/>
	<input type="hidden" name="token" value="<?= $tok ?>"/>
	<li class="li2">Data: <input type="text" id="dia" name="dia" class="text" value="<?= @$avs_titulo[0] ?>" maxlength="10" style="width:100px"/> (**/**/****)</li>
	<li class="li1">Título: <input type="text" id="titulo" name="titulo" class="text" value="<?= @$avs_titulo[1] ?>"/></li>
	<li class="li2">Texto:<br/><textarea name="texto" class="textarea_full"/><?= @$avs_texto ?></textarea></li>
	<li class="li1"><input type="submit" value="<?= $botao ?>"/></li>
	</form>
</ul>
<?php } ?>
<br/><br/>
<ul>
	<li class="titulo">Listar avisos</li>
<?php
$avs = mysqli_query($con, "select * from avisos order by avs_id desc");
(int)$j = 0;
(int)$i = 0;
while($lista_avs = mysqli_fetch_array($avs)){
	foreach($lista_avs as $campo => $valor){$$campo = stripslashes($valor);}
	$i++;
	$li = ($i%2 == 0) ? "li1" : "li2";
	$formdel = "del". $i;
	$formonoff = "onoff". $i;
	
	if($avs_status == 1){
		$astatus = "less";
		$aalt = "Desativar";
		$aonoff = 0;
	}else{
		$astatus = "more";
		$aalt = "Ativar";
		$aonoff = 1;
	}
?>
	<li class="<?= $li ?> h16">
		<label><?= $avs_titulo ?></label>
		<div><form id="<?= $formonoff ?>" method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="onoff"/><input type="hidden" name="reg_id" value="<?= $avs_id ?>"/><input type="hidden" name="onoff" value="<?= $aonoff ?>"/><input type="image" src="imagens/i_<?= $astatus ?>.png" title="<?= $aalt ?>" onclick="return confirmacao('<?= $aalt ?>','Aviso','<?= $formonoff ?>')"/></form></div>
		<div><form method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="edit"/><input type="hidden" name="reg_id" value="<?= $avs_id ?>"/><input type="image" src="imagens/i_edit.png" title="Editar"/></form></div>
		<div><form method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="view"/><input type="hidden" name="reg_id" value="<?= $avs_id ?>"/><input type="image" src="imagens/i_view.gif" title="Visualizar"/></form></div>
		<div><form id="<?= $formdel ?>" method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="del"/><input type="hidden" name="reg_id" value="<?= $avs_id ?>"/><input type="image" src="imagens/i_del.png" title="Excluir" onclick="return confirmacao('excluir','Aviso','<?= $formdel ?>')"/></form></div>
	</li>
<?php } ?>
</ul>
<?php }//end id=1 ?>

<?php
if($id == 2){
	if(@$acao == "view"){//se view
	$not = mysqli_fetch_array(mysqli_query($con, "select * from noticias where not_id = $reg_id"));
	foreach($not as $campo => $valor){$$campo = stripslashes($valor);}
?>
	<div id="view">
		<img src="../noticias/<?= $not_id ?>.jpg"/> <b><?= $not_titulo ?></b><hr/><?= nl2br($not_texto) ?>
	</div>
<?php
	}else{
	if(@$acao == "edit"){
		$not = mysqli_fetch_array(mysqli_query($con, "select * from noticias where not_id = $reg_id"));
		foreach($not as $campo => $valor){$$campo = stripslashes($valor);}
		$botao = "Atualizar";
		$act = "update";
		$pic = "<img src='../noticias/$not_id.jpg' class='img_update'/>";
		$alt = "<input type='hidden' name='reg_id' value='$not_id'>";
		$fix = 5;
		$textarea = "meio";
	}else{//apenas receber o ultimo registro + 1
		$not = mysqli_fetch_array(mysqli_query($con, "select not_id from noticias order by not_id desc"));
		foreach($not as $campo => $valor){$$campo = stripslashes($valor);}
		(int)$not_id = (int)$not_id+1;
		$botao = "Enviar";
		$fix = 4;
		$act = "add";
		$textarea = "full";
	}
?>
<ul>
	<li class="titulo">Enviar notícia</li>
	<form method="post" enctype="multipart/form-data" onsubmit="return fix(<?= $fix ?>)">
	<?php if (@alt){ echo @$alt; } ?>
	<input type="hidden" name="acao" value="<?= $act ?>"/>
	<input type="hidden" name="token" value="<?= $tok ?>"/>
	<input type="hidden" name="pasta" value="noticias"/>
	<input type="hidden" name="foto_id" value="<?= $not_id ?>"/>
	<li class="li1">Selecione a capa da notícia (JPG): <input type="file" id="anexo" name="anexo" class="width300"/></li>
	<li class="li2">Título: <input type="text" id="titulo" name="titulo" class="text" value="<?= @$not_titulo ?>"/></li>
	<li class="li1"><?= @$pic ?><textarea class="textarea_<?= $textarea ?>" id="texto" name="texto"><?= @$not_texto ?></textarea></li>
	<li class="li2"><input type="submit" value="<?= $botao ?>"/></li>
	</form>
</ul>
<?php } ?>
<br/><br/>
<ul>
	<li class="titulo">Listar notícias</li>
<?php
$not = mysqli_query($con, "select * from noticias order by not_id desc");
(int)$j = 0;
(int)$i = 0;
while($lista_not = mysqli_fetch_array($not)){
	foreach($lista_not as $campo => $valor){$$campo = stripslashes($valor);}
	$i++;
	$li = ($i%2 == 0) ? "li1" : "li2";
	$formdel = "delnoticia". $i;
	$formonoff = "onoff". $i;
	
	if($not_status == 1){
		$nstatus = "less";
		$nalt = "Desativar";
		$nonoff = 0;
	}else{
		$nstatus = "more";
		$nalt = "Ativar";
		$nonoff = 1;
	}
?>
	<li class="<?= $li ?> h16">
		<label><?= $not_titulo ?></label>
		<div><form id="<?= $formonoff ?>" method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="onoff"/><input type="hidden" name="reg_id" value="<?= $not_id ?>"/><input type="hidden" name="onoff" value="<?= $nonoff ?>"/><input type="image" src="imagens/i_<?= $nstatus ?>.png" title="<?= $nalt ?>" onclick="return confirmacao('<?= $nalt ?>','Notícia','<?= $formonoff ?>')"/></form></div>
		<div><form method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="edit"/><input type="hidden" name="reg_id" value="<?= $not_id ?>"/><input type="image" src="imagens/i_edit.png" title="Editar"/></form></div>
		<div><form method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="view"/><input type="hidden" name="reg_id" value="<?= $not_id ?>"/><input type="image" src="imagens/i_view.gif" title="Visualizar"/></form></div>
		<div><form id="<?= $formdel ?>" method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="del"/><input type="hidden" name="reg_id" value="<?= $not_id ?>"/><input type="image" src="imagens/i_del.png" title="Excluir" onclick="return confirmacao('excluir','Notícia','<?= $formdel ?>')"/></form></div>
	</li>
<?php } ?>
</ul>
<?php }//end id=2 ?>

<?php
if($id == 3){
	if(@$acao == "view"){//se view
	$cps = mysqli_fetch_array(mysqli_query($con, "select * from capas where cps_id = $reg_id"));
	foreach($cps as $campo => $valor){$$campo = stripslashes($valor);}
?>
	<div id="view">
		<img src="../capas/<?= $cps_id ?>.jpg"/> <b><?= $cps_titulo ?></b><hr/><?= nl2br($cps_texto) ?>
	</div>
<?php
	}else{
	if(@$acao == "edit"){
		$cps = mysqli_fetch_array(mysqli_query($con, "select * from capas where cps_id = $reg_id"));
		foreach($cps as $campo => $valor){$$campo = stripslashes($valor);}
		$botao = "Atualizar";
		$act = "update";
		$pic = "<img src='../capas/$cps_id.jpg' class='img_update'/>";
		$alt = "<input type='hidden' name='reg_id' value='$cps_id'>";
		$fix = 5;
		$textarea = "meio";
	}else{//apenas receber o ultimo registro + 1
		$cps = mysqli_fetch_array(mysqli_query($con, "select cps_id from capas order by cps_id desc"));
		foreach($cps as $campo => $valor){$$campo = stripslashes($valor);}
		(int)$cps_id = (int)$cps_id+1;
		$botao = "Enviar";
		$fix = 4;
		$act = "add";
		$textarea = "full";
	}
?>
<ul>
	<li class="titulo">Enviar capa</li>
	<form method="post" enctype="multipart/form-data" onsubmit="return fix(<?= $fix ?>)">
	<?php if (@alt){ echo @$alt; } ?>
	<input type="hidden" name="acao" value="<?= $act ?>"/>
	<input type="hidden" name="token" value="<?= $tok ?>"/>
	<input type="hidden" name="pasta" value="capas"/>
	<input type="hidden" name="foto_id" value="<?= $cps_id ?>"/>
	<li class="li1">Selecione a capa da notícia (JPG 560 x 220): <input type="file" id="anexo" name="anexo" class="width300"/></li>
	<li class="li2">Título: <input type="text" id="titulo" name="titulo" class="text" value="<?= @$cps_titulo ?>"/></li>
	<li class="li1"><?= @$pic ?><textarea class="textarea_<?= $textarea ?>" id="texto" name="texto"><?= @$cps_texto ?></textarea></li>
	<li class="li2"><input type="submit" value="<?= $botao ?>"/></li>
	</form>
</ul>
<?php } ?>
<br/><br/>
<ul>
	<li class="titulo">Listar capas</li>
<?php
$cps = mysqli_query($con, "select * from capas order by cps_id desc");
(int)$j = 0;
(int)$i = 0;
while($lista_cps = mysqli_fetch_array($cps)){
	foreach($lista_cps as $campo => $valor){$$campo = stripslashes($valor);}
	$i++;
	$li = ($i%2 == 0) ? "li1" : "li2";
	$formdel = "delnoticia". $i;
	$formonoff = "onoff". $i;
	
	if($cps_status == 1){
		$nstatus = "less";
		$nalt = "Desativar";
		$nonoff = 0;
	}else{
		$nstatus = "more";
		$nalt = "Ativar";
		$nonoff = 1;
	}
?>
	<li class="<?= $li ?> h16">
		<label><?= $cps_titulo ?></label>
		<div><form id="<?= $formonoff ?>" method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="onoff"/><input type="hidden" name="reg_id" value="<?= $cps_id ?>"/><input type="hidden" name="onoff" value="<?= $nonoff ?>"/><input type="image" src="imagens/i_<?= $nstatus ?>.png" title="<?= $nalt ?>" onclick="return confirmacao('<?= $nalt ?>','Capa','<?= $formonoff ?>')"/></form></div>
		<div><form method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="edit"/><input type="hidden" name="reg_id" value="<?= $cps_id ?>"/><input type="image" src="imagens/i_edit.png" title="Editar"/></form></div>
		<div><form method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="view"/><input type="hidden" name="reg_id" value="<?= $cps_id ?>"/><input type="image" src="imagens/i_view.gif" title="Visualizar"/></form></div>
		<div><form id="<?= $formdel ?>" method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="del"/><input type="hidden" name="reg_id" value="<?= $cps_id ?>"/><input type="image" src="imagens/i_del.png" title="Excluir" onclick="return confirmacao('excluir','Capa','<?= $formdel ?>')"/></form></div>
	</li>
<?php } ?>
</ul>
<?php }//end id=3 ?>

<?php
if($id == 4){
	if(@$acao == "view"){//se view
	$fts = mysqli_fetch_array(mysqli_query($con, "select * from fotos where fts_id = $reg_id"));
	foreach($fts as $campo => $valor){$$campo = stripslashes($valor);}
?>
	<div id="view">
		<b><?= $fts_titulo ?></b><hr/><?= nl2br($fts_texto) ?>
		<ul>
<?php
$path = "../fotos/". $fts_id;
$diretorio = dir($path);
while($arquivo = $diretorio -> read()){
	if($arquivo != ".." && $arquivo != "."){
		$file = $path ."/". $arquivo;
?>
			<li style="height:70px"><img src="<?= $file ?>" style="width:100px"/> <form id="delfoto" method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="delfoto"/><input type="hidden" name="reg_id" value="<?= $fts_id ?>"/><input type="hidden" name="foto" value="<?= $arquivo ?>"/><input type="image" src="imagens/i_del.png" title="Excluir" onclick="return confirmacao('excluir','Foto','delfoto')"/></form></li>
<?php
	}
}
?>
		</ul>
	</div>
<?php
	}else{
	if(@$acao == "edit"){
		$fts = mysqli_fetch_array(mysqli_query($con, "select * from fotos where fts_id = $reg_id"));
		foreach($fts as $campo => $valor){$$campo = stripslashes($valor);}
		$botao = "Atualizar";
		$act = "update";
		$alt = "<input type='hidden' name='reg_id' value='$fts_id'>";
	}else{
		$act = "mkfotos";
		$botao = "Enviar";
	}
?>
<ul>
	<li class="titulo">Criar pasta de fotos</li>
	<form method="post">
	<?php if (@alt){ echo @$alt; } ?>
	<input type="hidden" name="acao" value="<?= $act ?>"/>
	<input type="hidden" name="token" value="<?= $tok ?>"/>
	<li class="li1">Título: <input type="text" name="titulo" class="text" value="<?= @$fts_titulo ?>"/></li>
	<li class="li2"><input type="submit" value="<?= $botao ?>"/></li>
	</form>
</ul>

<?php
if(@$acao == "edit"){//ini if acao=edit > envia foto desta pasta
	$path = "../fotos/". $fts_id;
	$files = scandir($path);
	$num_files = count($files)-2;
	$file = $num_files + 1;
?>
<ul>
	<li class="titulo">Enviar fotos para esta pasta</li>
	<form method="post" enctype="multipart/form-data">
	<input type="hidden" name="acao" value="sendfile"/>
	<input type="hidden" name="token" value="<?= $tok ?>"/>
	<input type="hidden" name="pasta" value="fotos/<?= $fts_id ?>"/>
	<input type="hidden" name="foto_id" value="<?= $file ?>"/>
	<li class="li1">Pasta: <?= $fts_titulo ?></li>
	<li class="li2">Foto: <input type="file" name="anexo" class="width300"/></li>
	<li class="li1"><input type="submit" value="Enviar"/></li>
	</form>
</ul>
<?php }//fim if acao=edit > envia foto desta pasta ?>

<?php } ?>
<br/><br/>
<ul>
	<li class="titulo">Listar pastas de fotos</li>
<?php
$fts = mysqli_query($con, "select * from fotos order by fts_id desc");
(int)$j = 0;
(int)$i = 0;
while($lista_fts = mysqli_fetch_array($fts)){
	foreach($lista_fts as $campo => $valor){$$campo = stripslashes($valor);}
	$i++;
	$li = ($i%2 == 0) ? "li1" : "li2";
	$formdel = "delfotos". $i;
	$formonoff = "onoff". $i;
	
	if($fts_status == 1){
		$nstatus = "less";
		$nalt = "Desativar";
		$nonoff = 0;
	}else{
		$nstatus = "more";
		$nalt = "Ativar";
		$nonoff = 1;
	}
?>
	<li class="<?= $li ?> h16">
		<label><?= $fts_titulo ?></label>
		<div><form id="<?= $formonoff ?>" method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="onoff"/><input type="hidden" name="reg_id" value="<?= $fts_id ?>"/><input type="hidden" name="onoff" value="<?= $nonoff ?>"/><input type="image" src="imagens/i_<?= $nstatus ?>.png" title="<?= $nalt ?>" onclick="return confirmacao('<?= $nalt ?>','Galeria','<?= $formonoff ?>')"/></form></div>
		<div><form method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="edit"/><input type="hidden" name="reg_id" value="<?= $fts_id ?>"/><input type="image" src="imagens/i_edit.png" title="Editar"/></form></div>
		<div><form method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="view"/><input type="hidden" name="reg_id" value="<?= $fts_id ?>"/><input type="image" src="imagens/i_view.gif" title="Visualizar"/></form></div>
		<div><form id="<?= $formdel ?>" method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="del"/><input type="hidden" name="reg_id" value="<?= $fts_id ?>"/><input type="image" src="imagens/i_del.png" title="Excluir" onclick="return confirmacao('excluir','Galeria','<?= $formdel ?>')"/></form></div>
	</li>
<?php } ?>
</ul>
<?php }//end id=4 ?>

<?php if($id == 5){
	if(@$acao == "view"){//se view
	$ytb = mysqli_fetch_array(mysqli_query($con, "select * from youtube where ytb_id = $reg_id"));
	foreach($ytb as $campo => $valor){$$campo = stripslashes($valor);}
	$ytb_link = explode("=",$ytb_link);
	$ytb_link = $ytb_link[1];
?>
	<div id="view">
		<?= $ytb_titulo ?></b><hr/><iframe src="https://www.youtube.com/embed/<?= $ytb_link ?>" frameborder="0" name="youtube" allowfullscreen></iframe>
	</div>
<?php
	}else{
	if(@$acao == "edit"){
		$ytb = mysqli_fetch_array(mysqli_query($con, "select * from youtube where ytb_id = $reg_id"));
		foreach($ytb as $campo => $valor){$$campo = stripslashes($valor);}
		$ytb_dados = explode(";",$ytb_titulo);
		$botao = "Atualizar";
		$act = "update";
		$alt = "<input type='hidden' name='reg_id' value='$ytb_id'>";
	}else{
		$act = "add";
		$botao = "Enviar";
	}
?>
<ul>
	<li class="titulo">Enviar vídeo do YouTube</li>
	<form method="post" onsubmit="return fix(6)">
	<?php if (@alt){ echo @$alt; } ?>
	<input type="hidden" name="acao" value="<?= $act ?>"/>
	<input type="hidden" name="token" value="<?= $tok ?>"/>
	<li class="li1"><label>URL:</label> <input type="text" id="titulo" name="texto" class="text" value="<?= @$ytb_link ?>"/></li>
	<li class="li2"><label>Título:</label> <input type="text" id="titulo" name="titulo" class="text" value="<?= @$ytb_dados[1] ?>"/></li>
	<li class="li1"><label>Tempo:</label> <input type="text" id="tempo" name="tempo" class="text" value="<?= @$ytb_dados[0] ?>"/></li>
	<li class="li2"><input type="submit" value="<?= $botao ?>"/></li>
	</form>
</ul>
<?php } ?>
<br/><br/>
<ul>
	<li class="titulo">Listar avisos</li>
<?php
$ytb = mysqli_query($con, "select * from youtube order by ytb_id desc");
(int)$j = 0;
(int)$i = 0;
while($lista_ytb = mysqli_fetch_array($ytb)){
	foreach($lista_ytb as $campo => $valor){$$campo = stripslashes($valor);}
	$i++;
	$li = ($i%2 == 0) ? "li1" : "li2";
	$formdel = "del". $i;
	$formonoff = "onoff". $i;
	
	if($ytb_status == 1){
		$astatus = "less";
		$aalt = "Desativar";
		$aonoff = 0;
	}else{
		$astatus = "more";
		$aalt = "Ativar";
		$aonoff = 1;
	}
?>
	<li class="<?= $li ?> h16">
		<label><?= $ytb_titulo ?></label>
		<div><form id="<?= $formonoff ?>" method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="onoff"/><input type="hidden" name="reg_id" value="<?= $ytb_id ?>"/><input type="hidden" name="onoff" value="<?= $aonoff ?>"/><input type="image" src="imagens/i_<?= $astatus ?>.png" title="<?= $aalt ?>" onclick="return confirmacao('<?= $aalt ?>','Vídeo','<?= $formonoff ?>')"/></form></div>
		<div><form method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="edit"/><input type="hidden" name="reg_id" value="<?= $ytb_id ?>"/><input type="image" src="imagens/i_edit.png" title="Editar"/></form></div>
		<div><form method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="view"/><input type="hidden" name="reg_id" value="<?= $ytb_id ?>"/><input type="image" src="imagens/i_view.gif" title="Visualizar"/></form></div>
		<div><form id="<?= $formdel ?>" method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="del"/><input type="hidden" name="reg_id" value="<?= $ytb_id ?>"/><input type="image" src="imagens/i_del.png" title="Excluir" onclick="return confirmacao('excluir','Vídeo','<?= $formdel ?>')"/></form></div>
	</li>
<?php } ?>
</ul>
<?php }//end id=5 ?>

<?php
if($id == 6){
	if(@$acao == "view"){//se view
	$ctd = mysqli_fetch_array(mysqli_query($con, "select * from conteudo where ctd_id = $reg_id"));
	foreach($ctd as $campo => $valor){$$campo = stripslashes($valor);}
?>
	<div id="view">
		<b><?= $ctd_titulo ?></b><hr/><?= nl2br($ctd_texto) ?>
	</div>
<?php
	}else{
	if(@$acao == "edit"){
		$ctd = mysqli_fetch_array(mysqli_query($con, "select * from conteudo where ctd_id = $reg_id"));
		foreach($ctd as $campo => $valor){$$campo = stripslashes($valor);}
		$botao = "Atualizar";
		$act = "update";
		$alt = "<input type='hidden' name='reg_id' value='$ctd_id'>";
?>
<ul>
	<li class="titulo">Editar conteúdo</li>
	<form method="post" onsubmit="return fix(6)">
	<?php if (@alt){ echo @$alt; } ?>
	<input type="hidden" name="acao" value="<?= $act ?>"/>
	<input type="hidden" name="token" value="<?= $tok ?>"/>
	<li class="li1">Título: <input type="text" id="titulo" name="titulo" class="text" value="<?= @$ctd_titulo ?>"/></li>
	<li class="li2">Texto:<br/><textarea name="texto" class="textarea_full"/><?= @$ctd_texto ?></textarea></li>
	<li class="li1"><input type="submit" value="<?= $botao ?>"/></li>
	</form>
</ul><br/><br/>
<?php
	}
}
?>
<ul>
	<li class="titulo">Listar conteúdo</li>
<?php
$ctd = mysqli_query($con, "select * from conteudo order by ctd_titulo");
(int)$j = 0;
(int)$i = 0;
while($lista_ctd = mysqli_fetch_array($ctd)){
	foreach($lista_ctd as $campo => $valor){$$campo = stripslashes($valor);}
	$i++;
	$li = ($i%2 == 0) ? "li1" : "li2";
	$formdel = "delfotos". $i;
	$formonoff = "onoff". $i;
?>
	<li class="<?= $li ?> h16">
		<label><?= $ctd_titulo ?></label>
		<div><form method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="edit"/><input type="hidden" name="reg_id" value="<?= $ctd_id ?>"/><input type="image" src="imagens/i_edit.png" title="Editar"/></form></div>
		<div><form method="post"><input type="hidden" name="token" value="<?= $tok ?>"/><input type="hidden" name="acao" value="view"/><input type="hidden" name="reg_id" value="<?= $ctd_id ?>"/><input type="image" src="imagens/i_view.gif" title="Visualizar"/></form></div>
	</li>
<?php } ?>
</ul>
<?php }//end id=6 ?>

</div>
<?php }//if loged=on ?>
</div>
</body>
</html>
<?php $_SESSION["token_safe"] = @$_SESSION["token"]; ?>