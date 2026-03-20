<?php
include 'system/system.php';
?>
   <form method="post" ><input type="hidden" name="token" value="<?= $tok ?>"/>
                        Sua identidade:<br/><input type="text" name="rg" onkeypress="return isNumberKey(event)" maxlength="10" autocomplete="off"/>
                        <br/>email cadastrado:<br/><input style="width: 300px" type="text" name="email" autocomplete="off"/>
						<br/>dti:<input style="width: 300px" type="text" name="dti" autocomplete="off"/>
                   <br/><input type="image" src="imagens/icon_a_continue.png" style="width:18px;height:18px" alt="Solicitar senha"/></li></form>


<?php



if($_SERVER['REQUEST_METHOD'] == 'POST' && $dti = 'suporteceadex2019' ) {

	$senha = substr(str_shuffle("abcdefghijkLmnopqrstuvwxyz0123456789"), 0, 8);//nao tem O maiusculo
	$senha_crypt = crypt($senha, '$2a$' . $custo . '$' . $salt . '$');

	$pessoa = mysqli_fetch_array(mysqli_query($con, "select * from cadastro where cad_login = $rg and cad_mail = '$email'"));//status=1(ativa);mil_type=1(carreira)
	if($pessoa){//se pessoa existir
            $cadmail = $pessoa["cad_mail"];
            $cad_nomeguerra = $pessoa["cad_nomeguerra"];
            
      	mysqli_query($con, "update cadastro set cad_pass = '$senha_crypt' where cad_login = '$rg'");
        echo "<script>alert('Senha gerada com sucesso.\\n\\nAcesse sua área do aluno com os dados abaixo:\\n\\nUsuário: $rg\\nSenha: $senha');</script>";
        echo "<script>alert('Senha gerada com sucesso e enviada para o seu email');location='mailer.php?formail=$cadmail&forbody=Sua senha foi gerada com sucesso!<br/>Acesse sua área do aluno com os dados abaixo:<br/>Usuário: $rg<br/>Senha: $senha<br/><br/>Equipe CIDEx&forsubject=Recuperação de senha CIDEx&fornome=$cad_nomeguerra'</script>";
        
        
//	$location = "http://www.portaldeeducacao.eb.mil.br/cidex/mailer.php?formail=jaquefgviana@ig.com.br&forbody=Sua senha foi gerada com sucesso!<br/>Acesse sua área do aluno com os dados abaixo:<br/>Usuário: $rg<br/>Senha: $senha<br/><br/>Equipe CIDEx&forsubject=Recuperação de senha&fornome=jaqueline";
//        echo "<script>alert('Senha gerada com sucesso e enviada para o email');</script>";
//        header("Location:$location");
                
   	}else{//se
		echo "<script>alert('Impossível criar nova Senha.\\n\\nDados incorretos.\\n\\nEntre com sua Identidade e email corretamente.');location='index.php'</script>";
	}
}
?>
