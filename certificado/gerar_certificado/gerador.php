<?php
ini_set('display_errors', 1);
session_start();
setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
date_default_timezone_set( 'America/Sao_Paulo' );
require('fpdf/alphapdf.php');
require('../../system/csrf.class.php');
require('../../system/system.php');

if( /*Token::check($_POST['tok']) && */ isset($_POST['codigo']) && isset($_POST['idt']) && $_POST['idt'] == $_SESSION['login']){



$idt = addslashes($_POST['idt']);
$codigo = addslashes($_POST['codigo']);

$sqlMilitar = mysqli_fetch_assoc(mysqli_query($con, "select * from cadastro where cad_login = '$idt'"));

$sqlDgp = "SELECT PROF.PES_IDENTIFICADOR_COD,IDI.DESCRICAO as IDIOMA, IDI.codigo, 
                  MAX( coalesce( PROF.nivel_compr_auditiva,0) ) as COMP_AUD,
				  MAX( coalesce( PROF.nivel_expr_oral,0) ) as EXP_ORAL,
				  MAX( coalesce( PROF.nivel_compr_leitora,0) ) as COMP_LEIT,
				  MAX( coalesce( PROF.nivel_expr_escrita,0) ) as EXP_ESC 
            FROM RH_QUADRO.INDICE_PROF_LINGUISTICA PROF 
            INNER JOIN RH_QUADRO.IDIOMA_IPL IDI ON PROF.codigo_idioma = IDI.codigo 
            WHERE PROF.PES_IDENTIFICADOR_COD = '$idt' AND IDI.CODIGO = '$codigo' GROUP BY PROF.PES_IDENTIFICADOR_COD,IDI.DESCRICAO,IDI.codigo";

$queryDgp = ociparse($oci_connect,$sqlDgp);
oci_close($oci_connect);
ociexecute($queryDgp);
$countIdioma = oci_fetch($queryDgp);

if ($countIdioma == false) {
    echo "<script>alert('Militar não possui o índice informado!'); window.location = '../../index.php?a=8';</script>";
}

ociexecute($queryDgp);
$dadosDgp = oci_fetch_assoc($queryDgp);

$validador = strtoupper(date('Y').uniqid(""));

$sqlGravCert = mysqli_query($con, "INSERT INTO `certificado` (`cad_login`, `cert_validador`, `cert_idioma`, `cert_ca`, `cert_eo`, `cert_cl`, `cert_ee`) VALUES ('$idt', '$validador', '$codigo', '".$dadosDgp['COMP_AUD']."', '".$dadosDgp['EXP_ORAL']."', '".$dadosDgp['COMP_LEIT']."', '".$dadosDgp['EXP_ESC']."');");

if ($sqlGravCert != true) {
    echo "<script>alert('Erro ao gravar certficado!'); window.location = '../../index.php?a=8';</script>";
}


// --------- Variáveis do Formulário ----- //
$email    = $sqlMilitar['cad_mail'];
$nome     = mb_strtoupper($sqlMilitar['cad_nome'],'UTF-8');
$cpf      = $sqlMilitar['cad_cpf'];
$pg       = $sqlMilitar['cad_postograd'];
$idioma   = mb_strtoupper($dadosDgp['IDIOMA'],'UTF-8');
$idi      = mb_strtoupper(substr($dadosDgp['IDIOMA'],0,3),'UTF-8');
$compAud  = (($dadosDgp['COMP_AUD'] == '' || $dadosDgp['COMP_AUD'] == 0) ? "-" : $dadosDgp['COMP_AUD']);
$expOral  = (($dadosDgp['EXP_ORAL'] == '' || $dadosDgp['EXP_ORAL'] == 0) ? "-" : $dadosDgp['EXP_ORAL']);
$compLeit = (($dadosDgp['COMP_LEIT'] == '' || $dadosDgp['COMP_LEIT'] == 0) ? "-" : $dadosDgp['COMP_LEIT']);
$expEsc   = (($dadosDgp['EXP_ESC'] == '' || $dadosDgp['EXP_ESC'] == 0) ? "-" : $dadosDgp['EXP_ESC']);

// --------- Variáveis que podem vir de um banco de dados por exemplo ----- //
$idiomatit  = "  IDIOMA ".$idioma;
$idiomaipls  = $idi ." ".$compAud." ".$expOral." ".$compLeit." ".$expEsc;

$texto1 = utf8_decode($idiomatit);
$texto2 = utf8_decode("         O Comandante do Centro de Idiomas do Exército, no uso de sua atribuição, certifica que o/a  $pg $nome, identidade nº $idt - MD/EB, possui o Índice de Proficiência Linguística (IPL)");
$texto3 = utf8_decode($idiomaipls);
$texto4 = utf8_decode("conforme consta em sua Ficha Cadastro do Sistema de Cadastramento de Pessoal do Exército (SiCaPEx). Sendo assim, é outorgado ao/à militar acima descrito(a) o presente Certificado, a fim de que possa gozar de todos os direitos e prerrogativas legais.");
$texto5 = utf8_decode("Rio de Janeiro, RJ, ".strftime( '%d de %B de %Y', strtotime( date( 'Y-m-d' ) ) ).".");
$texto6 = ""; //utf8_decode("JOSÉ AUGUSTO PEREIRA DA COSTA - Ten Cel");
$texto7 = ""; //utf8_decode("Chefe da Divisão de Certificação                       ");
$texto8 = utf8_decode("MAURÍCIO AVELAR TINOCO - Ten Cel");
$texto9 = utf8_decode("Comandante do Centro de Idiomas do Exército");
$texto10 = utf8_decode("Código para verificação de validade: ".$validador);



$pdf = new AlphaPDF();

// Orientação Landing Page ///
$pdf->AddPage('L');

$pdf->SetLineWidth(1.5);


// desenha a imagem do certificado
$pdf->Image('certificadocidexv2.png',1,1,295);

//$pdf->Image('tc-augusto.png',25,155,100,30);
$pdf->Image('cel-tinoco2.png',80,150,120,40);
// opacidade total
$pdf->SetAlpha(1);

// Mostrar texto no topo
$pdf->SetFont('Arial', '', 32); // Tipo de fonte e tamanho
$pdf->SetXY(138,72); //Ajustando a posição X e Y
$pdf->MultiCell(265, 10, $texto1, '', 'L', 0); // Tamanho width e height e posição

//// Mostrar o nome
//$pdf->SetFont('Arial', '', 30); // Tipo de fonte e tamanho
//$pdf->MultiCell(265, 10, $nome, '', 'C', 0); // Tamanho width e height e posição

// Mostrar o corpo
$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetXY(20,85); //Ajustando a posição X e Y
$pdf->MultiCell(260, 10, $texto2, '', 'J', 0); // Tamanho width e height e posição

// Mostrar a data no final
$pdf->SetFont('Arial', 'B', 32); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(116,111); //Ajustando a posição X e Y
$pdf->MultiCell(165, 10, $texto3, '', 'L', 0); // Tamanho width e height e posição

// Mostrar a data no final
$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(20,123); //Ajustando a posição X e Y
$pdf->MultiCell(260, 10, $texto4, '', 'J', 0); // Tamanho width e height e posição

// Mostrar a data no final
$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(100,148); //Ajustando a posição X e Y
$pdf->MultiCell(265, 10, $texto5, '', 'L', 0); // Tamanho width e height e posição

// Mostrar a data no final
$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(25,170); //Ajustando a posição X e Y
$pdf->MultiCell(265, 10, $texto6, '', 'L', 0); // Tamanho width e height e posição

// Mostrar a data no final
$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(39,176); //Ajustando a posição X e Y
$pdf->MultiCell(265, 10, $texto7, '', 'L', 0); // Tamanho width e height e posição

// Mostrar a data no final
$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(105,170); //Ajustando a posição X e Y
$pdf->MultiCell(265, 10, $texto8, '', 'L', 0); // Tamanho width e height e posição

// Mostrar a data no final
$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(98,176); //Ajustando a posição X e Y
$pdf->MultiCell(265, 10, $texto9, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetAutoPageBreak(false); 
// Mostrar a data no final
$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(80,190); //Ajustando a posição X e Y
$pdf->MultiCell(265, 10, $texto10, '', 'L', 0); // Tamanho width e height e posição


$texto11 = utf8_decode("Política de Ensino e Diretriz Estratégica do Ensino do Exército");
$texto12 = utf8_decode("Portarias nº 715 e nº 716 do Cmt EB, de 06 de dezembro de 2002 ");
$texto13 = utf8_decode("(BE nº 51, de 20  de  dezembro  de  2002).");
$texto14 = utf8_decode("Diretriz de Educação e Cultura do Exército Brasileiro");
$texto15 = utf8_decode("Portaria nº 341-EME, de 17 de dezembro de 2015 (BE nº 6, de 12  ");
$texto16 = utf8_decode("de  fevereiro  de  2016).");
$texto17 = utf8_decode("Sistema de Ensino de Idiomas e Certificação");
$texto18 = utf8_decode("de Proficiência Linguística do Exército (SEICPLEx)");
$texto19 = utf8_decode("Portaria nº 311-EME, de 08 de agosto de 2017 (BE nº 33, de 18 de ");
$texto20 = utf8_decode("agosto de 2017).");
$texto21 = utf8_decode("Subsistema de Certificação de Proficiência Linguística (SCPL)");
$texto22 = utf8_decode("(EB60-N-52.001)");
$texto23 = utf8_decode("Portaria nº 207-DECEx, de 30 de novembro de 2016 (BE nº 49, de");
$texto24 = utf8_decode("9 de dezembro de 2016), alterada pela Portaria nº 236-DECEx, de");
$texto25 = utf8_decode("1º de novembro de 2017 (BE nº 46, de 17 de novembro de 2017). ");
$texto26 = utf8_decode("Descritores da Escala de Proficiência Linguística do Exército");
$texto27 = utf8_decode("(EB60-N-19.003)");
$texto28 = utf8_decode("Portaria nº 20-DECEx, de 11 de fevereiro de 2016 (BE nº 7, de 19");
$texto29 = utf8_decode("de fevereiro de 2016). ");
$texto30 = utf8_decode("Índice de Proficiência Linguística (IPL)");
$texto31 = utf8_decode("O IPL é o índice utilizado no Sistema de Ensino de Idiomas e Certificação de Proficiência Linguística do Exército para expressar o desempenho linguístico do militar no respectivo idioma, sendo que "); 
$texto32 = utf8_decode("Para verificar a validade deste documento, acesse https://portaldeeducacao.eb.mil.br/cidex/validacao/ e insira o código localizado na parte inferior da frente do certificado.");

$pdf->AddPage('L');

$pdf->SetLineWidth(1.5);
// desenha a imagem do certificado
$pdf->Image('certificadocidexp2.png',1,1,295);
// opacidade total
$pdf->SetAlpha(1);
// Mostrar texto no topo
$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetXY(9,7); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto11, '', 'L', 0); // Tamanho width e height e posição
// Mostrar texto no topo
$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetXY(9,12); //Ajustando a posição X e Y
$pdf->MultiCell(140, 16, $texto12, '', 'L', 0); // Tamanho width e height e posição
$pdf->SetXY(9,12); //Ajustando a posição X e Y
$pdf->MultiCell(140, 26, $texto13, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetXY(17,35); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto14, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetXY(9,44); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto15, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetXY(9,49); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto16, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetXY(28,70); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto17, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetXY(19,75); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto18, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetXY(9,84); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto19, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetXY(9,89); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto20, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetXY(9,109); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto21, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetXY(59,114); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto22, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetXY(9,122); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto23, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetXY(9,127); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto24, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetXY(9,133); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto25, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetXY(10,147); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto26, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetXY(59,153); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto27, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetXY(9,163); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto28, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetXY(9,168); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto29, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'B', 13); // Tipo de fonte e tamanho
$pdf->SetXY(182,7); //Ajustando a posição X e Y
$pdf->MultiCell(140, 10, $texto30, '', 'L', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'I', 13); // Tipo de fonte e tamanho
$pdf->SetXY(160,16); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, $texto31, '', 'J', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'BI', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(201,37); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, 'as letras', '', 'J', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'I', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(220,37); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, ' indicam o idioma;', '', 'J', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'BI', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(258,37); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, ' o  primeiro', '', 'J', 0); // Tamanho width e height e posição



