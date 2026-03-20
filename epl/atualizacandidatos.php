<?php

$whrOMSE = ($_SESSION["perfil"] == PERFIL_USUARIO_SECRETARIA ) ? "om_id in ({$_SESSION["omse"]})" : '1=1';
$omse = mysqli_fetch_all(mysqli_query($con, "select om_id, om_sigla, om_nome, codigodgp 
                                                       from om
                                                      where {$whrOMSE} 
                                                      order by om_sigla " ), MYSQLI_ASSOC); 
$posto_graduacao = mysqli_fetch_all(mysqli_query($con, "select idpostograduacao, descricao, codigodgp
                                                          from postograduacao
                                                         order by descricao" ), MYSQLI_ASSOC); 
?>
<head>
    <title> Atualização de Dados </title>
    <style>
        #tabelaResultado {
            width: 75%;
        }
        #tabelaResultado caption {
            font-size: large;
            font-weight: 700;
            text-decoration: underline;
        }
        .erro{
           background-color: red; 
        }
        .nao_cadastrado{
           background-color: orange; 
        }  
        .cadastro_ok{
           background-color: greenyellow; 
        }
    </style>
</head>
<body>
    <h1>Atualização de dados SicaPex ==> CIDEx</h1>
      <div id="blocker" style="display: none;">
            <div>
                <img src='../../academico/img/carregando.gif'>
            </div>
        </div>
    <form method="POST" action="atualizar_pg.php">
        <label for='omse'>OMSE: </label>
            <select name="omse" >
               <?php 
               foreach ($omse as $om) {
                   $selected = $om['om_id'] == $_SESSION["omse"] ? ' selected ' : '';  
                  echo "<option value='{$om['codigodgp']}' {$selected} >".$om['om_sigla'].' - '.$om['om_nome']."</option>";    
               }

               ?> 
            </select>
            <div class='div_muiltselecao div_posto' >
                   <span>Posto/graduação </span>
                   <?php 
                       foreach ($posto_graduacao as $pg) {
                          $postograduacaoliberados = $_SESSION["postograduacaoliberados"]; 
                          $selected = in_array($pg['idpostograduacao'], explode(',', $postograduacaoliberados) ) ? ' checked ' : ''; 
                          echo '<div>  <input type="checkbox" name="postogrduacao[]" value="'.$pg['codigodgp'].'"'.$selected.'/>  <label for="postogrduacao[]">'.$pg['descricao'].' </label> </div>' ; 
                       }

                  ?> 
               </div>
            </div>    
            <input type="button" value="Atualizar" name="btnGravar" class="btn btn-success" />
    </form>
    <div id="div_resposta" style="display: none;">
        <table id="tabelaResultado" class="tabela">
            <caption>Resultado</caption>
            <thead>
                <tr>
                    <th>Identidade</th>
                    <th>Nome</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</body>
<script>
    $("input[name='btnGravar']").click(function(){
        let posto = $("input[name='postogrduacao[]']:checked").map(function(){
                        return $(this).val();
                    }).get();
        $.ajax({
            url: 'pages/atualizar_pg.php',
            type: 'POST',
            data:{'omse': $('select[name="omse"]').val(),
                  'postogrduacao' : posto  },
            beforeSend: function () {
                        $("#div_resposta").hide();
                        $("#tabelaResultado tbody tr").remove();
                        $("#blocker").show();
                    },  
            success: function (resposta) {
                        let resposta_json =JSON.parse(resposta.trim());                        
                        if (resposta_json.length == 0){
                            alert('Sem dados para mostrar');
                        }
                        $(resposta_json).map(function(index, elemento){
                             let classe = 'erro';
                             if (elemento.codstatus == '-2'){
                                classe = 'nao_cadastrado';  
                             } 
                             if (elemento.codstatus == '1'){
                                classe = 'cadastro_ok';  
                             } 
                             $("#tabelaResultado tbody").append("<tr class='"+classe+"'> <td>"+elemento.identidade+"</td> <td>"+elemento.nome+"</td> <td>"+elemento.status+"</td> </tr>");
                        });
                        
                    }   
        }).done(function(){
            $("#div_resposta").show();
            $("#blocker").hide();
        });
    });
</script>    