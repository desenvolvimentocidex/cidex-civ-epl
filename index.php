<?php
if (! session_start()) {
	die('Erro ao criar sessão');
}
header("Content-Type: text/html; charset=utf-8");
print_r($_SESSION);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="br" lang="pt" Content‑Security‑Policy: script‑src 'self' ; media‑src 'none' ; img‑src *; default‑src 'self' http://*.eb.mil.br X-XSS-Protection: 1; mode=block>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>CIDEx - Exército Brasileiro</title>
	<?php include 'system/system.php'; ?>
	<link href="imagens/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
	<link rel="stylesheet" href="style/style.css" />
	<link rel="stylesheet" href="style/jquery-confirm.css" />
	<link rel="stylesheet" href="style/jAlert.css" />
	<!--<link rel="stylesheet" href="style/bootstrap.css"/>-->
	<!-- <script src="https://www.google.com/recaptcha/api.js"></script>-->
	<script type="text/javascript" src="script/jquery-3.6.0.js"></script>
	<script type="text/javascript" src="script/jquery-confirm.js"></script>
	<script type="text/javascript" src="script/jAlert.js"></script>
	<script type="text/javascript" src="script/jAlert-functions.js"></script>
	<script type="text/javascript" src="script/script.js"></script>
	<!--<style id="antiClickjack">body{display:none !important;}</style>-->
	<!--		<script type="text/javascript">
		if(self === top){
			var antiClickjack = document.getElementById("antiClickjack");
			antiClickjack.parentNode.removeChild(antiClickjack);
		}else{
			top.location = self.location;
		}
		</script>-->
</head>

<body onLoad="showtime()">
	<div id="corpo">
		<?php include 'top.php'; ?>

		<div id="conteudo">
			<div id="index_conteudo">
				<?php
				if ($_GET['a'] == 'atualizasenha') {
					include "system/atualizasenha.php";
				} else {
					include 'conteudo.php';
				}
				?>
			</div>
		</div>
		<?php include 'rodape.php' ?>
	</div>
</body>

</html>
<?php
$_SESSION["token_safe"] = @$_SESSION["token"];
mysqli_close($con);
oci_close($oci_connect);
?>