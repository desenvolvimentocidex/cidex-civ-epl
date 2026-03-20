
<head>
Atualização de Dados
</head>
</br>
<?php

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

ignore_user_abort(true);
set_time_limit(0);
flush();

$dbstr ="(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.67.7.129)(PORT = 1521))
(CONNECT_DATA =
(SERVER = DEDICATED)
(SERVICE_NAME = ebcorp)
))";

$db_username = "ceadex";
$db_password = "brasil_ceadex_23422";

// CONEXAO DGP ORACLE
$oci_connect = oci_connect($db_username,$db_password, $dbstr,'AL32UTF8');


#ini banco dgp
//$bancodgp = "ebcorp"; @$condgp = mysqli_connect("10.67.5.67:3306","detmil","detmil2015") or die("<script>alert('A CONEXÃO COM O SERVIDOR DO DGP FALHOU!\\n\\nTENTE REALIZAR SEU CADASTRO MAIS TARDE.');location='?id=100'</script>");
//$dbdgp = mysqli_select_db($bancodgp,$condgp);
//mysqli_query($con, "SET NAMES 'utf8'");
//mysqli_query($con, "SET character_set_connection=utf8");
//mysqli_query($con, "SET character_set_client=utf8");
//mysqli_query($con, "SET character_set_results=utf8");
#fim banco dgp


$banco = "db_cidex_inscricao"; $con = mysqli_connect("localhost","root","#c34d3x@mysql+", $banco,'3306') or die("ERRO: ". mysqli_error());
//$db = mysqli_select_db($banco,$con);
mysqli_query($con, "SET NAMES 'utf8'");
mysqli_query($con, "SET character_set_connection=utf8");
mysqli_query($con, "SET character_set_client=utf8");
mysqli_query($con, "SET character_set_results=utf8");

?>

<body>
<?php
flush();
$cad = mysqli_query($con, "select * from cadastro where cad_codpg = 0");

while($cad_lista = mysqli_fetch_array($cad)){
	$rg = $cad_lista["cad_login"];
	
//	$pessoa = mysqli_fetch_array(mysqli_query($con, "select m.*,p.*,pg.*,q.*,o.CODOM as om_id, o.RM_COD as om_rm_id, o.SIGLA as om_sigla, o.NOME as om_desc, rm.CODIGO as rm_id, rm.CMA_CODIGO as rm_cma_id, cma.codigo as cma_id, rm.sigla as rm_sigla, rm.descricao as rm_desc, cma.sigla as cma_sigla, cma.descricao as cma_desc 
//	from MILITAR m
//	left join PESSOA p on m.PES_IDENTIFICADOR_COD = p.IDENTIFICADOR_COD
//	left join POSTO_GRAD_ESPEC pg on m.POSTO_GRAD_CODIGO = pg.CODIGO
//	left join QAS_QMS q on m.QQ_COD_QAS_QMS = q.COD_QAS_QMS
//	left join ORGAO o on o.codom = m.OM_CODOM
//	left join RM rm on o.rm_cod = rm.CODIGO
//	left join COMANDO_MILITAR_AREA cma on rm.CMA_CODIGO = cma.codigo
//	where m.PES_IDENTIFICADOR_COD = $rg",$condgp));
        
        //Conexão com BD Oracle 
        $consultapessoa = oci_parse($oci_connect, "select m.*,p.*,pg.*,q.*,o.CODOM as om_id, o.RM_COD as om_rm_id, o.SIGLA as om_sigla, o.NOME as om_desc, rm.CODIGO as rm_id, rm.CMA_CODIGO as rm_cma_id, cma.codigo as cma_id, rm.sigla as rm_sigla, rm.descricao as rm_desc, cma.sigla as cma_sigla, cma.descricao as cma_desc 
	from RH_QUADRO.MILITAR m
	left join RH_QUADRO.PESSOA p on m.PES_IDENTIFICADOR_COD = p.IDENTIFICADOR_COD
	left join RH_QUADRO.POSTO_GRAD_ESPEC pg on m.POSTO_GRAD_CODIGO = pg.CODIGO
	left join RH_QUADRO.QAS_QMS q on m.QQ_COD_QAS_QMS = q.COD_QAS_QMS
	left join RH_QUADRO.ORGAO o on o.codom = m.OM_CODOM
	left join RH_QUADRO.RM rm on o.rm_cod = rm.CODIGO
	left join RH_QUADRO.COMANDO_MILITAR_AREA cma on rm.CMA_CODIGO = cma.codigo
	where m.PES_IDENTIFICADOR_COD = $rg");
        oci_execute($consultapessoa);
        $pessoa = oci_fetch_array($consultapessoa, OCI_ASSOC+OCI_RETURN_NULLS);
        //Conexão com BD Oracle 
	
	if($pessoa){
		foreach($pessoa as $campo => $valor){$$campo = stripslashes($valor);}
		
		$pg_sigla = $SIGLA;
                $qasqms = $SIGLA_QAS_QMS . " - " . $DESC_QAS_QMS;
                $rm = $RM_SIGLA . " - " . $RM_DESC;
                $cma = $CMA_SIGLA . " - " . $CMA_DESC;
                $om = $OM_SIGLA . " - " . $OM_DESC;
		
 	        $NOME = addslashes ($NOME);
                $NOME_PAI = addslashes ($NOME_PAI);
                $NOME_MAE = addslashes ($NOME_MAE);
                $NOME_GUERRA = addslashes ($NOME_GUERRA);
                $rm = addslashes ($rm);
                $qasqms = addslashes ($qasqms);
                $codigo_pg = addslashes ($POSTO_GRAD_CODIGO);
                
                $query = "update cadastro set cad_nome = '$NOME',cad_pai = '$NOME_PAI',cad_mae ='$NOME_MAE',cad_sexo = '$SEXO',cad_nascimento = '$DT_NASCIMENTO',cad_cpf = '$CPF',cad_nomeguerra = '$NOME_GUERRA',cad_postograd = '$pg_sigla',cad_codpg = '$codigo_pg',cad_qasqms = '$qasqms',cad_preccp = '$PREC_CP',cad_rm = '$rm',cad_cma = '$cma',cad_om = '$om', cad_atualizado = 1 where cad_login = '$rg'";
                print $query;
                
		mysqli_query($con, $query);
	        
                ?>
                
        <span><?php echo $rg; ?> atualizado</span> 
        </br>
        
	<?php	
	flush();
        }
	else
	{
            ?>
                <span><?php echo $rg; ?> não encontrado</span> 
                </br>
                
	<?php	
	flush();
        
        }

}
echo "<script>alert('dados atualizados com sucesso')</script>";
?>

</body>