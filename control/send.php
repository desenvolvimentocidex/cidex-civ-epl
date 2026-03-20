<?php
if($acao){
	if(Token::check($token)){
		@ $img_type = $_FILES['anexo']['type'];
		list($img_x,$img_y,$img_t,$img_xy) = getimagesize($_FILES['anexo']['tmp_name']);
		#echo "<script>alert('Tipo de arquivo: ". $img_type ."\\nTamanho do arquivo: ". $img_xy ."');</script>";

		if($img_type == "image/jpeg" && !empty($img_xy)){//se é jpg e se tem tamanho
			@ $name = $_FILES['anexo']['name'];
			@ $tmpn = $_FILES['anexo']['tmp_name'];
			$ext = explode(".",$name);
			$ext = $ext[count($ext)-1];
			if($ext == "jpg" || $ext == "JPG"){
				@ $pasta = "../". $_POST["pasta"];
				@ $foto_id = (int)$_POST["foto_id"];
				$foto = $pasta ."/". $foto_id .".jpg";
				if(move_uploaded_file($tmpn,$foto)){
					list($largura,$altura,$tipo) = getimagesize($foto);
					$img = imagecreatefromjpeg($foto);
					$thumb = imagecreatetruecolor(1280,856);
					imagecopyresampled($thumb,$img,0,0,0,0,1280,856,$largura,$altura);
					imagejpeg($thumb,$foto);
					imagedestroy($img);
					imagedestroy($thumb);
				}
			}else{
				echo "<script>alert('O arquivo deve ser uma imagem JPG');</script>";
			}
		}else{//ou a imagem nao é jpg ou teve a extensao alterada para jpg
			echo "<script>alert('O ARQUIVO NÃO PODE SER ENVIADO\\n\\nCausas:\\n1. A imagem não é JPG;\\n2. O arquivo não é uma imagem.');</script>";
		}
	}
}else{
	$pagina = "http://". $_SERVER['SERVER_NAME'];
	header("location:$pagina");
}
?>