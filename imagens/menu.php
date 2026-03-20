<div id="menu">

	<ul>
		<div id="menu_pri">
		<li><a href="?id=6"><span>Histórico</span></a></li>
		<li><a href="?id=15"><span>Fale Conosco</span></a></li>
		<li><a href="?id=16"><span>Localização</span></a></li>
		</div>
	</ul>
	
	<ul>
		<li class="menu_tit">Jornada EAD</li>
		<li><a href="jornada.php"><img src="imagens/logoEAD.png"/><span>Inscrição Jornada</span></a></li>
		
	</ul>
	<ul>
		<li class="menu_tit">CEADEx</li>
		<li><a href="index.php"><img src="imagens/icon_home.png"/><span>Página inicial</span></a></li>
		<li><a href="?id=1"><img src="imagens/icon_subordinacao.png"/><span>Subordinação</span></a></li>
		<li><a href="?id=9"><img src="imagens/icon_doutrina.png"/><span>Organograma</span></a></li>
		<li><a href="?id=21"><img src="imagens/icon_sobre.png"/><span>Sobre</span></a></li>
	</ul>
	<ul>
		<li class="menu_tit">Cursos/Exames</li>
		<li><a href="?id=2"><img src="imagens/icon_idiomas.png"/><span>Idiomas</span></a></li>
		<li><a href="?id=3"><img src="imagens/icon_calendario.png"/><span>Calendário</span></a></li>
		<li><a href="?id=4"><img src="imagens/icon_ava.png"/><span>AVA</span></a></li>
		<li><a href="?id=5"><img src="imagens/icon_inscricao.png"/><span>Inscrições</span></a></li>
	</ul>
	<ul>
		<li class="menu_tit">Institucional</li>
		<li><a href="?id=7"><img src="imagens/icon_missao.png"/><span>Missão</span></a></li>
		<li><a href="?id=8"><img src="imagens/icon_visao.png"/><span>Visão</span></a></li>
	</ul>
	<ul>
		<li class="menu_tit">Documentos</li>
		<!--<li><a href="?id=9"><img src="imagens/icon_doutrina.png"/><span>Doutrina</span></a></li>-->
		<li><a href="?id=10"><img src="imagens/icon_legislacao.png"/><span>Legislação/Normas</span></a></li>
		<!--<li><a href="?id=11"><img src="imagens/icon_normas.png"/><span>Normas</span></a></li>-->
	</ul>
	<ul>
		<li class="menu_tit">Central de conteúdos</li>
		<li><a href="?id=19"><img src="imagens/icon_downloads.png"/><span>Downloads</span></a></li>
		<li><a href="?id=12"><img src="imagens/icon_imagens.png"/><span>Imagens</span></a></li>
		<li><a href="?id=13"><img src="imagens/icon_videos.png"/><span>Vídeos</span></a></li>
	</ul>
	
	<ul>
		<li class="menu_tit">Visitas</li>
<?php
if(!$_SESSION["visitas"]){
	mysqli_query($con, "update visitas set vst_qtd = vst_qtd + 1 where vst_id = 1");//soma mais 1 download
	$_SESSION["visitas"] = "on";
}
$vst = mysqli_fetch_array(mysqli_query($con, "select * from visitas where vst_id = 1"));
$total = str_pad($vst["vst_qtd"], 6, "0", STR_PAD_LEFT);
?>
		<li><a><img src="imagens/icon_doutrina.png"/><span><?= $total ?></span></a></li>
	</ul>
</div>