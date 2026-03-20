<?php
   require '../system/system.php';
   
   if ( !empty($_POST) ){
       try
       {
            $idregranivelminimo = $_POST['idregranivelminimo'];
            $nomeregranivelminimo = $_POST['nomeregranivelminimo'];
            $formaexigencianivelmin = $_POST['formaexigencianivelmin'];

            if ( $idregranivelminimo == -1 ){
                $strSQL = "insert into regranivelminimo(nomeregranivelminimo,formaexigencianivelmin) 
                                                 values( '".$nomeregranivelminimo."', '".$formaexigencianivelmin."')";
                execSQL($strSQL);
                $idregranivelminimo = getSQLMySQL("SELECT LAST_INSERT_ID() as id");
                $idregranivelminimo = $idregranivelminimo[0]['id'];           
            } else {
                $strSQL = "update regranivelminimo 
                              set nomeregranivelminimo ='".$nomeregranivelminimo."', 
                                  formaexigencianivelmin = '".$formaexigencianivelmin."' 
                            where idregranivelminimo = ".$idregranivelminimo;    
                execSQL($strSQL);
            }
            $strSQL = "delete from regranivelminimoidioma
                        where idregranivelminimo = ".$idregranivelminimo;
            execSQL($strSQL);
            $regraidioma = $_POST['regranivelminimoidioma'];
            for( $index = 0; $index < count($regraidioma['idioma']); $index +=1 ){                      
                $strSQL = "insert into regranivelminimoidioma(idregranivelminimo,ididioma, 
                                                              nivelmin_ca, nivelmin_ee, nivelmin_eo, nivelmin_cl)
                                                       values( ".$idregranivelminimo.", ".
                                                                 $regraidioma['idioma'][$index].", ".
                                                                 $regraidioma['nivelminca'][$index].", ".
                                                                 $regraidioma['nivelminee'][$index].", ".
                                                                 $regraidioma['nivelmineo'][$index].", ".    
                                                                 $regraidioma['nivelmincl'][$index].") ";    

                execSQL($strSQL);
            }
            $reposta = ['erro' => false,
                        'idregranivelminimo' => $idregranivelminimo,
                        'nomeregranivelminimo' => $nomeregranivelminimo ];
            echo json_encode($reposta); 
       } catch (Exception $e){
           $reposta = ['erro' => true,
                        'msg' => $e->getMessage() ];
            echo json_encode($reposta);
           
       }
       return;
   }
   
   $idregranivelminimo = -1;
   if ( ! empty($_GET['idregranivelminimo']) ){
      $idregranivelminimo = $_GET['idregranivelminimo'];
   }
   $nomeregranivelminimo = "";
   $formaexigencianivelmin = "";
   $strSQL = "select idregranivelminimo, nomeregranivelminimo,formaexigencianivelmin
                from regranivelminimo 
               where idregranivelminimo =  ".$idregranivelminimo;
   $regras = getSQLMySQL($strSQL);
   if ( !empty($regras) ){
     $nomeregranivelminimo = $regras[0]['nomeregranivelminimo'];
     $formaexigencianivelmin = $regras[0]['formaexigencianivelmin'];
   }
   
   $strSQL = "SELECT idioma.idm_id, idioma.idm_nome
                FROM idioma";
   $idiomas = getSQLMySQL($strSQL);   
   $strSQL = " select ididioma, nivelmin_ca, nivelmin_ee, nivelmin_eo, nivelmin_cl
                 from regranivelminimoidioma
                where idregranivelminimo = ".$idregranivelminimo;
   $regranivelminimoidioma = getSQLMySQL($strSQL);
   
   
 ?>
