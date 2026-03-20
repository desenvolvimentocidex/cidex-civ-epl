<div id="barra-brasil"><script src="https://barra.brasil.gov.br/barra.js" type="text/javascript"></script></div>
<div id="top">
	<div id="top_content">
		<div id="top_up">
			<div id="top_up_left">
				<a href="#conteudo">Ir para o conteúdo <span>1</span></a>
				<a href="#menu">Ir para o menu <span>2</span></a>
				<a href="#busca">Ir para a busca <span>3</span></a>
				<a href="#rodape">Ir para o rodapé <span>4</span></a>
			</div>
			<div id="top_up_right">
				<a href="#" style="padding:0px">Acessibilidade</a>
				<a href="#">Alto Contraste</a>
				<a href="#">Mapa do Site</a>
			</div>
		</div>
		<br/>
		<div id="top_down">
			<div id="top_down_left">
				<div id="logo"></div>
				<div id="logo_text">Exército Brasileiro - Diretoria de Educação Técnica Militar </br>
                                    <h1><a style="font-size: 40px !important" href="index.php">Centro de Idiomas do Exército</a></h1>
                                    <div style="text-transform: uppercase">Centro de Estudos de Pessoal e Forte Duque de Caxias</div>
                                </div>
			</div>
			<div id="top_down_right"><form method="post" action="?id=17"><input type="hidden" name="token" value="<?php echo $tok ?>"/> <input type="image" src="../imagens/icon_search.png" title="Pesquisar" align="absmiddle" border="0"/></form></div>
		</div>
	</div>
</div>
<div id="top_menu">
	<div id="top_menu_link">
            <a href="index.php" >Validação de Certificado</a> |
                <a href="validacao/validanuminscricao.php" >Validação de nº de inscrição</a> |
		<a href="http://www.eb.mil.br/" target="_blank">Exército Brasileiro</a> |
		<!-- <a href="?id=14">Perguntas frequentes</a> | -->
		<a href="http://www.eb.mil.br/web/guest/duvidas-mais-frequentes1" target="_blank">Perguntas frequentes</a> |
		<!-- <a href="?id=15">Contatos</a> |-->
		<a href="http://www.eb.mil.br/web/ouvidoria/tire-sua-duvida" target="_blank">Contatos</a> |
		<!--<a href="?id=16" style="padding-right:0px">Localização</a> -->
	</div>
</div>
<?php
$_SESSION["token_safe"] = @$_SESSION["token_temp"];
if(@$_SESSION["token_temp"] <> @$_SESSION["token"]){
	@$_SESSION["token_temp"] = @$_SESSION["token"];
}
?>