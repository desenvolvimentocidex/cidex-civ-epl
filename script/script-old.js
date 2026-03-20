defaultStep=1 
step=defaultStep 

function scrollDivDown(id){
	clearTimeout(timerDown) 
	document.getElementById(id).scrollLeft+=step 
	timerDown=setTimeout("scrollDivDown('"+id+"')",10)
} 

function scrollDivUp(id){
	clearTimeout(timerUp)
	document.getElementById(id).scrollLeft-=step 
	timerUp=setTimeout("scrollDivUp('"+id+"')",10)
} 

timerDown="" 
timerUp="" 

function stopMe(){
	clearTimeout(timerDown) 
	clearTimeout(timerUp)
}

function TrocarFoto(id){
	document.getElementById('fotos_view').src = id;
}
 
function VoltarFoto1(){
	document.getElementById('fotos_view').src = "1.jpg"
}

function fix(id){
	var obrigatorio = "";
	switch(id){
		case 1:
			obrigatorio = ['login','pass','captcha'];//login
			break;
		case 2:
			obrigatorio = ['mail','senha','senha2'];//cadastro
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

function isNumberKey(evt){
	var charCode = (evt.which) ? evt.which : event.keyCode;
	if (charCode != 45 && charCode > 31 && (charCode < 48 || charCode > 57)){
		return false;
	}else{
		return true;
	}
}

//ini script inscrioes
function force_check(){
	senha = document.getElementById("senha").value;
	forca = 0;
	if(senha.length == 8){
		forca = forca + 20;
	}
	if(senha.match(/[a-z]+/)){
		forca = forca + 10;
	}
	if(senha.match(/[A-Z]+/)){
		forca = forca + 10;
	}
	if(senha.match(/[0-9]+/)){
		forca = forca + 10;
	}
	if(senha.match(/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/)){
		forca = forca + 10;
	}
	return mostra_barra();
}
function mostra_barra(){
	senha = document.getElementById("senha");
	senha2 = document.getElementById("senha2");
	barra = document.getElementById("force_text");
	bar = document.getElementById("bar");
	if(forca == 0){
		bar.style.backgroundColor = "#fff";	
	}else if(forca <= 30){
		bar.style.width = "20px";
		bar.style.backgroundColor = "red";
	}else if(forca >= 31 && forca <= 40){
		bar.style.width = "50px";
		bar.style.backgroundColor = "yellow";
	}else if(forca >= 41 && forca <= 51){
		bar.style.width = "70px";
		bar.style.backgroundColor = "blue";
	}else{
		bar.style.width = "100px";
		bar.style.backgroundColor = "green";
	}
	barra.value = forca;
}
function check_pass(){
	senha = document.getElementById("senha");
	senha2 = document.getElementById("senha2");
	if(senha.value.length == 8){
		if(barra.value <= 30){
			senha.focus();
			senha.style.border='1px solid red';
			alert('A senha digitada é muito fraca.');
			return false;
		}
		if(senha.value != senha2.value){
			senha2.focus();
			senha2.style.border = "1px solid red";
			alert('A senha digitada não confere com a confirmada.');
			return false;	
		}
	}else{
		alert('A senha deve conter 8 caracteres.');
		return false;
	}
}
function keyban(evt){
	var charCode = (evt.which) ? evt.which : event.keyCode;
	if (charCode == 39){
		return false;
	}else{
		return true;
	}
}
function checkmail(field) {
	usuario = field.value.substring(0, field.value.indexOf("@"));
	dominio = field.value.substring(field.value.indexOf("@")+ 1, field.value.length);
	if ((usuario.length >=1) &&
    (dominio.length >=3) && 
    (usuario.search("@")==-1) && 
    (dominio.search("@")==-1) &&
    (usuario.search(" ")==-1) && 
    (dominio.search(" ")==-1) &&
    (dominio.search(".")!=-1) &&      
    (dominio.indexOf(".") >=1)&& 
    (dominio.lastIndexOf(".") < dominio.length - 1)){
		document.getElementById("msgmail").innerHTML = "<img src='imagens/icon_checked.png' width='16' align='absmiddle'/>";
		document.getElementById("mail").style.border = "1px solid #CCC";
	}else{
		document.getElementById("msgpass").innerHTML = "";
		document.getElementById("mail").value = "";
		document.getElementById("mail").style.border = "1px solid red";
	}
}
function checkpass(field){
	senha = document.getElementById("senha");
	if(barra.value <= 30){
		document.getElementById("senha").style.border = "1px solid red";
	}else{
		document.getElementById("senha").style.border = "1px solid #CCC";
	}
}
function checkconfirm(field){
	senha = document.getElementById("senha");
	senha2 = document.getElementById("senha2");
	if(senha.value == senha2.value){
		document.getElementById("msgpass").innerHTML="<img src='imagens/icon_checked.png' width='16' align='absmiddle'/>";
		document.getElementById("senha2").style.border = "1px solid #CCC";
	}else{
		document.getElementById("msgpass").innerHTML = "";
		document.getElementById("senha2").style.border = "1px solid red";
	}
}
//fim script inscricoes