<style>
    .negrito {
        font-weight: bolder;
    }

    .vermelho {
        color: red;
    }
</style>
<?php

$id = htmlspecialchars($id);

$idtratado  = -1;

if (isset($_GET["id"])) {
    $idtratado  = addslashes((int)$_GET["id"]);
}

$a = htmlspecialchars($a);

if (empty($id)) { ?>
    <div id="index_principal">
        <?php
        $dadosUser['qtd'] = 0;
        $temPesquisa = false;
        if (isset($_SESSION['login'])) {
            $identidadeSec = $_SESSION['login'];
            $periodo_pesquisa = getUltimoPeriodo();

            $temPesquisa = @$periodo_pesquisa['cp_pesquisa'] == 'SIM';
            if ($temPesquisa) {
                $dadosUser = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(*) as qtd 
                                            FROM cadastro c 
                                        INNER JOIN pesquisa p 
                                            ON c.cad_id = p.cad_id                                                    
                                        WHERE cad_login = $identidadeSec and cp_id = {$periodo_pesquisa['cp_id']} "));
            }
        }

        //var_dump($dadosUser);exit;


        if ($dadosUser['qtd'] == 0 && $temPesquisa) {
            include 'conteudo_pesquisa.php';
        } else {
            if ($a) {
                include 'conteudo_aluno.php';
            } else {
                include 'conteudo_index.php';
            }
        }

        ?>
    </div>
    <div id="index_links"><?php include 'links.php'; ?></div>
    <?php if (!isset($_SESSION['login'])) { ?>
        <?php
        $hojeDtTime = date("Y-m-d H:i:s");
        $crs = mysqli_query($con, "select * from curso where crs_status = 1 and ('$hojeDtTime' between crs_dtinicio and crs_dttermino) order by crs_id");
        $crs_lista = mysqli_fetch_assoc($crs);
        if ($crs_lista != NULL) {
        ?>
            <div id="aviso" style="margin-left: 200px; font-family:arial, helvetica, verdana; color: blue; padding: 10px; font-size: 17px;  text-align: justify;">
                <b style="color: red">ATENÇÃO:</b><br />

            </div>
        <?php } ?>
        <?php if (htmlspecialchars(isset($_GET['blq'])) && htmlspecialchars($_GET['blq'] == 1)) { ?>
            <div class="alert alert-danger" style="padding: 1.5%; margin-left: 200px;font-family:arial, helvetica, verdana;color: black; background-color: #eb939a; *//* border-color: blue; */padding: 10px;border: 1px solid;font-size: 17px;text-align: justify;">
                <strong>
                    <p>O seu acesso ao sistema está bloqueado. Favor entrar em contato com a Divisão de Educação a Distância através do telefone: 21 - 2457-1991 ou RITEx: 810-4249 para maiores esclarecimentos. </p>
                </strong>
            </div>
        <?php } ?>
        <div id="aviso" style="margin-left: 200px;font-family:arial, helvetica, verdana;color: black;/* background-color: #000; *//* border-color: blue; */padding: 10px;border: 1px solid;font-size: 17px;text-align: justify;">

            <div style="text-align: center;">
                <span style="padding: 1.5%;color: blue;font-weight: bolder;font-size: 14pt;">INFORMAÇÕES SOBRE O 2° EPLE/ EPLO (CA) ESCOLAR 2025</span>
            </div>
            <ul>
                <li>
                    <ul>
                        <li>&nbsp; &nbsp; Port/DECEx nº 844, de 17 DEZ 24.
                            <a href="https://www.cidex.eb.mil.br/images/X_normas_para_o_subsistema_de_certificacao_de_proficiencia_linguistica_scpl_portaria_1.pdf?csrt=11061179168288944659">Clique aqui</a> <br> <br>
                            <ul>
                                <li>Inscrições do 2° EPLE/ EPLO (CA) ESCOLAR 2025
                                    das 10:00 horas do dia 30 JUL 2025 às 16:00 horas do dia 06 AGO 2025; </li>
                                <li>Público alvo: Cadetes da AMAN, Al da EsPCEx, Al CFGS. </li>
                            </ul>
                        </li>




                    </ul>

                    <p><u><span class="" style="color: rgb(239, 69, 64);">Padrões de Inscrição</span></u><span class="" style="color: rgb(239, 69, 64);">:</span></p>

                    <p><br></p>
                    <ol style="list-style-type: decimal-leading-zero;">
                        <li>&nbsp;<b>Alunos dos CFGS</b>:</li>

                        <ul>
                            <ul>
                                <ul>
                                    <li>
                                        inscrições para as habilidades de CA e CL, nível 1. Os Al CFGS devem se inscrever apenas para as habilidades nas quais ainda não tenham obtido o IPL exigido (1-0-1-0).
                                    </li>
                                </ul>
                            </ul>
                        </ul>
                        <li>&nbsp; <b>Cadetes e Al EsPCEx</b>:
                            inscrições para as habilidades de CA, CL e EE, no formato multinível. Os Cadetes e Al EsPCEx devem se inscrever apenas para as habilidades nas quais ainda não tenham obtido o IPL exigido (2-1-2-2) e farão as provas dos níveis 1 e 2 (formato multinível) dessas habilidades.
                        </li>



                    </ol>
                    <p style='align:"center";'>Em caso de <b>dúvidas ou intercorrências</b>, contatar a
                        Secretaria da Divisão de Certificação do CIdEx, através do <b>e-mail </b><a href="mailto:secrctf@cidex.eb.mil.br">secrctf@cidex.eb.mil.br</a><a href="mailto:secrctf@cidex.eb.mil.br"> </a>ou através do
                        <b>WhatsApp (21) 97479-186</b>1.
                    </p>

                    <p style='align:"center";'>&nbsp;</p>

                    <p style='align:"center";'><b><u>Horário de
                                atendimento da SCrt:</u></b><u></u></p>

                    <p>-
                        Segunda a quinta: 7h30 às 12h e das 13h às 16h.</p>

                    <p>-
                        Sexta: 7h30 às 12h.</p><br>
                    <p></p>
                </li>
            </ul>
        </div>
    <?php } ?>
<?php
} else { //paginas internas
    $conteudo = mysqli_fetch_array(mysqli_query($con, "select * from conteudo where mnu_id = $idtratado"));
    if ($conteudo) {
        foreach ($conteudo as $campo => $valor) {
            $$campo = stripslashes($valor);
        }
    } else {
        echo "<script>location='?id=100'</script>";
        exit;
    }
?>


    <div id="ctd_titulo"><?= $ctd_titulo ?></div>
    <div id="ctd_texto">
        <?= nl2br($ctd_texto) ?>
        <?php if ($idtratado == 5) {
            include 'inscricoes.php';
        } //incricoes 
        ?>
        <?php if ($idtratado == 12) {
            include 'galeria.php';
        } //imagens 
        ?>
        <?php if ($idtratado == 13) {
            include 'youtube.php';
        } //videos 
        ?>
        <?php if ($idtratado == 22) {
            include 'jornada_frame.php';
        } //videos 
        ?>
        <?php if ($idtratado == 17) {
            include 'noticias.php';
        } //noticias 
        ?>
        <?php if ($idtratado == 19) {
            include 'downloads.php';
        } //downloads 
        ?>
        <?php if ($idtratado == 20) {
            include 'avisos.php';
        } //avisos 
        ?>
    </div>
<?php } ?>