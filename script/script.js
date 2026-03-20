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
	if(id == 1){ obrigatorio = ['login','pass','captcha']; }//login
	if(id == 2){ obrigatorio = ['mail','senha','senha2']; }//cadastro
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
//ini show/hidde em select
function change(obj,block){//campo select,o que quer mostrar
	var selectBox = obj;
	var selected = selectBox.options[selectBox.selectedIndex].value;
	var show = document.getElementById(block);
	if(selected === ''){
		show.style.display = "none";
	}else{
		show.style.display = "block";
	}
}
//fim show/hidde em select
function confirmarForm(){
        var nivel = $("#nivel").val();
        var idm_id = $("#idm_id").val();
        var local = $("#local").val();
        var localNome = $("#local option:selected" ).text();
        var crsNome = $('#realizar-inscricao').attr('crsNome');
        var idmNome = $('#realizar-inscricao').attr('idmNome');
        var formName = $('#realizar-inscricao').attr('formName');
        
        return $.confirm({
            title: 'Confirmação',
            content: 'Deseja realizar sua inscrição no: ' + crsNome + ' - ' + idmNome + ' Nível: ' + nivel + '? <br>Local para realização da prova: '+ localNome ,
            buttons: {
                confirmar: {
                    btnClass: 'btn-green',
                    action: function () {
                        $('#'+formName).submit();
                    }
                },
                cancelar: {
                    btnClass: 'btn-red',
                    action: function () {
                    }
                }
            }
        });
};


