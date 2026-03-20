<div id="user">
	<ul>
		<li class="user_tit">Área do aluno</li>
<?php if(@$_SESSION["loged"] == "on"){   ?>
		<li><a href="?a=2"><img src="imagens/icon_user.png"/><span><?= $_SESSION["login"] ?></span></a></li>
<?php 
    $cad_id = $_SESSION['cad_id'];
    $query = mysqli_query($con, "SELECT *, (SELECT MAX(cc_parcela) from cadastro_curso WHERE cad_id =$cad_id) as ultima_parcela
        FROM cadastro c
        INNER JOIN cadastro_curso cc ON c.cad_id = cc.cad_id
        WHERE c.cad_id = $cad_id
        AND cc.crs_id = 1 
        AND cc.cc_parcela = (SELECT MAX(cc_parcela) from cadastro_curso WHERE cad_id =$cad_id)
        AND DATEDIFF(DATE(cc.cc_vencimento), DATE(NOW())) < 30
        GROUP BY c.cad_id");

    if ($query->num_rows == 1){
?>
		<!--<li><a href="?a=6"><img src="imagens/icon_a_ok.png"/><span>Renovar Matrícula</span></a></li>-->
<?php } ?>
		<li><a href="?a=3"><img src="imagens/icon_novocurso.png"/><span>Novas inscrições</span></a></li>
<?php        
    
    $queryCancelar = mysqli_query($con, "SELECT * 
        FROM curso crs
        INNER JOIN cadastro_curso cc ON crs.crs_id = cc.crs_id
        WHERE cc.cad_id = $cad_id
        AND crs.crs_id = 1 
        AND cc.cc_parcela = 1 
        AND cc.ccs_id in (0,1,9,11) 
        AND DATE(NOW()) between crs.crs_dtinicio AND crs.crs_dttermino
        GROUP BY cc.cad_id");
    
    if ($queryCancelar->num_rows == 1){
?> 
                <!--<li><a href="?a=7"><img src="imagens/icon_a_close.png"/><span style="color: red; font-weight: bold">Cancelar Inscrição</span></a></li>-->
<?php } ?>
		<li><a href="?a=1"><img src="imagens/icon_meuscursos.png"/><span>Gerar GRU</span></a></li>
		<li><a href="?a=4"><img src="imagens/icon_pagamentos.png"/><span>GRUs pagas</span></a></li>
		<li><a href="?a=5"><img src="imagens/icon_pass.png"/><span>Alterar senha</span></a></li>
		<li><a href="?id=100"><img src="imagens/icon_sair.png"/><span>Sair</span></a></li>
                <li>
                    <script language="JavaScript">
                    function showtime()
                    {	
                        setTimeout("showtime();",1000);
                        callerdate.setTime(callerdate.getTime()+1000);
                        var hh = String(callerdate.getHours());
                        var mm = String(callerdate.getMinutes());
                        var ss = String(callerdate.getSeconds());
                        document.clock.face.value = 
                        ((hh < 10) ? " " : "") + hh +
                        ((mm < 10) ? ":0" : ":") + mm +
                        ((ss < 10) ? ":0" : ":") + ss;
                    }
                    callerdate=new Date(<?php echo date("Y,m,d,H,i,s");?>);
                    </script>
                    <style type="text/css">
                        .clock{ 
                            width: 100px;
                            border: 0px; 
                            text-align:center; 
                            font-family: 'Source Sans Pro', sans-serif; 
                            color: #868686; 
                            margin:0 2px 0 2px; 
                            padding:0 5px 0 5px; 
                        }
                        .clock #titulo{ font-weight:bold; font-size:14px; }
                        .clock #face{ color: #00420C; font-weight:bold; font-size:30px; }
                        .clock #data{ color:#999; font-weight:normal; font-size:11px;}
                    </style>
                    <div class="clock">
                        <span id="titulo">Hora Servidor</span><br />
                        <form name="clock" style="margin-bottom: 0px">
                            <input type="text" name="face" value="" size=5 style="border: 0px;color: #19882C; font-weight:bold; font-size:20px; text-align: center">
                        </form>
                    </div>
                </li>
<?php }else{ ?>
		<form method="post" onsubmit="return fix(1)">
		<input type="hidden" name="token" value="<?= $tok ?>"/>
		<input type="hidden" name="acao" value="cad_login"/>
		<li class="input"><img src="imagens/icon_user.png"/><span class="input"><input id="login" type="text" name="cad_login" placeholder="Identidade" onkeypress="return isNumberKey(event)" maxlength="10" autocomplete="off"/></span></li>
		<li class="input"><img src="imagens/icon_pass.png"/><span class="input"><input id="pass" type="password" name="pass" placeholder="Senha" autocomplete="off" maxlength="8"/></span></li>
		<li class="input"><div id="captcha_box"><div id="captcha_content" class="g-recaptcha" data-sitekey="6Lcurj4UAAAAANZ3xtKcKt7s6nCQ0N9EQqe9pFqP"></div></div></li>
		<li><input class="botao" type="submit" value="Entrar"/></li>
		</form>
		<li><a href="?id=5&amp;cid=9999"><img src="imagens/icon_cadastro.png"/><span>Cadastre-se</span></a></li>
		<li><a href="#" onclick="getElementById('esqueci').style.display='block'"><img src="imagens/icon_whatpass.png"/><span>Esqueci a senha</span></a></li>
		<li style="display:none" id="esqueci">
                    <form method="post" action="pass.php"><input type="hidden" name="token" value="<?= $tok ?>"/>
                        Sua identidade:<br/><input type="text" name="rg" onkeypress="return isNumberKey(event)" maxlength="10" autocomplete="off"/>
                        <br/>email cadastrado:<br/><input style="width: 300px" type="text" name="email" autocomplete="off"/>
                   <br/><input type="image" src="imagens/icon_a_continue.png" style="width:18px;height:18px" alt="Solicitar senha"/></li></form>
<?php } ?>
	</ul>
</div>

<!-- <div id="menuEAD">
	<ul>
		<li class="menu_tit">Jornada EAD</li>
		<li><a href="#"><img src="imagens/logoEAD.png"/><span>Jornada EAD</span></a></li>
		
	</ul>
</div>
-->

<!-- <div id="links">
	<ul>
		<li class="link_tit">Cursos online</li>
		<li><a href="http://www.enap.gov.br/" target="_blank"><img src="logos/enap.png"/></a></li>
		<li><a href="http://www5.fgv.br/fgvonline/" target="_blank"><img src="logos/fgv.png"/></a></li>
		<li><a href="http://www.ev.org.br/" target="_blank"><img src="logos/fbradesco.png"/></a></li>
		<li><a href="http://www.senai.br/ead/transversais/" target="_blank"><img src="logos/senai.png"/></a></li>
		<li><a href="https://www.ead.sebrae.com.br/" target="_blank"><img src="logos/sebrae.png"/></a></li>
	</ul>
</div>
-->
