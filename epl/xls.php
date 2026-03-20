<?php
include '../system/system.php';

//ini verifica ultimo periodo para epl
if (empty($_GET['cpid'])){
   $cp = mysqli_fetch_array(mysqli_query($con, "select * from curso_periodo order by cp_id desc"));
   $last_cp_id = $cp["cp_id"];//ultimo periodo cadastrado
} else{   
  $last_cp_id = $_GET["cpid"];
}
//fim verifica ultimo periodo para epl

$crs = mysqli_fetch_array(mysqli_query($con, "select * from curso where crs_id = $cid"));
$crs_nome = $crs["crs_nome"];
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
$html .= "<td width=150><b>Código</b></td>";
$html .= "<td width=400><b>Candidato</b></td>";
$html .= "<td width=150><b>Posto</b></td>";
$html .= "<td width=150><b>E-mail</b></td>";
$html .= "<td width=150><b>OM Candidato</b></td>";
$html .= "<td width=150><b>Municipio OM</b></td>";
$html .= "<td width=150><b>UF OM</b></td>"; 
$html .= "<td width=100><b>Identidade</b></td>";
$html .= "<td width=150><b>Credenciamento</b></td>";
$html .= "<td width=50><b>Nível</b></td>";
$html .= "<td width=150><b>OMSE</b></td>";
$html .= "<td width=150><b>Celular</b></td>";
/*$html .= "<td width=150><b>Municipio OMSE</b></td>";
$html .= "<td width=150><b>UF OMSE</b></td>"; */
$html .= "</tr>";

$i = 1;
$cc = mysqli_query($con, "   select concat(num_inscricao,'-',digito_verificador) referencia,
                                    cad_nome, 
                                    cad_postograd,
                                    cad_login, 
                                    CONCAT(cc.cp_id, SUBSTRING_INDEX(crs_cod,'/',1), SUBSTRING_INDEX(cp_nome,'.',1),'-',idm_sigla,'-', SUBSTRING_INDEX(crs_cod,'/',-1) )  AS credenciamento,
                                    nivel_id,  	    
                                    concat(om.om_sigla,'-',om.om_nome) as om_sigla,
                                    om.om_municipio, 
                                    om.om_uf,
                                    cad.cad_om,
                                    cad.cad_om_municipio AS om_municipio_candidato , 
                                    cad.cad_om_uf AS om_uf_candidato,
                                    cad.cad_mail,
                                    concat('(', replace(cad.cad_cel,';',') ') ) celular
                               from cadastro cad
                              INNER join cadastro_curso cc
                                 ON ( cc.cad_id = cad.cad_id  )
                              INNER join idioma idm
                                 ON ( cc.idm_id = idm.idm_id )
                              INNER join curso c
                                 ON ( c.crs_id = cc.crs_id  ) 
                              INNER join curso_periodo cp 
                                 ON ( cp.cp_id = cc.cp_id )
                              INNER JOIN curso_local
                                 ON (curso_local.cl_id = cc.cl_id)
                              INNER JOIN om 
                                 ON (om.om_id = curso_local.om_id)                                      
                           where cc.crs_id = {$cid}                             
                             and ccs_id = 1                              
                             and cc.cp_id = {$last_cp_id} 
                           order by cc.idm_id,nivel_id,cad.cad_nome");
while($cc_lista = mysqli_fetch_array($cc)){
	foreach($cc_lista as $campo => $valor) {
            $$campo = addslashes($valor);
            
        }
	/*$bol_id = str_pad($cc_id, 6, "0", STR_PAD_LEFT);//999.999 registros        
	$referencia = $idm_id.$nivel_id.$crs_id.$bol_id;
	$crs_cod = explode("/",$crs_cod);
	$cp_nome = explode(".",$cp_nome);
	$credenciamento = $cp_id . $crs_cod[0] . $cp_nome[0] ."-". $idm_sigla ."-". $crs_cod[1];
	$omse = mysqli_fetch_array(mysqli_query($con, "select * from curso_local cl,om where cl.om_id = om.om_id and cl.cl_id = $cl_id"));
	$om_sigla = $omse["om_sigla"];*/
	$identidade = "&nbsp;". $cad_login;
$html .= "<tr>";
$html .= "<td>". $i++ ."</td>";
$html .= "<td align=right>&nbsp;". $referencia ."</td>";
$html .= "<td>". $cad_nome ."</td>";
$html .= "<td>". $cad_postograd ."</td>";
$html .= "<td>". $cad_mail ."</td>";
$html .= "<td>". $cad_om."</td>";
$html .= "<td>". $om_municipio_candidato ."</td>";
$html .= "<td>". $om_uf_candidato ."</td>";
$html .= "<td>". $identidade ."</td>";
$html .= "<td>". $credenciamento ."</td>";
$html .= "<td>". $nivel_id ."</td>";
$html .= "<td>". $om_sigla ."</td>";
$html .= "<td>". $celular ."</td>";
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