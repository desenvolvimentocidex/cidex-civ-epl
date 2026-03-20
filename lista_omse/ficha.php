<?php
include '../system/system.php';
//ini verifica ultimo periodo para epl
$cps = getSQLMySQL( "select * from curso_periodo order by cp_id desc limit 3");
$cp_id = @$_GET['cp_id'];
$title = "Exame de Proficiência Linguística ";
if ( ! empty($cp_id) ){
    foreach ($cps as $value) {
        if ($value['cp_id'] == $cp_id){
            $title .= $value['cp_nome'];
        }        
    } 
}                    

//$cp_nome = $cp["cp_nome"];
//$cp_id = $cp["cp_id"];//ultimo periodo cadastrado
//dados da ficha
//$cp_id = 6;//2017.1

if($acao == "update_cl"){
	$clold = htmlspecialchars($_GET["cl_old"]);
	$clid = htmlspecialchars($_GET["cl_id"]);
	mysqli_query($con, "update cadastro_curso set cl_id = $clid where cc_id = $cid");
	mysqli_query($con, "insert into log_omse (cc_id,cl_id_old,cl_id_new) values($cid,$clold,$clid)");
	echo "<script>alert('OMSE alterada com sucesso.');location='?cid=$cid'</script>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="br" lang="pt">
	<head>
		<style>
       	body{
			margin:0px;
			font:10px verdana;
			text-transform:uppercase;
                        -webkit-print-color-adjust:exact;
		}
		select{
			font:10px verdana;
			width:400px;
			height:20px;
			text-transform:uppercase;
		}
		input{
			font:10px verdana;
			width:100px;
		}
		#bg{

			width:840px;
		}
		table,td,th{
			font:10px verdana;
			text-transform:uppercase;
			border:1px solid #000;
		}
		td{
			height:26px;
		}
		</style>
	</head>
<body>
<?php if(!$oid){ ?>
Gerar Lista de Presença<br/>
Período: <select name="periodo"  onchange="location='?cp_id='+ this.value">
                <option value='-1' >Selecione</option>
                <?php 
                    
                    foreach ($cps as $value) {
                        $selecionado = "";
                        if ($value['cp_id'] == $cp_id){
                            $selecionado = "selected";                            
                        }
                        echo "<option value='".$value['cp_id']."' {$selecionado} > EPL ".$value['cp_nome']."</option>";
                    } 
                 ?>
          </select> 

<br/>
exame:<br/>
<select onchange="location='?cp_id=<?= $cp_id ?>&cid='+ this.value">
	<option></option>
<?php
$crs = mysqli_query($con, "select * from curso where crs_id in (2,4,5)");
while($crs_lista = mysqli_fetch_array($crs)){
	foreach($crs_lista as $campo => $valor){$$campo = stripslashes($valor);}
?>
	<option value="<?= $crs_id ?>" <?php if($crs_id == $cid){ echo "selected"; } ?>><?= $crs_nome ?></option>
<?php } ?>
</select>
<br/>
<?php if($cid){ ?>
idioma:<br/>
<select onchange="location='?cp_id=<?= $cp_id ?>&cid=<?= $cid ?>&iid='+ this.value">
	<option></option>
<?php
$idm = mysqli_query($con, "select * from idioma order by idm_nome");
while($idm_lista = mysqli_fetch_array($idm)){
	foreach($idm_lista as $campo => $valor){$$campo = stripslashes($valor);}
?>
	<option value="<?= $idm_id ?>" <?php if($idm_id == $iid){ echo "selected"; } ?>><?= $idm_nome ?></option>
<?php } ?>
</select>
<?php } ?>
<br/>
<?php if($iid){ ?>
nivel:<br/>
<select onchange="location='?cp_id=<?= $cp_id ?>&cid=<?= $cid ?>&iid=<?= $iid ?>&nid='+ this.value">
	<option></option>
<?php for($x = 1;$x <= 3;$x++){ ?>
	<option <?php if($x == $nid){ echo "selected"; } ?>><?= $x ?></option>
<?php } ?>
        <option <?php if($nid == 99){ echo "selected"; } ?> value="99"> MULTINÍVEL </option>
</select>
<?php } ?>
<br/>
<?php if($nid){ ?>
omse:<br/>
<select onchange="location='?cp_id=<?= $cp_id ?>&cid=<?= $cid ?>&iid=<?= $iid ?>&nid=<?= $nid?>&oid='+ this.value">
	<option></option>
<?php
$cl = mysqli_query($con, "select * from curso_local cl, om,rm where cl.om_id = om.om_id and om.rm_id = rm.rm_id and cl.crs_id = $cid and cl.ativo = 1 order by rm.rm_id,om.om_nome");

while($cl_lista = mysqli_fetch_array($cl)){
	foreach($cl_lista as $campo => $valor){$$campo = stripslashes($valor);}
?>
	<option value="<?= $cl_id ?>" <?php if($cl_id == $oid){ echo "selected"; } ?>><?= $rm_sigla ." - ". $om_nome ." (". $om_sigla .") - ". $om_municipio ." - ". $om_uf ?></option>
<?php } ?>
</select>
<?php } ?>
<br/>

<?php } ?>

