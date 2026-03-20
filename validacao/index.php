<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="br" lang="pt">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>Exército Brasileiro</title>
                <?php include '../system/system.php'; ?>
		<link href="imagens/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon"/>
		<link rel="stylesheet" href="../style/style.css"/>
		<link rel="stylesheet" href="../style/jquery-confirm.css"/>
		<link rel="stylesheet" href="../style/jAlert.css"/>
                <!--<link rel="stylesheet" href="style/bootstrap.css"/>-->
		<script src="https://www.google.com/recaptcha/api.js"></script>
                <script type="text/javascript" src="../script/jquery-3.6.0.js"></script>
                <script type="text/javascript" src="../script/jquery-confirm.js"></script>
                <script type="text/javascript" src="../script/jAlert.js"></script>
                <script type="text/javascript" src="../script/jAlert-functions.js"></script>
		<script type="text/javascript" src="../script/script.js"></script>
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
<?php include 'top.php'; ?>
<div id="conteudo">
	<!--<div id="index_menu"></div>-->
        <div id="index_conteudo" style="height: 200px">
            <p><span style="color: blue; font-weight: bold;">Validação de Certificado de Índice de Proficiência Linguística (IPL)</span></p>
            <p><span>Digite abaixo o código constante na parte inferior da frente do certificado e após clique em VALIDAR:</span></p>
            <p>
                <form id="validar-certificado" action="validacao-certificado.php" method="post">
                    <input type="hidden" name="token" id="token" value="<?= $tok ?>"/>
                    <table>
                        <tr>
                            <td>
                                <input type="text" name="validador" id="validador" style="width: 300px; height: 23px; text-transform: uppercase;" value="" />
                            </td>
                            <td>
                                <input id="validacao" type="button" style="color: #00420C; font-weight: bold; height: 30px; font-size: 18px" value="VALIDAR" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"> 
                                <span id="msg" style="color:#FF0000;"><?php if(@$_GET['erro']==1) {echo "Certificado não encontrado! Verifique se o código de validação está correto!";} ?></span>
                                
                            </td>
                        </tr>
                    </table>
                </form>
            </p>
        </div>
</div>
<?php include 'rodape.php' ?>
</div>
</body>
</html>
<?php 
mysqli_close($con);
oci_close($oci_connect);
?>