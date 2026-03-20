<?php 
session_start();
?>
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
                <script type="text/javascript" src="../script/jquery-3.4.1.js"></script>
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
<?php 
if(isset($_POST['validador']) && !empty($_POST['validador'])){

    $validador = addslashes($_POST['validador']);
    $dadosCertificado = mysqli_fetch_assoc(mysqli_query($con, "select * from certificado where cert_validador = '$validador'"));

    $idt = $dadosCertificado['cad_login'];
    $codigoIdioma = $dadosCertificado['cert_idioma'];

    $dadosMilitar = mysqli_fetch_assoc(mysqli_query($con, "select * from cadastro where cad_login = '$idt'"));


    if(empty($dadosMilitar)){
        echo "<script>alert('Certificado não encontrado! Verifique se o código de validação está correto!'); </script>";
        echo "<script>setTimeout(function(){ location='index.php?erro=1'; }, 2000);</script>";
    } else {
?>
<div id="corpo">
<?php include 'top.php'; ?>
<div id="conteudo">
	<!--<div id="index_menu"></div>-->
        <?php 
        
        $sqlDgp = "SELECT PROF.PES_IDENTIFICADOR_COD,IDI.DESCRICAO as IDIOMA, IDI.codigo, MAX(PROF.nivel_compr_auditiva) as COMP_AUD,MAX(PROF.nivel_expr_oral) as EXP_ORAL,MAX(PROF.nivel_compr_leitora) as COMP_LEIT,MAX(PROF.nivel_expr_escrita) as EXP_ESC 
            FROM RH_QUADRO.INDICE_PROF_LINGUISTICA PROF 
            INNER JOIN RH_QUADRO.IDIOMA_IPL IDI ON PROF.codigo_idioma = IDI.codigo 
            WHERE PROF.PES_IDENTIFICADOR_COD = '$idt' AND IDI.CODIGO = '$codigoIdioma' GROUP BY PROF.PES_IDENTIFICADOR_COD,IDI.DESCRICAO,IDI.codigo";

        $queryDgp = ociparse($oci_connect,$sqlDgp);
        oci_close($oci_connect);
        ociexecute($queryDgp);
        $countIdioma = oci_fetch($queryDgp);
        
        ociexecute($queryDgp);
        $dadosDgp = oci_fetch_assoc($queryDgp);
        
        $compAud  = (($dadosDgp['COMP_AUD'] == '' || $dadosDgp['COMP_AUD'] == 0) ? "-" : $dadosDgp['COMP_AUD']);
        $expOral  = (($dadosDgp['EXP_ORAL'] == '' || $dadosDgp['EXP_ORAL'] == 0) ? "-" : $dadosDgp['EXP_ORAL']);
        $compLeit = (($dadosDgp['COMP_LEIT'] == '' || $dadosDgp['COMP_LEIT'] == 0) ? "-" : $dadosDgp['COMP_LEIT']);
        $expEsc   = (($dadosDgp['EXP_ESC'] == '' || $dadosDgp['EXP_ESC'] == 0) ? "-" : $dadosDgp['EXP_ESC']);
        
        ?>
        
        
        <div id="index_conteudo" style="height: 600px">
            <table style="width:600px; margin: 0 auto;">
                <tr>
                    <td colspan="2" style=""><span style="font-weight: bold; font-size: 26px">Validação de Certificado de IPL</span></td>
                    <td style="">
                        <form id="validar-certificado" target="_blank" action="../certificado/gerar_certificado/geradorConfirmacao.php" method="post">
                            <input type="hidden" name="token" id="token" value="<?= $tok ?>"/>
                            <input type="hidden" name="validador" id="validador" style="width: 300px;" value="<?= $validador ?>" />
                            <input type="submit" value="IMPRIMIR VALIDAÇÃO" style="color: #00420C; font-weight: bold; height: 26px"/>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td><span style="font-weight: bold;">Código do certificado: </span></td>
                    <td><span style="font-weight: bold;"><?php echo $validador; ?></span></td>
                </tr>
                <tr>
                    <td><span style="font-weight: bold;">Idioma: </span></td>
                    <td><span style="font-weight: bold;"><?php echo mb_strtoupper($dadosDgp['IDIOMA'],'UTF-8');?></span></td>
                </tr>
                <tr>
                    <td><span style="font-weight: bold;">IPL: </span></td>
                    <td><span style="font-weight: bold;"><?php echo $compAud." ".$expOral." ".$compLeit." ".$expEsc; ?></span></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center"><span style="font-weight: bold;"><?php echo mb_strtoupper($dadosMilitar['cad_postograd'],'UTF-8')." ".$dadosMilitar['cad_nome'] ?> </span></td>
                </tr>
                <tr>
                    <td colspan="2" style="background-image: url('../certificado/cidex.png'); background-repeat: no-repeat; background-position-x: 130px; width: 200px; height: 280px"></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center"><span style="font-weight: bold; color: red; font-size: 14px;">Este certificado é valido e verdadeiro conforme a legislação do Exército Brasileiro</span></td>
                </tr>
                <tr>
                    <td colspan="2" style="background-image: url('../certificado/gerar_certificado/cel-tinoco2.png'); background-repeat: no-repeat; width: 680px; height: 140px; background-size: cover;"></td>                </tr>
                <tr>
                    <td colspan="2" style="text-align: center"><span style="font-weight: bold;">MAURÍCIO AVELAR TINOCO - Ten Cel</span></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center"><span style="font-weight: bold;">Comandante do Centro de Idiomas do Exército</span></td>
                </tr>
            </table>
            
        </div>
        
</div>
<?php include 'rodape.php' ?>
</div>
<?php 
    }
} 
?>
</body>
</html>
<?php 
mysqli_close($con);
oci_close($oci_connect);
?>