<body>
    <form method="POST" id="frm_regranivelminimo">
        <input type="hidden" name="idregranivelminimo" value="<?=$idregranivelminimo ?>"  >
        <div id="regranivelminimo" style="display: inline-flex;"> 
            <div>
              <label for="formaexigencianivelmin" style="width: auto;margin: 2px;"> Nome da regra </label>
              <input type="text" name="nomeregranivelminimo" maxlength="50" value="<?=$nomeregranivelminimo ?>" style="width: auto;margin: 4px;" >
            </div>
            <div>
                <label for="formaexigencianivelmin" style="width: auto;margin: 3px;">Forma de exigência dos níveis </label>
                <select name="formaexigencianivelmin" style="width: auto;margin: 6px;">
                           <option value="U" <?= $formaexigencianivelmin == "U" ? "selected" : "" ?> > Ao menos 1 idioma </option>
                           <option value="T" <?= $formaexigencianivelmin == "T" ? "selected" : "" ?> > Todos os idiomas </option>
                </select>
            </div>
        </div>

        <div id="regranivelminimoitens"> 
            <input type="button" name="addidioma" value="Incluir idioma" class="btnacao" id="addidioma">
            <table id="tbl_idiomanivelminimo">
                <thead>
                    <tr>
                        <th width="20%">Idioma</th>
                        <th width="10%">Nível minimo EPLO/CA</th>
                        <th width="10%">Nível minimo EPLO/EO</th>
                        <th width="10%">Nível minimo EPLE/CL</th>
                        <th width="10%">Nível minimo EPLE/EE</th>                                        
                        <th width="5%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="registropadrao" >
                        <td>
                            <select name="regranivelminimoidioma[idioma][]" class="cmbidioma">
                                <option value="-1">Selecione </option>
                                <?php
                                 foreach ($idiomas as $idm) {
                                     echo '<option value="'.$idm['idm_id'].'" >'.$idm['idm_nome'] .' </option>';  
                                 } 
                                ?>
                            </select>
                        </td> 
                        <td>
                            <input type="number" min="0" max="3" name="regranivelminimoidioma[nivelminca][]">
                        </td>
                        <td>
                            <input type="number" min="0" max="3" name="regranivelminimoidioma[nivelmineo][]">
                        </td>
                        <td>
                            <input type="number" min="0" max="3" name="regranivelminimoidioma[nivelmincl][]">
                        </td>
                        <td>
                            <input type="number" min="0" max="3" name="regranivelminimoidioma[nivelminee][]">
                        </td>
                        <td>                        
                            <a href="javascript:void(0)" class="removernivel" title="Remover"> 
                                  <img src="imagens/i_del.png" style="width: 20px;" > 
                            </a>
                        </td>
                    </tr>
                    <?php 
                        $i = 1;
                        foreach ($regranivelminimoidioma as $regra){ 
                            $i += 1;    
                      ?>   
                            <tr>
                                    <td>
                                        <select name="regranivelminimoidioma[idioma][]" class="cmbidioma">
                                            <option value="-1">Selecione </option>
                                            <?php
                                             foreach ($idiomas as $idm) {
                                                 $marcado = "";
                                                 if (  $idm['idm_id'] == $regra["ididioma"] ){
                                                   $marcado = "selected";  
                                                 }
                                                 echo '<option value="'.$idm['idm_id'].'" '.$marcado.' >'.$idm['idm_nome'] .' </option>';  
                                             } 
                                            ?>
                                        </select>
                                    </td> 
                                    <td>
                                        <input type="number" min="0" max="3" name="regranivelminimoidioma[nivelminca][]" value="<?=  $regra["nivelmin_ca"] ?>">
                                    </td>
                                    <td>
                                        <input type="number" min="0" max="3" name="regranivelminimoidioma[nivelmineo][]" value="<?=  $regra["nivelmin_eo"] ?>" >
                                    </td>
                                    <td>
                                        <input type="number" min="0" max="3" name="regranivelminimoidioma[nivelmincl][]" value="<?=  $regra["nivelmin_cl"] ?>" >
                                    </td>
                                    <td>
                                        <input type="number" min="0" max="3" name="regranivelminimoidioma[nivelminee][]" value="<?=  $regra["nivelmin_ee"] ?>">
                                    </td>
                                    <td>                                    
                                        <a href="javascript:void(0)" class="removernivel" title="Remover"> 
                                              <img src="imagens/i_del.png" style="width: 20px;" > 
                                        </a>
                                    </td>
                                </tr>
                      <?php      
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div>
            <input type="button" value="Cancelar" id="btncancelar_nivelmin" class="btncancelar btn_acao_frmaltconfiggraduacao">
            <input type="button" value="Gravar" id="btngravar_nivelmin" class="btngravar btn_acao_frmaltconfiggraduacao">
        </div>
    </form>    
</body>
<script>
    function maxidiomas(){
        var qtdidiomas = $(".cmbidioma").eq(0).find('option').length -1;
        var qtdregistros = $("#tbl_idiomanivelminimo tbody tr").length  ;        
        if ( qtdregistros >= qtdidiomas && qtdregistros > 0 ){
            $("#addidioma").prop("disabled", true);
            $("#addidioma").addClass("desabilitado");
        }else{
            $("#addidioma").prop("disabled", false);
            $("#addidioma").removeClass("desabilitado");
        }
    }

    function podegravar(){
      if ( $("input[name='nomeregranivelminimo']").val() == "" ){
         $("input[name='nomeregranivelminimo']").focus();
         window.alert("Informe o nome da regra");
         return false;
      }

      if ( $(".cmbidioma option[value='-1']:selected").length > 0 ){    
         $(".cmbidioma option[value='-1']:selected").parent().focus();
         window.alert("Existem idiomas não selecionados.");
         return false;
      }
      if ( $(".cmbidioma").length == 0 ){        
         window.alert("Não existem idiomas para gravar.\n O senhor(a) deve clicar em incluir idioma.");
         return false;  
      }

      return true;
    }

        
    $(document).ready(function(){
      var registropadrao = $("#tbl_idiomanivelminimo").find(".registropadrao").clone();      
      registropadrao.removeClass("registropadrao");
      if (  $("#tbl_idiomanivelminimo tbody tr").length  == 0 ){
          $("#addidioma").click();
      }
      $("#tbl_idiomanivelminimo").find(".registropadrao").remove();
      
      $("#btncancelar_nivelmin").click(function(){
           $("#div_cad_nivelmin").hide();
        }); 
     $("#addidioma").click(function(){
           var registronovo = $(registropadrao).clone();
           var qtditens = $("#tbl_idiomanivelminimo tbody tr").length +1;
           $(registronovo).find("input, select").each(function(){
               var nome = $(this).prop("name");
               nome = nome.toString().replace("[1]",'['+qtditens+"]");
               $(this).prop("name", nome);
           });           
           $("#tbl_idiomanivelminimo").append(registronovo);
           maxidiomas();
        }); 
        $(document).off("click",".removernivel");
        $(document).on("click",".removernivel",function(e){
           e.preventDefault();
           if ( window.confirm("Deseja realmente remover esse idioma?") ){
             $(this).parent().parent().remove();
             maxidiomas();
           }
           
           return true;
        });
        
        $(document).on("blur","input[type='number']",function(){
            var max = $(this).prop("max");
            var min = $(this).prop("min");
            if ( $(this).val() > max ){
              $(this).val( max );
            }
            if ( $(this).val() < min ){
              $(this).val( min );
            }

          });
          
          $(document).on("change",".cmbidioma",function(){
            var idmslecionado = $(this).val();
            var qtdidmselecionado = $(".cmbidioma option[value='"+idmslecionado+"']:selected").length;
            if ( qtdidmselecionado > 1 ){                                
                window.alert("Esse idioma já foi utilizado."); 
                $(this).find("option").eq(0).prop("selected", true);
                $(this).focus();
                return false;
            }

          });
          
         
        
        maxidiomas();
    });
</script>    
    