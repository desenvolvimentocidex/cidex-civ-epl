	<div id="top">CIDEx - Centro de Idiomas do Exército<br/><span>Exército Brasileiro</span></div>
	<div id="top_menu">
		<ul style="width:620px">
<?php
if(@$_SESSION["loged"] == "adm_on"){
?>
			<li style="float:right;border:0px;"><a href="?id=100">Usuário: <?= $_SESSION["login"] . " Perfil: " . $_SESSION["perfil"] ?> | [X] Sair</a></li>
<?php }else{ ?>
			<form method="post" onsubmit="return fix(1)">
			<input type="hidden" name="token" value="<?= $tok ?>"/>
			<input type="hidden" name="acao" value="adm_login"/>
			<li>Login: <input id="login" class="w100px" type="text" name="login" autocomplete="off"/></li>
			<li>Senha: <input id="pass" class="w100px" type="password" name="pass" autocomplete="off"/></li>
			<li><div id="captcha_box"><div id="captcha_content" class="g-recaptcha" data-sitekey="6Lcurj4UAAAAANZ3xtKcKt7s6nCQ0N9EQqe9pFqP"></div></div></li>
			<li style="border:0px"><input class="botao" class="w50px" type="submit" value="Entrar"/></li>
			</form>
<?php } ?>
		</ul>
	</div>