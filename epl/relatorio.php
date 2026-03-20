<?php
// perfil do usuário logado
$perfil = $_SESSION["perfil"];
$omseacesso = $_SESSION["omse"];
$postograduacaoliberados = $_SESSION["postograduacaoliberados"];
$idiomasliberados = $_SESSION["idiomasliberados"];

// Consultar posto e graduacao que são permitidos por escola, nesse momento somente cadetes da Aman
$sqlCadetes = "SELECT * FROM RH_QUADRO.POSTO_GRAD_ESPEC p WHERE p.codigo in ($postograduacaoliberados)";
$dadospost = ociparse($oci_connect, $sqlCadetes);
ociexecute($dadospost);

// Consultar escola do usuário ECEME, ESPCEX, ETC
$sqlOm = "SELECT * FROM RH_QUADRO.ORGAO O WHERE O.CODIGO in ('$omseacesso')";
$dadosom = ociparse($oci_connect, $sqlOm);
ociexecute($dadosom);

// Somente busca os idiomes Ingles e Espanhol
$idm = mysqli_query($con, "select * from idioma i where idm_id in ($idiomasliberados)");

//ultimo período de cadastro
$cpatual = mysqli_fetch_array(mysqli_query($con, "select * from curso_periodo order by cp_id desc"));
    $last_cp_id_data_inicio = $cpatual["data_ini_inc_centralizada"];
    $last_cp_id_data_fim = $cpatual["data_fim_inc_centralizada"];
    
    ?>




    <?php

