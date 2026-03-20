<?php
 require "../system/system.php";
 $strSQL = "SELECT idioma.idm_id, idioma.idm_nome
                 FROM idioma";
 $idiomas = getSQLMySQL($strSQL);
 $strSQL = "SELECT crs_id,crs_nome
                 FROM curso
                WHERE crs_status = 1 and crs_id > 1";
 $cursos = getSQLMySQL($strSQL);
    
 if (! empty($_POST) ){
     $post = $_POST;        
     try {         
     

     foreach ($cursos as $curso){         
       $cursosisiomaspadrao[$curso['crs_id']] = array_column($idiomas, 'idm_id');
     }
     
     $idregracurso = $post['idregracurso'];
     $idomse = "null";
     $flagommilitaromse = "'N'";
     if ( !empty($post['idomse'])  ){
         $idomse = $post['idomse'];
         $flagommilitaromse = "'S'";
     }
     
     $tipoobrigacaoidm = "NULL";
     if ( ! empty($post['tipoobrigacaoidm']) ){
          $tipoobrigacaoidm = "'{$post['tipoobrigacaoidm']}'";     
     }
     
     $array_idm_obrigatorios = "null";
     if (! empty($post['array_idm_obrigatorios']) ){
            $array_idm_obrigatorios = implode(',', $post['array_idm_obrigatorios']);
            $array_idm_obrigatorios = "'{$array_idm_obrigatorios}'";
     }
     $valor = 0;
     if ( isset($post['valor']) && empty( ! $post['valor']  ) ){
         $valor = $post['valor'];
     }
     $qtdmax = $post['qtdmax'];
     if ( empty($qtdmax) ){
         $qtdmax = 0;
     }
     $idregranivelminimo = "null";
     if ( ! empty($post['nivelminimo']) ){
            $idregranivelminimo = $post['nivelminimo'];
     }
    
     
     if ( $idregracurso  == -1  ){ // criar
         $strSQL = "INSERT INTO regracurso(nomeregra, flaginativa, qtdmax, 
                                           array_idm_obrigatorios, tipoobrigacaoidm, 
                                           flagommilitaromse, valor, idregranivelminimo, idomse)
                                    VALUES('{$post['nomeregra']}','N',{$qtdmax},{$array_idm_obrigatorios},
                                           {$tipoobrigacaoidm},  {$flagommilitaromse},{$valor}, {$idregranivelminimo}, {$idomse}  ) ";
        
        // var_dump($strSQL);
         //die;
        execSQL($strSQL);
        $idregracurso = getSQLMySQL("SELECT LAST_INSERT_ID() as id");
        $idregracurso = $idregracurso[0]['id'];        
     }else{ // Atualizar
        $strSQL = "UPDATE regracurso 
                      set nomeregra = '{$post['nomeregra']}', 
                          qtdmax  = {$post['qtdmax']},
                          array_idm_obrigatorios = {$array_idm_obrigatorios},
                          tipoobrigacaoidm  = {$tipoobrigacaoidm},
                          flagommilitaromse = {$flagommilitaromse},
                          valor =  {$valor},
                          idregranivelminimo =  {$idregranivelminimo}, 
                          idomse = {$idomse}    
                    where idregracurso = {$idregracurso}";
                 
        execSQL($strSQL);            
     }     
     
     
     $strSQL = "delete from regrapostograduacao where idregracurso = {$idregracurso} ";
     execSQL($strSQL);
     if ( empty($post['idiomacurso']) ){
         $idiomacurso = [];
     } else {
         $idiomacurso = $post['idiomacurso'];
     }
     foreach ($post['posto'] as $posto){
          
         foreach ($cursosisiomaspadrao as $idcurso => $idiomas) {                     
            foreach ($idiomas as $idm ){
               $bloqueado = 'N';  
               if ( ! isset($idiomacurso[$idcurso]) ||  ! in_array($idm, $idiomacurso[$idcurso] ) ){
                 $bloqueado = 'S';  
               }
               
               $strSQL = "INSERT INTO regrapostograduacao(idregracurso, idpostograduacao, ididioma, idcurso, bloqueado)
                                                VALUES({$idregracurso},{$posto},{$idm}, {$idcurso},'{$bloqueado}' )";
               execSQL($strSQL);
            }
            
            
         }  
         
     }
     
      $strSQL = "delete from regracursonivel where idregracurso = {$idregracurso} ";
      execSQL($strSQL);
      foreach ( array_keys($post['nivel']) as $curso ){
          $niveis = implode(',', $post['nivel'][$curso]);
          $strSQL = "INSERT INTO regracursonivel(idregracurso, idcurso, nivel)
                                                VALUES({$idregracurso},{$curso},'{$niveis }' )";
          execSQL($strSQL);
      }
      echo "OK";
     } catch (Exception $exc) {
         echo $exc->getTraceAsString();
     }
     
     return ;
 } else {
    $idregra = empty($_GET['regra']) ? -1 : $_GET['regra'];
    $idtipocandidato = $_GET['tc'];

    $strSQL = "SELECT idregracurso, nomeregra, flaginativa, qtdmax, array_idm_obrigatorios,
                      tipoobrigacaoidm, flagommilitaromse, valor, idregranivelminimo, idomse
                 FROM regracurso
                WHERE regracurso.idregracurso = ".$idregra;
    $regra = getSQLMySQL($strSQL);

    $regra = $regra[0];

    $strSQL = "SELECT idregranivelminimo, nomeregranivelminimo, formaexigencianivelmin
                 FROM regranivelminimo";
    $regranivelminimo = getSQLMySQL($strSQL);

    $strSQL = "SELECT idpostograduacao, descricao, sigla
                 FROM postograduacao
                WHERE idtipocandidato = ".$idtipocandidato;
    $postograduacao = getSQLMySQL($strSQL);
    
    $strSQL = "SELECT regrapostograduacao.idpostograduacao,
                      regrapostograduacao.ididioma,
                      regrapostograduacao.idcurso,
                      regrapostograduacao.bloqueado
                 FROM regrapostograduacao
                WHERE regrapostograduacao.idregracurso =".$idregra;
    $regrapostograduacao = getSQLMySQL($strSQL);
    $strSQL = "SELECT om_id,  concat(om_sigla,' - ', om_nome) AS nome_om
                 FROM om order by 2 ";
    $omse = getSQLMySQL($strSQL);
    
      $strSQL = " select nivel, idcurso 
                    from regracursonivel
                   where idregracurso = ".$idregra;
       $regraniveis = getSQLMySQL($strSQL);    
       $regranivel = [];
       foreach ($regraniveis as $item){         
          $regranivel[$item['idcurso']] = explode(',', $item['nivel']); 
       }                     
       
 }
 
