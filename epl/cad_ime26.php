<?php
include '../system/system.php';

$cp_id = 3;//2017.1
$alunos = array("0107793077","0107792277","0218358547","0107843971","0107791774","0107793473","1101473658","0107844078","0107845372","0108596875","0107844870","0107844573","0107845471","0219735842","0107845273","0107791873","0107845075","1103740476","0107842072","0107793572","0107791675","0107791972","0107792376","0107792871","0107794273","0106263577");
foreach($alunos as $value){
	$aluno = mysqli_fetch_array(mysqli_query($con, "select * from cadastro where cad_login = $value"));
	echo "#". $value ."<br/>";
	$cad_id = $aluno["cad_id"];
	echo "insert into cadastro_curso (cad_id,cp_id,crs_id,idm_id,nivel_id,cl_id,ccs_id) values($cad_id,$cp_id,2,4,3,342,1);<br/>";//ca
	echo "insert into cadastro_curso (cad_id,cp_id,crs_id,idm_id,nivel_id,cl_id,ccs_id) values($cad_id,$cp_id,4,4,3,344,1);<br/>";//cl
	echo "insert into cadastro_curso (cad_id,cp_id,crs_id,idm_id,nivel_id,cl_id,ccs_id) values($cad_id,$cp_id,5,4,3,345,1);<br/>";//ca
	echo "<br/>";
	$pgt = mysqli_query($con, "select * from cadastro_curso where cad_id = $cad_id");
	while($lista_pgt = mysqli_fetch_array($pgt)){
		$cc_id = $lista_pgt["cc_id"];
		echo "insert into pagamento (cc_id) values($cc_id);<br/>";
	}
	echo "<br/>";
}
?>