<?php if($oid){ ?>
<div id="bg">
<table cellpadding="2" cellspacing="0">
	<tr>
		<td colspan="6" style="text-align:right">
		<input type="button" value="Imprimir" onclick="print()"/>
		<input type="button" value="Limpar" onclick="location='ficha.php'"/>
		</td>
	</tr>
	<tr>
		<th colspan="6">CENTRO DE IDIOMAS DO EXÉRCITO<BR/><?= $title ?></th>
	</tr>
	<tr>
		<th colspan="6">Lista de Presença</th>
	</tr>
	<tr>
		<td colspan="2" style="border-right:0px">
			OMSE:<br/>
			Exame:<br/>
			Idioma:<br/>
			Nível:<br/>
		</td>

<?php
//dados do cabecalho
$cl = mysqli_fetch_array(mysqli_query($con, "select * from curso_local cl, om,rm where cl.om_id = om.om_id and om.rm_id = rm.rm_id and cl_id = $oid order by rm.rm_id,om.om_nome"));
foreach($cl as $campo => $valor){$$campo = stripslashes($valor);}
$idm = mysqli_fetch_array(mysqli_query($con, "select * from idioma where idm_id = $iid"));
foreach($idm as $campo => $valor){$$campo = stripslashes($valor);}
$crs = mysqli_fetch_array(mysqli_query($con, "select * from curso where crs_id = $cid"));
foreach($crs as $campo => $valor){$$campo = stripslashes($valor);}

?>
		<td colspan="4" style="border-left:0px">
			<?= $rm_sigla ." - ". $om_nome ." (". $om_sigla .") - ". $om_municipio ." - ". $om_uf ?><br/>
			<?= $crs_cod ." - ". $crs_nome ?><br/>
			<?= $idm_nome ?><br/>
			<?= $nid == 99  ? 'Multinível' :  $nid ?>
		</td>
	</tr>
	<tr>
		<th width="40"></th>
                <th width="50">Posto/</br>Graduação</th>
                <th width="320">Nome do Militar</th>
		<th width="90">Nº de Inscrição</th>
                <th width="100">Identidade</th>
		<th width="240">Assinatura</th>
	</tr>
<?php
	$strNivel = $nid == 99 ? " and nivel_id in (1,2) and ( num_inscricao like '8%' or num_inscricao like '9%')   " : 'and nivel_id ='. $nid;        
        $cc = mysqli_query($con, "select * from cadastro_curso cc,cadastro cad where cad.cad_id = cc.cad_id and crs_id = $cid and idm_id = $iid $strNivel  and cl_id = $oid and cp_id = $cp_id and ccs_id = 1 order by cad_nome");
	$cor = "lightgrey"; //Define valor inicial de cor        
        $x = 0;
        while($cc_lista = mysqli_fetch_array($cc)){
		foreach($cc_lista as $campo => $valor){$$campo = stripslashes($valor);}
		$bol_id = str_pad($cc_id, 6, "0", STR_PAD_LEFT);
		$referencia = $idm_id.$nivel_id.$crs_id.$bol_id;
		$x++;
                
                if ($cor=="lightgrey"){ //alterna a cor
                   $cor = "white";
                } else { 
                    $cor="lightgrey";
                }
?>
    
    <tr style="background:<?php echo $cor ?>">
		<td><?= $x ?></td>
		<td style="text-align: center"><?= $cad_postograd ?></td>
                <td><?= $cad_nome ?></td>
                <td style="text-align: center"><?= $num_inscricao.$digito_verificador?></td>
                <td></td>
		<td></td>
	</tr>
   <?php  

} ?>
</table>
</div>
<?php } ?>
</body>
</html>