?>

<div id="div_altconfiggraduacao">
    <form name="frmaltconfiggraduacao" id="frmaltconfiggraduacao" method="POST">
        <div id="div_regras" style="margin: 8px; padding: 3px;">
            
                <input type="hidden" name="idregracurso" value="<?= $idregra ?>">
                <input type="hidden" name="idtipocandidato" value="<?= $idtipocandidato ?>">
                    <label for="nomeregra">Nome </label>
                    <input type="text" name="nomeregra" placeholder="Nome" maxlength="50" required="required" value="<?= $regra['nomeregra'] ?>">
                    
                    <input type="checkbox" name="isento" value="S" <?= $regra['valor'] == 0? "checked" : "" ?>>
                    <label for="isento"  style="width: auto;" >Isento? </label>            
                    <label for="valor"  style="width: auto;">Valor </label>
                    <input type="number"  name="valor" value="<?= $regra['valor'] ?>">
                  <label for="qtdmax">Qtd. max de idiomas </label>
                  <input type="number" name="qtdmax" value="<?= $regra['qtdmax'] ?>">                        
                <div id="div_omse">                   
                   <label for="idomse">OMSE</label>
                   <select name="idomse">
                       <option value="">Livre escolha do candidato</option>
                        <?php 
                            foreach ($omse as $om){
                                $checked = "";
                                if ( $om["om_id"] == $regra["idomse"] ){
                                    $checked = "selected";
                                }
                                echo "<option value='{$om["om_id"]}' {$checked} > {$om['nome_om']} </option>";
                            }
                            ?>
                   </select>
                </div>   
                <div id="div_nivelmin">
                   <label for="nivelminimo">Regra de nível mínimo </label>
                   <select name="nivelminimo">
                       <option value="">...</option>
                        <?php 
                            foreach ($regranivelminimo as $nivel){
                                $checked = "";
                                if ( $nivel["idregranivelminimo"] == $regra["idregranivelminimo"] ){
                                    $checked = "selected";
                                }
                                echo "<option value='{$nivel['idregranivelminimo']}' {$checked} > {$nivel['nomeregranivelminimo']} </option>";
                            }
                            ?>
                   </select>  
                   <a href="javascript:void(0)" title="Nova regra nível mínimo" id="novo_nivelmin" ><img src="imagens/i_new.png"></a>
                   <a href="javascript:void(0)" title="Alterar regra nível mínimo" id="edita_nivelmin" ><img src="imagens/i_editar.png"></a>
                </div>
        </div>    
        
        <div id="div_curso_idioma" class="region" style="width: 95%;">
                <legend>Cursos/idiomas permitidos</legend>
                <div>
                    <?php                          
                                 
                      foreach ($cursos as $curso) {
                                      
                                
                        echo '<div class="curso"> <div> <span class="nomecurso" > '.$curso['crs_nome']." </span> </div>";
                        foreach ($idiomas as $idm) {
                            $checked = "checked";
                            foreach ($regrapostograduacao as $item){
                              if ( $item['idcurso'] == $curso['crs_id'] &&  
                                   $item['ididioma'] == $idm['idm_id']  ){
                                  if ( $item['bloqueado'] == 'S' ){ 
                                      $checked = ""; 
                                  }                                  
                                  break;
                              }    
                            }
                            $nome = "idiomacurso[{$curso['crs_id']}][]";
                            echo '<div class="grupocheckbox" > <input type="checkbox" name="'.$nome.'" value="'.$idm['idm_id'].'" '.$checked.'>
                              <label for="'.$nome.'">'.$idm['idm_nome'].'</label> </div>';
                        }
                        echo "<div class='niveispermitidos' style='padding: 1em;margin-top: -2%;' > <h4> Níveis permitidos </h4>  ";
                        for($nivel = 1; $nivel <= 3; $nivel ++){
                            $checado = '';
                            if (key_exists( $curso['crs_id'], $regranivel ) ) {
                             $checado = in_array($nivel, $regranivel[$curso['crs_id']]) ? 'checked' : ''; 
                            }
                            echo "<input type='checkbox' name='nivel[{$curso['crs_id']}][]' value='{$nivel}'  {$checado} /> {$nivel}" ; 
                            
                        }
                        
                        if (key_exists( $curso['crs_id'], $regranivel ) ) {
                            $checado = in_array(Candidato::MULTINIVEL, $regranivel[$curso['crs_id']]) ? 'checked' : ''; 
                        }
                        echo "<input type='checkbox' name='nivel[{$curso['crs_id']}][]' value='".Candidato::MULTINIVEL."'  {$checado} /> Multinível" ; 
                        echo "</div></div>";
                      }
                    ?>
               </div>
            </div>
        <input type="checkbox" name="idmobrigatorio" value="S" <?= ! empty($regra['array_idm_obrigatorios']) ? "checked" : "" ?>>
        <label for="idmobrigatorio">Usa idiomas essenciais? </label>        
        <div style="display: flex;">          
            <div id="div_idomasobrigatorios" class="region">                
                <legend>Idiomas essenciais</legend>
                <div>
                    <?php 
                    $idmobrigatorios = explode(',', $regra["array_idm_obrigatorios"] );
                    foreach ($idiomas as $idm){
                        $checked = "";
                        if ( in_array($idm["idm_id"], $idmobrigatorios) ){
                            $checked = "checked";
                        }
                        echo '<div class="grupocheckbox" > <input type="checkbox" name="array_idm_obrigatorios[]" value="'.$idm["idm_id"].'" '.$checked.'>
                              <label for="array_idm_obrigatorios[]">'.$idm["idm_nome"].'</label>  </div>';
                    }
                    ?>
               </div>     
               <label for="tipoobrigacaoidm" style="width: 100%;margin-top: 8px;margin-left: 8px;">Forma de obrigação dos idiomas </label>
               <select name="tipoobrigacaoidm" style="width: 73%; margin-left: 6px;">
                   <option value="U" <?= $regra['tipoobrigacaoidm'] == "U" ? "selected" : "" ?> > Ao menos 1 </option>
                   <option value="T" <?= $regra['tipoobrigacaoidm'] == "T" ? "selected" : "" ?> > Todos </option>
               </select>

            </div>
            <div id="div_postograduacao" class="region">
                <legend>Posto/graduação contemplados</legend>
                <div>
                    <?php 
                      $idpostograduacao = array_unique( array_column($regrapostograduacao, 'idpostograduacao') );                      
                      foreach ($postograduacao as $posto) {
                        $checked = "";
                        if ( in_array($posto["idpostograduacao"], $idpostograduacao) ){
                            $checked = "checked";
                        }
                        echo '<div class="grupocheckbox" > <input type="checkbox" name="posto[]" value="'.$posto["idpostograduacao"].'" '.$checked.'>
                              <label for="idpostograduacao[]">'.$posto["descricao"].'</label> </div>';
                      }
                    ?>
               </div>
            </div>
            
        </div>    
        
        <input type="button" value="Cancelar" name="btncancelar" class=" btncancelar btn_acao_frmaltconfiggraduacao"  id="btncancelar" >
        <input type="button" value="Salvar" name="btngravar" class="btngravar btn_acao_frmaltconfiggraduacao" id="btngravar">
    </form>
