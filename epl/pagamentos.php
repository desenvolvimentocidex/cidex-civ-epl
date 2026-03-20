<?php
include '../system/system.php';
include 'action.php';
$i = 0;
error_reporting(0);
$cpini = htmlspecialchars((int)$_GET["cpini"]);


if(empty($cpini)){
	$cp = mysqli_fetch_array(mysqli_query($con, "select * from curso_periodo order by cp_id desc limit 1"));
	$cpid = $cp["cp_id"];
	$cpini = $cp["cp_ini"];
}
$corrigido = htmlspecialchars($_GET['corrigido'] == 1);
$complemento = ($corrigido)? "pgt.pgt_duplicado = 'NÃO' and pgt.flagpagamentocorrigido = 1"   : "pgt.pgt_duplicado = 'SIM' and pgt.flagpagamentocorrigido = 0" ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="br" lang="pt">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>CIDEx - Centro de Idiomas do Exército</title>
		<link href="imagens/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon"/>
		<link rel="stylesheet" href="style/style.css"/>
                <script src="script/jquery-3.6.0.js" type="text/javascript"></script>
		<script type="text/javascript" src="script/script.js"></script>
		<script src="https://www.google.com/recaptcha/api.js"></script>
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
<?php include ("top.php"); ?>

<?php if(@$_SESSION["loged"] == "adm_on"){ ?>
<?php include ("menu.php"); ?>
<div id="conteudo">
    <table width="880">
	<tr>
            <td colspan="2" class="tit2">Selecione o período: 
            <select onchange="location='?cpid='+ this.value+'&corrigido=<?=(htmlspecialchars($_GET['corrigido']) == 1)? '1': '0' ?>'">
                <option></option>
                <?php
                $cpid = htmlspecialchars((int)$_GET["cpid"]);
                if(empty($cpid)){
                    $cp = mysqli_fetch_array(mysqli_query($con, "select * from curso_periodo order by cp_id desc limit 1"));
                    $cpid = $cp["cp_id"];
                    $cpini = $cp["cp_ini"];
                }

                $cp = mysqli_query($con, "select * from curso_periodo order by cp_id desc");
                while($cp_lista = mysqli_fetch_assoc($cp)){
                ?>
                <option value="<?= $cp_lista['cp_id'] ?>" <?php if($cp_lista['cp_id'] == $cpid){ echo "selected"; } ?>><?= $cp_lista['cp_nome'] ?></option>
                <?php } ?>
            </select>
            </td>
        </tr>
    </table>
    <br/><br/>
    <table width="880">
	<tr>
            <td class="tit2" colspan="9">Lista de militares com Pagamentos Duplicados:</td>
	</tr>
        <tr>
            <td colspan="9">Total: <?php                                                                                                                                                               
                                       $cc = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(*) as total 
                                                                                        FROM pagamento pgt 
                                                                                       INNER JOIN cadastro_curso cc 
                                                                                          ON cc.cc_id = pgt.cc_id 
                                                                                       WHERE {$complemento } and cc.cp_id = $cpid  " )); 
                                       echo $cc['total']; ?></td>
	</tr>
	<tr>
            <!--<td class="tit2 w50px">Nr</td>-->
            <td class="tit2 w100px">CPF</td>
            <td class="tit2">Nome</td>
            <td class="tit2 w200px"></td>
            <td class="tit2 w100px">E-mail</td>
            <td class="tit2 w50px">Celular</td>
            <td class="tit2 w50px">Pagamentos</td>
            <?php 
              if ( $corrigido ){
                  echo '<td class="tit2 w50px">Data/hora correção</td>
                        <td class="tit2 w50px">Responsável correção</td>';
              }
            ?>
            <!--<td class="tit2 w100px">Quantidade</td>-->
            <td class="tit2 w100px"></td>
	</tr>
      
        <?php
        
        $strSQL = "SELECT pgt.*, cc.cc_id, cad.cad_id, cad.cad_nome, 
                          cad.cad_mail, cad.cad_cel, cad.cad_login, 
                          cad.cad_cpf,user.u_nome
                     FROM cadastro_curso cc 
                    INNER JOIN pagamento pgt
                       ON cc.cc_id = pgt.cc_id
                    INNER JOIN cadastro cad
                       ON cc.cad_id = cad.cad_id     
                     left join user
                       on (user.u_id = pgt.idusuariocorrecao)
                    WHERE cc.cp_id = $cpid AND  {$complemento}
                    ORDER By cad.cad_nome";
        //debug_zval_dump($strSQL);                              
        $cc = mysqli_query($con, $strSQL);
        
        while($cc_lista = mysqli_fetch_assoc($cc)){
//                foreach($cc_lista as $campo => $valor){$$campo = addslashes($valor);}
                $i++;
//                $cc = mysqli_query($con, "SELECT pag.*, cad.cad_id, cad.cad_nome, cad.cad_nome, cad.cad_cpf
//                                    FROM pagamento pag
//                                    INNER JOIN cadastro cad 
//                                    ON cad.cad_cpf = pag.pgt_cpf");
            $sqlqtdDup = "SELECT count(*) as qtd
                FROM pagamento pgt
                WHERE pgt.pgt_cpf = ".$cc_lista['cad_cpf'];
            
            $qtdPagTotal = mysqli_fetch_assoc(mysqli_query($con,$sqlqtdDup));
        ?>
        
        <tr>
            <td class="w100px" colspan=""  style="background-color: #bcdcc0; border-top: 2px solid #0a5517; font-weight: bold;"><?= $cc_lista['cad_cpf'] ?></td>
            <td class="w100px" colspan="2" style="background-color: #bcdcc0; border-top: 2px solid #0a5517; font-weight: bold;"><?= $cc_lista['cad_nome'] ?></td>
            <td class="w100px" colspan=""  style="background-color: #bcdcc0; border-top: 2px solid #0a5517; font-weight: bold;"><?= $cc_lista['cad_mail'] ?></td>
            <td class="w100px" colspan=""  style="background-color: #bcdcc0; border-top: 2px solid #0a5517; font-weight: bold;"><?= $cc_lista['cad_cel'] ?></td>
            <td class="w100px" colspan="<?= ($corrigido)? '': "2"?> " style="background-color: #bcdcc0; border-top: 2px solid #0a5517; font-weight: bold;">Total de pagamentos: <?= $qtdPagTotal['qtd'] ?></td>
            <?php 
              if ( $corrigido ){
                 echo '<td class="w100px" colspan="" style="background-color: #bcdcc0; border-top: 2px solid #0a5517; font-weight: bold;"> ' .  date_format(date_create($cc_lista['datacorrigido']), "d/m/Y") ." ".$cc_lista['horacorrigido'].'  </td> ';
                 echo '<td class="w100px" colspan="2" style="background-color: #bcdcc0; border-top: 2px solid #0a5517; font-weight: bold;"> '.  $cc_lista['u_nome'] .' </td> ';
            }
            ?>

        </tr>
        
        <tr>   
            <td class=" w100px" style="background: #FFF;"><?php //  $duplicadoLista['cad_login'] ?></td>
            <td class="" style="background: #FFF;"><?php // $duplicadoLista['cad_nome'] ?></td>
            <td class=" w200px" style="background: #eaeaea; border-bottom: 2px solid #9fceea; font-weight: bold;">Exame</td>
            <td class=" w100px" style="background: #eaeaea; border-bottom: 2px solid #9fceea; font-weight: bold;">Idioma</td>
            <td class=" w50px"  style="background: #eaeaea; border-bottom: 2px solid #9fceea; font-weight: bold;">Nível</td>
            <td class=" w50px"  style="background: #eaeaea; border-bottom: 2px solid #9fceea; font-weight: bold;">Situação</td>
            <?php 
              if (! $corrigido ) {
            ?>
               <td class=" w100px" style="background: #eaeaea; border-bottom: 2px solid #9fceea; font-weight: bold;">Ação</td>
              <?php } ?>   
	</tr>
        
        <?php 
        
        
        
//        $sqlDup = "SELECT *
//                    FROM cadastro_curso cc
//                    INNER JOIN cadastro cad 
//                    ON cad.cad_id = cc.cad_id
//                    INNER JOIN curso crs ON crs.crs_id = cc.crs_id
//                    INNER JOIN idioma idm ON idm.idm_id = cc.idm_id
//                    WHERE cc.cp_id = $cpid AND cc.cad_id = ".$cc_lista['cad_id']." AND cc.cc_id <> ".$cc_lista['cc_id'];
//        $conDuplicado = mysqli_query($con,$sqlDup);
//        
//         while($duplicadoLista = mysqli_fetch_assoc($conDuplicado)){
//        if ($duplicadoLista['cad_login'] == '0624935243'){ echo $sqlDup; }
        
        $conDuplicado = mysqli_query($con, "SELECT *
                                        FROM cadastro_curso cc
                                        INNER JOIN curso crs
                                        on cc.crs_id = crs.crs_id
                                        INNER JOIN idioma idm
                                        on cc.idm_id = idm.idm_id
                                        INNER JOIN cadastro_curso_status ccs
                                        on cc.ccs_id = ccs.ccs_id
                                        WHERE cc.cad_id = ".$cc_lista['cad_id']." AND cc.cp_id = $cpid");
        
        while($duplicadoLista = mysqli_fetch_assoc($conDuplicado)){
        
        
        ?>
        <tr>
            <!--<td class="w50px"></td>-->
            <td class="w100px" style="background: #FFF;"><?php //  $duplicadoLista['cad_login'] ?></td>
            <td class=""       style="background: #FFF;"><?php // $duplicadoLista['cad_nome'] ?></td>
            <td class="w200px"><?= $duplicadoLista['crs_nome'] ?> </td>
            <td class="w100px"><?= $duplicadoLista['idm_nome'] ?></td>
            <td class="w50px"><?= $duplicadoLista['nivel_id'] ?></td>
            <td class="w50px" id="<?= $duplicadoLista['cc_id'] ?>"><?= $duplicadoLista['ccs_nome'] ?></td>
            <!--<td class="w100px"><?php // $duplicadoLista['duplicado'] ?></td>-->
            <?php 
               if ( ! $corrigido ) { ?>
            <td class="w100px" id="btn-<?= $duplicadoLista['cc_id'] ?>" style="text-align: center;">
                <?php if(  in_array( $duplicadoLista['ccs_id']  , [0,11] ) ){ ?>
                    <input type="button" class="pagar-duplicado" cpid="<?= $cpid ?>" cpf="<?= $cc_lista['cad_cpf'] ?>" cc_id="<?= $duplicadoLista['cc_id'] ?>" tok="<?= $tok ?>" acao="pagar" value="Pagar" />
               <?php } } ?>
	</tr></td>
         <?php } } ?>
    </table>
</div>
</body>
</html>
<?php }#fim adm_on ?>
<?php
if(empty($_SESSION["token"])){
	$_SESSION["token"] = $tok;
}
$_SESSION["token_safe"] = @$_SESSION["token"];
?>