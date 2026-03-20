<?php
session_start();
setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
date_default_timezone_set( 'America/Sao_Paulo' );
require('fpdf/alphapdf.php');
require('../../system/csrf.class.php');
require ('conecta.php');

if(isset($_POST['validador']) && !empty($_POST['validador'])){

    $validador = addslashes($_POST['validador']);
    $dadosCertificado = mysqli_fetch_assoc(mysqli_query($con, "select * from certificado where cert_validador = '$validador'"));

    $idt = $dadosCertificado['cad_login'];
    $codigoIdioma = $dadosCertificado['cert_idioma'];

    $dadosMilitar = mysqli_fetch_assoc(mysqli_query($con, "select * from cadastro where cad_login = '$idt'"));
    
    $sqlDgp = "SELECT PROF.PES_IDENTIFICADOR_COD,IDI.DESCRICAO as IDIOMA, IDI.codigo, MAX(PROF.nivel_compr_auditiva) as COMP_AUD,MAX(PROF.nivel_expr_oral) as EXP_ORAL,MAX(PROF.nivel_compr_leitora) as COMP_LEIT,MAX(PROF.nivel_expr_escrita) as EXP_ESC 
                FROM RH_QUADRO.INDICE_PROF_LINGUISTICA PROF 
                INNER JOIN RH_QUADRO.IDIOMA_IPL IDI ON PROF.codigo_idioma = IDI.codigo 
                WHERE PROF.PES_IDENTIFICADOR_COD = '$idt' AND IDI.CODIGO = '$codigoIdioma' GROUP BY PROF.PES_IDENTIFICADOR_COD,IDI.DESCRICAO,IDI.codigo";

    $queryDgp = ociparse($oci_connect,$sqlDgp);
    oci_close($oci_connect);
    ociexecute($queryDgp);
    $countIdioma = oci_fetch($queryDgp);

    if ($countIdioma == false) {
        echo "<script>alert('Militar não possui o índice informado!'); window.location = '../../index.php?a=8';</script>";
    }

    ociexecute($queryDgp);
    $dadosDgp = oci_fetch_assoc($queryDgp);

// --------- Variáveis do Formulário ----- //
$email    = $dadosMilitar['cad_mail'];
$nome     = mb_strtoupper($dadosMilitar['cad_nome'],'UTF-8');
$cpf      = $dadosMilitar['cad_cpf'];
$pg       = mb_strtoupper($dadosMilitar['cad_postograd'],'UTF-8');
$idioma   = mb_strtoupper($dadosDgp['IDIOMA'],'UTF-8');
$idi      = mb_strtoupper(substr($dadosDgp['IDIOMA'],0,3),'UTF-8');
$compAud  = (($dadosDgp['COMP_AUD'] == '' || $dadosDgp['COMP_AUD'] == 0) ? "-" : $dadosDgp['COMP_AUD']);
$expOral  = (($dadosDgp['EXP_ORAL'] == '' || $dadosDgp['EXP_ORAL'] == 0) ? "-" : $dadosDgp['EXP_ORAL']);
$compLeit = (($dadosDgp['COMP_LEIT'] == '' || $dadosDgp['COMP_LEIT'] == 0) ? "-" : $dadosDgp['COMP_LEIT']);
$expEsc   = (($dadosDgp['EXP_ESC'] == '' || $dadosDgp['EXP_ESC'] == 0) ? "-" : $dadosDgp['EXP_ESC']);

// --------- Variáveis que podem vir de um banco de dados por exemplo ----- //
$idiomatit  = "Idioma:                          ".$idioma;
$idiomaipls = "IPL:                                ".$compAud." ".$expOral." ".$compLeit." ".$expEsc;

$texto1 = utf8_decode("Validação de Certificado de IPL");
$texto2 = utf8_decode("Código do certificado: ".$validador);
$texto3 = utf8_decode($idiomatit);
$texto4 = utf8_decode($idiomaipls);
$texto5 = utf8_decode($pg." ".$nome);
$texto6 = utf8_decode("Este certificado é valido e verdadeiro conforme a legislação do Exército Brasileiro");
$texto7 = utf8_decode("MAURÍCIO AVELAR TINOCO - Ten Cel");
$texto8 = utf8_decode("Comandante do Centro de Idiomas do Exército");



$pdf = new AlphaPDF();

// Orientação Landing Page ///
$pdf->AddPage('P');

$pdf->SetLineWidth(1.5);


// desenha a imagem do certificado
$pdf->Image('validacaotopo.png',1,1,210);
$pdf->Image('../cidex.png',83,105,45);

//$pdf->Image('tc-augusto.png',25,155,100,30);
$pdf->Image('cel-tinoco2.png',47,180,120,40);
// opacidade total
$pdf->SetAlpha(1);

$pdf->SetFont('Arial', 'B', 18); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(55,50); //Ajustando a posição X e Y
$pdf->MultiCell(265, 10, $texto1, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(54,62); //Ajustando a posição X e Y
$pdf->MultiCell(265, 10, $texto2, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(54,70); //Ajustando a posição X e Y
$pdf->MultiCell(265, 10, $texto3, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(54,78); //Ajustando a posição X e Y
$pdf->MultiCell(265, 10, $texto4, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(54,90); //Ajustando a posição X e Y
$pdf->MultiCell(110, 5, $texto5, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(255,0,0); //Cor em RGB
$pdf->SetXY(40,165); //Ajustando a posição X e Y
$pdf->MultiCell(130, 6, $texto6, '', 'C', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(70,200); //Ajustando a posição X e Y
$pdf->MultiCell(265, 10, $texto7, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(55,206); //Ajustando a posição X e Y
$pdf->MultiCell(265, 10, $texto8, '', 'L', 0); // Tamanho width e height e posição


$pdfdoc = $pdf->Output('', 'S');

$certificado="arquivos/validacao-$cpf.$codigo.pdf"; //atribui a variável $certificado com o caminho e o nome do arquivo que será salvo (vai usar o CPF digitado pelo usuário como nome de arquivo)
$pdf->Output($certificado,'F'); //Salva o certificado no servidor (verifique se a pasta "arquivos" tem a permissão necessária)

$pdf->Output(); // Mostrar o certificado na tela do navegador

} else {
    echo "Erro ao gerar certificado!!";
}
?>
