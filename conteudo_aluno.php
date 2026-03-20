<?php
 $datahora = new DateTime();
 $datahora->setTimezone(new DateTimeZone('America/Recife'));                     
 $hojeDtTime = $datahora->format("Y,m,d,H,i,s"); //date("Y-m-d H:i:s");
 //var_dump($hojeDtTime);

 $a = htmlspecialchars($a);
  
if (@$_SESSION["loged"] == "on") {//se aluno logado
    //ini verifica ultimo periodo para epl
    $cp = mysqli_fetch_array(mysqli_query($con, "select * from curso_periodo order by cp_id desc"));
    $last_cp_id = $cp["cp_id"]; //ultimo periodo cadastrado
    //fim verifica ultimo periodo para epl
    if ($_SESSION["cad_id"]) {//se session[cad_id] ok, usuario logado
        $cad_id = htmlspecialchars($_SESSION["cad_id"]);
        $cad = mysqli_fetch_array(mysqli_query($con, "select * from cadastro where cad_id = $cad_id"));
        foreach ($cad as $campo => $valor) {
            $$campo = stripslashes($valor);
        }
        if ($cad_cel == 0 && $a != 2) {
            echo "<script>alert('Sr candidato.\\n\\nPara realizar inscrição nos cursos/exames disponíveis o sr deve atualizar seus CONTATOS.');setTimeout(function(){ location='?a=2'; }, 3000);</script>";
        }

        $cad_id = htmlspecialchars($_SESSION["cad_id"]); //meu usuario logado
        $tit_array = array("", "Gerar GRU", "Meus dados", "Novas inscrições", "Incrições realizadas", "Alterar senha", "Renovar Matrícula","Cancelar Matrícula","Meus IPLs");
        $tit_qtd = count($tit_array);        
        if ($a != 31) {
            @$a = ($tit_qtd > $a) ? $a : 1; //se $a for maior que qtd array: erro e $a recebe 1
            @$tit = $tit_array[$a];
        } else {
            @$tit = "Atualizar Nível/OMSE";
        }
    } else {
        echo "<script>location='?id=100'</script>";
    }
   
    ?>
    <div id="conteudo_aluno">
        <div id="ctdal_titulo"><?= $tit ?></div>
        <?php if ($a == 1) {//ini meus cursos ?>
        <?php        

        $b = 0;
       
        if (!empty($_GET["b"])){
           $b = htmlspecialchars(addslashes((int)$_GET["b"]));
        }   
        
        if ($b == 1) {//ini meus cursos 

        $crs_id = htmlspecialchars(addslashes((int)$_GET["crsid"]));
        $cp_id = htmlspecialchars(addslashes((int)$_GET["cpid"]));
        $idm_id = htmlspecialchars(addslashes((int)$_GET["idmid"]));
        $ref = htmlspecialchars(addslashes((int)$_GET["ref"]));
        
        $dadoExameInsc = mysqli_fetch_assoc(mysqli_query($con, "select * from cadastro_curso cc inner join idioma i on cc.idm_id = i.idm_id inner join curso c on cc.crs_id = c.crs_id WHERE cc.cad_id = $cad_id and cc.crs_id = $crs_id and cc.cp_id = $cp_id and cc.idm_id = $idm_id"));
        $exameNome = $dadoExameInsc["crs_nome"]." - ".$dadoExameInsc["idm_nome"]." - Nível ".$dadoExameInsc["nivel_id"];
        
        ?>
        <script type='text/javascript'>
            $(document).ready(function(){
                $('.jnone').click();
                $("#sessao").attr({  bolid: <?= $ref ?>});
            });
        </script>

        <?php } 
        
        if ($b == 2) {//ini meus cursos 
            
        $crs_id = htmlspecialchars(addslashes((int)$_GET["crsid"]));
        $cp_id = htmlspecialchars(addslashes((int)$_GET["cpid"]));
        $idm_id = htmlspecialchars(addslashes((int)$_GET["idmid"]));
        
        $dadoExameInsc = mysqli_fetch_assoc(mysqli_query($con, "select * from cadastro_curso cc inner join idioma i on cc.idm_id = i.idm_id inner join curso c on cc.crs_id = c.crs_id WHERE cc.cad_id = $cad_id and cc.crs_id = $crs_id and cc.cp_id = $cp_id and cc.idm_id = $idm_id"));
        $exameNome = $dadoExameInsc["crs_nome"]." - ".$dadoExameInsc["idm_nome"]." - Nível ".$dadoExameInsc["nivel_id"];
        ?>
        
        <a href='#' class='btn btn-primary jnone' exame="<span style='text-align: justify'>Sua solicitação de inscrição no <?= $exameNome ?> foi realizada com sucesso, porém ultrapassou o número de vagas da <u>1ª convocação.</u></span><br><br>" style="display: none;">#</a>
        <script type='text/javascript'>

            var msg = $('.jnone').attr('exame');
            
            $('.jnone').alertOnClick({
                'title': '', 
//                'theme':'dark_green', 
                'content': msg+'<span style="text-align: justify">O(a) Sr(a) será incluído(a) na <u>2ª convocação</u>, prevista para o dia <span style="color:red"> <?= $_SESSION['DATA_SEGUNDA_CONVOCACAO'] ?> </span>, quando receberá um e-mail e, então, deverá imprimir e pagar a GRU para confirmar sua inscrição. <br><br>O local de seu Exame será divulgado <span style="color:red">até o dia <?= $_SESSION['DATA_DIVULGACAO'] ?> </span> no site do CIdEx <i>(www.cidex.eb.mil.br)</i>, no link <u><i> <?= $_SESSION['NOME_PERIODO'] ?></i></u>, com a lista dos candidatos efetivamente inscritos.</span><br><p style="text-align:right"><input style="width:65px; height: 40px; color: #000; font-weight: bold; font-size:12pt; " type="button" value="SAIR" id="sair2" /></p>', 
                'size': {'height':'250px', 'width':'740px'},
                'closeBtn': true, 
                'closeOnClick': false, 
                'closeOnEsc': false,
                'type':	'modal'
            });
            $('.jnone').click();
            
            $('#sair2').click(function(){ 
                $(".closejAlert").click(); 
                location='?a=10';
            });
            
        </script>
        <?php } 
        
        ?>
        
            <ul id="lista_aluno" >
                <li class="tit">
                    <label class="w70pc" style="width: 55%;"><b>Código</b></label>
                    <label class="w30pc">Exame</label>
                    <label class="w20pc">Ações</label>
                    
                </li>
                <?php                 
                $i = 0;                
                $cc = mysqli_query($con, "select * from curso c,cadastro_curso cc,idioma i where c.crs_status = 1 and c.crs_id = cc.crs_id and cc.idm_id = i.idm_id and c.crs_id <> 1 and cc.cad_id = $cad_id and ccs_id in (0,9,11) and cp_id in ($last_cp_id,0) order by c.crs_id desc"); //buscando pelo ultimo periodo cadastrado
                while ($cc_lista = mysqli_fetch_array($cc)) {//ini lista cursos do aluno
                if(($cc_lista['crs_id'] == 1 && $cc_lista['idm_id'] == 4 && date($cc_lista['cc_vencimento']) < date('2019-03-01')) || ($cc_lista['crs_id'] == 1 && $cc_lista['idm_id'] != 4 && date($cc_lista['cc_vencimento']) < date('2019-04-01')) || $cc_lista['crs_id'] != 1){
                    foreach ($cc_lista as $campo => $valor) {
                        $$campo = stripslashes($valor);
                    }
                    /*$vencimento = explode("-", $cc_vencimento);
                    $vencimento = $vencimento[2] . "/" . $vencimento[1] . "/" . $vencimento[0];*/

                    $cc_parcela = ($cc_parcela == 1 || $cc_parcela == 13 || $cc_parcela == 25 || $cc_parcela == 37 || $cc_parcela == 49) ? $cc_parcela . " - <b style='color:red'>INSCRIÇÃO</b>" : $cc_parcela;

                    $idioma_nivel = ($crs_id == 1) ? $idm_nome . " - Parcela: " . $cc_parcela  : $idm_nome . " Nível " . $nivel_id; //idioma_nivel se crs_id = 1
                    if ($ccs_id != 1) {
                        $img = "close";
                        $msg = "Deseja solicitar cancelamento da inscriçao do $crs_nome - $idioma_nivel?";
                        $title = "Solicitar cancelamento da inscriçao";
                    } 
                    $i++;
                    $li = ($i % 2 == 0) ? "li1" : "li2";
                    ?>
                    <li class="<?= $li ?>">
                        <label class="w70pc" style="width: 55%;"><?= $crs_nome ?> </label>
                        <label class="w30pc" style=""><?= $idioma_nivel ?> </label>
                        <label class="w10pc" style="width:14px; margin-left: 15px;">
                            <?php
//ver dia vencimento
                            $cgru = mysqli_fetch_array(mysqli_query($con, "select * from curso_gru where crs_id = $crs_id"));
                            $cg_vencimento = $cgru["cg_vencimento"];
                            if ($hoje <= $cg_vencimento || $crs_id == 1) {//ini hoje antes do dia do vencimento ou curso = CIV
                                if ($crs_id == 1) {//se civ
                                    $pos_cad = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso where cc_id <= $cc_id and crs_id = $crs_id and idm_id = $idm_id and cc_parcela = 1 and ccs_id in(0,1,2)")); //verifica posicao do aluno civ
                                } else {//se epl
                                    $pos_cad = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso where cc_id <= $cc_id and crs_id = $crs_id and idm_id = $idm_id and cp_id = $last_cp_id")); //verifica posicao do aluno epl
                                }
                                #cc_id <= $cc_id --> conta registros desde o inicio ate a posicao dele

                                $vaga = mysqli_fetch_array(mysqli_query($con, "select * from curso_idioma where crs_id = $crs_id and idm_id = $idm_id")); //qtd de vaga para o idioma e curso
                                $qtd_vagas = $vaga["ci_vagas"];                                                                
                                if ($qtd_vagas >= $pos_cad) {//se numero de vagas for maior que a posicao do inscrito: gera boleto                                                                        
                                    if (in_array($ccs_id, [0,11])) {//fim se nao estiver pago                                        
                                        include 'gerar_gru.php'; //gerar gru
                                    ?>
                                    <?php
                                    }//fim se nao estiver pago
                                } else {//se estiver fora do numero de vagas
                                    $posicao = $pos_cad - $qtd_vagas;
                                    ?>                            
                                    <img src="imagens/icon_a_gru_off.png" style="width: 14px; height: 14px; border: 0; padding: 0; padding-bottom: 7px; margin-top: -1px;" title="" align="absmiddle" class="gru-off"/>
                                    
                                    <?php
                                }//fim se numero de vagas for maior que a posicao do aluno
                            } else {//fim hoje antes do dia do vencimento - se fora do vencimento
                                ?>
                                <img src="imagens/icon_a_gru_off.png" style="width: 14px; height: 14px; border: 0; padding: 0; padding-bottom: 7px; margin-top: -1px;" title="O prazo para geração de GRU expirou em <?= date('d/m/Y', strtotime($cg_vencimento)) ?>." align="absmiddle" onclick="alert('O prazo para geração de GRU expirou em <?= date('d/m/Y', strtotime($cg_vencimento)) ?>.\n\nAguarde abertura de novas vagas.')"/>
                            <?php }//fim hoje antes do dia do vencimento ?>
                        </label>
                        <label class="w10pc" style="width:14px;">
                            <?php
                            if ($crs_id <> 1) {//ini diferente de civ
                                if ($hoje <= $crs_dtlocal) {//ini se hoje for menor que limite
                                    ?>
                                    <form method="post" action="?a=31">
                                        <input type="hidden" name="token" value="<?= $tok ?>"/>
                                        <input type="hidden" name="cc_id" value="<?= $cc_id ?>"/>
                                        <input type="hidden" name="back" value="1"/>
                                        <input type="image" src="imagens/icon_a_edit.png" title="Editar Nível/OMSE" align="absmiddle" style="margin-top: -9px;"/>
                                    </form>
                                    <?php
                                }//fim se hoje for menor que limite
                            }//fim diferente de civ
                            ?>
                        </label>
                        <label class="w10pc" style="width:14px; margin-right: 20px">
                            <form method="post" action="print.php" target="print">
                                <input type="hidden" name="token" value="<?= $tok ?>"/>
                                <input type="hidden" name="cc_id" value="<?= $cc_id ?>"/>
                                <input type="hidden" name="last_cp_id" value="<?= $last_cp_id ?>"/>
                                <input style="margin-top: -9px;" type="image" src="imagens/icon_a_print.png" title="Imprimir Ficha de Inscrição" align="absmiddle"/>
                            </form>	
                        </label>
                        <label class="w20pc" style="width:14px">
                        <?php // if ($crs_id != 0) {//($crs_id != 1 && $crs_id != 3)pode sair de fila se curso não for CIV ou EPLO/EO  
                            
//                            $sqlPagamento = mysqli_num_rows(mysqli_query($con, "select * from pagamento where cc_id = $cc_id"));
//                            if($sqlPagamento == 0){
                        ?>
<!--                            <form method="post" id="cancelar-form<?php //$cc_id ?>">
                                <input type="hidden" name="token" value="<?php //$tok ?>"/>
                                <input type="hidden" name="acao" value="del_curso"/>
                                <input type="hidden" name="cc_id" value="<?php //$cc_id ?>"/>
                                <input type="hidden" name="ccs_id" value="<?php //$ccs_id ?>"/>
                                <input type="hidden" name="ccs_id" value="<?php //$ccs_id ?>"/>
                                <input style="margin-top: -9px;" type="image" src="imagens/icon_a_close.png" title="<?php //$title ?>" align="absmiddle" />
                            </form>-->
                        <?php // } } ?>
                        </label>
                    </li>
                <?php }}//fim lista cursos do aluno  ?>
            </ul>
        <script type='text/javascript'>
            $('.gru-off').alertOnClick({
                'title': '', 
//                'theme':'dark_green', 
                'content':'<span style="text-align: justify">Ainda não é possível gerar GRU para esta inscrição.<br><br>O(a) Sr(a) será incluído(a) na <u>2ª convocação</u>, prevista para o dia <span style="color:red"><?= $_SESSION['DATA_SEGUNDA_CONVOCACAO'] ?></span>, quando receberá um e-mail e, então, deverá imprimir e pagar a GRU para confirmar sua inscrição. <br><br>O local de seu Exame será divulgado <span style="color:red">até o dia <?= $_SESSION['DATA_DIVULGACAO'] ?></span> no site do CIdEx <i>(www.cidex.eb.mil.br)</i>, no link <u><i><?= $_SESSION['NOME_PERIODO'] ?></i></u>, com a lista dos candidatos efetivamente inscritos.</span><br><p style="text-align:right"></p>', 
                'size': {'height':'250px', 'width':'600px'},
                'closeBtn': true, 
                'closeOnClick': true, 
                'closeOnEsc': true,
                'autoClose' : 10000,
                'type':	'modal'
            });

        </script>
            <script type='text/javascript'>
                var tempo = new Number();
                // Tempo em segundos
                tempo = 30;

                function startCountdown(){
                    // Se o tempo não for zerado
                    if((tempo - 1) >= 0){
                            // Pega a parte inteira dos minutos
                            var min = parseInt(tempo/60);
                            // Calcula os segundos restantes
                            var seg = tempo%60;
                            // Formata o número menor que dez, ex: 08, 07, ...
                            if(min < 10){
                                min = "0"+min;
                                min = min.substr(0, 2);
                            }
                            if(seg <=9){ 
                                seg = "0"+seg; 
                            }
                            // Cria a variável para formatar no estilo hora/cronômetro
                            horaImprimivel = min + ':' + seg;
                            //JQuery pra setar o valor
                            $("#sessao").val(horaImprimivel);
                            $(".closejAlert").hide();

                            // Define que a função será executada novamente em 1000ms = 1 segundo
                            setTimeout('startCountdown()',1000);
                            // diminui o tempo
                            tempo--;
                    // Quando o contador chegar a zero faz esta ação
                    } else {
                        $("#sessao").val('IMPRIMIR GRU');
                        $('#sessao').click(function(){ 
                            $("#"+$(this).attr('bolid')).click(); 
                            $('.ja_wrap_black').hide();
//                            location.reload();
                            window.location.href = "index.php?a=10";
                        });
                        $('#sair').click(function(){ 
                            $('.ja_wrap_black').hide();
//                            location.reload();
                            window.location.href = "index.php?a=10";
                        });
                    }
                }
                
                $('.jnone').click(function () {
                    $('.ja_wrap_black').show();   
                    $("#sessao").attr({  bolid: $(this).attr('bolid')});
                    startCountdown();
                });
            </script>
            <div class="ja_wrap ja_wrap_black" style="display:none;">
                <div class="jAlert animated ja_red ja_setheight fadeInUp" style="width: 1200px; height: 550px;" id="ja_156158001706229596">
                    <div>
                        <div class="closejAlert ja_close ja_close_round">×</div>
                        <div class="ja_title">
                            <div>ATENÇÃO</div>
                        </div>
                        <div class="ja_body">
                            <div style="float: left; width: 450px; font-size: 11pt;">
                                <span>O(a) Sr(a) deverá imprimir e pagar a GRU até o dia 
                                    <span style="color:red"><?= $_SESSION['DATA_GRU'] ?></span> para confirmar sua inscrição. 
                                    <br><br>
                                </span>
                                <span>
                                    No preenchimento, o código de recolhimento <strong>é diferente do Nr Referência.</strong>
                                </span>                        
                                <br><br>
                                <p>Preenchimento da GRU:</p>
                                <span>
                                    <ol>
                                        <li>O Cód Recolhimento é sempre <strong>28922-1 </strong> (serviços educacionais) </li>
                                        <li>O Nr de <strong>Referência é o NÚMERO DA INSCRIÇÃO</strong> - código ÚNICO para cada prova (é n° de inscrição diferente em cada GRU)</li>
                                        <li>O CPF da GRU é sempre <strong>do militar candidato</strong></li>
                                        <li>O código da <strong>UG</strong> Secundária é sempre <strong>167289</strong></li>
                                    </ol>
                                    
                                </span>
                                    <u>
                                        <p style="text-align:center;  width: 50%; float: left;">
                                            <input style="width:165px; height: 40px; color: #FFF; background-color:#000; font-weight: bold; font-size:15pt;" type="button" value="IMPRIMIR GRU" id="sessao">
                                        </p> 
                                        <p style="text-align:right;  width: 50%; float: left;">
                                            <input style="width:65px; height: 40px; color: #000; font-weight: bold; font-size:12pt;" type="button" value="SAIR" id="sair">
                                        </p>
                                    </u>
                            </div>
                            <u>
                                <div style="float: left; margin-left: 20px;">
                                    <p id="exemplogru" style="
                                        font-size: x-large;
                                        font-weight: 900;
                                        margin-bottom: 10px;
                                        margin-left: 18px;
                                        color: #e20d0d;
                                    ">Imagem meramente ilustrativa:</p>
                                    <img src="imagens/modelo-gru.jpg" width="650px" height="370px">
                                </div>
                            </u>
                        </div>
                    </div>
                </div>
            </div>
            

            <?php }//fim meus cursos ?>

            <?php
            if ($a == 2) {//ini meus dados
                $cad = mysqli_fetch_array(mysqli_query($con, "select * from cadastro where cad_id = $cad_id"));
                foreach ($cad as $campo => $valor) {
                    $$campo = stripslashes($valor);
                }
               /*if ($cad_endereco != "") {
                    $end = explode(";", $cad_endereco); //rua;nr;comp;bairro;cidade;uf;pais;cep
                    $end0 = $end[0];
                    $end1 = $end[1];
                    $end2 = $end[2];
                    $end3 = $end[3];
                    $end4 = $end[4];
                    $end5 = $end[5];
                    $end6 = $end[6];
                    $end7 = $end[7];
                }*/
                if (@$cad_tel) {
                    $tel = explode(";", $cad_tel); //ddd;telefone
                    $tel0 = $tel[0];
                    $tel1 = $tel[1];
                }
                if (@$cad_cel) {
                    $cel = explode(";", $cad_cel); //ddd;celular
                    $cel0 = $cel[0];
                    $cel1 = $cel[1];
                }
                ?>
            <ul id="lista_aluno">
                <li class="tit">Informações pessoais</li>
                <li class="li1"><label>Nome:</label> <?= $cad_nome ?></li>
                <li class="li2"><label>Identidade:</label> <?= mask($cad_login, '#########-#') ?></li>
                <li class="li1"><label>Pai:</label> <?= $cad_pai ?></li>
                <li class="li2"><label>Mãe:</label> <?= $cad_mae ?></li>
                <li class="li1"><label>Sexo:</label> <?= ($cad_sexo == 1) ? "Masculino" : "Feminino"; ?></li>
                <li class="li2"><label>Nascimento:</label> <?= $cad_nascimento ?></li>
                <li class="li1"><label>CPF:</label> <?= mask($cad_cpf, '###.###.###-##') ?></li>

                <li class="tit">Informações militares</li>
                <li class="li1"><label>Nome de guerra:</label> <?= $cad_nomeguerra ?></li>
                <li class="li2"><label>Posto/Graduação:</label> <?= $cad_postograd ?></li>
                <li class="li1"><label>QAS/QMS:</label> <?= $cad_qasqms ?></li>
                <li class="li2"><label>Prec-CP:</label> <?= mask($cad_preccp, '## #######') ?></li>
                <li class="li1"><label>Região Militar:</label> <?= $cad_rm ?></li>
                <li class="li2"><label>Comando Militar:</label> <?= $cad_cma ?></li>
                <li class="li1"><label>Organização militar:</label> <?= $cad_om ?></li>

                <li class="tit">Contatos</li><a name="contatos"></a>
                <form method="post" id="update_aluno">
                    <input type="hidden" name="token" value="<?= $tok ?>"/>
                    <input type="hidden" name="acao" value="update_aluno"/>
                    <li class="li2"><label>Telefone Fixo:</label> ( <input type="text" name="telddd" value="<?= @$tel0 ?>" style="width:20px"/> ) <input type="text" name="telnr" value="<?= @$tel1 ?>" style="width:130px"/> <b>Celular:</b> ( <input type="text" name="celddd" value="<?= @$cel0 ?>" style="width:20px"/> ) <input type="text" name="celnr" value="<?= @$cel1 ?>" style="width:104px"/> <?= (@$cel1) ? $img_checked : $img_nochecked ?></li>
                    <li class="li1"><label>E-mail:</label> <input type="text" name="mail" value="<?= $cad_mail ?>" style="width:400px"/> <?= (@$cad_mail) ? $img_checked : $img_nochecked ?></li>
                    <li class="li2 alignr"><input class="botao" type="submit" value="Atualizar"/></li>
                </form>
            </ul>
        <?php }//fim meus dados ?>

        <?php
        if ($a == 3) {//ini novos cursos   
            ?>
               

        <?php
				
//  se for militar inativo não pode se inscrever
//            $pessoa = mysql_fetch_array(mysql_query("select * from MILITAR m where m.PES_IDENTIFICADOR_COD = $rg", $-condgp)); //status=1(ativa);mil_type=1(carreira)
            $rg = $_SESSION['login'];
            $candidato = Candidato::getCandidato();
            
            //Conexão com BD Oracle             
           /* $stid = oci_parse($oci_connect, "select * from RH_QUADRO.MILITAR m where m.PES_IDENTIFICADOR_COD = '$rg'");
            oci_execute($stid);
            $pessoa = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
            oci_close($oci_connect); // Fecha a Conexão oracle            
            //Conexão com BD Oracle 
            foreach ($pessoa as $campo => $valor) {
                $$campo = stripslashes($valor);
            } */
            if (! empty($candidato->getIdCadastro() )  )  {
                if (! $candidato->getAtivo() && ! $candidato->ehAluno() ) {
                    echo "<script>alert('Somente militar da ativa pode realizar a inscrição.'); setTimeout(function(){ location='index.php' }, 30000);</script>";
                    exit;
                }
            }

//ini condicoes para permanecer selecionando curso
            if ($iid) {//ini se idioma esta habilitado para o curso selecionado               
                $idm = mysqli_num_rows(mysqli_query($con, "select * from curso_idioma where idm_id = $iid and crs_id = $cid and ci_status = 1"));
                if ($idm == 0) {
                    echo "<script>location='?a=3'</script>";
                }
            }//fim se idioma esta habilitado para o curso selecionado
//fim condicoes para permanecer selecionando curso
            ?>
            <ul id="lista_aluno">
                <li class="tit"><label class="w20pc">Código</label><label class="w70pc"><b>Exame</b></label></li>
                <?php
                if (empty($cid)) {//ini se curso não foi selecionado
                    $i = 0;                 
					
                    $crs = mysqli_query($con, "select * from curso where crs_status = 1 and ('$hojeDtTime' between crs_dtinicio and crs_dttermino) order by crs_id"); //lista cursos
					
                    $cursosbloqueados = $candidato->getCursosBloqueados();
                                        
                    while ($crs_lista = mysqli_fetch_assoc($crs)) {
                        foreach ($crs_lista as $campo => $valor) {$$campo = stripslashes($valor);}

                        $i++;
                        $li = ($i % 2 == 0) ? "li1" : "li2"; 
                        if ( in_array($crs_id, $cursosbloqueados) ){
                            continue;
                        }
		if($crs_id == 3){
                        ?>                           
                        <li class="<?= $li ?>"  style="cursor:pointer">
                           
                            <table>
                                <tr>
                                    <td rowspan="2" class="w20pc" style="font-size: 12px; font-weight: bold;"><?= $crs_cod ?></td>
                                    <td rowspan="2"  class="w70pc" style="font-size: 12px; width: 64%;"><span style=" margin-left: 20px; white-space: nowrap;"><?= $crs_nome ?></span></td>
                                    <td style="font-size: 12px; width: 85px; height: 30px;">Rio de Janeiro</td>
                                    <td style="font-size: 12px;"><img src="imagens/icon_a_continue.png" title="Selecionar Idioma" align="absmiddle" onclick="location = '?a=<?= $a ?>&amp;cid=6'" /></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 12px; height: 30px">Outras Guarnições</td>
                                    <td style="font-size: 12px;"><img src="imagens/icon_a_continue.png" title="Selecionar Idioma" align="absmiddle" onclick="location = '?a=<?= $a ?>&amp;cid=3'" /></td>
                                </tr>
                            </table>
                            
                        </li>
                        <?php
                } else if ($crs_id != 6){
                        ?>
                        <li class="<?= $li ?>" onclick="location = '?a=<?= $a ?>&amp;cid=<?= $crs_id ?>'" style="cursor:pointer">
                            <label class="w20pc"><?= $crs_cod ?></label>
                            <label class="w70pc"><?= $crs_nome ?></label>
                            <label class="w10pc" style="text-align: left;"><img style="margin-left: 18px;" src="imagens/icon_a_continue.png" title="Selecionar Idioma" align="absmiddle"/></label>
                        </li>
                    <?php
                }
                    }
                } else {//ini se curso foi selecionado                        
                    $crs = mysqli_fetch_array(mysqli_query($con, "select * from curso where crs_id = $cid and crs_status = 1 and ('$hojeDtTime' between crs_dtinicio and crs_dttermino)")); //mostra curso se etiver dentro do prazo e ativo
                    if ($crs) {//ini se curso existir
                        foreach ($crs as $campo => $valor) {
                            $$campo = stripslashes($valor);
                        }
                        
                        /*
                        //Bloqueio EPLO/EO para AAA4 = Alunos do 5º Ano / AAA1 = Alunos EsFCEx / AAA2 =Alunos EsSEx / 64 = CFS (ESA, ESLOG e CIAvEx). 
                        $consultapessoa = oci_parse($oci_connect, "select * from RH_QUADRO.MILITAR m left join RH_QUADRO.QAS_QMS q on q.cod_qas_qms = m.QQ_COD_QAS_QMS where m.PES_IDENTIFICADOR_COD = '$login'");
                        oci_execute($consultapessoa);
                        $pessoa = oci_fetch_array($consultapessoa, OCI_ASSOC+OCI_RETURN_NULLS);
                        oci_close($oci_connect); // Fecha a Conexão oracle
                       
                        if($pessoa['QQ_COD_QAS_QMS'] == "AAA1" || $pessoa['QQ_COD_QAS_QMS'] == "AAA2" || $pessoa['QQ_COD_QAS_QMS'] == "AAA4" || $pessoa['POSTO_GRAD_CODIGO'] == 54 || $pessoa['POSTO_GRAD_CODIGO'] == 55 || $pessoa['POSTO_GRAD_CODIGO'] == 56 || $pessoa['POSTO_GRAD_CODIGO'] == 57 ||$pessoa['POSTO_GRAD_CODIGO'] == 64) {
                           $_SESSION["aluno_bloqueio"] = "on";
                        }
                        //Bloqueio EPLO/EO para AAA4 = Alunos do 5º Ano / AAA1 = Alunos EsFCEx / AAA2 =Alunos EsSEx / 64 = CFS (ESA, ESLOG e CIAvEx).
                        
                        if (isset($_SESSION["aluno_bloqueio"]) && $_SESSION["aluno_bloqueio"] == "on") {//verifica se é aluno (aman,ime,cfs)
                            //ini verifica se ja tem idioma selecionado em outro curso
                            $idm_on = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso where cad_id = $cad_id and not crs_id = 1 and cp_id in ($last_cp_id,0)"));
                            if ($idm_on) {
                                $aluno_idm = $idm_on["idm_id"];
                            }//fim
                            //ini não permite se ja esta inscrito no curso selecionado ou se é eplo/eo
                            $crs_on = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso where cad_id = $cad_id and crs_id = $crs_id and cp_id in ($last_cp_id,0)"));
                            if ($crs_on || $crs_id == 3 || $crs_id == 6) {
                                echo "<script>alert('Sr Candidato,\\nnão é possível realizar sua inscrição.\\n\\nPossíveis motivo:\\n1. Não é possível realizar inscrição para mais de 1 (um) idioma por Exame;\\n2. Inscrição não autorizada para o Exame selecionado.'); setTimeout(function(){ location='index.php?a=3'; }, 30000);</script>";
                            }//fim
                        }*/
                        
                        
                        $respostaCandidato = $candidato->podeFazerCurso($cid);                        
                        if (  $respostaCandidato["bloqueado"] ){
                            echo "<script>alert(null, '".$respostaCandidato["msg"]."', function(){ location='index.php?a=3'; });</script>"; 
                        }
                        
                        ?>
                        <form id="crs<?= $crs_id ?>" method="post" >
                            <input type="hidden" name="token" value="<?= $tok ?>"/>
                            <input type="hidden" name="acao" value="add_curso"/>
                            <input type="hidden" name="crs_id" value="<?= $crs_id ?>"/>
                            <input type="hidden" name="cp_id" value="<?= $last_cp_id ?>"/>
                                        <?php
                                        $display = ($cid == 1 && $iid) ? "show" : "none"; //verifica se é civ ou eple/eplo para habilitar/desabilitar botao submit
                                        ?>
                            <li class="li1"><label class="w20pc"><?= $crs_cod ?></label><label class="w70pc"><?= $crs_nome ?><?php if ($cid == 6){ ?> (Rio de Janeiro)<?php } else if($cid == 3) { ?> <span style="color:red;">(Outras Guarnições)</span> <?php } ?></label></li>	
                            <li class="li2"><label class="w20pc">Idioma:</label><label class="w70pc">
                   <?php //  trace("select * from cadastro_curso where cad_id = $cad_id and crs_id = $cid and idm_id = $idm_id and cp_id in ($last_cp_id,0) and cc_parcela = 1 and (ccs_id = 0 or ccs_id = 1 or ccs_id = 9)"); ?>
                                    <select id="idm_id" name="idm_id" onchange="location = '?a=<?= $a ?>&amp;cid=<?= $cid ?>&amp;iid=' + this.value">
                                        <option></option>
                                        <?php
                                        if (@$aluno_idm) {
                                            $idm = mysqli_query($con, "select * from idioma i,curso_idioma ci where i.idm_id = $aluno_idm and i.idm_id = ci.idm_id and ci.crs_id = $cid and ci_status = 1 order by idm_nome");
                                        } else {
                                            $idm = mysqli_query($con, "select * from idioma i,curso_idioma ci where i.idm_id = ci.idm_id and ci.crs_id = $cid and ci_status = 1 order by idm_nome");
                                        }

//ini ver se ha esta inscrito em algum idioma
                                        /*$_SESSION["opt"] = "";
                                        for ($x = 1; $x <= 6; $x++) {//6 idiomas
                                            $idm_id = $x;
                                                $c1 = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso where cad_id = $cad_id and crs_id = $cid and idm_id = $idm_id and cp_id in ($last_cp_id,0) and cc_parcela = 1 and (ccs_id = 0 or ccs_id = 1 or ccs_id = 9)"));
                                            if ($c1) {//ja esta inscrito
                                                $_SESSION["opt"] = "off";
                                            }
                                        }*/
//fim ver se ha esta inscrito em algum idioma
                                        
                                        while ($idm_lista = mysqli_fetch_array($idm)) {
                                            
                                            foreach ($idm_lista as $campo => $valor) {
                                                $$campo = stripslashes($valor);
                                            }
                                            if ($cid == 1) {//se CIV
                                                $sql = "select * from cadastro_curso where cad_id = $cad_id and crs_id = $cid and idm_id = $idm_id and cp_id in ($last_cp_id,0) and cc_parcela = 1 and (ccs_id = 0 or ccs_id = 1 or ccs_id = 9 or ccs_id = 11)";
                                                $cc = mysqli_fetch_array(mysqli_query($con, $sql));
                                                //echo $sql;
                                            } else {//se EPLE/EPLO
                                                if($cid == 3 || $cid == 6){
                                                    $cc = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso where cad_id = $cad_id and crs_id in (3,6) and idm_id = $idm_id and cp_id in ($last_cp_id,0)"));
                                                } else {
                                                    $cc = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso where cad_id = $cad_id and crs_id = $cid and idm_id = $idm_id and cp_id in ($last_cp_id,0)"));
                                                }
                                            }
                                            $podefazercurso = $candidato->podeFazerCurso($cid,$idm_id);										
                                            $disabled = "";                                            
                                            if ($podefazercurso["bloqueado"]) { 
                                                    $disabled = "disabled";                                                    
                                                }  
                                            if ($cc['idm_id'] != $iid || in_array($candidato->getCodOM() , ['000109', '049403'] ) ) {
                                                
                                                 
                                            ?>
                                                <option value="<?= $idm_id ?>" <?php if ($iid == $idm_id) { echo "selected"; } echo $disabled ?>><?= $idm_nome ?></option>
                                            
                                            <?php
                                            } else {
                                                echo "<script>alert('Você já possui inscrição neste idioma.'); setTimeout(function(){ location='index.php?a=3' }, 5000);</script>"; 
                                            }
                                        }
                                    ?>
                                    </select>
                                </label>
                            </li>
                                    <?php
                                    if ($iid) {//ini selecionou idioma                                        
                                        if ( $cid == 2 || $cid == 3 || $cid == 4 || $cid == 5 || $cid == 6 ) {//ini se for eple/eplo
                                            $respostaCandidato = $candidato->podeFazerCurso($cid,$iid);   
                                            
                                            if ( ! $respostaCandidato["podefazer"] ){
                                                 $msg = $respostaCandidato["msg"];
                                                 $msg = str_replace([chr(13), chr(10) ] , "@" , $msg ); // Trata quebra de linha do PHP
                                                 $msg = str_replace("@" , "" , $msg );                                                 
                                                 echo "<script>alert('Atenção!','". $msg ."',function(){ location='index.php?a=3' });</script>"; 
                                                 exit;
                                            }
                                            ?>
                                    <li class="li1"><label class="w20pc">Seus índices:<br/><br/><br/><br/></label>
                                        <label class="w70pc">
                                            <?php
                                            $login = $_SESSION["login"]; //recupera id usuario da sessao gerada ao efetuar login
                                     
                                            //consulta dgp para saber nivel do usuario
                                            $eplecl = 0;
                                            $epleee = 0;
                                            $eploca = 0;
                                            $eploeo = 0;
                                            
                                            $idioma_aluno_int = htmlspecialchars(intval($_GET['iid']));
                                            
                                            if(isset($idioma_aluno_int) && is_int($idioma_aluno_int)){ $idioma_aluno = $idioma_aluno_int; }
                                            
                                            $sqlNomeIdm = mysqli_query($con, "select * from idioma WHERE idm_id = $idioma_aluno_int");
                                            $resultadoNome = mysqli_fetch_assoc($sqlNomeIdm);
                                            
                                           $dado = $candidato->getIPL($idioma_aluno_int);                                           
                                         
                                            $eplecl = $dado["NIVEL_COMPR_LEITORA"];
                                            $epleee = $dado["NIVEL_EXPR_ESCRITA"];
                                            $eploca = $dado["NIVEL_COMPR_AUDITIVA"];

                                            if ($dado["NIVEL_EXPR_ORAL"] == null) {
                                                $eploeo = 0;
                                            } else {
                                                $eploeo = $dado["NIVEL_EXPR_ORAL"];
                                            }

                                            if ($dado["NIVEL_EXPR_ESCRITA"] == null) {
                                                $epleee = 0;
                                            } else {
                                                $epleee = $dado["NIVEL_EXPR_ESCRITA"];
                                            }
                     
                                            ?>		
                                            <label class="w20pc">EPLO/CA:</label><label class="w70pc"><?= $eploca ?> <span style="color:#666">(EPLO/CA é pré-requisito para o EPLO/EO)</span></label>
                                            <label class="w20pc">EPLO/EO:</label><label class="w70pc"><?= $eploeo ?> <span style="color:#666">(EPLO/EO somente para o nível acima)</span></label>
                                            <label class="w20pc">EPLE/CL:</label><label class="w70pc"><?= $eplecl ?> <span style="color:#666">(EPLE/CL é pré-requisito para o EPLE/EE)</span> </label>
                                            <label class="w20pc">EPLE/EE:</label><label class="w70pc"><?= $epleee ?></label>
                                    </li>
                                    <?php
                                    
                                    switch ($cid) {//ini variaveis
                                        case 2:
                                            $nivel = $eploca;
                                            $c_sigla = "EPLO/CA";
                                            break;
                                        case 3:
                                            $nivel = $eploca;
                                            $niveleo = $eploeo;
                                            $c_sigla = "EPLO/EO";
                                            break;
                                        case 4:
                                            $nivel = $eplecl;
                                            $c_sigla = "EPLE/CL";
                                            break;
                                        case 5://se eplo/eo: seleciona o nivel de eplo/ca
                                            $nivel = $eplecl;
                                            $c_sigla = "EPLE/EE";
                                            break;
                                        case 6://se eplo/eo: seleciona o nivel de eplo/ca
                                            $nivel = $eploca;
                                            $niveleo = $eploeo;
                                            $c_sigla = "EPLE/EO";
                                            break;
                                    }
									
									
                                   ?>
                                    <li class="li2"><label class="w20pc">Nível:</label><label class="w70pc">
                                            <select id="nivel" name="nivel" onchange="change(this, 'loc'); if (getElementById('nivel').options[getElementById('nivel').selectedIndex].value === '') {
                                                    getElementById('submit').style.display = 'none';
                                                }">
                                                <option value=""></option>
                                                <?php 
                                                    //for ($x = $x_ini; $x <= $x_fim; $x++) { 
													
                                                    foreach ($candidato->getNiveis($cid,$iid ) as $x ) {														
													   $id_nivel = (int) trim($x);
													   if ( ! in_array( $id_nivel , $respostaCandidato['niveis_autorizados']) ) continue;		
														
                                                ?>
                                                    <option value="<?=  $id_nivel ?>"><?= $id_nivel == Candidato::MULTINIVEL ? 'Multinível' : $id_nivel ?></option>
                                                    
                        <?php } ?>
                                            </select></label>
                                    </li>
                                    <li class="li1" id="loc" style="display:none"><label class="w20pc"><?php if ($cid == 3 || $cid == 6){ ?>Guarnição de Exame: <?php } else { ?>Local: <?php } ?></label><label class="w70pc">
                                            <select id="local" name="cl_id" onchange="change(this, 'submit')" style="width:470px">
                                                <option value=""></option>
                                    <?php                          
                                    $respostaCandidato = $candidato->podeFazerCurso($cid,$iid, 0);                                    
                                    $wh = "";
                                    $selected = "";
                                    if ( ! $respostaCandidato['podealteraromse'] ){
                                        $wh = " and om.om_id = '".$candidato->getOMSE($cid)."'";
                                        $selected = "selected";                                        
                                    }
                                    if ( ! $candidato->ehAluno() ){
                                        $wh .= " and flagapenasparalunos = 'N' ";
                                    }
                                    $strSQL = "select * 
                                                 from curso_local cl, om,rm 
                                                where cl.om_id = om.om_id 
                                                  and om.rm_id = rm.rm_id 
                                                  and crs_id = {$cid} 
                                                  and ativo = 1 {$wh}  
                                                order by rm.rm_id,om.om_nome";
                                    var_dump($strSQL);              
                                    $cl = mysqli_query($con, $strSQL);
                                    while ($lista_cl = mysqli_fetch_array($cl)) {
                                        foreach ($lista_cl as $campo => $valor) {
                                            $$campo = stripslashes($valor);
                                        }
                                        
                                        
                                        ?>
                                                    <option value="<?= $cl_id ?>"  ><?= $rm_sigla . " - " . $om_nome . " (" . $om_sigla . ") - " . $om_municipio . " - " . $om_uf ?></option>
                        <?php } ?>
                                            </select></label>	
                                    </li>
                                <?php
                            } else {//se civ
                                echo "<input type='hidden' id='nivel' name='nivel' value='0'/>";
                            }//fim se eple/eplo/civ
                            
                            if($cid == 3){
                                echo "<span style='color:red'>A critério do Centro de Idiomas do Exército (CIdEx), os candidatos inscritos no nível 1, nos idiomas INGLÊS e/ou ESPANHOL no exame oral, poderão ter seus(s) exame(s) agendado(s) para o Colégio Militar da respectiva cidade ou cidade mais próxima da OMSE escolhida.</span>";
                            }
                            if($cid == 6){
                                echo "<span style='color:red'>A critério do Centro de Idiomas do Exército (CIdEx), o candidato inscrito no nível 1, nos idiomas INGLÊS e/ou ESPANHOL no exame oral presencial, poderá ter seu exame agendado para o CIdEx ou para o Colégio Militar(CMRJ).</span>";
                            }
                            ?>
                                    <li class="li2" id="submit" style="display:<?= $display ?>;text-align:right;width:586px"><input type="button" id="realizar-inscricao" class="botao_style" style="width:150px" value="Realizar inscrição" crsNome="<?= $crs_nome ?>" idmNome="<?= $resultadoNome['idm_nome'] ?>" formName="crs<?= $crs_id ?>" onclick="return confirmarForm(this)"  /></li>
                                
                            </form>
                            <?php
                        }//fim selecionou idioma
                    }//fim se curso existir
                }//fim lista cursos abertos
                ?>
                <li>
                    <span>
                       Candidato do Rio de Janeiro - poderá ter seu exame agendado para o CIdEx (inglês e/ou espanhol). <br>
                       Para o CMRJ (apenas inglês) ou para o CMVM (inglês e/ou espanhol).
                    </span>
                </li>
            </ul>
            
            <?php }//fim novos cursos ?>

            <?php
            if ($a == 31) {//ini atualizar local
                if (@$_POST["cc_id"]) {//ini cc
                    $x_ini = 0;
                    $x_fim = 0;
                    $niveltroca = 0;
                    $crs_aluno = 0;
                    $idioma_aluno = 0;
                    $login = $_SESSION["login"]; //recupera id usuario da sessao gerada ao efetuar login
                    //consulta dgp para saber nivel do usuario
                    $eplecl = 0;
                    $epleee = 0;
                    $eploca = 0;
                    $eploeo = 0;

                    var_dump("SELECT * FROM curso c,cadastro_curso cc,idioma i,cadastro ca WHERE c.crs_id = cc.crs_id and cc.idm_id = i.idm_id and cc.cc_id = $cc_id and ca.cad_id = cc.cad_id");
                    $cc = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM curso c,cadastro_curso cc,idioma i,cadastro ca WHERE c.crs_id = cc.crs_id and cc.idm_id = i.idm_id and cc.cc_id = $cc_id and ca.cad_id = cc.cad_id"));
//                        var_dump($cc);exit;
                    if (!empty($cc)) {
                        foreach ($cc as $campo => $valor) {
                            $$campo = stripslashes($valor);
                        }
                        $cl_ok = $cl_id; //cl_ok = local gravado da prova
                        $crs_aluno = $crs_id;
                        $idioma_aluno = $idm_id;
                        
                        
                        $login_aluno = $cad_login;
                    } else {
                        echo "<script>location='?id=100</script>";
                    }
                    $candidato = Candidato::getCandidato();
                    ?>	
                <ul id="lista_aluno">
                    <form method="post">
                        <input type="hidden" name="token" value="<?= $tok ?>"/>
                        <input type="hidden" name="acao" value="update_local"/>
                        <input type="hidden" name="cc_id" value="<?= $cc_id ?>"/>
                        <input type="hidden" name="a" value="<?= $back ?>"/>
                        <li class="tit"><label class="w20pc">Código</label><label class="w70pc"><b>Exame</b></label></li>
                                    <?php
                                    $cc = mysqli_fetch_array(mysqli_query($con, "select * from curso c,cadastro_curso cc,idioma i where c.crs_id = cc.crs_id and cc.idm_id = i.idm_id and cc.cc_id = $cc_id"));
                                    if (!empty($cc)) {
                                        foreach ($cc as $campo => $valor) {
                                            $$campo = stripslashes($valor);
                                        }
                                        $cl_ok = $cl_id; //cl_ok = local gravado da prova
                                    } else {
                                        echo "<script>location='?id=100</script>";
                                    }
                                    ?>
                        <li class="li1"><label class="w20pc"><?= $crs_cod ?></label><label class="w70pc"><?= $crs_nome ?></label></li>	
                        <li class="li2"><label class="w20pc">Idioma:</label><label class="w40pc"><?= $idm_nome ?> - Nível </label>
                            <select name="nivel" onchange="getElementById('submit').style.display = 'block'">
                                    <?php 
                                            foreach ($candidato->getNiveis($crs_aluno,$idioma_aluno ) as $x ){
                                            ?>
                                        <option value="<?= $x ?>" <?php
                                        if ($x == $nivel_id) {
                                            echo "selected";
                                        }
                                        ?>><?= $x == Candidato::MULTINIVEL ? 'Multinível' : $x ?></option>
                                    <?php } ?>
                                </select>
                            </label>
                        </li>                        
                        <li class="li1"><label class="w20pc">Local:</label><label class="w70pc">
                                <select id="local" name="cl_id" onchange="getElementById('submit').style.display = 'block'" style="width:470px">
            <?php
             $wh = "";             
             //$candidato = Candidato::getCandidato();
             $respostaCandidato = $candidato->podeFazerCurso($crs_aluno,$idm_id, 0);  
             $ipl = $candidato->getIPL($idm_id);
             $eploca = $ipl['NIVEL_COMPR_AUDITIVA'];
             $eploeo = $ipl['NIVEL_EXPR_ORAL'];
             $eplecl = $ipl['NIVEL_COMPR_LEITORA'];
             $epleee = $ipl['NIVEL_EXPR_ESCRITA'];
             
             if ( ! $respostaCandidato['podealteraromse'] ){
                $wh = " and om.om_id = '".$candidato->getOMSE($crs_aluno)."'";             
             }
             if ( ! $candidato->ehAluno() ){
               $wh .= " and flagapenasparalunos = 'N' ";
             }
                                    
            $cl = mysqli_query($con, "select * from curso_local cl, om,rm where cl.om_id = om.om_id and om.rm_id = rm.rm_id and crs_id = $crs_id and ativo = 1 {$wh}  order by rm.rm_id,om.om_nome");
            while ($lista_cl = mysqli_fetch_array($cl)) {
                foreach ($lista_cl as $campo => $valor) {
                    $$campo = stripslashes($valor);
                }
                ?>
                                        <option value="<?= $cl_id ?>" <?php if ($cl_id == $cl_ok) {
                    echo "selected";
                } ?>><?= $rm_sigla . " - " . $om_nome . " (" . $om_sigla . ") - " . $om_municipio . " - " . $om_uf ?></option>
                <?php } ?>
                                </select></label>	
                        </li>
                        <li class="li2" id="submit" style="text-align:right"><input type="submit" class="botao_style" style="width:150px" value="Atualizar Dados"/></li>
                    </form>
                </ul>
                <div id="ctdal_titulo">Meus Níveis</div> 

                <ul id="lista_aluno">
                    <li class="tit"><label class="w20pc">EXAME</label><label class="w70pc"><b>Nível</b></label></li>
                    <li><label class="w20pc">EPLO/CA:</label><label class="w70pc"><?= $eploca ?> <span style="color:#666">(EPLO/CA é pré-requisito para o EPLO/EO)</span></label></li>
                    <li><label class="w20pc">EPLO/EO:</label><label class="w70pc"><?= $eploeo ?> <span style="color:#666">(EPLO/EO somente para o nível acima)</span></label></li>
                    <li><label class="w20pc">EPLE/CL:</label><label class="w70pc"><?= $eplecl ?> <span style="color:#666">(EPLE/CL é pré-requisito para o EPLE/EE)</span> </label></li>
                    <li><label class="w20pc">EPLE/EE:</label><label class="w70pc"><?= $epleee ?></label></li>

                    </br>
                </ul>

                            <?php
                        } else {
                            echo "<script>location='?a=1'</script>";
                        }//fim cc
                    }//fim atualizar local 
                    ?>

                    <?php
                    if ($a == 4) {//meus pagamento
                        $candidato = Candidato::getCandidato();
                        if ( ! $candidato->ehAluno() ){
                     ?>

                          <script>alert('Srs. candidatos,\n\ninformamos que a compensação do pagamento da GRU NÃO é automática.\n\nFavor aguardar 3 dias úteis, após o período de inscrição, para visualizar a atualização do status de seu pagamento.\n\n<b style="color: red">OBS</b>: NÃO HAVERÁ DEVOLUÇÃO DE PAGAMENTOS REALIZADOS DE FORMA INCORRETA OU FORA DO VENCIMENTO (item 4 da <?=$_SESSION["PORTARIA"] ?>)\n\n');</script>
                    <?php 
                        }
                    ?>      
            <ul id="lista_aluno">
                <li class="tit"><label class="w20pc" style="width:150px">Código</label><label class="w70pc" style="width:300px"><b>Exame</b></label><label class="w20pc"><b>Ações</b></label></li>
        <?php   		
        $cc = mysqli_query($con, "select * from curso c,cadastro_curso cc,idioma i,pagamento pgt,curso_periodo cp where pgt.cc_id = cc.cc_id and c.crs_id = cc.crs_id and cc.idm_id = i.idm_id and cc.cad_id = $cad_id and cp.cp_id = cc.cp_id order by cc.cc_id desc");
        $i = 0;
        while ($cc_lista = mysqli_fetch_array($cc)) {//ini lista cursos do aluno
            foreach ($cc_lista as $campo => $valor) {
                $$campo = stripslashes($valor);
            }
            $idioma_nivel = ($crs_id == 1) ? $idm_nome . " - Parcela: " . $cc_parcela : $idm_nome . " Nível " . $nivel_id; //idioma_nivel se crs_id = 1
            $i++;
            ?>
                    <li class="<?= linecolor($i) ?>">
                        <label class="w20pc" style="width:150px"><?= $crs_cod ?> <?php if ($crs_id != 1) {
                echo $cp_nome;
            } ?></label>
                        <label class="w70pc" style="width:347px"><?= $idioma_nivel ?></label>
                    <?php if ( $hoje <= $data_local_exame ) {//ini visuliza troca de omse/comprovante ?>
                            <label class="w10pc" style="width:14px">
                    <?php if ( $hoje <= $crs_dtlocal) {//ini se hoje for menor que limite ?>
                                    <form method="post" action="?a=31">
                                        <input type="hidden" name="token" value="<?= $tok ?>"/>
                                        <input type="hidden" name="cc_id" value="<?= $cc_id ?>"/>
                                        <input type="hidden" name="back" value="4"/>
                                        <input type="image" src="imagens/icon_a_edit.png" title="Editar Nível/OMSE" align="absmiddle" style=""/>
                                    </form>
                <?php }//fim se hoje for menor que limite  ?>
                            </label>
                            <label class="w10pc" style="width:14px">
                                <form method="post" action="print.php" target="print">
                                    <input type="hidden" name="token" value="<?= $tok ?>"/>
                                    <input type="hidden" name="cc_id" value="<?= $cc_id ?>"/>
                                    <input type="image" src="imagens/icon_a_print.png" title="Imprimir Ficha de Inscrição" align="absmiddle"/>
                                </form>	
                            </label>
            <?php }//fim visuliza troca de omse/comprovante  ?>
                    </li>
                <?php } ?>
            </ul>
                <?php
                    ?>
                  
            <?php //} ?>
            </ul>
            <!-- fim civ -->
            <p>
                Motivos para seu pagamento não ser reconhecido:<br/>
                1. Prazo inferior a 3 dias para compensação;<br/>
                2. Os dados da GRU não foram preenchidos corretamente no momento do pagamento:<br/>
                a) o campo "NÚMERO DE REFERÊNCIA";<br/>
                b) o campo "CÓDIGO DE RECOLHIMENTO";<br/>
                c) o campo CPF.<br/>
                OBS: Candidatos compreendidos no item 2 <b style="color:red">NÃO</b> poderão realizar o exame, de acordo com o item 4 da <?= $_SESSION['PORTARIA'] ?>.<br/>
                <br/>
    <?php }//fim a = 4  ?>

    <?php
    if ($a == 5) {//alterar senha
        ?>
            <form method="post">
                <input type="hidden" name="token" value="<?= $tok ?>"/>
                <input type="hidden" name="acao" value="update_pass"/>
                <ul id="lista_aluno">
                    <li class="l11"><label class="w20pc">Senha Atual</label><label class="w70pc"><input type="password" name="pass0" style="width:100px" maxlength="8"/></label></li>
                    <li class="li2"><label class="w20pc">Nova senha</label><label class="w70pc"><input type="password" name="pass1" style="width:100px" maxlength="8"/></label></li>
                    <li class="li1"><label class="w20pc">Confirme</label><label class="w70pc"><input type="password" name="pass2" style="width:100px" maxlength="8"/></label></li>
                    <li class="li2 alignr"><input class="botao_style" style="width:100px" type="submit" value="Atualizar"/></li>
                </ul>
            </form>
    <?php }?>
    <?php
    if ($a == 6) {//alterar senha
        $cad_id = $_SESSION['cad_id'];
        $cc = mysqli_query($con, "SELECT *, (SELECT MAX(cc_parcela) from cadastro_curso WHERE cad_id =$cad_id) as ultima_parcela
            FROM cadastro c
            INNER JOIN cadastro_curso cc ON c.cad_id = cc.cad_id
            WHERE c.cad_id = $cad_id
            AND cc.crs_id = 1 
            AND cc.cc_parcela = (SELECT MAX(cc_parcela) from cadastro_curso WHERE cad_id =$cad_id)
            AND DATEDIFF(DATE(cc.cc_vencimento), DATE(NOW())) < 30
            GROUP BY c.cad_id");
        
        if ($cc->num_rows == 1){
            
            $dadoUsuario = mysqli_fetch_assoc($cc);
            $dadosRenovacao = mysqli_fetch_assoc(mysqli_query($con, "select * from escola e left join idioma i on i.idm_id = e.idm_id WHERE e.idm_id =".$dadoUsuario['idm_id'])); 
            
    ?>
            <form method="post" id="form1" action="index.php?a=6&acao=confirmacao_renovacao">
                <input type="hidden" name="token" value="<?= $tok ?>"/>
                <input type="hidden" name="acao" value="confirmacao_renovacao"/>
                <input type="hidden" name="cad_id" value="<?= $cad_id ?>"/>
                <input type="hidden" name="idm_id" value="<?= $dadosRenovacao['idm_id'] ?>"/>
                <input type="hidden" name="parcela" value="<?= $dadoUsuario['ultima_parcela'] ?>"/>
                <p>Confirmação da renovação do CIV - <?php echo $dadosRenovacao['idm_nome']; ?> - <?php echo $dadosRenovacao['esc_nome']; ?></p>
                <p style="color: red; font-weight: bold">Observações importantes:</p>
                <p>1) Se optar por continuar no curso, o Sistema gerará, automaticamente, 12 
                    GRUs para possibilitar o pagamento e a continuidade do acesso ao Portal da <?php echo $dadosRenovacao['esc_nome']; ?>. </p>
                <p>2) Se optar por encerrar o curso, o Portal de Educação dará como finalizada sua matrícula 
                    (o encerramento acontecerá após a data de término) e se desejar voltar posteriormente, 
                    deverá realizar nova inscrição.</p>
                <p>3) Caso não escolha nenhuma das opções, até o dia <?php echo $dadosRenovacao['data_termino_renovacao']; ?> o Portal irá finalizar sua 
                    matrícula automaticamente e se desejar retornar posteriormente, deverá realizar nova inscrição.</p>
                <ul id="lista_aluno">
                    <li class="li2 alignr"><input class="botao_style" style="width:150px" type="submit" value="Confirmar Renovação"/></li>
                </ul>
            </form>
    <?php } }//fim a = 5  ?>
    <?php
    if ($a == 7) {//alterar senha
        $queryCancelar = mysqli_query($con, "SELECT * 
            FROM curso crs
            INNER JOIN cadastro_curso cc ON crs.crs_id = cc.crs_id
            WHERE cc.cad_id = $cad_id
            AND crs.crs_id = 1 
            AND cc.cc_parcela = 1 
            AND DATE(NOW()) between crs.crs_dtinicio AND crs.crs_dttermino
            GROUP BY cc.cad_id");
        
        if ($queryCancelar->num_rows == 1){
            
            $dadosCurso = mysqli_fetch_assoc($queryCancelar);
    ?>
            <form method="post" id="form1" action="index.php?a=7&acao=cancelar_inscricao">
                <input type="hidden" name="token" value="<?= $tok ?>"/>
                <input type="hidden" name="acao" value="cancelar_inscricao"/>
                <input type="hidden" name="cad_id" value="<?= $cad_id ?>"/>
                <input type="hidden" name="cc_id" value="<?= $dadosCurso['cc_id'] ?>"/>
                <input type="hidden" name="idm_id" value="<?= $dadosCurso['idm_id'] ?>"/>
                <input type="hidden" name="parcela" value="<?= $dadosCurso['cc_parcela'] ?>"/>
                <p>Confirmação de Cancelamento de matrícula do CIV</p>
                <p>Após a confirmação, sua solicitação será encaminhada para a Secretaria do CIV para deferimento do seu pedido. </p>
                <ul id="lista_aluno">
                    <li class="li2 alignr"><input class="botao_style" style="width:150px" type="submit" value="Confirmar Cancelamento"/></li>
                </ul>
            </form>
    <?php } }//fim a = 5  ?>
    <?php
    if ($a == 8) {//alterar senha
        $sqlIpls = "SELECT PROF.PES_IDENTIFICADOR_COD,IDI.DESCRICAO as IDIOMA, IDI.codigo, MAX(PROF.nivel_compr_auditiva) as COMP_AUD,MAX(PROF.nivel_expr_oral) as EXP_ORAL,MAX(PROF.nivel_compr_leitora) as COMP_LEIT,MAX(PROF.nivel_expr_escrita) as EXP_ESC 
                    FROM RH_QUADRO.INDICE_PROF_LINGUISTICA PROF 
                    INNER JOIN RH_QUADRO.IDIOMA_IPL IDI ON PROF.codigo_idioma = IDI.codigo 
                    WHERE PROF.PES_IDENTIFICADOR_COD = '$login' GROUP BY PROF.PES_IDENTIFICADOR_COD,IDI.DESCRICAO,IDI.codigo";

        $dados = ociparse($oci_connect,$sqlIpls);
        oci_close($oci_connect);
        ociexecute($dados);        
        ?>                
            <table width="580">
                <tr  style="background: #D4D4D4;    border-bottom: 3px solid #888;    font-weight: bold;">
                    <td class="tit" colspan="4" >Idioma</td>
                    <td class="tit w100px">EPLO/CA:</td>
                    <td class="tit w50px">EPLO/EO:</td>
                    <td class="tit w150px">EPLE/CL:</td>
                    <td class="tit w150px">EPLE/EE:</td>
                    <td class="tit w150px">CERTIFICADO</td>
                </tr>
        <?php
        $i = 0;
        while ($dado = oci_fetch_assoc($dados)) {
        $i++;
        $li = ($i % 2 == 0) ? "#e0dddd" : "#ffffff";
        ?>
                <tr>
                    <td class="" colspan="4" style="background-color:<?= $li ?> "><?= $dado["IDIOMA"] ?></td>
                    <td class=" w100px" style="background-color:<?= $li ?>; text-align: center;"><?= ($dado["COMP_AUD"] != 0) ? $dado["COMP_AUD"] : "-"; ?></td>
                    <td class=" w100px" style="background-color:<?= $li ?>; text-align: center; "><?= ($dado["EXP_ORAL"] != 0) ? $dado["EXP_ORAL"] : "-"; ?></td>
                    <td class=" w100px" style="background-color:<?= $li ?>; text-align: center; "><?= ($dado["COMP_LEIT"] != 0) ? $dado["COMP_LEIT"] : "-"; ?></td>
                    <td class=" w100px" style="background-color:<?= $li ?>; text-align: center; "><?= ($dado["EXP_ESC"] != 0) ? $dado["EXP_ESC"] : "-"; ?></td>
                    <td class=" w100px" style="background-color:<?= $li ?>; text-align: center; ">
                        <form action="certificado/gerar_certificado/gerador.php" method="POST" target="_blank" id="gerar-certificado">
                            <input type="hidden" value="<?= $dado["CODIGO"] ?>" id="codigo" name="codigo" />
                            <input type="hidden" value="<?= $login ?>" id="idt" name="idt" />
                            <input type="hidden" value="<?= $tok ?>" id="tok" name="tok" />
                            <input type="submit" value="EMITIR CERTIFICADO" style="color: #00420C; font-weight: bold;" />
                        </form>
                    </td>
                </tr>  
            
    <?php } ?> </table> <?php }//fim a = 5  ?>

    <?php
} else {//se aluno nao estiver logado
    echo "<script>location='?id=100'</script>";
}
?>
</div>