if ($perfil == 'secretaria') {
    $hoje = date("Y-m-d");
    ?>

    <script>

        var tamanhoPagina = 20;
        var pagina = 0;
        var dadosbusca = "";
        var om = $('#idom').val();
        var postograduacao = $('#idpostograducao').val();
        var ididioma = $('#ididioma').val();
        var alterado = 0;

        function wait(ms) {
            var start = new Date().getTime();
            var end = start;
            while (end < start + ms) {
                end = new Date().getTime();
            }
        }

        function validarnota(nota)
        {

            // valida se nota está entre 10
            if (nota < 0 || nota > 10)
            {
                alert("A nota deve ser entre 0 e 10 e possuir o formato decimal 0.00 !")
                return false;
            }

            if (hasDecimalPlace(nota, 3)) {
                alert("A nota deve possuir o formato decimal 0.00 !")
                return false;
            }
            return true;
        }

        function hasDecimalPlace(value, x) {
            var pointIndex = value.indexOf('.');
            return  pointIndex == 1 && pointIndex < value.length - x;
        }
        
        function inscreveremlote(tabela)
        {
                var cells = [];
                $("#tbrel > tbody > tr").length
        }
        
        function inscrever(rowindex) {

            $("#blocker").show();
            var cad_id = ($('table tbody tr:eq(' + rowindex + ') td:eq(0)')).text();
            var identidade = ($('table tbody tr:eq(' + rowindex + ') td:eq(1)')).text();
            //var status = $('table tbody tr').find('#status')[rowindex].value;    
            
            idioma = $('#idioma').val();
            idca = $('#idca').val();
            idcl = $('#idcl').val();
            idee = $('#idee').val();
            cpid = $('#cpid').val();
            
                // grava os dados no banco de dados
                $.getJSON('pages/ajax.php', {opcao: 'inscrevermilitar', cad_id: cad_id, identidade: identidade, idioma: idioma, idca: idca, idcl: idcl,idee: idee,cpid:cpid}, function () {
                })
                        .done(function (mensagem) {
                            // atualiza o registro com os valores e desabilita edicao
                            $('table tbody tr:eq(' + rowindex + ')').css('background-color', 'lightgreen');
                          //  $('table tbody tr:eq(' + rowindex + ')').find('#editar').text("Editado");
                            //alterado = 1;
                            $("#blocker").hide();
                            alert(mensagem);
                        })
                        .fail(function (mensagem) {
                            $("#blocker").hide();
                            alert(mensagem);
                        })
         }

        function paginar() {

            $("#blocker").show();
            if (alterado == 1)
            {
                $.getJSON('pages/ajax.php', {opcao: 'buscarmilitar', om: om, pg: pg}, function (dados) {
                    dadosbusca = dados;
                    alterado = 0;
                })
            }

            $('table > tbody > tr').remove();
            var tbody = $('table > tbody');
            j = 0;
            $("#quantidade").html(dadosbusca.length.toString());

            for (var i = pagina * tamanhoPagina; i < dadosbusca.length && i < (pagina + 1) * tamanhoPagina; i++) {
                tbody.append(
                        $('<tr ' + (dadosbusca[i]['editado'] == '1' ? 'class="colorir"' : '') + ' >')
                        .append($('<td>').append(dadosbusca[i]['cad_id']))
                        .append($('<td>').append(dadosbusca[i]['cad_login']))
                        .append($('<td >').append(dadosbusca[i]['cad_mail']))
                        .append($('<td>').append(dadosbusca[i]['cad_nome']))
                        .append($('<td>').append(dadosbusca[i]['cad_nomeguerra']))
                        .append($('<td>').append(dadosbusca[i]['cad_postograd']))
                        .append($('<td>').append('<select id="status" style="width: 55px; onchange="compareceu(' + j + ', this )" class="" name="inscrever">\n\
                                    <option value=S>Sim</option>\n\
                                    <option value=N>Não</option></select>'))
                        //.append($('<td  style="text-align: center;">').append('<input style="text-align: center;" type="number" min="0" max="10" ' + 'value=' + dadosbusca[i]['notavalor'] + ' id="nota"  name="nota" class="decimal span1" maxlength="4" required />'))
                        //onkeypress="return somenteNumeroDecimal(this,event);"))
                        // .append($('<td>').append(dadosbusca[i]['justificativa']))
                        .append($('<td class="acao">').append('<button id="inscrever" class="btnacao" style="width:100px;"  onclick="inscrever(' + j + ')" type="button">Inscrever</button>'))
                        )
                j++;
            }
            $('#numeracao').text('Página ' + (pagina + 1) + ' de ' + Math.ceil(dadosbusca.length / tamanhoPagina));
            $("#blocker").hide();
        }
        
//        function exportaExcel(){
//             var informacoes = new Array();
//             
//             $.getJSON('pages/ajax.php', {opcao: 'buscarmilitar', om: om, pg: pg}, function (dados) {
//                   $(dados).each(){
//                      if (! $(this).hasClass('acao') ){ 
//                        informacoes.push($(this).val());
//                      }        
//                   } 
//                });
//                  .done(function(){
//                     var data = "data:text/csv;charset=utf-8," +informacoes.join('\n');
//                     const link = document.createElement('a');
//                     link.setAttribute('href', data);
//                     link.setAttribute('download', nome_arquivo);
//                     link.click();
//                });
//        
//        }


        $(document).ready(function () {

            $("#idom").prop("disabled", true);
            $("#idpostograduacao").prop("disabled", true);

            $('.div-ajax-carregamento-pagina').fadeOut('fast');
            $('#idom').change(function (e) {
                $('#idpostograduacao').val(0);
                $('#dadosbusca').hide();
            })

            $('#idioma').change(function (e) {
                $('#idca').val(0);
                $('#idcl').val(0);
                $('#idee').val(0);
                $('#idpostograduacao').val(0);
                $('#idom').val(0);

            })

            $('#congelar').click(function (e) {
               if ($("#congelar").html() == "Descongelar dados")
                {
                    $("#congelar").html('Congelar');
                    $("#idioma").prop("disabled", false);
                    $("#idca").prop("disabled", false);
                    $("#idcl").prop("disabled", false);
                    $("#idee").prop("disabled", false);
                    $("#idom").prop("disabled", true);
                    $("#idpostograduacao").prop("disabled", true);
                    $("#idom").val(0);
                    $("#idpostograduacao").val(0);
                    $('#divmilitar').hide();

                } else
                {

                    idioma = $('#idioma').val();
                    idca = $('#idca').val();
                    idcl = $('#idcl').val();
                    idee = $('#idee').val();
                    
                    if (idioma == 0) {
                        alert('Favor selecionar o idioma!');
                        $('#idioma').focus();
                        return false;
                    }

                    if (idca == 0 && idcl == 0 && idee == 0)
                    {
                        alert('Favor indicar ao menos um tipo de teste para o candidato!');
                        $('#idca').focus();
                        return false;
                    }

                    $("#idioma").prop("disabled", true);
                    $("#idca").prop("disabled", true);
                    $("#idcl").prop("disabled", true);
                    $("#idee").prop("disabled", true);
                    
                    $("#idom").prop("disabled", false);
                    $("#idpostograduacao").prop("disabled", false);
                    $("#congelar").html('Descongelar dados');
                    $('#divmilitar').hide();

                }

            })


            $('#buscar').click(function (e) {
                idioma = $('#idioma').val();
                idca = $('#idca').val();
                idcl = $('#idcl').val();
                idee = $('#idee').val();
                om = $('#idom').val();
                cpid = $('#cpid').val();
                pg = $('#idpostograduacao').val();
                // pg = $('#idpostograduacao option:selected').text()
                pagina = 0;
                // verifica se todos os itens foram preenchidos para a consulta
                if (om == 0 || pg == 0)
                {
                    $('#dadosbusca').hide();
                    alert("Favor selecionar todas as opções antes de buscar.");
                } else
                {
                    $("#blocker").show();
                    $('#divmilitar').hide();
                    wait(2000);
                    $.getJSON('pages/ajax.php', {opcao: 'buscarmilitar', om: om, pg: pg, cpid: cpid, idioma: idioma, idca: idca, idcl: idcl, idee: idee}, function (dados) {

                        if (dados.length > 0) {
                            dadosbusca = dados;
                            paginar();
                            ajustarBotoes();
                            $('#divmilitar').show();
                            $("#blocker").hide();
                        } else {
                            $('#divmilitar').hide();
                            $("#blocker").hide();
                        }
                    })

                            .fail(function () {
                                alert('Erro ao buscar dados! ');
                            })
                }
            })

            function ajustarBotoes() {
                $('#proximo').prop('disabled', dadosbusca.length <= tamanhoPagina || pagina >= Math.ceil(dadosbusca.length / tamanhoPagina) - 1);
                $('#anterior').prop('disabled', dadosbusca.length <= tamanhoPagina || pagina == 0);
            }

            $(function () {
                $('#proximo').click(function () {
                    if (pagina < dadosbusca.length / tamanhoPagina - 1) {
                        pagina++;
                        paginar();
                        ajustarBotoes();
                    }
                });
                $('#anterior').click(function () {
                    if (pagina > 0) {
                        pagina--;
                        paginar();
                        ajustarBotoes();
                    }
                });
            });
        
        function sh(id) {
            document.getElementById(id).style.display = 'inline';
        }

        function nega(off, motivo) {
            if (off) {
                document.getElementById(off).checked = false;
            }
            alert(motivo);
        }
        
        })
    </script>

    <!--            <div class="div-ajax-carregamento-pagina">Carregando...</div>-->
    <h3>Relatório de Inscritos <?= 'Período  - ' . $last_cp_nome ?>   </h3>
    <h3>Período de Inscrição<?= ' De  - ' . $last_cp_id_data_inicio . ' Até ' . $last_cp_id_data_fim ?>   </h3>
    <input type="hidden" id="cpid" name="cpid" value="<?= $last_cp_id ?>" autocomplete="off"/>

    <div class="main">
        <div id="blocker" style="display: none;">
            <div>
                <img src='../../academico/img/carregando.gif'>
            </div>
        </div>
        <div class="main-inner">
            <div class="container">
                <div class="row">
                    <div class="span6">	      		
                        <div id="target-1" class="widget">
                            <div class="widget-content">
                                <fieldset>
                                    <div class="control-group">	   
                                        <label class="control-label" for="idioma">Idioma</label>
                                        <div class="controls" >
                                            <select id="idioma" class="form-control span8" name="idioma">
                                                <option value=0> --- Selecionar Idioma --- </option>
                                                <?php
                                                while ($crs_lista = mysqli_fetch_array($idm)) {
                                                    foreach ($crs_lista as $campo => $valor) {
                                                        $$campo = stripslashes($valor);
                                                    }
                                                    ?>
                                                    <option value=<?= $idm_id ?>><?= $idm_nome ?></option>
                                                <?php }
                                                ?>                                      </select>     
                                        </div>
                                        <table>
                                            <thead>
                                                <tr class="li1">
                                                    <th colspan="1">EPLO</th>
                                                    <th colspan="2">EPLE</th>
                                                </tr>
                                                <tr class="li1">
                                                    <td>
                                                        <h3>CA</h3>
                                                        <select id="idca" class="form-control span8" name="idca">
                                                            <option value=0> --- Selecionar Nível CA --- </option>
                                                            <option value=1> Nível 1</option>
                                                            <option value=2>Nível 2</option>
                                                            <option value=3>Nível 3</option>
                                                            <option value=4>Nível 4</option>
                                                        </select>
                                                    </td>
                                                    <td><h3>CL</h3>
                                                        <select id="idcl" class="form-control span8" name="idcl">
                                                            <option value=0> --- Selecionar Nível CL --- </option>
                                                            <option value=1> Nível 1</option>
                                                            <option value=2>Nível 2</option>
                                                            <option value=3>Nível 3</option>
                                                            <option value=4>Nível 4</option>
                                                        </select></td>
                                                    <td><h3>EE</h3>
                                                        <select id="idee" class="form-control span8" name="idee">
                                                            <option value=0> --- Selecionar Nível EE --- </option>
                                                            <option value=1> Nível 1</option>
                                                            <option value=2>Nível 2</option>
                                                            <option value=3>Nível 3</option>
                                                            <option value=4>Nível 4</option>
                                                        </select></td>
                                                </tr>
                                            </thead>
                                        </table>
                                        <div class="form-actions">
                                            <button id="congelar" name="congelar"  type="button" class="btnacao" style="width:200px;">Congelar dados</button>
                                            <input type="hidden" name="congelado" value="0" autocomplete="off"/>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="widget-header">
                                <i class="icon-user"></i>
                                <h3>Seleção dos alunos</h3>
                            </div>
                            <div class="widget-content">
                                <div class="control-group">	
                                    <form class="form-horizontal" method="post">
                                        <input type="hidden" name="u_login" value="<?= $_SESSION["login"] ?>" autocomplete="off"/>
                                        <input type="hidden" name="syncTokenPost" value="<?= $syncToken ?>" autocomplete="off"/>
                                        <fieldset>
                                            <div class="control-group">	   
                                                <label class="control-label" for="om">OM</label>
                                                <div class="controls" >
                                                    <select id="idom" class="form-control span8" name="idom">
                                                        <option value=0> --- Selecionar OM --- </option>
                                                        <?php
                                                        while ($dadoom = oci_fetch_assoc($dadosom)) {
                                                            $codom = $dadoom["CODIGO"];
                                                            $siglaom = $dadoom["SIGLA"];
                                                            ?>  
                                                            <option value=<?= $codom ?>><?= $siglaom ?></option>
                                                        <?php } ?>
                                                    </select>     
                                                </div>
                                            </div>
                                            <div id="selectpostograduacao" class="control-group">
                                                <label class="control-label" for="postograduacao">Posto/Graduação</label>
                                                <div class="controls" >
                                                    <select id="idpostograduacao" class="form-control span8" name="idpostograduacao">
                                                        <option value=0> --- Selecionar Posto/Graduação --- </option>
                                                        <?php
                                                        while ($dado = oci_fetch_assoc($dadospost)) {
                                                            $codpost = $dado["CODIGO"];
                                                            $descricaopost = $dado["SIGLA"];
                                                            ?>
                                                            <option value=<?= $codpost ?>><?= $descricaopost ?></option>
                                                        <?php } ?>


                                                    </select>
                                                </div>

                                            </div>
                                            <div class="form-actions">
                                                <button id="buscar" name="Buscar"  type="button" class="btnacao" style="width:200px;">Listar Candidatos</button>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                            <br>
                            <div id="divmilitar" style="display: none" class="widget-content">
                                <span>Quantidade de registros</span>
                                <span id="quantidade"></span>
<!--                                <button id="cadastroemlote" name="cadastroemlote"  type="button" class="btnacao" onclick="inscreveremlote('tbrel');" style="width:200px;">Inscrever Todos</button>-->
                                <br>
                                <a href="javascipt:void(0);" class="btn" id="btnExportarExcel" title="Exportar Excel"> <img src="imagens/excel.png"> </a> 

                                <table id="tbrel" style="background-color: #F3F3F3" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="bold">ID</th>
                                            <th>Identidade</th>
                                            <th>email</th>
                                            <th>Nome</th>
                                            <th>Nome de Guerra</th>
                                            <th>Posto Graduação</th>
                                            <th>Inscrever</th>
                                            <th  class="acao">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2" align="center">Nenhum dado ainda...</td>
                                        </tr>
                                    </tbody>
                           </table>
                                <div style="text-align: center">
                                    <button id="anterior" type="button" class="btnacao" disabled>&lsaquo; Anterior</button>
                                    <span id="numeracao" ></span>
                                    <button id="proximo" type="button" class="btnacao" disabled>Próximo &rsaquo;</button>
                                </div>
                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    header('location:index.php');
}
?>