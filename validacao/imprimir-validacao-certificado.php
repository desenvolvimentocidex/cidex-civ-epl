<html lang="pt_BR">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>Exército Brasileiro</title>
                <?php include '../system/system.php'; ?>
	</head>
        <body onload="window.print()">
	<!--<div id="index_menu"></div>-->
        <?php 
        @session_start();
        if(isset($_POST['validador']) && !empty($_POST['validador'])){
        
        $validador = addslashes($_POST['validador']);
        $dadosCertificado = mysqli_fetch_assoc(mysqli_query($con, "select * from certificado where cert_validador = '$validador'"));
        
        $idt = $dadosCertificado['cad_login'];
        $codigoIdioma = $dadosCertificado['cert_idioma'];
        
        $dadosMilitar= mysqli_fetch_assoc(mysqli_query($con, "select * from cadastro where cad_login = '$idt'"));
        
        
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
        
        ?>
        
        
        <div id="tabela" style="height: 560px; width: 450px; margin: 0 auto; border-top-color: #000;">
            <table style="width:400px; margin: 0 auto; font-family: Arial; background-image: url('../certificado/validacao_logos.png'); background-position-x: 5px; background-repeat: no-repeat; background-size: 400px 70px;">
                <tr>
                    <td colspan="3" style="font-weight: bold; font-size: 22px; text-align: center;">MINISTÉRIO DA DEFESA</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold; font-size: 22px; text-align: center;">EXÉRCITO BRASILEIRO</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold; font-size: 22px; text-align: center;">DECEx - DETMIL</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold; font-size: 22px; text-align: center;">CENTRO DE IDIOMAS DO EXÉRCITO</td>
                </tr>
                <tr>
                    <td colspan="3" style="height: 20px"></td>
                </tr>
                <tr>
                    <td colspan="3" style=""><span style="font-weight: bold; font-size: 26px">Validação de Certificado de IPL</span></td>
                </tr>
                <tr>
                    <td colspan="2" ><span style="font-weight: bold;">Código do certificado: </span></td>
                    <td><span style="font-weight: bold; text-decoration: underline"><?php echo $validador; ?></span></td>
                </tr>
                <tr>
                    <td colspan="2" ><span style="font-weight: bold;">Idioma: </span></td>
                    <td><span style="font-weight: bold;"><?php echo mb_strtoupper($dadosDgp['IDIOMA'],'UTF-8');?></span></td>
                </tr>
                <tr>
                    <td colspan="2" ><span style="font-weight: bold;">IPL: </span></td>
                    <td><span style="font-weight: bold;"><?php echo $dadosCertificado['cert_ca']." ".$dadosCertificado['cert_eo']." ".$dadosCertificado['cert_cl']." ".$dadosCertificado['cert_ee']; ?></span></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center"><span style="font-weight: bold;"><?php echo mb_strtoupper($dadosMilitar['cad_postograd'],'UTF-8')." ".$dadosMilitar['cad_nome'] ?> </span></td>
                </tr>
                <tr>
                    <td colspan="3" style="background-image: url('../certificado/certificado-mini.png'); background-repeat: no-repeat; background-position-x: 100px; width: 200px; height: 280px"></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center"><span style="font-weight: bold; color: red; font-size: 14px;">Este certificado é valido e verdadeiro conforme a legislação do Exército Brasileiro</span></td>
                </tr>
                <tr>
                    <td colspan="3" style="background-image: url('../certificado/cel-tinoco.png'); background-repeat: no-repeat; width: 200px; height: 80px; background-size: contain;"></td>                </tr>
                <tr>
                    <td colspan="3" style="text-align: center"><span style="font-weight: bold;">SERGIO AVELAR TINOCO - Ten Cel</span></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center"><span style="font-weight: bold;">Comandante do Centro de Idiomas do Exército</span></td>
                </tr>
            </table>
            
        </div>
        <?php } ?>
        
</body>
</html>
<?php 
mysqli_close($con);
oci_close($oci_connect);
?>