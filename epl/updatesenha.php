<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../system/system.php';
$idt = md5(htmlspecialchars($_POST['idt']));
$senhaNova = substr(str_shuffle("abcdefghijkLmnopqrstuvwxyz0123456789"), 0, 8);//nao tem O maiusculo
$senha_crypt = encripta($senhaNova);
if ( mysqli_query($con, "update cadastro set cad_pass = '$senha_crypt' where md5(cad_login) = '$idt'") ){  
    echo "Senha gerada: $senhaNova";      
 return 1;   
}

echo "Erro ao gerar senha!";