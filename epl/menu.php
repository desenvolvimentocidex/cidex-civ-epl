<?php
  require_once '../system/system.php';
  
?>
﻿<div id="menu">
    <ul>
        <?php
        if ($_SESSION["perfil"] == PERFIL_USUARIO_ADM ) {
            ?>
            <li class="tit3">Configurações</li>
            <li><a href="index.php?id=32">Período</a></li>
            <li><a href="index.php?id=1">OMSE</a></li>
            <li><a href="index.php?id=3">Cursos/Exames</a></li>
            <li><a href="index.php?id=2">OMSE :: Cursos/Exames</a></li>
            <li><a href="index.php?id=4">Estatísticas</a></li>
            <li><a href="index.php?id=6">Configurar GRU</a></li>
            <li><a href="index.php?id=5">Carregar GRUs</a></li> 
            <li><a href="index.php?id=11">Cancelamentos</a></li>
            <li><a href="index.php?id=12">Gratuidades</a></li>
            <li><a href="index.php?id=21">Regras</a></li>
            <li><a href="index.php?id=25">Perfil</a></li>            
            <!--<li><a href="?id=13">INSCREVER (NOVO)</a></li>-->
            <li class="tit3">Consultas</li>
            <li><a href="index.php?id=8">Militares</a></li>
            <li><a href="index.php?id=14">Listar por Nome</a></li>
            <li><a href="index.php?id=9">Militares por Exames</a></li>
            <li><a href="index.php?id=7">Exames por OMSE</a></li>
            <li><a href="index.php?id=10">Militares por OMSE</a></li>
            <li><a href="index.php?id=16">Mils por OMSE (dados)</a></li>
            <li><a href="index.php?id=15">Listar EPLO/EO - Vídeoconferência</a></li>
            <li><a href="index.php?id=19">Listar EPLO/EO - Presencial</a></li>
            <li><a href="index.php?id=28">Inscrições centralizadas(Resumido)</a></li>
            <li><a href="index.php?id=29">Inscrições centralizadas(Detalhado)</a></li>
            <li><a href="index.php?id=31">Atualização de dados SicaPex ==> CIDEx</a></li>
            <!-- <li><a href="index.php?id=20">Relatório de logs</a></li> -->
            <li class="tit3">Pagamentos</li>
            <li><a href="index.php?id=30">Realizar baixa de pagamentos</a></li>
            <li><a href="index.php?id=17">Extrato de pagamentos</a></li>
            <li><a href="index.php?id=18">Pagamentos errados</a></li>	
            <li><a href="pagamentos.php">Pagamentos duplicados</a></li>
            <li><a href="pagamentos.php?corrigido=1">Pagamentos duplicados(corrigidos)</a></li>
            <li class="tit3">Planilhas</li>
            <li><a href="xls.php?cid=2">EPLO/CA</a></li>
            <li><a href="xls.php?cid=3">EPLO/EO - Vídeoconferência</a></li>
            <li><a href="xls.php?cid=6">EPLO/EO - Presencial</a></li>
            <li><a href="xls.php?cid=4">EPLE/CL</a></li>
            <li><a href="xls.php?cid=5">EPLE/EE</a></li>
            <li><a href="xlsPesquisa.php">Pesquisa <b style="color: red">&nbsp;&nbsp;NOVO</b></a></li>
            
                <?php
            } else if ($_SESSION["perfil"] == PERFIL_USUARIO_SECRETARIA ) {
                ?>
            <li class="tit3">Inscrição Centralizada</li>
            <li><a href="index.php?id=22">Inscrição e Relatório</a></li>
            <!-- <li><a href="index.php?id=23">Minhas inscrições</a></li> -->
            <li><a href="index.php?id=28">Inscrições centralizadas(Resumido)</a></li>
            <li><a href="index.php?id=29">Inscrições centralizadas(Detalhado)</a></li>
            <li><a href="index.php?id=31">Atualização de dados SicaPex ==> CIDEx</a></li>
    <?php
}
?>
    </ul>
</div>
