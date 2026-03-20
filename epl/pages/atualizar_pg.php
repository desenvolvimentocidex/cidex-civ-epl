
<?php

include '../../system/system.php';
include '../action.php';



ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

ignore_user_abort(true);
set_time_limit(0);
$resposta_json = [];
 if ( isset($_POST['omse']) ) {
	$postoGraduacao = implode(',', $_POST['postogrduacao']);
        /*Atualiza com base no banco do CIDEX*/
        $strSQL = "select cadastro.cad_login, `cad_postograd`,cad_codpg, `cad_om`
                     FROM cadastro  
                    where cadastro.cad_codpg= {$postoGraduacao}
                      and `cad_om_id` = {$_POST['omse']}";
        $cadastros = getSQLMySQL($strSQL);
        foreach ($cadastros as $cadastro){
            $consultapessoa = oci_parse($oci_connect, 
                    "select m.*,p.*,pg.*,q.*,o.CODOM as om_id, o.RM_COD as om_rm_id, o.SIGLA as om_sigla, 
                            o.NOME as om_desc, rm.CODIGO as rm_id, rm.CMA_CODIGO as rm_cma_id, cma.codigo as cma_id,
                            rm.sigla as rm_sigla, rm.descricao as rm_desc, cma.sigla as cma_sigla, 
                            cma.descricao as cma_desc 
                        from RH_QUADRO.MILITAR m
                        left join RH_QUADRO.PESSOA p 
                          on m.PES_IDENTIFICADOR_COD = p.IDENTIFICADOR_COD
                        left join RH_QUADRO.POSTO_GRAD_ESPEC pg 
                          on m.POSTO_GRAD_CODIGO = pg.CODIGO
                        left join RH_QUADRO.QAS_QMS q 
                          on m.QQ_COD_QAS_QMS = q.COD_QAS_QMS
                        left join RH_QUADRO.ORGAO o 
                          on o.codom = m.OM_CODOM
                        left join RH_QUADRO.RM rm 
                          on o.rm_cod = rm.CODIGO
                        left join RH_QUADRO.COMANDO_MILITAR_AREA cma 
                          on rm.CMA_CODIGO = cma.codigo
                        where m.pes_identificador_cod = '{$cadastro['cad_login']}'"   
                        );
           oci_execute($consultapessoa);
           $pessoa = oci_fetch_array($consultapessoa, OCI_ASSOC+OCI_RETURN_NULLS);
           oci_close($oci_connect); 
           if ( $cadastro['cad_codpg'] != $pessoa['POSTO_GRAD_CODIGO'] ){
                
               $pg_sigla = $pessoa['SIGLA'];               
               $rm = $pessoa['RM_SIGLA'] . " - " . $pessoa['RM_DESC'];
               $cma = $pessoa['CMA_SIGLA'] . " - " . $pessoa['CMA_DESC'];
               $om = $pessoa['OM_SIGLA'] . " - " . $pessoa['OM_DESC'];
                
               $query = "update cadastro 
                             set cad_postograd = '$pg_sigla',
                                 cad_codpg = '{$pessoa['POSTO_GRAD_CODIGO']}',
                                 cad_rm = '$rm',
                                 cad_cma = '$cma',
                                 cad_om = '$om', 
                                 cad_atualizado = 1 
                           where cad_login = '{$pessoa['PES_IDENTIFICADOR_COD']}'";                              
                $result = $con->query($query);
                if ($result == 1){
                    $resposta_json[] = ['identidade' => $pessoa['PES_IDENTIFICADOR_COD'], 
                                        'nome' => $pessoa['NOME'],
                                        'codstatus' => 1,
                                        'status' => 'Posto/graduação Atualizado',                                    
                                         ];
                }
           }
        }
        //Conexão com BD Oracle 
            $consultapessoa = oci_parse($oci_connect, 
                    "select m.*,p.*,pg.*,q.*,o.CODOM as om_id, o.RM_COD as om_rm_id, o.SIGLA as om_sigla, 
                            o.NOME as om_desc, rm.CODIGO as rm_id, rm.CMA_CODIGO as rm_cma_id, cma.codigo as cma_id,
                            rm.sigla as rm_sigla, rm.descricao as rm_desc, cma.sigla as cma_sigla, 
                            cma.descricao as cma_desc 
                        from RH_QUADRO.MILITAR m
                        left join RH_QUADRO.PESSOA p 
                          on m.PES_IDENTIFICADOR_COD = p.IDENTIFICADOR_COD
                        left join RH_QUADRO.POSTO_GRAD_ESPEC pg 
                          on m.POSTO_GRAD_CODIGO = pg.CODIGO
                        left join RH_QUADRO.QAS_QMS q 
                          on m.QQ_COD_QAS_QMS = q.COD_QAS_QMS
                        left join RH_QUADRO.ORGAO o 
                          on o.codom = m.OM_CODOM
                        left join RH_QUADRO.RM rm 
                          on o.rm_cod = rm.CODIGO
                        left join RH_QUADRO.COMANDO_MILITAR_AREA cma 
                          on rm.CMA_CODIGO = cma.codigo
                        where m.om_codom = {$_POST['omse']}
                          and pg.codigo in ({$postoGraduacao})  
                          and status = 1"   
                        );
        oci_execute($consultapessoa);
        oci_fetch_all($consultapessoa, $pessoas,null, null, OCI_FETCHSTATEMENT_BY_ROW  );
        //Conexão com BD Oracle         
	foreach($pessoas as $pessoa){                                
		foreach($pessoa as $campo => $valor){
                    $$campo = stripslashes($valor);
                }    
                $pg_sigla = $SIGLA;
                $qasqms = $SIGLA_QAS_QMS . " - " . $DESC_QAS_QMS;
                $rm = $RM_SIGLA . " - " . $RM_DESC;
                $cma = $CMA_SIGLA . " - " . $CMA_DESC;
                $om = $OM_SIGLA . " - " . $OM_DESC;
                $NOME = addslashes ($pessoa['NOME']);                                
                $NOME_PAI = addslashes ($NOME_PAI);
                $NOME_MAE = addslashes ($NOME_MAE);
                $NOME_GUERRA = addslashes ($NOME_GUERRA);
                $rm = addslashes ($rm);
                $qasqms = addslashes ($qasqms);
                $codigo_pg = addslashes ($POSTO_GRAD_CODIGO);
                $PREC_CP = empty($PREC_CP) ? '0' : $PREC_CP;

                $query = "update cadastro 
                             set cad_nome = '$NOME',
                                 cad_pai = '$NOME_PAI',
                                 cad_mae ='$NOME_MAE',
                                 cad_sexo = '$SEXO',
                                 cad_nascimento = '$DT_NASCIMENTO',
                                 cad_cpf = '$CPF',
                                 cad_nomeguerra = '$NOME_GUERRA',
                                 cad_postograd = '$pg_sigla',
                                 cad_codpg = '$codigo_pg',
                                 cad_qasqms = '$qasqms',
                                 cad_preccp = '$PREC_CP',
                                 cad_rm = '$rm',
                                 cad_cma = '$cma',
                                 cad_om = '$om', 
                                 cad_om_id = $OM_ID,    
                                 cad_atualizado = 1 
                           where cad_login = '$PES_IDENTIFICADOR_COD'";                
              //  var_dump($query);
                $result = $con->query($query);
                $status = 'Erro';                
                $codstatus = '-1';
                if ($result == 1){
                   $cad = $con->query("SELECT cad_id FROM `cadastro` WHERE cad_login = '$PES_IDENTIFICADOR_COD'");                    
                   $status =  $cad->num_rows <=0  ? "Usuário não existe no sistema" : " Atualizado";
                   $codstatus =  $cad->num_rows <=0  ? '-2' : '1';
                } 
                $resposta_json[] = ['identidade' => $pessoa['PES_IDENTIFICADOR_COD'], 
                                    'nome' => $NOME,
                                    'codstatus' => $codstatus,
                                    'status' => $status,
                                    
                                     ];
                
        } 
                  
}                
   
echo json_encode($resposta_json);
	

