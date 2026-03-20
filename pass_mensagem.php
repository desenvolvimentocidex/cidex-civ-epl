<?php
include 'system/system.php';

if(Token::check($token)){

	echo "<script>alert('Impossível enviar a senha por email! Favor entrar em contato com o CEADEx (21)2457-1969 ou 810+4255 com seus dados de identificação em mãos.');location='index.php'</script>";

}
?>