$(document).ready(function() {
    $("#validacao").click(function(){
     var cont = 0;
//     $("#form input").each(function(){
        if($("#validador").val() == "")
        {
            $("#validador").css({"border" : "1px solid #F00", "padding": "2px"});
            cont++;
        }
        
        if($("#validador").val().length != 17)
        {
            $("#validador").css({"border" : "1px solid #FF0000", "padding": "2px"});
            $("#msg").html("Código inválido! O código digitado deve possuir 17 dígitos.");
            cont++;
        }
        
        if($("#validador").val().length == 17)
        {
            $("#validador").css({"border" : "1px solid #0000FF", "padding": "2px"});
            $("#msg").html(" ");
            cont = 0;
        }
        

     if(cont == 0)
         {
             $("#validar-certificado").submit();
         }
    });

    $("#preparatorio").change(function() {
        if ($("#preparatorio").val() == 'SIM') {
            
            $("#idiomas").show();
            $(".seleciona-idioma").prop( "disabled", false );
            $("#enviar").prop( "disabled", true );
            

        } else {
            $("#idiomas").hide();
            $(".seleciona-idioma").prop( "disabled", true );

        }
    });
    
    $(".seleciona-idioma").on('change', function() {
        
        classe = $(this).attr("idioma");
        if ($(this).val() == 'SIM') {
            $("#enviar").prop( "disabled", false );
            
            $("#itens-"+classe).show();
            $(".modalidade-"+classe).prop( "disabled", false );
            $(".modalidade-"+classe).prop( "required", true );
            $(".instituicao-"+classe).prop( "disabled", false );
            $(".instituicao-"+classe).prop( "required", true );
            $(".uf-"+classe).prop( "disabled", false );
            $(".uf-"+classe).prop( "required", true );
            $(".cidade-"+classe).prop( "disabled", false );
            $(".cidade-"+classe).prop( "required", true );
            
        } else {
            $("#itens-"+classe).hide();
            $(".modalidade-"+classe).prop( "disabled", true );
            $(".modalidade-"+classe).prop( "required", false );
            $(".instituicao-"+classe).prop( "disabled", true );
            $(".instituicao-"+classe).prop( "required", false );
            $(".uf-"+classe).prop( "disabled", true );
            $(".uf-"+classe).prop( "required", false );
            $(".cidade-"+classe).prop( "disabled", true );
            $(".cidade-"+classe).prop( "required", false );
        }
    });
    
//    $("#enviar").on('click', function () {
//        preparatorio = $("#preparatorio").val();
//        total = $(".seleciona-idioma [value='NÃO']:selected").length;
//        
//        if(preparatorio == 'NÃO') {
//            $("#form-idioma").submit();
//        } else if (preparatorio == 'SIM' && total == 6 ){
//            alert("Selecione ao menos um idioma para preenchimento.");
//            return;
//            event.preventDefault();
//        } else if (preparatorio == 'SIM' && total < 6 ){
//            $("#form-idioma").submit();
//        }
////        $( "span" ).text( "Not valid!" ).show().fadeOut( 1000 );
//    });
    
    


});
//
//var tempo = new Number();
//// Tempo em segundos
//tempo = 20;
//
//function startCountdown(){
//    // Se o tempo não for zerado
//    if((tempo - 1) >= 0){
//            // Pega a parte inteira dos minutos
//            var min = parseInt(tempo/60);
//            // Calcula os segundos restantes
//            var seg = tempo%60;
//            // Formata o número menor que dez, ex: 08, 07, ...
//            if(min < 10){
//                min = "0"+min;
//                min = min.substr(0, 2);
//            }
//            if(seg <=9){ 
//                seg = "0"+seg; 
//            }
//            // Cria a variável para formatar no estilo hora/cronômetro
//            horaImprimivel = min + ':' + seg;
//            //JQuery pra setar o valor
//            $("#sessao").val(horaImprimivel);
//            $(".closejAlert").hide();
//
//            // Define que a função será executada novamente em 1000ms = 1 segundo
//            setTimeout('startCountdown()',1000);
//            // diminui o tempo
//            tempo--;
//    // Quando o contador chegar a zero faz esta ação
//    } else {
//        $("#sessao").val('IMPRIMIR GRU');
//        $('#sessao').click(function(){ 
//            $(".closejAlert").click(); 
//            $("#<?= $bol_id ?>").click(); 
//            location.reload();
//        });
//        $('#sair').click(function(){ 
//            $(".closejAlert").click(); 
//            location.reload();
//        });
//    }
//}
// $('.jnone').click(function () {
//     alert('teste');
// });
//$(document).ready(function () {
//    
//
//
//function mostrarPop(){
////    alert('teste');
////    startCountdown();
//
//    $('.jnone').alertOnClick({
//        'title': 'ATENÇÃO', 
//        'theme':'red', 
//        'content': '<div  style="float: left; width: 450px"><span>O(a) Sr(a) deverá imprimir e pagar a GRU até o dia <span style="color:red">19 JUL 19</span> para confirmar sua inscrição. <br><br>O local de seu Exame será divulgado <span style="color:red">até o dia 07 AGO 19</span> no site do CIdEx <i>(www.cidex.eb.mil.br)</i>, no link <u><i>2º EPLE/EPLO 2019</i></u>, com lista dos candidatos efetivamente inscritos.</span><br><br><span>1) As GRUs geradas devem ser pagas, exclusivamente, no Banco do Brasil. <br><br>2) Os dados contidos na GRU impressa deverão ser usados para preenchimento da GRU "virtual" na hora do pagamento pelo candidato ou pelo caixa do BB.<br><br> <span style="color: red">ATENTAR PARA O CORRETO PREENCHIMENTO, CONFORME A GRU IMPRESSA.</span><br><br>3) O <u>código de recolhimento</u> é <span style="color: blue">SEMPRE o mesmo</span> para todos os exames: <span style="color: red">22714-5</span>. Esse código <u>NÃO é o de Nº Referência</u>.<br><br>Portanto, NÃO utilizar esse código (22714-5) no campo <u>"Número de Referência"</u>. <br><br> <u><span style="color: blue">Cada exame</span> possui um <span style="color: blue">Número de Referência PRÓPRIO</span></u>, que é o <u>"Código de sua Inscrição em cada exame"<u>. </span><p style="text-align:center;  width: 50%; float: left;"><input style="width:165px; height: 40px; color: #FFF; background-color:#000; font-weight: bold; font-size:15pt;" type="button" value="IMPRIMIR GRU" id="sessao" /></p> <p style="text-align:right;  width: 50%; float: left;"><input style="width:65px; height: 40px; color: #000; font-weight: bold; font-size:12pt;" type="button" value="SAIR" id="sair" /></p></div><div style="float: left; margin-left: 20px;"><img src="imagens/modelo-gru.jpg" width="650px" height="370px" /></div>', 
//        'size': {'height':'550px', 'width':'1200px'},
//        'closeBtn': true, 
//        'closeOnClick': true, 
//        'closeOnEsc': true,
//        'type': 'modal'
//    });
//   
//};
            
            

//});
