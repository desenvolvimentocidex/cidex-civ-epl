<?php
  require_once '../system/system.php';
  if ( isset( $_POST ) && count($_POST) > 0  ){ /*Valida*/
      $num = $_POST['numinscricao'];
      $dv = $_POST['dv'];      
      $dvcalculado = getSQLMySQL("select dv_inscricao_v3('{$num}') as dv ");	 	  
      $texto = "NÚMERO DE INSCRIÇÃO INVÁLIDO";    
      if ($dvcalculado[0]['dv'] == $dv){
          $texto = "NÚMERO DE INSCRIÇÃO OK";
      }
      
      echo "<script> alert('{$texto}'); location.href = '/cidex/';  </script>";
              
  }
  
  
  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="br" lang="pt">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>Exército Brasileiro</title>                
		<link href="imagens/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon"/>
		<link rel="stylesheet" href="../style/style.css"/>
		<link rel="stylesheet" href="../style/jquery-confirm.css"/>
		<link rel="stylesheet" href="../style/jAlert.css"/>
                <!--<link rel="stylesheet" href="style/bootstrap.css"/>-->
		<script src="https://www.google.com/recaptcha/api.js"></script>
                <script type="text/javascript" src="../script/jquery-3.6.0.js"></script>
                <script type="text/javascript" src="../script/jquery-confirm.js"></script>
                <script type="text/javascript" src="../script/jAlert.js"></script>
                <script type="text/javascript" src="../script/jAlert-functions.js"></script>
		<script type="text/javascript" src="../script/script.js"></script>
		<style id="antiClickjack">body{display:none !important;}</style>
		<script type="text/javascript">
		if(self === top){
			var antiClickjack = document.getElementById("antiClickjack");
			antiClickjack.parentNode.removeChild(antiClickjack);
		}else{
			top.location = self.location;
                            }
		</script>
	</head>
<body>
<div id="corpo">
<?php include 'top.php'; ?>
<div id="conteudo">	
        <div id="index_conteudo" style="height: 200px">
            <p><span style="color: blue; font-weight: bold;">Validação de nº de inscrição</span></p>            
            <p>
              <form method="POST" action="validanuminscricao.php">
                    <input type="text" maxlength="12" name="numinscricao" class="form-control" autocomplete="off" placeholder="Nº da inscrição" autofocus>
                    <input type="number" max="15" min="0" name="dv" class="form-control" placeholder="DV" >
                    <input type="submit" value="Validar">
              </form>
            </p>
        </div>
</div>
<?php include 'rodape.php' ?>
</div>
</body>
</html>
<?php 


?>

  
  
