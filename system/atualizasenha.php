<?php
  $cpf ="";
  $identidade = "";
  $datanascimento = "";
  if (isset($_POST) && isset($_POST['action']) == 'atualizasenha' ){
     $senha = htmlspecialchars($_POST['senha']);
     $senha2 = htmlspecialchars($_POST['senha2']);
     
     $cpf =preg_replace("/[^0-9]/", "",  htmlspecialchars($_POST['cpf']) );
     $identidade = preg_replace("/[^0-9]/", "", htmlspecialchars($_POST['identidade']) );
     $datanascimento = htmlspecialchars($_POST['datanascimento']);
     if ( empty($cpf) || empty($identidade) || empty($datanascimento) || empty($senha) ||empty($senha2) ){
        echo "<script type='text/javascript'>alert('Todos os campos devem ser preenchidos');</script>";
        
     }else {
        if ($senha != $senha2){
            echo "<script type='text/javascript'>alert('As senhas não conferem');</script>";        
        }else{         
            $pessoa = getSQLMySQL("select cad_cpf,  cad_nascimento from cadastro where md5(cad_login) = '".md5($identidade)."'" );           
            if ( empty($pessoa) || empty($pessoa[0]['cad_cpf']) ){
                echo "<script type='text/javascript'>alert('Militar não encontrado');</script>";
            }else{
                $pessoa = $pessoa[0];
                $arr_nascimento = explode('/',$pessoa['cad_nascimento']);
                $cad_nascimento = new DateTime($arr_nascimento[2].'-'.$arr_nascimento[1].'-'.$arr_nascimento[0]);
                $datanascimento = new DateTime($datanascimento);           
                if ($pessoa['cad_cpf'] != $cpf ||  $cad_nascimento != $datanascimento){
                    echo "<script type='text/javascript'>alert('Informações não conferem');</script>";
                }else{
                    global $con;
                    $strSQL = "update cadastro 
                                set cad_pass = '". hash('sha256',$senha)."'
                                where md5(cad_login) = '".md5($identidade)."'" ;
                    mysqli_query($con, $strSQL);              
                    echo "<script type='text/javascript'>alert('Cadastro atualizado com sucesso!'); setTimeout(function(){window.location = '/'},2000); </script>";
                    return;
                }
            

            }
        
        }
    }

  }
?>

<style>
    .div_atualizar_senha{
        display: flex;
        flex-direction: column;
        flex-wrap: nowrap;
        max-width: 20rem;
        margin: auto;
        padding: 1em;
        box-shadow: 0px 0px 3px 2px #a9a9a9;
        background-color: #e7e7e7;
    }
    .div_atualizar_senha label{
        text-align: start;
        font-weight: 600;
        padding: 3px;
        width: 100%;
    }
    .div_atualizar_senha input{
        height: 2em;
        margin: 0.3em;
        border: 1px solid #448dd0;
        border-radius: 3px;
    }

    .div_atualizar_senha button{
        height: 3em;
        border-radius: 7px;
        font-weight: 700;
        background-color: #317e31;
        color: #ffffff;
        width: 150px;
        margin: auto;
    }

    h4{
        font-size: 1.2em;
        font-family: sans-serif;
        padding: 0.5em;
        font-stretch: semi-condensed;
    }
</style>    

<form action="" method="POST">
    <div class="div_atualizar_senha">
        <h4>Precisamos atualizar sua senha, para isso, vamos confirmar alguns dados. </h4>
        <input type="hidden" name="action" value="atualizasenha">
        <label class="w70pc" for="identidade">Identidade</label>    
        <input type="text" name="identidade" maxlength="10" value="<?= $identidade ?>" >  
        <label class="w70pc" for="cpf">CPF</label>
        <input type="text" name="cpf" maxlength="11"  value="<?= $cpf ?>" >
        <label class="w70pc" for="datanascimento">Data de nascimento</label>
        <input type="date" name="datanascimento"  >    
        <label class="w70pc" for="senha">Digite uma nova senha</label>
        <input type="password" name="senha"  maxlength="8">
        <label class="w70pc" for="senha2">Repita a mesma senha</label>
        <input type="password" name="senha2"  maxlength="8">
        <button type="submit">Enviar</button>            
    </div>
</form>

