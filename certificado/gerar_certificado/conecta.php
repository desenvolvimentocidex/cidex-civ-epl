<?php

$dbstr ="(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.67.7.129)(PORT = 1521))
(CONNECT_DATA =
(SERVER = DEDICATED)
(SERVICE_NAME = ebcorp)
))";

$db_username = "ceadex";
$db_password = "brasil_ceadex_23422";

// CONEXAO DGP ORACLE
$oci_connect = oci_connect($db_username,$db_password, $dbstr,'AL32UTF8');

// CONEXAO MYSQL
$banco = "db_cidex_inscricao"; $con = mysqli_connect("10.166.64.145","cidex","ciadex#01@_", $banco,'3306') or die("ERRO: ". mysqli_error());
//$banco = "db_cidex_inscricao"; $con = mysqli_connect("10.166.64.145","cidex95","ciadex#01@_", $banco,'3306') or die("ERRO: ". mysqli_error());

mysqli_query($con, "SET NAMES 'utf8'");
mysqli_query($con, "SET character_set_connection=utf8");
mysqli_query($con, "SET character_set_client=utf8");
mysqli_query($con, "SET character_set_results=utf8");

?>