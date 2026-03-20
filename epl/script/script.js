//    
// ASP CARVALHOZA
//Correção na Troca de OMSE no EPL
//Necessário melhorar o script para identificar os botões pela classe 
//e não pelo ID para que o código fique mais limpo

$(document).ready(function() {

    //Altera o Status do Curso relacionado a OM e mostra uma alert de confirmação
    $("#ativar1").click(function(){ 
        var om_id = $("#om_id").val();
        var curso_id = $("#ativar1").attr('curso_id');
        var ativar = 1;
        var url = "curso_om.php";

        $.ajax({
            type: "POST",
            url: url,
            data: {om_id: om_id, curso_id: curso_id, ativar: ativar}, 
            success: function(data)
            {
                alert(data); // show response from the php script.
                $("#ativar1").hide();
                $("#desativar1").show();
            }
        });
    });
    $("#desativar1").click(function(){ 
        var om_id = $("#om_id").val();
        var curso_id = $("#desativar1").attr('curso_id');
        var ativar = 0;
        var url = "curso_om.php";

        $.ajax({
            type: "POST",
            url: url,
            data: {om_id: om_id, curso_id: curso_id, ativar: ativar}, 
            success: function(data)
            {
                alert(data);
                if(data){
                    $("#ativar1").show();
                    $("#desativar1").hide();
                }
            }
        });
    });
    
    $("#ativar2").click(function(){ 
        var om_id = $("#om_id").val();
        var curso_id = $("#ativar2").attr('curso_id');
        var ativar = 1;
        var url = "curso_om.php";

        $.ajax({
            type: "POST",
            url: url,
            data: {om_id: om_id, curso_id: curso_id, ativar: ativar}, 
            success: function(data)
            {
                alert(data); // show response from the php script.
                $("#ativar2").hide();
                $("#desativar2").show();
            }
        });
    });
    $("#desativar2").click(function(){ 
        var om_id = $("#om_id").val();
        var curso_id = $("#desativar2").attr('curso_id');
        var ativar = 0;
        var url = "curso_om.php";

        $.ajax({
            type: "POST",
            url: url,
            data: {om_id: om_id, curso_id: curso_id, ativar: ativar}, 
            success: function(data)
            {
                alert(data);
                if(data){
                    $("#ativar2").show();
                    $("#desativar2").hide();
                }
            }
        });
    });
    
    $("#ativar3").click(function(){ 
        var om_id = $("#om_id").val();
        var curso_id = $("#ativar3").attr('curso_id');
        var ativar = 1;
        var url = "curso_om.php";

        $.ajax({
            type: "POST",
            url: url,
            data: {om_id: om_id, curso_id: curso_id, ativar: ativar}, 
            success: function(data)
            {
                alert(data); // show response from the php script.
                $("#ativar3").hide();
                $("#desativar3").show();
            }
        });
    });
    $("#desativar3").click(function(){ 
        var om_id = $("#om_id").val();
        var curso_id = $("#desativar3").attr('curso_id');
        var ativar = 0;
        var url = "curso_om.php";

        $.ajax({
            type: "POST",
            url: url,
            data: {om_id: om_id, curso_id: curso_id, ativar: ativar}, 
            success: function(data)
            {
                alert(data);
                if(data){
                    $("#ativar3").show();
                    $("#desativar3").hide();
                }
            }
        });
    });
    
    $("#ativar4").click(function(){ 
        var om_id = $("#om_id").val();
        var curso_id = $("#ativar4").attr('curso_id');
        var ativar = 1;
        var url = "curso_om.php";

        $.ajax({
            type: "POST",
            url: url,
            data: {om_id: om_id, curso_id: curso_id, ativar: ativar}, 
            success: function(data)
            {
                alert(data); // show response from the php script.
                $("#ativar4").hide();
                $("#desativar4").show();
            }
        });
    });
    $("#desativar4").click(function(){ 
        var om_id = $("#om_id").val();
        var curso_id = $("#desativar4").attr('curso_id');
        var ativar = 0;
        var url = "curso_om.php";

        $.ajax({
            type: "POST",
            url: url,
            data: {om_id: om_id, curso_id: curso_id, ativar: ativar}, 
            success: function(data)
            {
                alert(data);
                if(data){
                    $("#ativar4").show();
                    $("#desativar4").hide();
                }
            }
        });
    });
    
    $("#ativar5").click(function(){ 
        var om_id = $("#om_id").val();
        var curso_id = $("#ativar5").attr('curso_id');
        var ativar = 1;
        var url = "curso_om.php";

        $.ajax({
            type: "POST",
            url: url,
            data: {om_id: om_id, curso_id: curso_id, ativar: ativar}, 
            success: function(data)
            {
                alert(data); // show response from the php script.
                $("#ativar5").hide();
                $("#desativar5").show();
            }
        });
    });
    $("#desativar5").click(function(){ 
        var om_id = $("#om_id").val();
        var curso_id = $("#desativar5").attr('curso_id');
        var ativar = 0;
        var url = "curso_om.php";

        $.ajax({
            type: "POST",
            url: url,
            data: {om_id: om_id, curso_id: curso_id, ativar: ativar}, 
            success: function(data)
            {
                alert(data);
                if(data){
                    $("#ativar5").show();
                    $("#desativar5").hide();
                }
            }
        });
    });
    $("#ativar6").click(function(){ 
        var om_id = $("#om_id").val();
        var curso_id = $("#ativar6").attr('curso_id');
        var ativar = 1;
        var url = "curso_om.php";

        $.ajax({
            type: "POST",
            url: url,
            data: {om_id: om_id, curso_id: curso_id, ativar: ativar}, 
            success: function(data)
            {
                alert(data); // show response from the php script.
                $("#ativar6").hide();
                $("#desativar6").show();
            }
        });
    });
    $("#desativar6").click(function(){ 
        var om_id = $("#om_id").val();
        var curso_id = $("#desativar6").attr('curso_id');
        var ativar = 0;
        var url = "curso_om.php";

        $.ajax({
            type: "POST",
            url: url,
            data: {om_id: om_id, curso_id: curso_id, ativar: ativar}, 
            success: function(data)
            {
                alert(data);
                if(data){
                    $("#ativar6").show();
                    $("#desativar6").hide();
                }
            }
        });
    });

    $(".botao-excluir-pagamento").on( "click", function(){

        var cc_id = $(this).attr('cc_id');
        var token = $(this).attr('tok');
        var acao = $(this).attr('acao');
        var referencia = $(this).attr('referencia');
        var valor = $(this).val();
        var url = "alterar_pagamento.php";
        
        var conf = confirm('Confirma a exclusão do pagamento?');
            
        if (conf) {
            $.ajax({
                type: "POST",
                url: url,
                data: {cc_id: cc_id, token: token, acao: acao, referencia: referencia, valor:valor}, 
                success: function(data)
                {
                    if(data){
                        $("#linha"+referencia).fadeOut();
                        $("#linha"+referencia).fadeIn();
                        $("[ref-realizar='"+referencia+"']").show('slow');
                        $("[ref-excluir='"+referencia+"']").hide('slow');
                        $("[ref-situacao='"+referencia+"']").html('Aguardando pagamento');
                    }
                }
            });
        };
    });
    
    $(".botao-realizar-pagamento").on( "click", function(){
        
        var cc_id = $(this).attr('cc_id');
        var token = $(this).attr('tok');
        var acao = $(this).attr('acao');
        var nr = $(this).attr('nr');
        var valor = $(this).val();
        var referencia = $(this).attr('referencia');
        var url = "alterar_pagamento.php";

        var conf = confirm('Confirma a realização do pagamento?');
            
        if (conf) {
            $.ajax({
                type: "POST",
                url: url,
                data: {cc_id: cc_id, token: token, acao: acao, referencia: referencia, nr:nr, valor:valor}, 
                success: function(data)
                {
                    if(data){
                        $("#linha"+referencia).fadeOut();
                        $("#linha"+referencia).fadeIn();
                        $("[ref-realizar='"+referencia+"']").hide('slow');
                        $("[ref-excluir='"+referencia+"']").show('slow');
                        $("[ref-situacao='"+referencia+"']").html('Pago');
                    }
                }
            });
        }
    });
    
    $(".botao-cancelar-inscricao").on( "click", function(){
        
        var cc_id = $(this).attr('cc_id');
        var token = $(this).attr('tok');
        var acao = $(this).attr('acao');
        var valor = $(this).val();
        var referencia = $(this).attr('referencia');
        var url = "cancelar_inscricao.php";

        var conf = confirm('Confirma a cancelamento da inscrição?');
            
        if (conf) {
            $.ajax({
                type: "POST",
                url: url,
                data: {cc_id: cc_id, token: token, valor:valor, acao: acao, referencia: referencia}, 
                success: function(data)
                {
                    if(data){
                        $("#linha"+referencia).fadeOut();
                    }
                }
            });
        }
    });
    
    
    //Tela de pagamentos duplicados
    
    $(".pagar-duplicado").on( "click", function(){
        
        var cc_id = $(this).attr('cc_id');
        var token = $(this).attr('tok');
        var cpf = $(this).attr('cpf');
        var acao = $(this).attr('acao');
        var url = "pagamentoDuplicado.php";
        
        $(this).hide('slow');
        $('#btn-'+cc_id).html('<img src="imagens/loading.gif" width="20" height="20" />');
//        alert('teste');
//        var conf = confirm('Confirma a realização do pagamento?');
            
//        if (conf) {
            $.ajax({
                context: this,
                type: "POST",
                url: url,
                data: {cc_id: cc_id, acao: acao, cpf:cpf, token: token}, 
                success: function(data)
                {
                    if(data == 'OK'){
                        $('#'+cc_id).html('Pago');
                        $('#btn-'+cc_id).html("");
                    } else {
                        $('#'+cc_id).html('Erro - Não existe mais nenhum pagamento duplicado');
                        $('#'+cc_id).css("background-color", "#ec9b9b");;
                        $('#btn-'+cc_id).html("");
                    }
                }
            });
//        }
    });


     $("#gerarNovaSenhaMilitar").click(function(){
        const identidade = $("input[name='searchrg']").val() ;
        $.ajax({
            url: "/epl/updatesenha.php",
            type: "POST",
            data: {idt: identidade},
            success: function(resposta){
                console.log(resposta);
                $("#senha_gerada").text(resposta);
            }
        });
    });
    
    
});



