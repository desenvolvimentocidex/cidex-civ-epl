<?php
include '../system/system.php';

$crs = mysql_fetch_array(mysql_query("select * from curso where crs_id = $cid"));
$crs_nome = $crs["crs_nome"];
/*
* Criando e exportando planilhas do Excel
* /
*/
// Definimos o nome do arquivo que será exportado
$arquivo = "planilha.xls";
// Criamos uma tabela HTML com o formato da planilha
$html = "";
$html .= "<table>";
$html .= "<tr>";
$html .= "<td colspan=8 align=center><b>". $crs_nome ."</b></tr>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td></tr>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td width=50><b>Nr</b></td>";
$html .= "<td width=150><b>Código</b></td>";
$html .= "<td width=400><b>Candidato</b></td>";
$html .= "<td width=150><b>Posto</b></td>";
$html .= "<td width=100><b>Identidade</b></td>";
$html .= "<td width=150><b>Credenciamento</b></td>";
$html .= "<td width=50><b>Nível</b></td>";
$html .= "<td width=150><b>OMSE</b></td>";
$html .= "</tr>";

$i = 1;
$cc = mysql_query("select * from cadastro cad,cadastro_curso cc,idioma idm,curso c where cc.crs_id = $cid and c.crs_id = cc.crs_id and cc.cad_id = cad.cad_id and ccs_id = 1 and cc.idm_id = idm.idm_id order by cc.idm_id,nivel_id,cad.cad_nome");
while($cc_lista = mysql_fetch_array($cc)){
	foreach($cc_lista as $campo => $valor){$$campo = addslashes($valor);}
	$bol_id = str_pad($cc_id, 10, "0", STR_PAD_LEFT);
	$codigo = "0".$idm_id."0".$crs_id."0".$nivel_id.$bol_id;
	$crs_cod = explode("/",$crs_cod);
	$credenciamento = "1". $crs_cod[0] ."2016-". $idm_sigla ."-". $crs_cod[1];
	$omse = mysql_fetch_array(mysql_query("select * from curso_local cl,om where cl.om_id = om.om_id and cl.cl_id = $cl_id"));
	$om_sigla = $omse["om_sigla"];
$html .= "<tr>";
$html .= "<td>". $i++ ."</td>";
$html .= "<td align=right>&nbsp;". $codigo ."</td>";
$html .= "<td>". $cad_nome ."</td>";
$html .= "<td>". $cad_postograd ."</td>";
$html .= "<td>&nbsp;". $cad_login ."</td>";
$html .= "<td>". $credenciamento ."</td>";
$html .= "<td>". $nivel_id ."</td>";
$html .= "<td>". $om_sigla ."</td>";
$html .= "</tr>";
}
$html .= "</table>";

// Configurações header para forçar o download
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