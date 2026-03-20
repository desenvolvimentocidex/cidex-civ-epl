function fix(id){
	var obrigatorio = "";
	switch(id){
		case 1:
			obrigatorio = ['login','pass','captcha'];//login
			break;
		case 2:
			obrigatorio = ['nome','login','pass1','pass2'];//cad user
			break;
		case 3:
			obrigatorio = ['pass_atual','pass_new1','pass_new2'];//troca senha
			break;
		case 4:
			obrigatorio = ['anexo','titulo','texto'];//add noticia
			break;
		case 5:
			obrigatorio = ['titulo','texto'];//update noticia
			break;
		case 6:
			obrigatorio = ['titulo'];//add/update aviso
			break;
	}
	for(x = 0;x <= obrigatorio.length;x++){
		if(document.getElementById(obrigatorio[x]).value == ""){
			document.getElementById(obrigatorio[x]).focus();
			document.getElementById(obrigatorio[x]).style.border='1px solid red';
			return false;
		}else{
			document.getElementById(obrigatorio[x]).style.border='1px solid green';
		}
	}
}

function limpa_campo(id){
	campo = document.getElementById(id).value;
	var msg = "";
	if(campo.search(/\s/g) != -1){
		msg+= "espaço negado";
		campo = campo.replace(/\s/g, "") ;
	}
	if(campo.search(/[^a-z0-9]/i) != -1){
		msg += "caracter especial negado";
		campo = campo.replace(/[^a-z0-9]/gi, "");
	}
	if(msg){
		//alert(msg);
		document.getElementById(id).value = campo;
		return false;
	}
	return true;
}

function show_busca(id1,id2){
	document.getElementById(id1).style.display ='block';document.getElementById(id2).style.display ='none';
}

//deletar
function confirmacao(id,id2,id3){
	var x = confirm("Deseja realmente "+ id +" "+ id2 +"?");
	if(x){
		document.forms[id3].submit();
	}else{
		return false;
	}
}

//fomatar campo texto (###.###.###-## - ##/##/#### - ## #####-####)
function formatar(mascara, documento){
	var i = documento.value.length;
	var saida = mascara.substring(0,1);
	var texto = mascara.substring(i)
	if (texto.substring(0,1) != saida){
		documento.value += texto.substring(0,1);
	}
}

//mouse over na tr
function trcolor(id,id2){
	document.getElementById(id).style.backgroundColor=id2;
}