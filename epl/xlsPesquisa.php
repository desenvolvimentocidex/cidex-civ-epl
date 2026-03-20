<?php
include '../system/system.php';

//ini verifica ultimo periodo para epl
$cp = mysqli_fetch_array(mysqli_query($con, "select * from curso_periodo order by cp_id desc"));
$last_cp_id = $cp["cp_id"];//ultimo periodo cadastrado
$last_cp_nome = $cp["cp_nome"];//ultimo periodo cadastrado
//fim verifica ultimo periodo para epl

$crs_nome = "Pesquisa ".$last_cp_nome;
/*
* Criando e exportando planilhas do Excel
* /
*/
// Definimos o nome do arquivo que será exportado
$arquivo = "planilha.xls";
// Criamos uma tabela HTML com o formato da planilha
$html = "";
$html .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
$html .= "<table>";
$html .= "<tr>";
$html .= "<td colspan=11 align=center><b>". $crs_nome ."</b></tr>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td></tr>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td width=50><b>Nr</b></td>";
$html .= "<td width=400><b>Candidato</b></td>";
$html .= "<td width=150><b>Posto</b></td>";
$html .= "<td width=150><b>OM Candidato</b></td>";
$html .= "<td width=150><b>Período</b></td>";
$html .= "<td width=150><b>Fez preparatório</b></td>";
$html .= "<td width=150><b>Idioma</b></td>";
$html .= "<td width=150><b>Modalidade</b></td>";
$html .= "<td width=150><b>Instituição</b></td>";
$html .= "<td width=150><b>UF</b></td>";
$html .= "<td width=150><b>Cidade</b></td>";
/*$html .= "<td width=150><b>Municipio OMSE</b></td>";
$html .= "<td width=150><b>UF OMSE</b></td>"; */
$html .= "</tr>";

$i = 1;
$cc = mysqli_query($con, "SELECT *
FROM cadastro c 
INNER JOIN pesquisa p ON c.cad_id = p.cad_id
LEFT JOIN idioma i ON i.idm_id = p.idm_id
LEFT JOIN curso_periodo cp ON cp.cp_id = p.cp_id
WHERE cp.cp_id = $last_cp_id");
while($cc_lista = mysqli_fetch_array($cc)){
	foreach($cc_lista as $campo => $valor) {
            $$campo = addslashes($valor);
            
        }

$identidade = "&nbsp;". $cad_login;
$html .= "<tr>";
$html .= "<td>". $i++ ."</td>";
$html .= "<td>". $cad_nome ."</td>";
$html .= "<td>". $cad_postograd ."</td>";
$html .= "<td>". $cad_om."</td>";
$html .= "<td>". $cp_nome ."</td>";
$html .= "<td>". $preparatorio ."</td>";
$html .= "<td>". $idm_nome ."</td>";
$html .= "<td>". $modalidade ."</td>";
$html .= "<td>". $instituicao ."</td>";
$html .= "<td>". $uf ."</td>";
$html .= "<td>". $cidade ."</td>";
$html .= "</tr>";
}
$html .= "</table>";

// Configurações header para forçar o download
header ('Content-Type: application/vnd.ms-excel; charset=utf-8');
//header('Content-Type: application/vnd.ms-excel; charset=iso-8859-1');
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
header ("Content-Description: PHP Generated Data" );
// Envia o conteúdo do arquivo

echo $html;
exit;
?>