<?php
  require_once '../system/system.php';
  if ( $_SESSION["perfil"]  != PERFIL_USUARIO_ADM ){
      return header("location:../index.php"); 
  }
  $where = "1=1";
  $mostradados = false;
  if ( key_exists('p', $_GET) ){
    $where .= " and md5(cp_id) =  md5('{$_GET['p']}')";    
    $mostradados = true;
  }
  
  $periodos = getSQLMySQL( "SELECT cp_id, cp_nome, cp_ini, cp_cor, localportaria,
                                                          cp_pesquisa, data_ini_inc_centralizada, 
                                                          data_fim_inc_centralizada, data_segunda_convocacao, 
                                                          data_divulgacao, data_local_exame, data_fim_periodo, 
                                                          desc_portaria, hora_ini_periodo,  
                                                          texto_gru,linkresultado                                                           
                                                   FROM curso_periodo 
                                                  where  {$where} ");
?>
<style>
    tr:hover{
        background: none;
    }
</style>    
<table class="tabela" id="tblperiodo">
    <caption>
        <h2>Períodos de EPL/EPLO </h2>
        <a href="index.php?id=32&p=-1" class="btn btninfo novo">Criar período</a>
    </caption>
    <thead>
        <th class="col2">Período</th>        
        <th class="col3">Início</th>
        <th class="col3">Fim</th>
        <th class="col3">Início inscrição centralizada</th>
        <th class="col3">Fim inscrição centralizada</th>        
        <th class="col2">Ações</th>        
    </thead> 
    <tbody>
        <?php           
          foreach ($periodos as $periodo) {
              
         ?>
            <tr>
                <td> <span style="background-color:<?= $periodo["cp_cor"] ?>; ">  <?= $periodo["cp_nome"] ?> </span>  </td>        
                <td><?= $periodo["cp_ini"] ?></td>
                <td><?= $periodo["data_fim_periodo"] ?></td>
                <td><?= $periodo["data_ini_inc_centralizada"] ?></td>
                <td><?= $periodo["data_fim_inc_centralizada"] ?></td>                           
                <td>
                    <a href="index.php?id=32&p=<?= $periodo["cp_id"] ?>" class="btn  btngravar editar">Editar</a>                     
                </td>        
           </tr>
         <?php     
          }        
        ?>
        
    </tbody>
</table>    
<?php 
   if ( $mostradados ){ 
      $periodo = $periodos[0]; 
      $nomeperido = explode('.', $periodo['cp_nome']); 
      $ano = 0;
      $turma = 1;
      if ( count($nomeperido) > 1 ){
        $ano = $nomeperido[0];
        unset($nomeperido[0]);
        $turma = join('.', $nomeperido);        
      }
  ?> 
<div id="periodo" >
    <form method="POST" name="frmPeriodo" enctype="multipart/form-data">
        <h1> Dados do período </h1>
        <div class="bloco_lado bloco">
            <input type="hidden"  name="cp_id" value="<?= $periodo['cp_id'] ?>"  > 
            <input type="hidden"  name="localportaria" value="<?= $periodo['localportaria'] ?>"  >
            <input type="hidden" name="acao" value="manut_periodo">
            <input type="hidden" name="texto_gru" value="">
            <input type="hidden" name="token" value="<?= $tok ?>"/>
            <label for="ano"> Ano </label>
            <input type="number" step="1" min="2000" max="2050" name="ano" class="tamanho_automatico" value="<?= $ano ?>"  >        
            <label for="turma"> Turma </label>
            <select class="tamanho_automatico" name="turma">
                <option default value="1" <?= $turma == '1' ? 'selected' : '' ?> >1</option>
                <option default value="1.1" <?= $turma == '1.1' ? 'selected' : '' ?> >1.1</option>
                <option  value="2" <?= $turma == '2' ? 'selected' : '' ?> >2</option>
                <option  value="2.1" <?= $turma == '2.1' ? 'selected' : '' ?> >2.1</option>
            </select>       
            <label for="cp_cor"> Cor </label>
            <input type="color"  name="cp_cor" class="w100px" value="<?= $periodo['cp_cor'] ?>"  >        
            <label for="cp_pesquisa" title="Pergunta ao usário se ele realizou cursos de idioma em outra instituição"> Pesquisa? </label>
            <input type="checkbox"  title="Pergunta ao usário se ele realizou cursos de idioma em outra instituição" name="cp_pesquisa" class="tamanho_automatico" value="SIM" <?= $periodo['cp_pesquisa'] == 'SIM' ? 'checked' : '' ?>  > 
        </div>
        <div class="bloco_lado bloco">
            <label for="cp_ini"> Início </label>
            <input type="date"  name="cp_ini" class="tamanho_automatico"  value="<?= $periodo['cp_ini'] ?>"  >
        
            <label for="data_fim_periodo"> Fim </label>
            <input type="date"  name="data_fim_periodo" class="tamanho_automatico" value="<?= $periodo['data_fim_periodo'] ?>"  >
        </div>
        <div class="bloco_lado bloco">
            <label for="data_ini_inc_centralizada"> Data início inscrição centralizada </label>
            <input type="date"  name="data_ini_inc_centralizada" class="tamanho_automatico" value="<?= $periodo['data_ini_inc_centralizada'] ?>"  >
       
            <label for="data_fim_inc_centralizada"> Data fim inscrição centralizada </label>
            <input type="date"  name="data_fim_inc_centralizada" class="tamanho_automatico" value="<?= $periodo['data_fim_inc_centralizada'] ?>"  >
      
            <label for="data_segunda_convocacao"> Data segunda convocação </label>
            <input type="date"  name="data_segunda_convocacao" class="tamanho_automatico" value="<?= $periodo['data_segunda_convocacao'] ?>"  >
        </div>
        <div class="bloco_lado bloco">
            <label for="data_divulgacao"> Data divulgação </label>
            <input type="date"  name="data_divulgacao" class="tamanho_automatico" value="<?= $periodo['data_divulgacao'] ?>"  >
       
            <label for="data_local_exame"> Data local de exame </label>
            <input type="date"  name="data_local_exame" class="tamanho_automatico" value="<?= $periodo['data_local_exame'] ?>"  >
      
            <label for="hora_ini_periodo"> Hora do início do exame </label>
            <input type="time"  name="hora_ini_periodo" class="tamanho_automatico" value="<?= $periodo['hora_ini_periodo'] ?>"  >
        </div>
        <div class="bloco_lado bloco">
            <label for="portaria_file"> Arquivo portaria </label>
            <input type="file" name="portaria_file" accept=".pdf" >
            <label for="desc_portaria"> Descrição portaria </label>
            <input type="text"  name="desc_portaria" maxlength="80" class="" value="<?= $periodo['desc_portaria'] ?>"  >
        </div>
        <div class="bloco_lado bloco">
            <label for="linkresultado"> Link do resultado </label>
            <input type="text"  name="linkresultado" maxlength="300" class="" value="<?= $periodo['linkresultado'] ?>"  >           
        </div>
        
        <div>
            <input type="submit" value="Gravar" name="btnGravar" class="btn btn-success" />  
            <button class="btn btnwarning cancelar">Cancelar</button>  
        </div>    
        
    </form>
</div>
<?php 
   }
 ?>


<script>
    $(".cancelar").click(function(e){
        e.preventDefault();
        return location.href = 'https://'+location.host+'/cidex/epl/index.php?id=32';
    });  
    $(".gravar").click(function(e){
        e.preventDefault();        
        return location.href = 'https://'+location.host+'/cidex/epl/index.php?id=32';
    });  
</script>    