//deletar
function confirmacao(id,id2,id3){
	var x = confirm("Deseja realmente "+ id +" "+ id2 +"?");
	if(x){
		document.forms[id3].submit();
	}else{
		return false;
	}
}

    // construindo o calendário
    function popdate(obj,div,tam,ddd)
    {
        if (ddd) 
        {
            day = ""
            mmonth = ""
            ano = ""
            c = 1
            char = ""
            for (s=0;s<parseInt(ddd.length);s++)
            {
                char = ddd.substr(s,1)
                if (char == "/") 
                {
                    c++; 
                    s++; 
                    char = ddd.substr(s,1);
                }
                if (c==1) day    += char
                if (c==2) mmonth += char
                if (c==3) ano    += char
            }
            ddd = day + "/" + mmonth + "/" + ano
        }
      
        if(!ddd) {today = new Date()} else {today = new Date(ddd)}
        date_Form = eval (obj)
        if (date_Form.value == "") { date_Form = new Date()} else {date_Form = new Date(date_Form.value)}
      
        ano = today.getFullYear();
        mmonth = today.getMonth ();
        day = today.toString ().substr (8,2)
      
        umonth = new Array ("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro")
        days_Feb = (!(ano % 4) ? 29 : 28)
        days = new Array (31, days_Feb, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31)
     
        if ((mmonth < 0) || (mmonth > 11))  alert(mmonth)
        if ((mmonth - 1) == -1) {month_prior = 11; year_prior = ano - 1} else {month_prior = mmonth - 1; year_prior = ano}
        if ((mmonth + 1) == 12) {month_next  = 0;  year_next  = ano + 1} else {month_next  = mmonth + 1; year_next  = ano}
        txt  = "<table bgcolor='#efefff' style='border:solid #330099; border-width:2' cellspacing='0' cellpadding='3' border='0' width='"+tam+"' height='"+tam*1.1 +"'>"
        txt += "<tr bgcolor='#FFFFFF'><td colspan='7' align='center'><table border='0' cellpadding='0' width='100%' bgcolor='#FFFFFF'><tr>"
        txt += "<td width=20% align=center><a href=javascript:popdate('"+obj+"','"+div+"','"+tam+"','"+((mmonth+1).toString() +"/01/"+(ano-1).toString())+"') class='Cabecalho_Calendario' title='Ano Anterior'><<</a></td>"
        txt += "<td width=20% align=center><a href=javascript:popdate('"+obj+"','"+div+"','"+tam+"','"+( "01/" + (month_prior+1).toString() + "/" + year_prior.toString())+"') class='Cabecalho_Calendario' title='Mês Anterior'><</a></td>"
        txt += "<td width=20% align=center><a href=javascript:popdate('"+obj+"','"+div+"','"+tam+"','"+( "01/" + (month_next+1).toString()  + "/" + year_next.toString())+"') class='Cabecalho_Calendario' title='Próximo Mês'>></a></td>"
        txt += "<td width=20% align=center><a href=javascript:popdate('"+obj+"','"+div+"','"+tam+"','"+((mmonth+1).toString() +"/01/"+(ano+1).toString())+"') class='Cabecalho_Calendario' title='Próximo Ano'>>></a></td>"
        txt += "<td width=20% align=right><a href=javascript:force_close('"+div+"') class='Cabecalho_Calendario' title='Fechar Calendário'><b>X</b></a></td></tr></table></td></tr>"
        txt += "<tr><td colspan='7' align='right' bgcolor='#ccccff' class='mes'><a href=javascript:pop_year('"+obj+"','"+div+"','"+tam+"','" + (mmonth+1) + "') class='mes'>" + ano.toString() + "</a>"
        txt += " <a href=javascript:pop_month('"+obj+"','"+div+"','"+tam+"','" + ano + "') class='mes'>" + umonth[mmonth] + "</a> <div id='popd' style='position:absolute'></div></td></tr>"
        txt += "<tr bgcolor='#330099'><td width='14%' class='dia' align=center><b>Dom</b></td><td width='14%' class='dia' align=center><b>Seg</b></td><td width='14%' class='dia' align=center><b>Ter</b></td><td width='14%' class='dia' align=center><b>Qua</b></td><td width='14%' class='dia' align=center><b>Qui</b></td><td width='14%' class='dia' align=center><b>Sex<b></td><td width='14%' class='dia' align=center><b>Sab</b></td></tr>"
        today1 = new Date((mmonth+1).toString() +"/01/"+ano.toString());
        diainicio = today1.getDay () + 1;
        week = d = 1
        start = false;
     
        for (n=1;n<= 42;n++) 
        {
            if (week == 1)  txt += "<tr bgcolor='#efefff' align=center>"
            if (week==diainicio) {start = true}
            if (d > days[mmonth]) {start=false}
            if (start) 
            {
                dat = new Date((mmonth+1).toString() + "/" + d + "/" + ano.toString())
                day_dat   = dat.toString().substr(0,10)
                day_today  = date_Form.toString().substr(0,10)
                year_dat  = dat.getFullYear ()
                year_today = date_Form.getFullYear ()
                colorcell = ((day_dat == day_today) && (year_dat == year_today) ? " bgcolor='#FFCC00' " : "" )
                txt += "<td"+colorcell+" align=center><a href=javascript:block('"+  d + "/" + (mmonth+1).toString() + "/" + ano.toString() +"','"+ obj +"','" + div +"') class='data'>"+ d.toString() + "</a></td>"
                d ++ 
            } 
            else 
            { 
                txt += "<td class='data' align=center> </td>"
            }
            week ++
            if (week == 8) 
            { 
                week = 1; txt += "</tr>"} 
            }
            txt += "</table>"
            div2 = eval (div)
            div2.innerHTML = txt 
    }
      
    // função para exibir a janela com os meses
    function pop_month(obj, div, tam, ano)
    {
      txt  = "<table bgcolor='#CCCCFF' border='0' width=80>"
      for (n = 0; n < 12; n++) { txt += "<tr><td align=center><a href=javascript:popdate('"+obj+"','"+div+"','"+tam+"','"+("01/" + (n+1).toString() + "/" + ano.toString())+"')>" + umonth[n] +"</a></td></tr>" }
      txt += "</table>"
      popd.innerHTML = txt
    }
     
    // função para exibir a janela com os anos
    function pop_year(obj, div, tam, umonth)
    {
      txt  = "<table bgcolor='#CCCCFF' border='0' width=160>"
      l = 1
      for (n=1991; n<2012; n++)
      {  if (l == 1) txt += "<tr>"
         txt += "<td align=center><a href=javascript:popdate('"+obj+"','"+div+"','"+tam+"','"+(umonth.toString () +"/01/" + n) +"')>" + n + "</a></td>"
         l++
         if (l == 4) 
            {txt += "</tr>"; l = 1 } 
      }
      txt += "</tr></table>"
      popd.innerHTML = txt 
    }
     
    // função para fechar o calendário
    function force_close(div) 
        { div2 = eval (div); div2.innerHTML = ''}
        
    // função para fechar o calendário e setar a data no campo de data associado
    function block(data, obj, div)
    { 
        force_close (div)
        obj2 = eval(obj)
        obj2.value = data 
    }

   