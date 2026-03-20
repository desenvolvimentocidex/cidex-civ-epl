<?php
// perfil do usuário logado
$perfil = $_SESSION["perfil"];
$omseacesso = $_SESSION["omse"];
$postograduacaoliberados = $_SESSION["postograduacaoliberados"];
$idiomasliberados = $_SESSION["idiomasliberados"];

$strsql = "select concat(char(39), upper(replace(replace(om_sigla,' ',''),'º','')) ,char(39) )   oms,  om_sigla, om_id
            from om 
           where om_id in ({$omseacesso})";
 $omsAcesso = getSQLMySQL($strsql);
 
 
if ($perfil == 'secretaria') {

    $datahora = DateTime::createFromFormat("!Y-m-d", date('Y-m-d') );
    
    
//ultimo período de cadastro
    $cpatual = mysqli_fetch_array(mysqli_query($con, "select * from curso_periodo order by cp_id desc"));
    $last_cp_id_data_inicio =  DateTime::createFromFormat("!Y-m-d",($cpatual["data_ini_inc_centralizada"]));
    $last_cp_id_data_fim = DateTime::createFromFormat("!Y-m-d", $cpatual["data_fim_inc_centralizada"]);
   // var_dump($datahora, $last_cp_id_data_inicio, $last_cp_id_data_fim);

    if ($datahora < $last_cp_id_data_inicio || $datahora > $last_cp_id_data_fim) {
        ?>
        <h3 style="color: red;font: bold">Período de Inscrição Fechado</h3>
        <h3>Cadastro de militares <?= 'Período  - ' . $last_cp_nome ?>   </h3>
        <h3>Período de Inscrição Centralizada<?= ' De ' . $last_cp_id_data_inicio->format('d/m/Y') . ' Até ' . $last_cp_id_data_fim->format('d/m/Y') ?>   </h3>



        <?php
        exit;
    }



// Consultar posto e graduacao que são permitidos por escola, nesse momento somente cadetes da Aman
    $sqlCadetes = "SELECT * FROM RH_QUADRO.POSTO_GRAD_ESPEC p WHERE p.codigo in ($postograduacaoliberados)";
    $dadospost = ociparse($oci_connect, $sqlCadetes);
    ociexecute($dadospost);

// Consultar escola do usuário ECEME, ESPCEX, ETC
    /*$sqlOm = "SELECT * FROM RH_QUADRO.ORGAO O WHERE upper(replace(replace(O.sigla,' ',''),'º','')) in ($omsAcesso)";
   
    $dadosom = ociparse($oci_connect, $sqlOm);
    ociexecute($dadosom);*/

// Somente busca os idiomes Ingles e Espanhol
    $idm = mysqli_query($con, "select * from idioma i where idm_id in ($idiomasliberados)");
    ?>


    <script>

        var tamanhoPagina = 500000;
        var pagina = 0;
        var dadosbusca = "";
        var om = $('#idom').val();
        var postograduacao = $('#idpostograducao').val();
        var ididioma = $('#ididioma').val();
        var alterado = 0;
        var informacoes = new Array();

        function wait(ms) {
            var start = new Date().getTime();
            var end = start;
            while (end < start + ms) {
                end = new Date().getTime();
            }
        }

        function inscreveremlote()
        {
            $('select[name="inscrever"] option:selected[value="S"]').each(function(){
                 $(this).parent().parent().parent().addClass('lote');
                 $(this).parent().parent().parent().find("#inscrever").click();  
                // $(this).parent().parent().parent().removeClass('lote');
            });
            var interval_id = null;
            interval_id = setInterval(function(){                 
                 if ($(".lote").length > 0){
                     $("#blocker").show();
                 } else{
                     $("#blocker").hide();
                     clearInterval(interval_id);
                 }
                
            }, 1000);
            
           /*$("#blocker").show();
            
            var tamanhopagina = $('table >tbody >tr').length;

            // percorre toda a tabela e verifica os militares marcados com sim
            for (var i = 0; i < tamanhopagina; i++) {
                var desejainscrever = ($('table tbody tr:eq(' + i + ')').find('#desejainscrever')).val();

                if (desejainscrever == 'S') {
                    inscrever(i, "lote");
                    setTimeout(() => {  console.log("Inscrevendo!"); }, 2000);
                }
            }
            */
           
        }

        function deletar(rowindex) {

            $("#blocker").show();
            var cad_id = ($('table tbody tr:eq(' + rowindex + ') td:eq(0)')).text();
            var identidade = ($('table tbody tr:eq(' + rowindex + ') td:eq(1)')).text();
            //var status = $('table tbody tr').find('#status')[rowindex].value;    

            idioma = $('#idioma').val();
            idca = $('#idca').val();
            idcl = $('#idcl').val();
            idee = $('#idee').val();
            cpid = $('#cpid').val();
            ideo = $('#ideo').val();

            // grava os dados no banco de dados
            $.getJSON('pages/ajax.php', {opcao: 'deletarinscricoescentralizadas', cad_id: cad_id, identidade: identidade, idioma: idioma, idca: idca, idcl: idcl, idee: idee, cpid: cpid, ideo:ideo}, function () {
            })
                    .done(function (retorno) {
                        // atualiza o registro com os valores e desabilita edicao
                        $('table tbody tr:eq(' + rowindex + ')').css('background-color', '');
                        ($('table tbody tr:eq(' + rowindex + ') td:eq(6)')).text(retorno[0]["inscricoes"]);
                        alterado = 1;
                        $("#blocker").hide();
                        alert(retorno[1]["mensagem"]);
                    })
                    .fail(function (retorno) {
                        alert(retorno[1]["mensagem"]);
                        $("#blocker").hide();
                    })
        }


        function inscrever(rowindex) {

            $("#blocker").show();
            var emlote = $('table tbody tr:eq(' + rowindex + ')').hasClass('lote');
            var cad_id = ($('table tbody tr:eq(' + rowindex + ') td:eq(0)')).text();
            var identidade = ($('table tbody tr:eq(' + rowindex + ') td:eq(1)')).text();
            //var status = $('table tbody tr').find('#status')[rowindex].value;    

            idioma = $('#idioma').val();
            idca = $('#idca').val();
            idcl = $('#idcl').val();
            idee = $('#idee').val();
            cpid = $('#cpid').val();
            id_om = $("#idom").val();
            ideo = $('#ideo').val();

            // grava os dados no banco de dados
            $.getJSON('pages/ajax.php', {opcao: 'inscrevermilitar', cad_id: cad_id, identidade: identidade, 
                                         idioma: idioma, idca: idca, idcl: idcl, idee: idee, 
                                         cpid: cpid, omid: id_om, ideo: ideo}, function () {
            })
                    .done(function (retorno) {
                        // atualiza o registro com os valores e desabilita edicao
                        $('table tbody tr:eq(' + rowindex + ')').css('background-color', 'lightgreen');
                        ($('table tbody tr:eq(' + rowindex + ') td:eq(6)')).text(retorno[0]["inscricoes"]);                        
                        alterado = 1;                       
                        if (! emlote)
                        {
                            $("#blocker").hide();
                            alert(retorno[1]["mensagem"]);
                        } else
                        {
                            $('table tbody tr:eq(' + rowindex + ')').removeClass('lote');
                            return retorno;
                        }
                    })
                    .fail(function (retorno) {
                        $("#blocker").hide();
                        if (! emlote) {
                            alert(retorno[1]["mensagem"]);
                        } else
                        {
                            return retorno;
                        }
                    })
        }

        function exportaExcel() {
            console.log('exportaExcel');
            const quebradelinha = "\r\n";
            let temp = new Array();
            let arquivo = new Array();
           
            temp.push('Identidade');
            temp.push('Nome de guerra');
            temp.push('Email');
            temp.push('Posto');
            temp.push('Inscrições');

            arquivo.push(temp.join(";"));
            arquivo.push(quebradelinha);
            temp = [];
                          
            $(informacoes).each(function () {
                let item = $(this)[0];
                console.log(item);
                temp.push('"'+item.cad_login+'"');
                temp.push('"'+item.cad_nomeguerra+'"');
                temp.push('"'+item.cad_mail+'"');
                temp.push('"'+item.cad_postograd+'"');
                if (item.inscricoes == null ){
                   temp.push(" "); 
                }else{
                  temp.push('"'+item.inscricoes.toString().replaceAll("\n", ' ')+'"');
                }                
                arquivo.push(temp.join(";"));
                temp = [];

            });
            var data = "data:text/csv;charset=utf-8,%EF%BB%BF "+ arquivo.join( quebradelinha );
            const link = document.createElement('a');
            link.setAttribute('href', data);
            link.setAttribute('download', 'lista.csv');
            link.click();
 
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
                        .append($('<td>').append(dadosbusca[i]['inscricoes']))
                        .append($('<td>').append('<select id="desejainscrever" style="width: 55px; class="" name="inscrever">\n\
                                    <option value=S>Sim</option>\n\
                                    <option value=N>Não</option></select>'))
                        //.append($('<td  style="text-align: center;">').append('<input style="text-align: center;" type="number" min="0" max="10" ' + 'value=' + dadosbusca[i]['notavalor'] + ' id="nota"  name="nota" class="decimal span1" maxlength="4" required />'))
                        //onkeypress="return somenteNumeroDecimal(this,event);"))
                        // .append($('<td>').append(dadosbusca[i]['justificativa']))
                        .append($('<td>').append('<button id="inscrever" class="btnacao" style="width:100px;"  onclick="inscrever(' + j + ')" type="button">Inscrever</button>'))
                        .append($('<td>').append('<button id="deletar" class="btnacao" style="width:100px;"  onclick="deletar(' + j + ')" type="button">Remover</button>'))
                        )
                j++;
            }
            $('#numeracao').text('Página ' + (pagina + 1) + ' de ' + Math.ceil(dadosbusca.length / tamanhoPagina));
            $("#blocker").hide();
        }


        $(document).ready(function () {

            $("#idom").prop("disabled", true);
            $("#idpostograduacao").prop("disabled", true);

            $('.div-ajax-carregamento-pagina').fadeOut('fast');
            $('#idom').change(function (e) {
                $('#idpostograduacao').val(0);
                $('#dadosbusca').hide();
            })
            $("#btnExportarExcel").on('click', exportaExcel);

            $('#idioma').change(function (e) {
                $('#idca').val(0);
                $('#idcl').val(0);
                $('#idee').val(0);
                $('#ideo').val(0);
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
                    $("#ideo").prop("disabled", false);
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
                    ideo = $('#ideo').val();

                    if (idioma == 0) {
                        alert('Favor selecionar o idioma!');
                        $('#idioma').focus();
                        return false;
                    }

                    if (idca == 0 && idcl == 0 && idee == 0 && ideo == 0)
                    {
                        alert('Favor indicar ao menos um tipo de teste para o candidato!');
                        $('#idca').focus();
                        return false;
                    }

                    $("#idioma").prop("disabled", true);
                    $("#idca").prop("disabled", true);
                    $("#idcl").prop("disabled", true);
                    $("#idee").prop("disabled", true);
                    $("#ideo").prop("disabled", true);

                    $("#idom").prop("disabled", false);
                    $("#idpostograduacao").prop("disabled", false);
                    $("#congelar").html('Descongelar dados');
                    $('#divmilitar').hide();

                }

            })


            $("#btnExport").click(function (e) {
                var $table = $('<table>');
    // caption
                $table.append('<caption>MyTable</caption>')
    // thead
                        .append('<thead>').children('thead')
                        .append('<tr />').children('tr').append('<th>A</th><th>B</th><th>C</th><th>D</th>');

    //tbody
                var $tbody = $table.append('<tbody />').children('tbody');

    // add row
                $tbody.append('<tr />').children('tr:last')
                        .append("<td>val</td>")
                        .append("<td>val</td>")
                        .append("<td>val</td>")
                        .append("<td>val</td>");

    // add another row
                $tbody.append('<tr />').children('tr:last')
                        .append("<td>val</td>")
                        .append("<td>val</td>")
                        .append("<td>val</td>")
                        .append("<td>val</td>");

                exportTableToCSV($table, 'exportar.csv');
                e.preventDefault();

            })

            function exportTableToCSV($table, filename) {

                var $rows = $table.find('tr:has(td)'),
                        // Temporary delimiter characters unlikely to be typed by keyboard
                        // This is to avoid accidentally splitting the actual contents
                        tmpColDelim = String.fromCharCode(11), // vertical tab character
                        tmpRowDelim = String.fromCharCode(0), // null character

                        // actual delimiter characters for CSV format
                        colDelim = '","',
                        rowDelim = '"\r\n"',
                        // Grab text from table into CSV formatted string
                        csv = '"' + $rows.map(function (i, row) {
                            var $row = $(row),
                                    $cols = $row.find('td');

                            return $cols.map(function (j, col) {
                                var $col = $(col),
                                        text = $col.text();

                                return text.replace(/"/g, '""'); // escape double quotes

                            }).get().join(tmpColDelim);

                        }).get().join(tmpRowDelim)
                        .split(tmpRowDelim).join(rowDelim)
                        .split(tmpColDelim).join(colDelim) + '"',
                        // Data URI
                        csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

                $(this)
                        .attr({
                            'download': filename,
                            'href': csvData,
                            'target': '_blank'
                        });
            }

            $('#buscar').click(function (e) {
                idioma = $('#idioma').val();
                idca = $('#idca').val();
                idcl = $('#idcl').val();
                idee = $('#idee').val();
                ideo = $('#ideo').val();
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
                    id_om = $("#idom").val();
                    wait(2000);
                    $.getJSON('pages/ajax.php', {opcao: 'buscarmilitar', om: om, pg: pg, cpid: cpid, idioma: idioma, idca: idca, idcl: idcl, idee: idee,omid: id_om,ideo: ideo }, function (dados) {

                        informacoes = $(dados);

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
    <h3>Cadastro de militares <?= 'Período  - ' . $last_cp_nome ?>   </h3>
    <h3>Período de Inscrição<?= ' De ' . $last_cp_id_data_inicio->format('d/m/Y') . ' Até ' . $last_cp_id_data_fim->format('d/m/Y') ?>   </h3>
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
                                                    <th colspan="3">EPLE</th>
                                                </tr>
                                                <tr class="li1">
                                                    <td>
                                                        <h3>CA</h3>
                                                        <select id="idca" class="form-control span8" name="idca">
                                                            <option value=0> --- Selecionar Nível CA --- </option>
                                                            <option value=1> Nível 1</option>
                                                            <option value=2>Nível 2</option>
                                                            <option value=3> Nível 3</option>
                                                            <option value=4>Nível 4</option>
                                                        </select>
                                                    </td>
                                                    <td><h3>EO</h3>
                                                        <select id="ideo" class="form-control span8" name="ideo">
                                                            <option value=0> --- Selecionar Nível EO --- </option>
                                                            <option value=1> Nível 1</option>
                                                            <option value=2>Nível 2</option>
                                                            <option value=3> Nível 3</option>
                                                            <option value=4>Nível 4</option>
                                                        </select></td>   
                                                    <td><h3>CL</h3>
                                                        <select id="idcl" class="form-control span8" name="idcl">
                                                            <option value=0> --- Selecionar Nível CL --- </option>
                                                            <option value=1> Nível 1</option>
                                                            <option value=2>Nível 2</option>
                                                            <option value=3> Nível 3</option>
                                                            <option value=4>Nível 4</option>
                                                        </select></td>
                                                    <td><h3>EE</h3>
                                                        <select id="idee" class="form-control span8" name="idee">
                                                            <option value=0> --- Selecionar Nível EE --- </option>
                                                            <option value=1> Nível 1</option>
                                                            <option value=2>Nível 2</option>
                                                            <option value=3> Nível 3</option>
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
                                                        /*while ($dadoom = oci_fetch_assoc($dadosom)) {
                                                            $codom = $dadoom["CODIGO"];
                                                            $siglaom = $dadoom["SIGLA"];
                                                            ?>  
                                                            <option value=<?= $codom ?>><?= $siglaom ?></option>
                                                        <?php } */                                                      
                                                           foreach ($omsAcesso as $om){
                                                               echo "<option value='".$om['om_id']."' >  {$om['om_sigla']} </option>";
                                                           }
                                                        
                                                        
                                                        ?>
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
                                <a href="javascript:void(0);" class="btn" id="btnExportarExcel" title="Exportar para Excel" style="
                                   width: 32px;
                                   height: 32px;
                                   display: inline;
                                   position: relative;
                                   float: right;
                                   margin: 5px;
                                   z-index: 999;"> <img src="imagens/excel.png" style="width: 32px;"> </a> 
                                <br>
                                   <button id="cadastroemlote" name="cadastroemlote"  type="button" class="btnacao" onclick="inscreveremlote('');" style="width:200px;">Inscrever Militares da Página atual com "Sim"</button>
                                <br>
                                <table id="tbrel" style="background-color: #F3F3F3" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="bold">ID</th>
                                            <th>Identidade</th>
                                            <th>email</th>
                                            <th>Nome</th>
                                            <th>Nome de Guerra</th>
                                            <th>Posto Graduação</th>
                                            <th>Inscrições</th>
                                            <th>Inscrever</th>
                                            <th colspan="2">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2" align="center">Nenhum dado ainda...</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- 
                                <div style="text-align: center">
                                    <button id="anterior" type="button" class="btnacao" disabled>&lsaquo; Anterior</button>
                                    <span id="numeracao" ></span>
                                    <button id="proximo" type="button" class="btnacao" disabled>Próximo &rsaquo;</button>
                                </div>
                                -->
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