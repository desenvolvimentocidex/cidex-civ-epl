<?php
  require_once '../system/system.php';
  $perfis = mysqli_fetch_all(mysqli_query($con, "select idperfil, nomeperfil
                                                     from perfil "), MYSQLI_ASSOC);
  
 ?>
<div class="row" >    
    <table  class="tabela">
        <thead>
           <th>Perfil</th>
           <th>Ação</th>
        </thead>
        <tbody>
            <?php
             foreach ($perfis as $perfil) {
                  echo "<tr> <td> ".$perfil['nomeperfil']."</th> <td> <a href='index.php?id=25&idperfil=".$perfil['idperfil']."' class='btn btninfo'> Mostrar usuários </a> </td> </tr>";
             }
            ?>
        </tbody>
    </table> 
</div>

<?php 
   if (isset($_GET['idperfil'])){
       $idperfil = md5($_GET['idperfil']);
      
       $usuarios = mysqli_fetch_all(mysqli_query($con, "select * 
                                                        from user 
                                                       inner join perfil
                                                          on (perfil.idperfil = user.idperfil)
                                                        left join om
                                                          on (om.om_id = user.omse )
                                                        where md5(user.idperfil) ='{$idperfil}'" ), MYSQLI_ASSOC);
   
?>
<div id="perfil">
    <legend>Usuários do perfil <span class="destaque"> <?= $usuarios[0]['nomeperfil'] ?> </span> </legend>
    <a href="index.php?id=25&idperfil=<?= $_GET['idperfil']?>&idusuario=-1" class="btn btn-success"> Incluir</a>
    <table  class="tabela">
        <thead>
            <tr>
                <th>Usuário</th>
                <th>OMSE</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php
              foreach ($usuarios as $usuario) {
            ?>
                <tr>
                    <td>
                        <input type="hidden" name= 'idusuario'  value="<?= $usuario['u_id'] ?>" >
                        <input type="hidden" name= 'idperfil'  value="<?= $usuario['idperfil'] ?>" >
                        <input type="hidden" name= 'nomeusuario'  value="<?= $usuario['u_nome'] ?>" >
                        <span> <?= $usuario['u_nome'] ?> </span>
                    </td>
                     <td>
                        <span> <?= $usuario['om_sigla'].' - '.$usuario['om_nome'] ?> </span>
                    </td>
                    <td>
                        <a href="index.php?id=25&idperfil=<?= $_GET['idperfil']?>&idusuario=<?= $usuario['u_id']?>&a=m" class='btn btninfo'>Alterar</a>
                        <a href='index.php?id=25&idperfil=<?= $_GET['idperfil']?>&idusuario=<?= $usuario['u_id']?>&a=d' onclick="confirmaDel(event)" class='btn btnperigo'>Remover</a>
                    </td>
                </tr>
            <?php 
              }
            }  
            ?>  
        </tbody>
    </table>

</div>
<?php 
   if (isset($_GET['idusuario'])){       
       $idusuario = md5($_GET['idusuario']);         
       if ($_GET['a'] == 'd'){
           $strSQL = "delete from user where md5(user.u_id) ='{$idusuario}'";
           execSQL($strSQL);
           echo "<script>alert('Usuário removido com sucesso');location='?id=$id'</script>";
           return;
       }
       $usuario = mysqli_fetch_all(mysqli_query($con, "select * 
                                                        from user                                                        
                                                        where md5(user.u_id) ='{$idusuario}'" ), MYSQLI_ASSOC);
        $usuario = $usuario[0];
        $omse = mysqli_fetch_all(mysqli_query($con, "select om_id, om_sigla, om_nome 
                                                       from om
                                                      order by om_sigla " ), MYSQLI_ASSOC);   
        $idiomas = mysqli_fetch_all(mysqli_query($con, "select idm_id, idm_nome
                                                       from idioma" ), MYSQLI_ASSOC); 
        $posto_graduacao = mysqli_fetch_all(mysqli_query($con, "select idpostograduacao, descricao, codigodgp
                                                              from postograduacao
                                                             order by descricao" ), MYSQLI_ASSOC); 
?>
<div id="usuario">
    <form name="form_usuario" action="" method="POST" onsubmit="beforeSubmit(event)">
        <input type="hidden" name="acao" value="manut_usuario">
        <input type="hidden" name="token" value="<?= $tok ?>"/>
        <input type="hidden" name="idusuario" value="<?= $_GET['idusuario'] ?>"/>        
        <label for='nome'>Nome </label>
        <input type="text" name="nome" value="<?= $usuario['u_nome'] ?>"  maxlength="100" autofocus="true" />        
        <label for='email'>E-mail </label>
        <input type="email" name="email" value="<?= $usuario['email'] ?>"  maxlength="100" autofocus="true" />        
        <div class="bloco">
            <label for='login'> Login: </label>
            <input type="login" name="login" value="<?= $usuario['u_login'] ?>"   maxlength="20" />
            <label for='omse'>OMSE: </label>
            <select name="omse" >
               <?php 
               foreach ($omse as $om) {
                   $selected = $om['om_id'] == $usuario['omse'] ? ' selected ' : '';  
                  echo "<option value='{$om['om_id']}' {$selected} >".$om['om_sigla'].' - '.$om['om_nome']."</option>";    
               }

               ?> 
            </select>

            <label for='idperfil'>Perfil: </label>
            <select name="idperfil" class='select' >
               <?php 
               foreach ($perfis as $perfil) {
                  $selected = $perfil['idperfil'] == $_GET['idperfil'] ? ' selected ' : ''; 
                  echo "<option value='{$perfil['idperfil']}' {$selected}>".$perfil['nomeperfil']."</option>";    
               }

               ?> 
            </select>
            <?php
              if ( $_GET['idusuario'] != -1  ){ ?>
                 <div style="display: inline-flex; padding: 5px;">
                    <label for='login'> Gerar nova senha </label>
                    <input type="checkbox" name="gerarsenha" value="S"   />
                 </div>
             <?php } ?>
           
        </div>
        
        <div class='div_muiltselecao' >
            <span>Idiomas liberados </span>
           <?php 
                foreach ($idiomas as $idioma) {
                   $selected = in_array($idioma['idm_id'], explode(',', $usuario['idiomasliberados']) ) ? ' checked ' : ''; 
                   echo '<div>  <input type="checkbox" name="idiomas[]" value="'.$idioma['idm_id'].'" '.$selected.' />  <label for="idiomas[]">'.$idioma['idm_nome'].' </label> </div>' ; 
                }
           
           ?> 
        </div>  
        <div>
            <div class='div_muiltselecao div_posto' >
               <span>Posto/graduação liberados </span>
               <?php 
                   foreach ($posto_graduacao as $pg) {
                      $selected = in_array($pg['idpostograduacao'], explode(',', $usuario['postograduacaoliberados']) ) ? ' checked ' : ''; 
                      echo '<div>  <input type="checkbox" name="postogrduacao[]" value="'.$pg['idpostograduacao'].'"'.$selected.'/>  <label for="postogrduacao[]">'.$pg['descricao'].' </label> </div>' ; 
                   }

              ?> 
           </div>
        </div>    
        <input type="submit" value="Gravar" name="btnGravar" class="btn btn-success" />
        <input type="reset" onclick="voltaPerfil(this)" value="Cancelar" name="btnCancelar" " class="btn btnwarning"/>
        
    </form>

</div>
<?php

   }
   
   ?>
<script>
     function voltaPerfil(){         
         location.href = location.href.substring(0,location.href.indexOf('idusuario=')-1);
     } 
     function confirmaDel(e){
         if (! window.confirm('Deseja realmente excluir esse usuário?')){             
             e.preventDefault();
             return false;
         }  
     };
     
     function beforeSubmit(e){
         if ( document.getElementsByName('nome')[0].value == "" ){
             window.alert('Informe o nome do usuário.');
             document.getElementsByName('nome')[0].focus();
             e.preventDefault();
             return false;
         }
         if ( document.getElementsByName('login')[0].value == "" ){
             window.alert('Informe o login do usuário.');
             document.getElementsByName('login')[0].focus();
             e.preventDefault();
             return false;
         }
         
         
         return true;
     }
</script>    