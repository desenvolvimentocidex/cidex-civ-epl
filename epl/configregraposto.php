<?php
  if ( !empty($_GET["apagar"]) && $_GET["apagar"] == 'S' ){
      $idregra = htmlspecialchars($_GET["idregra"]);
      execSQL('delete from regracurso where idregracurso = '.$idregra);
      header('Location: index.php?id=21');
  }
  if( empty($_GET['tc']) ){ 
    $strSQL = "SELECT idtipocandidato, descricao
                 FROM tipocandidato"; 
    $tipocandidato = getSQLMySQL($strSQL);
    ?>
    <table id="table_tipocandidato" width="100%">
        <caption>Tipo de candidatos</caption>
        <thead>
            <tr>
              <th class="tit"> Descrição</th>              
              <th class="tit"> Posto/graduação englobados</th> 
            </tr>
        </thead>
        <tbody>
            <?php 
             foreach ($tipocandidato as $tc){
                 echo "<tr> ";
                 echo '   <td> <a href="javascript:void(0)" class="link_tipocandidato"> ' .$tc['descricao'].'</a></td> ';
                 $strSQL = "SELECT sigla, descricao
                            FROM postograduacao
                           where idtipocandidato =  {$tc['idtipocandidato']}
                          order by codigodgp";
                 $postos = getSQLMySQL($strSQL);
                 echo "<td class='colunapostograduacao'> <ul class='listapostograduacao'>";
                 $i = 0;
                 foreach ($postos as $posto) {                     
                     if ($i == 5) {
                         echo "</ul>  <ul class='listapostograduacao'>";
                         $i = 0;
                     }
                     $i += 1;
                     echo "<li> {$posto['descricao']} </li>";
                     
                 } 
                 ?>
                    </ul> 
                   </td> 
                 </tr>
                 <tr class='detalhestipocandidato'>
                     <td colspan="2" style="border: none;">
                         <table id="tabela_regrascurso">
                             <caption> Regras por tipo de candidatos </caption>
                             <thead>
                                 <tr>
                                     <td>Nome</td>
                                     <td>Valor do GRU</td>
                                     <td>Idiomas essenciais</td>
                                     <!-- <td>Forma de necessidade dos idiomas</td> -->
                                     <td>OMSE é OM do candidato?</td>
                                     <td>Configuração de nível mínimo exigido</td>
                                     <td>Ações</td>
                                 </tr>
                             </thead>
                             <tbody>
                                 <?php
                                   $strSQL = 
                                       "SELECT regracurso.idregracurso, 
                                               regracurso.nomeregra, 
                                               case when regracurso.tipoobrigacaoidm = 'U' then 'Ao menos 1' 
                                                     when regracurso.tipoobrigacaoidm = 'T' then 'Todos'
                                                                         END  tipoobrigacaoidm, 
                                               case when regracurso.flagommilitaromse = 'S' then 'Sim' ELSE 'Não' END ommilitaromse, 
                                               case when regracurso.valor = 0 then 'Isento' else format(valor ,2) end as valor,
                                               (SELECT GROUP_CONCAT( idm_nome)
                                                  FROM idioma
                                                 WHERE FIND_IN_SET(idm_id, regracurso.array_idm_obrigatorios ) )idm_obrigatorios,
                                               regranivelminimo.nomeregranivelminimo  
                                          FROM regracurso
                                          LEFT JOIN regranivelminimo
                                            ON (regranivelminimo.idregranivelminimo = regracurso.idregranivelminimo  )
                                         WHERE regracurso.flaginativa = 'N'
                                           and regracurso.idregracurso in (SELECT regrapostograduacao.idregracurso
                                                                             FROM regrapostograduacao
                                                                            INNER JOIN postograduacao		                
                                                                               ON (regrapostograduacao.idpostograduacao = postograduacao.idpostograduacao ) 
                                                                            where idtipocandidato =  {$tc['idtipocandidato']} )";    
                                   $regras = getSQLMySQL($strSQL);
                                   foreach ($regras as $regra ){
                                       echo '<tr>';
                                       echo "   <td> ".$regra['nomeregra']."</td>";
                                       echo "   <td>  ". $regra['valor'] ." </td>";
                                       echo "   <td> ".$regra['idm_obrigatorios']."</td>";
                                       //echo "   <td> ".$regra['tipoobrigacaoidm']."</td>";
                                       echo "   <td> ".$regra['ommilitaromse']."</td>";
                                       echo "   <td> ".$regra['nomeregranivelminimo']."</td>";
                                       echo "   <td> ";
                                       echo "       <input type='hidden' name='idregracurso' value='{$regra['idregracurso']}'> ";
                                       echo "       <input type='hidden' name='idtipocandidato' value='{$tc['idtipocandidato']}'> ";
                                       echo "       <a href='javascript:void(0)' title='Alterar' class='btnalterar' > <img src='./imagens/i_editar.png' width='20' heigth='20'> </a> ";
                                       echo "       <a href='javascript:void(0)' title='Excluir' class='btnexcluir'> <img src='./imagens/i_del.png' width='20' heigth='20'> </a> ";
                                       echo '   </td>';
                                       echo '</tr>';
                                   }
                                 ?>
                             </tbody>
                             <tfoot>
                             <button class="btn_fecharregras">Fechar</button>
                             <a href="javascript:void(0)" class="btn_criar">Nova regra</a>
                             </tfoot>
                         </table>
                     </td>
                 </tr>
            <?php     
             }
            ?>
        </tbody>

    </table>
               
<?php 
 }
?>
<div id="div_formeditregra">
    
</div>
<div id="aguarde">
    <img src="imagens/loading.gif">
</div>
<script>
    $('document').ready(function(){
        $(".detalhestipocandidato").hide();
        $("#div_formeditregra").hide();
        $(".link_tipocandidato").click(function(){
            $(".detalhestipocandidato").hide();
            $(this).parent().parent().next().toggle();
        });
        $(".btn_fecharregras").click(function(){
            $(".detalhestipocandidato").hide();
        });
        
        $(".btnalterar").click(function(){
            var idregra = $(this).parent().find("input[name='idregracurso']").val();
            var idtipocandidato = $(this).parent().find("input[name='idtipocandidato']").val();
            var url = location.href.substring(0,location.href.lastIndexOf('/'))+"/altconfigpostograduacao.php?tc="+idtipocandidato+"&regra="+idregra;           
            $.get(url,function(resposta){
                $("#div_formeditregra").html(resposta).show();
            });
        });
       $(".btn_criar").click(function(){
           var idtipocandidato = $(this).parent().find("input[name='idtipocandidato']").val();
           var url = location.href.substring(0,location.href.lastIndexOf('/'))+"/altconfigpostograduacao.php?tc="+idtipocandidato+"&regra=-1";           
            $.get(url,function(resposta){
                $("#div_formeditregra").html(resposta).show();
            });
       });
       $(".btnexcluir").click(function(){
           if (  window.confirm("Deseja realmente apagar essa regra?") ){
              location = location.href+"&apagar=S&idregra="+$(this).parent().find("input[name='idregracurso']").val();
              
           }
       });
       
       
        
    });
</script>    