<?php

require_once '../../system/system.php';
require_once '../action.php';
@session_start();
$om_id = 0; 
$omseacesso = $_SESSION["omse"];
$perfil = $_SESSION["perfil"];


/*
if ($omseacesso == 109)
{
    $om_id = 1; // Aman
}elseif ($omseacesso == 49403)
{
    $om_id = 8; //Espcex
}else if  ($omseacesso == 49601)
{
    $om_id = 136; //EsSEx
}*/

if ($perfil == 'secretaria') {

    $cad_id = isset($_GET['cad_id']) ? $_GET['cad_id'] : '';
    $opcao = isset($_GET['opcao']) ? $_GET['opcao'] : '';
    $valor = isset($_GET['valor']) ? $_GET['valor'] : '';
    $om = isset($_GET['om']) ? $_GET['om'] : '';
    $pg = isset($_GET['pg']) ? $_GET['pg'] : '';
    $cpid = isset($_GET['cpid']) ? $_GET['cpid'] : '';
    $idca = isset($_GET['idca']) ? $_GET['idca'] : '';
    $idcl = isset($_GET['idcl']) ? $_GET['idcl'] : '';
    $idee = isset($_GET['idee']) ? $_GET['idee'] : '';
    $idioma = isset($_GET['idioma']) ? $_GET['idioma'] : '';
    $inscrever = isset($_GET['compareceu']) ? $_GET['compareceu'] : '';
    $om_id = isset($_GET['omid']) ? $_GET['omid'] : '';
    $ideo = isset($_GET['ideo']) ? $_GET['ideo'] : '';
    

    $myArray = array();
    $mensagem = "";


    if (!empty($opcao)) {
        switch ($opcao) {
            case 'buscarmilitar': {
                
                if ($pg == 16)
                {                
                    $select = "select cad_id, cad_login, cad_nome, cad_mail, cad_nomeguerra,cad_postograd,
                                      GROUP_CONCAT(CONCAT(idm_sigla,' ',crs_cod,' Nível ', nivel_id, '\n')) as inscricoes 
                                from (select distinct c.cad_id, c.cad_login, c.cad_nome, c.cad_mail,cc.crs_id, 
                                             cad_nomeguerra, cc.nivel_id, i.idm_sigla, cu.crs_cod,
                                             cad_postograd 
                                        from cadastro c 
                                        left join (select * 
                                                     from cadastro_curso cc1 
                                                    where cc1.cp_id = $cpid) as cc 
                                          on c.cad_id = cc.cad_id 
                                        left join idioma i 
                                          on i.idm_id = cc.idm_id 
                                        left join curso cu 
                                          on cu.crs_id = cc.crs_id 
                                       where c.cad_codpg = $pg and c.cad_qasqms like '%ESSEX%' order by c.cad_nome)x 
                            group by cad_id, cad_login, cad_nome, cad_mail, cad_nomeguerra,cad_postograd
                            order by cad_nome";
                } else
                {
                     $select = "select cad_id, cad_login, cad_nome, cad_mail, cad_nomeguerra,cad_postograd,
                                      GROUP_CONCAT(CONCAT(idm_sigla,' ',crs_cod,' Nível ', nivel_id, '\n')) as inscricoes 
                                from (select distinct c.cad_id, c.cad_login, c.cad_nome, c.cad_mail,cc.crs_id, 
                                             cad_nomeguerra, cc.nivel_id, i.idm_sigla, cu.crs_cod,
                                             cad_postograd 
                                        from cadastro c 
                                       inner join om
                                          on (om.codigodgp = c.cad_om_id)
                                        left join (select * 
                                                     from cadastro_curso cc1 
                                                    where cc1.cp_id = $cpid) as cc 
                                          on c.cad_id = cc.cad_id 
                                        left join idioma i 
                                          on i.idm_id = cc.idm_id 
                                        left join curso cu 
                                          on cu.crs_id = cc.crs_id 
                                       where c.cad_codpg = $pg 
                                         and om.om_id = $om    
                                       order by c.cad_nome)x 
                            group by cad_id, cad_login, cad_nome, cad_mail, cad_nomeguerra,cad_postograd
                             order by cad_nome";
                }
                    //var_dump($select); 
                    $result = $con->query($select);
                    //var_dump($result);
                    //die;
                    sleep(1);

                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

                        $myArray[] = $row;
                    }
                    echo json_encode($myArray);
                    break;
                }
            case 'deletarinscricoescentralizadas': {
                    $mensagem = "";
                    
                        $insertcl = "delete from cadastro_curso where cad_id = $cad_id and cp_id = $cpid and idm_id = $idioma"; //insert                               
                        $result = $con->query($insertcl);
                        $mensagem .= "As inscrições centralizadas foram apagadas com Sucesso!";
                        
                    $retorno = array();
                    
                    $select = "select cad_id, cad_login, cad_mail, cad_nomeguerra,cad_postograd, GROUP_CONCAT(CONCAT(idm_sigla,' ',crs_cod,' Nível ', nivel_id, '\n')) as inscricoes from (select distinct c.cad_id, c.cad_login, c.cad_mail,cc.crs_id, cad_nomeguerra, cc.nivel_id, i.idm_sigla, cu.crs_cod,cad_postograd from cadastro c left join (select * from cadastro_curso cc1 where cc1.cp_id = $cpid) as cc on c.cad_id = cc.cad_id left join idioma i on i.idm_id = cc.idm_id left join curso cu on cu.crs_id = cc.crs_id where c.cad_id = $cad_id)x group by cad_id, cad_login, cad_mail, cad_nomeguerra,cad_postograd";
                    $result = $con->query($select);
                     while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        $retorno[] = $row;
                    }
                    
                    $retorno[] = array("mensagem" => $mensagem == "" ?"Inscrição apagada com Sucesso":$mensagem);
                                                         
                    sleep(1);
                    echo json_encode($retorno);
                    break;
                }
                
                case 'inscrevermilitar': {
                    $id_status = 1; //tem gratuidade
                    $mensagem = "";
                    
                    if ($idca != 0) {
                        $crs_id = 2;
                        // busca o curso local do exame
                        $cursolocalid = mysqli_fetch_array(mysqli_query($con, "select * from curso_local where om_id = $om_id and crs_id = $crs_id"));
                        $cl_id = $cursolocalid["cl_id"];
                        //valida inscrição    
                        $verificaca = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso where cad_id = $cad_id and crs_id = $crs_id and cp_id = $cpid and idm_id = $idioma and nivel_id = $idca"));
                                               
                        if ($verificaca == false) {
                            $insertca = "insert into cadastro_curso (cad_id,crs_id,cp_id,idm_id,nivel_id,cl_id, ccs_id,cc_parcela,cc_vencimento, insc_centralizada) values($cad_id,$crs_id,$cpid,$idioma,$idca,$cl_id,$id_status,0,null, 1)"; //insert                               
                          
                            $result = $con->query($insertca);
                        } else {
                            $mensagem .= "Militar estava inscrito CA \\n ";
                        }
                    }


                    if ($idcl != 0) {
                        //inscreve cl
                        $crs_id = 4;
                        $cursolocalid1 = mysqli_fetch_array(mysqli_query($con, "select * from curso_local where om_id = $om_id and crs_id = $crs_id"));
                        $cl_id = $cursolocalid1["cl_id"];
                        //valida inscrição    
                        $verificacl = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso where cad_id = $cad_id and crs_id = $crs_id and cp_id = $cpid and idm_id = $idioma and nivel_id = $idcl"));
                        
                        if ($verificacl == false) {
                        $insertcl = "insert into cadastro_curso (cad_id,crs_id,cp_id,idm_id,nivel_id,cl_id, ccs_id,cc_parcela,cc_vencimento) values($cad_id,$crs_id,$cpid,$idioma,$idcl,$cl_id,$id_status,0,null)"; //insert                               
                        $result = $con->query($insertcl);
                        } else {
                            $mensagem .= "Militar estava inscrito CL \\n ";
                        }
                    }

                    if ($idee != 0) {//inscreve ee
                        $crs_id = 5;
                        $cursolocalid2 = mysqli_fetch_array(mysqli_query($con, "select * from curso_local where om_id = $om_id and crs_id = $crs_id"));
                        $cl_id = $cursolocalid2["cl_id"];
                        
                        //valida inscrição    
                        $verificaee = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso where cad_id = $cad_id and crs_id = $crs_id and cp_id = $cpid and idm_id = $idioma and nivel_id = $idee"));
                        
                        if ($verificaee == false) {
                        $insertee = "insert into cadastro_curso (cad_id,crs_id,cp_id,idm_id,nivel_id,cl_id, ccs_id,cc_parcela,cc_vencimento) values($cad_id,$crs_id,$cpid,$idioma,$idee,$cl_id,$id_status,0,null)"; //insert                               
                        $result = $con->query($insertee);
                         } else {
                            $mensagem .= "Militar estava inscrito EE \\n ";
                        }
                    }
                    if ($ideo != 0) {//inscreve eo
                        $crs_id = 6;
                        $cursolocalid2 = mysqli_fetch_array(mysqli_query($con, "select * from curso_local where om_id = $om_id and crs_id = $crs_id"));
                        $cl_id = $cursolocalid2["cl_id"];
                        
                        //valida inscrição    
                        $verificaee = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso where cad_id = $cad_id and crs_id = $crs_id and cp_id = $cpid and idm_id = $idioma and nivel_id = $ideo"));
                        
                        if ($verificaee == false) {
                        $insertee = "insert into cadastro_curso (cad_id,crs_id,cp_id,idm_id,nivel_id,cl_id, ccs_id,cc_parcela,cc_vencimento) values($cad_id,$crs_id,$cpid,$idioma,$ideo,$cl_id,$id_status,0,null)"; //insert                               
                       // var_dump($insertee);
                        $result = $con->query($insertee);
                         } else {
                            $mensagem .= "Militar estava inscrito EO \\n ";
                        }
                    }
                    $retorno = array();
                    
                    $select = "select cad_id, cad_login, cad_mail, cad_nomeguerra,cad_postograd, GROUP_CONCAT(CONCAT(idm_sigla,' ',crs_cod,' Nível ', nivel_id, '\n')) as inscricoes from (select distinct c.cad_id, c.cad_login, c.cad_mail,cc.crs_id, cad_nomeguerra, cc.nivel_id, i.idm_sigla, cu.crs_cod,cad_postograd from cadastro c left join (select * from cadastro_curso cc1 where cc1.cp_id = $cpid) as cc on c.cad_id = cc.cad_id left join idioma i on i.idm_id = cc.idm_id left join curso cu on cu.crs_id = cc.crs_id where c.cad_id = $cad_id)x group by cad_id, cad_login, cad_mail, cad_nomeguerra,cad_postograd";
                    $result = $con->query($select);
                     while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        $retorno[] = $row;
                    }
                    
                    $retorno[] = array("mensagem" => $mensagem == "" ?"Inscrição realizada com Sucesso":$mensagem);
                                                         
                    sleep(1);
                    echo json_encode($retorno);
                    break;
                }
                
        }
    }
} else {
    header('location: ?index.php?id=22');
}
?>