</div> 
<div id="div_cad_nivelmin" >
    
</div>


<script>
    function podegravar(){
        if ( $("input[name='nomeregra']").val() == "" ){
           $("input[name='nomeregra']").focus();
           window.alert("Informe o nome da regra");
           return false;
        }
        if ( $("input[name='idmobrigatorio']").is(":checked") ){   
           var qtdchecksdesmarcado = $("#div_idomasobrigatorios").find("input[type='checkbox']:checked").length;
           if ( qtdchecksdesmarcado === 0 ){
              window.alert("Deve ser informado ao menos 1 idioma.");
              return false;
           }
        }
       if ( $("#div_postograduacao").find("input[type='checkbox']:checked").length === 0 ){
          window.alert("Deve ser informado ao menos 1 posto/graduação.");
          $("#div_postograduacao").focus();
          return false;
       } 
      return true;
    }
    
    $("document").ready(function(){
        $("input[name='isento']").change(function(){
            $("input[name='valor']").attr('disabled', $(this).is(":checked"));
            if ($(this).is(":checked")){
                $("input[name='valor']").val(0);
            }
        });
        
        $("input[name='idmobrigatorio']").change(function(){
            $("#div_idomasobrigatorios").attr('disabled', $(this).is(":checked"));
        });
        
        $("input[name='idmobrigatorio']").change(function(){
            $("#div_idomasobrigatorios").find("input, select").attr('disabled', ! $(this).is(":checked"));
            if (! $(this).is(":checked") ){
                $("#div_idomasobrigatorios").find("input").prop('checked', false);
            }
        });
        
         $("input[name='btncancelar']").click(function(){            
            $("#div_formeditregra").hide();
        });
        
        $("#btngravar").click(function(){
            if ( ! podegravar() ){
                return false;
            }
            var dados = $("#frmaltconfiggraduacao").serializeArray();
            $.ajax({
                url:"altconfigpostograduacao.php",
                type:"POST",
                data: dados,
                beforeSend: function (xhr) {
                        $("#aguarde").show(); 
                    },
                success: function (data) {
                        if ( data.toString().trim() == "OK"  ){
                            location.reload();
                        }
                    },
                error: function (jqXHR, textStatus, errorThrown) {
                        console.log("ERRO: "+jqXHR);
                    }    
                    
                
            }).done(function(){
               // $("#aguarde").hide();
            });
        });
        
        $("#novo_nivelmin").click(function(){
            var urlcad = location.href.substr(0,location.href.indexOf("index.php"))+'nivelminimo.php';
            $.ajax({url: urlcad,
                    success: function (resposta) {
                        $("#div_cad_nivelmin").html(resposta).show();
                    }
            });    
        
        });
        
         $("#edita_nivelmin").click(function(){
            var idnivel = $("select[name='nivelminimo'] option:selected").val();
            if ( idnivel == "" ){
                if ( window.confirm("Não existe regra para editar. Deseja criar uma nova?") ){
                   $("#novo_nivelmin").click(); 
                }
                return false;
            }
            var urlcad = location.href.substr(0,location.href.indexOf("index.php"))+'nivelminimo.php?idregranivelminimo='+idnivel;
            $.ajax({url: urlcad,
                    success: function (resposta) {
                        $("#div_cad_nivelmin").html(resposta).show();
                    }
            });    
        
        });
        
         $(document).on("click", "#btngravar_nivelmin", function(){
                if ( ! podegravar() ){
                    return false;
                }
                var dados = $("#frm_regranivelminimo").serializeArray();
                 $.ajax({
                  url:"nivelminimo.php",
                  type:"POST",
                  data: dados,
                  success: function (resposta) {
                          var respostajson = JSON.parse(resposta) ;
                          if ( ! respostajson.erro  ){
                              if ( $("select[name='nivelminimo'] option[value='"+respostajson.idregranivelminimo+"']").length > 0 ){
                                $("select[name='nivelminimo'] option[value='"+respostajson.idregranivelminimo+"']").text(respostajson.nomeregranivelminimo);  
                              } else {
                                 $("select[name='nivelminimo']").append("<option value='"+respostajson.idregranivelminimo+"'> "+respostajson.nomeregranivelminimo+"</option>");                              
                              }
                              $("#div_cad_nivelmin").hide();
                          } else {
                              window.alert(respostajson.msg);
                          }
                      },
                  error: function (jqXHR, textStatus, errorThrown) {
                          console.log("ERRO: "+jqXHR);
                      }    
                });
          });
        
            
    
        $("#div_cad_nivelmin").hide();
        $("input[name='idmobrigatorio']").change();
        $("input[name='nomeregra']").focus();
        $("input[name='isento']").change();
});     
  
</script>    