<?php
include 'system/system.php';

if(Token::check($token)){

	$senha = substr(str_shuffle("abcdefghijkLmnopqrstuvwxyz0123456789"), 0, 8);//nao tem O maiusculo
	$senha_crypt = encripta($senha);
	
	$pessoa = mysqli_fetch_array(mysqli_query($con, "select * from cadastro where cad_login = '$rg' and cad_mail = '$email'"));//status=1(ativa);mil_type=1(carreira)
	if($pessoa){//se pessoa existir
            $cadmail = addslashes($pessoa["cad_mail"]);
            $cad_nomeguerra = addslashes($pessoa["cad_nomeguerra"]);
            $cad_nomeguerra = str_replace('\'', '', $cad_nomeguerra);
            
      	mysqli_query($con, "update cadastro set cad_pass = '$senha_crypt' where cad_login = '$rg'");

//        echo "<script>alert('Senha gerada com sucesso.\\n\\nAcesse sua área do aluno com os dados abaixo:\\n\\nUsuário: $rg\\nSenha: //$senha');</script>";
        echo "<script>
              var xhr = new XMLHttpRequest();
              var data = 'formail=$cadmail&forsubject=Recuperação de senha CIDEx&forbody=Sua senha foi gerada com sucesso!<br/>Acesse sua área do aluno com os dados abaixo:<br/>Usuário: $rg<br/>Senha: $senha<br/><br/>Equipe CIDEx & fornome=$cad_nomeguerra' ;
              xhr.open('POST', location.origin+'/mailer.php', true);
              xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');              
              xhr.send(data);";
        //echo "<script>alert('Senha gerada com sucesso e enviada para o seu email');location='mailer.php?formail=$cadmail&forbody=Sua senha foi gerada com sucesso!<br/>Acesse sua área do aluno com os dados abaixo:<br/>Usuário: $rg<br/>Senha: $senha<br/><br/>Equipe CIDEx&forsubject=Recuperação de senha CIDEx&fornome=$cad_nomeguerra'</script>";
            echo "alert('Senha gerada com sucesso e enviada para o seu email'); location=location.origin+'/index.php'; </script>";      
   	}else{//se
		echo "<script>alert('Impossível criar nova Senha.\\n\\nDados incorretos.\\n\\nEntre com sua Identidade e email corretamente.');location='index.php'</script>";
	}
}else{//se teoken for errado
	echo "<script>location='index.php'</script>";
}
?>