$pdf->SetFont('Arial', 'BI', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(160,44); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, 'algarismo', '', 'J', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'I', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(184,44); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, utf8_decode("indica o nível atingido na"), '', 'J', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'IU', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(237,44); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, utf8_decode("compreensão auditiva;"), '', 'J', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'BI', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(160,51); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, 'o  segundo,', '', 'J', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'I', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(187,51); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, utf8_decode("indica  o   nível   atingido   na"), '', 'J', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'IU', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(249,51); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, utf8_decode("expressão   oral;"), '', 'J', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'BI', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(160,58); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, 'o  terceiro,', '', 'J', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'I', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(186,58); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, utf8_decode("indica o nível atingido na"), '', 'J', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'IU', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(240,58); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, utf8_decode("compreensão leitora;"), '', 'J', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'BI', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(160,65); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, 'o  quarto,', '', 'J', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'I', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(184,65); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, utf8_decode("indica  o   nível  atingido  na"), '', 'J', 0); // Tamanho width e height e posição

$pdf->SetFont('Arial', 'IU', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(244,65); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, utf8_decode("expressão  escrita;"), '', 'J', 0); // Tamanho width e height e posição

$texto33 = utf8_decode("Esses níveis possuem equivalência com os níveis do Quadro Comum Europeu de Referência (QCER) e do Standardized Agreement (STANAG) 6001.");
$pdf->SetFont('Arial', 'I', 13); // Tipo de fonte e tamanho
$pdf->SetTextColor(0,0,0); //Cor em RGB
$pdf->SetXY(160,73); //Ajustando a posição X e Y
$pdf->MultiCell(125, 7, $texto33, '', 'J', 0); // Tamanho width e height e posição


$pdf->SetFont('Arial', '', 13); // Tipo de fonte e tamanho
$pdf->SetXY(10,191); //Ajustando a posição X e Y
$pdf->MultiCell(275,5, $texto32, '', 'J', 0); // Tamanho width e height e posição


$pdfdoc = $pdf->Output('', 'S');



$certificado="arquivos/$cpf.$codigo.pdf"; //atribui a variável $certificado com o caminho e o nome do arquivo que será salvo (vai usar o CPF digitado pelo usuário como nome de arquivo)
$pdf->Output($certificado,'F'); //Salva o certificado no servidor (verifique se a pasta "arquivos" tem a permissão necessária)
// Utilizando esse script provavelmente o certificado ficara salvo em www.seusite.com.br/gerar_certificado/arquivos/999.999.999-99.pdf (o 999 representa o CPF digitado pelo usuário)

$pdf->Output(); // Mostrar o certificado na tela do navegador

} else {
    echo "Erro ao gerar certificado!!";
}
?>
