<?php
require_once '/var/www/html/cidex/system/mailClass.php';
//require_once '/var/www/html/cidex/epl/baixapagamento.php';
if(@$acao){//ini se acao
	if(Token::check($token)){//ini token
		if($acao == "add_om"){
			mysqli_query($con, "insert into om (rm_id,om_sigla,om_nome,om_uf,om_municipio,flagapenasparalunos) values($rm_id,'$om_sigla','$om_nome','$om_uf','$om_municipio','$flagapenasparalunos')");
			echo "<script>alert('OM cadastrada com sucesso.');location='?id=$id'</script>";
		}
		
		if($acao == "update_om"){                   
                        $flagapenasparalunos = empty($flagapenasparalunos) ?'N' :$flagapenasparalunos;
                        
			mysqli_query($con, "update om 
                                            set rm_id = $rm_id,
                                                om_sigla = '$om_sigla',
                                                om_nome='$om_nome',
                                                om_uf = '$om_uf',
                                                om_municipio = '$om_municipio', 
                                                flagapenasparalunos = '$flagapenasparalunos' 
                                          where om_id = $om_id");
			echo "<script>alert('OM atualizada com sucesso.');location='?id=$id'</script>";
		}
		
		if($acao == "delete_om"){
			mysqli_query($con, "delete from om where om_id = $om_id");
			echo "<script>alert('OM excluida com sucesso.');location='?id=$id'</script>";
		}

        if($acao == "update_crs"){
            if($crs_dtinicio == 0 || $crs_dttermino == 0) {
                echo "<script>alert('Selecione um intevalo de datas válido!')</script>"; 

            } else {
                $inicio = new DateTime($crs_dtinicio); $dtinicio = $inicio->format('d/m/Y H:i');
                $termino = new DateTime($crs_dttermino); $dttermino = $termino->format('d/m/Y H:i');
                $dtlocal = explode("-",$crs_dtlocal); $dtlocal = $dtlocal[2] ."/". $dtlocal[1] ."/". $dtlocal[0];
                
                mysqli_query($con, "update curso set crs_dtinicio = '$crs_dtinicio',crs_dttermino = '$crs_dttermino',crs_dtlocal = '$crs_dtlocal' where crs_id <> 1 ");
                //mysqli_query($con, "update curso_idioma set ci_status = $crs_status where crs_id <> 1 ");
                $crs = mysqli_fetch_array(mysqli_query($con, "select * from curso where crs_id <> 1"));
                foreach($crs as $campo => $valor){$$campo = addslashes($valor);}
                $ativo = ($crs_status == 0)? "Não" : "Sim";
                
                echo "<script>alert('DADOS ATUALIZADOS COM SUCESSO.');location='?id=$id'</script>";
                
            }
        }

		if($acao == "update_ci"){
                    if($ci_dtinicio == 0) {
                        echo "<script>alert('Selecione uma data válida!')</script>"; 

                    } else {
			$ci_valof = str_replace(",",".",$ci_valof);
			$ci_valpraca = str_replace(",",".",$ci_valpraca);
						
			$dtinicio = explode("-",$ci_dtinicio); $dtinicio = $dtinicio[2] ."/". $dtinicio[1] ."/". $dtinicio[0];
			mysqli_query($con, "update curso_idioma set ci_vagas = $ci_vagas,ci_dtinicio = '$ci_dtinicio' where ci_id = $ci_id");
			$crs = mysqli_fetch_array(mysqli_query($con, "select * from curso crs,idioma idm, curso_idioma ci where ci.ci_id = $ci_id and crs.crs_id = ci.crs_id and idm.idm_id = ci.idm_id"));
			foreach($crs as $campo => $valor){$$campo = addslashes($valor);}
			$ativo = ($ci_status == 0)? "Não" : "Sim";
			echo "<script>alert('DADOS ATUALIZADOS COM SUCESSO.\\n\\nCURSO: ". $crs_nome ."\\n\\nDATA DO EXAME: ". $dtinicio ."\\nIDIOMA: ". $idm_nome ."\\nVAGAS: ". $ci_vagas ."');location='?id=$id'</script>";
                    }
		}

		if($acao == "gru"){          
                        set_time_limit('3600');	
			$nr = file_get_contents($_FILES['arquivo']['tmp_name']);                        
			$nr  = ltrim($nr);//limpa brancos a esquerda
			$nr  = rtrim($nr);//limpa brancos a direita
			$nr = preg_replace('/[ ]{2,}|[\t]/', ';', trim($nr));//replace "tab character" para ";"
			$lista = explode("\n", $nr);//separa nr ref por linha
			for($x = 0;$x < count($lista);$x++){//le todas as linhas separadamente
			
				$pgt = explode(";",$lista[$x]);
				$cc_id = substr($pgt[0],-6);//recupera os ultimos 6 carateres (x.x.x.xxxxxx - idioma.nivel.exame.num ref)
				if(empty($cc_id)){ $cc_id = "0"; }
				$valor = $pgt[1];
				$cpf = str_pad(rtrim($pgt[2]),11,'0', STR_PAD_LEFT);
				$autenticacao = rtrim($pgt[3]);
				$exame = substr($pgt[0], -7,1);

				if($exame != 1){//se exame não for civ
					$cc = mysqli_fetch_assoc(mysqli_query($con, "select cc.cc_id from cadastro c,cadastro_curso cc where ( (cc.cc_id = $cc_id) or (concat(`num_inscricao`,`digito_verificador`) = '$pgt[0]' )) and c.cad_cpf = '$cpf' and c.cad_id = cc.cad_id"));//verifica se usuario pagou a propria gru diferente de civ
//                                        var_dump(count($cc));exit;
					if(count($cc) > 0){//se pagamento foi realizado pelo usuario que gerou a gru
                                                
                                             
                                                $cc_id = $cc['cc_id'];
                                                $pgt_ok = mysqli_num_rows(mysqli_query($con, "select * from pagamento where cc_id = $cc_id"));
						if(empty($pgt_ok)){//se nao foi registrado pagamento
							mysqli_query($con, "insert into pagamento (cc_id,pgt_valor, pgt_cpf,pgt_autenticacao,pgt_duplicado) values($cc_id,'$valor','$cpf','$autenticacao','NÃO')");
							mysqli_query($con, "update cadastro_curso set ccs_id = 1 where cc_id = $cc_id");
							$pgt_msg = "[v] ". $cpf ." gerou GRU Nr RF ". $cc_id ." - ". $valor ."<br/>";
						}else{//se ja registrou
                                                        $pgt_dup_ok = mysqli_num_rows(mysqli_query($con, "select * from pagamento where pgt_autenticacao = '$autenticacao'"));
                                                        if(empty($pgt_dup_ok)){
                                                            mysqli_query($con, "insert into pagamento (cc_id,pgt_valor,pgt_cpf,pgt_autenticacao,pgt_duplicado) values($cc_id,'$valor', '$cpf','$autenticacao','SIM')");
                                                        }
							$pgt_msg = "[x] ". $cpf ." gerou GRU Nr RF ". $cc_id ." - ". $valor ." - Pagamento já registrado anteriormente<br/>";
						}
					}else{//se pagamento nao foi realizado pelo usuario que gerou a gru
						$cad = mysqli_fetch_array(mysqli_query($con, "select * from cadastro where cad_cpf = '$cpf'"));
						$cad_id = $cad["cad_id"];
						$pgt_no = mysqli_num_rows(mysqli_query($con, "select * from pagamento_erro where cc_id = $cc_id and cad_id = $cad_id"));
						if(empty($pgt_no)){//se nao registrado o erro do pagamento do usuario
							mysqli_query($con, "insert into pagamento_erro (cc_id,cad_id,pe_valor) values($cc_id,$cad_id,'$valor')");
							$pgt_msg = "[x] ". $cpf ." NÃO gerou GRU Nr RF ". $cc_id ." - ". $valor ."<br/>";
						}else{//se ja registrou
							$pgt_msg = "[x] ". $cpf ." NÃO gerou GRU Nr RF ". $cc_id ." - ". $valor ." - Erro no pagamento já registrado anteriormente<br/>";
						}
					}
					echo $pgt_msg;
					$pgt_msg_full = $pgt_msg_full .";". $pgt_msg;
				}
			}
			mysqli_query($con, "insert into pagamento_extrato (pex_texto) values('$pgt_msg_full')");
			echo "<script>alert('Pagamentos carregados com sucesso.')</script>";
		}
		
		if($acao == "del_gru"){
			mysqli_query($con, "update cadastro_curso set ccs_id in(0,11) where cc_id = $cc_id");//altera para 0 (aguardando pagamento)
			mysqli_query($con, "delete from pagamento where cc_id = $cc_id");//remove pagamento da tabela pagamento
			echo "<script>alert('O pagamento foi excluído com sucesso.');location='?id=$id'</script>";
		}
		
		if($acao == "clear_crs"){
			$vagas = mysqli_fetch_array(mysqli_query($con, "select * from curso_idioma where crs_id = $crs_id and idm_id = $idm_id"));//total de vagas do curso/exame selecionado
			$ci_vagas = $vagas["ci_vagas"];
			$npv = mysqli_query($con, "select * from cadastro_curso where crs_id = $crs_id and idm_id = $idm_id and cp_id = $cp_id limit $ci_vagas");//lista candidatos de 1 ate o total de vagas dentro do periodo atual
			$novasvagas = 0;
			while($lista_npv = mysqli_fetch_array($npv)){
				foreach($lista_npv as $campo => $valor){$$campo = addslashes($valor);}
				if( in_array($ccs_id, [0,11] ) ) {//se esta dentro do numero de vagas, porem nao pagou (ccs_id = 0)
					$novasvagas++;
					mysqli_query($con, "insert into cadastro_curso_lixo (cad_id,cc_id,cp_id,crs_id,idm_id,nivel_id,cl_id,cc_date,ccs_id) values($cad_id,$cc_id,$cp_id,$crs_id,$idm_id,$nivel_id,$cl_id,'$cc_date',6)");//excluido
					mysqli_query($con, "delete from cadastro_curso where cc_id = $cc_id and ccs_id in(0,11) ");
				}
			}
			$u_id = $_SESSION["uid"];
			mysqli_query($con, "insert into curso_novasvagas (crs_id,idm_id,ci_vagas,cnv_qtd,u_id) values ($crs_id,$idm_id,$ci_vagas,$novasvagas,$u_id)");//grava a limpeza de inscricoes nao pagas
			echo "<script>alert('Foram abertas $novasvagas novas vagas.');location='?id=$id'</script>";
		}
		
		if($acao == "update_gru"){//atualizar dados da GRU
			$venc = explode("/",$vencimento); $vencimento = $venc[2] ."-". $venc[1] ."-". $venc[0];
			mysqli_query($con, "update curso_gru set cg_nome = '$nome',cg_codrec = '$codrec',cg_vencimento = '$vencimento',cg_favorecido = '$favorecido',cg_gestao = '$gestao' where crs_id = $cid");
			echo "<script>alert('Dados da GRU atualizados com sucesso.');location='?id=$id&cid=$cid'</script>";
		}
		
		if($acao == "cancelhomolog"){
			$cc = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso where cc_id = $cc_id"));
			if($cc){
				foreach($cc as $campo => $valor){$$campo = stripslashes($valor);}
				mysqli_query($con, "insert into cadastro_curso_lixo (cad_id,crs_id,idm_id,nivel_id,cl_id,cc_date,ccs_id) values($cad_id,$crs_id,$idm_id,$nivel_id,$cl_id,'$cc_date',3)");
				mysqli_query($con, "delete from cadastro_curso where cc_id = $cc_id");
				echo "<script>alert('Cancelamento realizado com sucesso.\\n\\nLembrando que a inscrição estava paga e a devolução do valor dependerá de autorização.');location='?id=$id'</script>";
			}
		}
		
		if($acao == "homologall"){//homologa todas as gratuidades
			$i = 0;
			$cc = mysqli_query($con, "select * from cadastro_curso cc,cadastro cad where cad.cad_id = cc.cad_id and cc.cp_id = $cp_id and cad.cad_codpg in ($codgp) and (cc.crs_id = 2 or cc.crs_id = 4 or cc.crs_id = 5) and (cc.idm_id = 2 or cc.idm_id = 4) and cc.ccs_id in(0,11) ");
			while($cc_lista = mysqli_fetch_array($cc)){
				$uid = $_SESSION["uid"];
				$cc_id = $cc_lista["cc_id"];
				$gratis = mysqli_fetch_array(mysqli_query($con, "select * from gratuidade where cc_id = $cc_id"));
				if($gratis){
					mysqli_query($con, "update gratuidade set gra_status = 1,user_id = $uid where cc_id = $cc_id");//gratuidade homologada
				}else{//se
					mysqli_query($con, "insert into gratuidade (cc_id,gra_status,user_id) values($cc_id,1,$uid)");
				}
				mysqli_query($con, "insert into pagamento (cc_id) value($cc_id)");
				mysqli_query($con, "update cadastro_curso set ccs_id = 1 where cc_id = $cc_id");//aluno pago
				$i++;
			}
			echo "<script>alert('$i gratuidades homologadas com sucesso.');location='?id=$id'</script>";
		}
		
		if($acao == "gratishomolog"){
			mysqli_query($con, "update cadastro_curso set ccs_id = 1 where cc_id = $cc_id");//aluno pago
			$uid = $_SESSION["uid"];
			mysqli_query($con, "update gratuidade set gra_status = 1,user_id = $uid where cc_id = $cc_id");//gratuidade homologada
			mysqli_query($con, "insert into pagamento (cc_id) value($cc_id)");
			echo "<script>alert('Gratuidade homologada com sucesso.');location='?id=$id'</script>";
		}
                
                
                if($acao == "update_email"){//ini atualiza dados aluno
    			$cad_id = $identidade;
			mysqli_query($con, "update cadastro set cad_mail = '$mail' where cad_login = '$cad_id'");
			echo "<script>alert('Email atualizado com sucesso.');location='?a=$a'</script>";
                }
		
		if($acao == "gratisnega"){
			$uid = $_SESSION["uid"];
			mysqli_query($con, "update gratuidade set gra_status = 2,user_id = $uid where cc_id = $cc_id");//gratuidade negada
			$cc = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso where cc_id = $cc_id"));
			if($cc){
				foreach($cc as $campo => $valor){$$campo = stripslashes($valor);}
				mysqli_query($con, "insert into cadastro_curso_lixo (cad_id,crs_id,idm_id,nivel_id,cl_id,cc_date,ccs_id) values($cad_id,$crs_id,$idm_id,$nivel_id,$cl_id,'$cc_date',3)");
				mysqli_query($con, "delete from cadastro_curso where cc_id = $cc_id");
			}
			echo "<script>alert('Gratuidade negada com sucesso.');location='?id=$id'</script>";
		}
		
		if($acao == "add_manual"){
			$rs = mysqli_fetch_array(mysqli_query($con, "select * from cadastro where cad_login = $rg"));
			foreach($rs as $campo => $valor){$$campo = stripslashes($valor);}
			mysqli_query($con, "insert into cadastro_curso (cad_id,crs_id,idm_id,nivel_id,cl_id) values($cad_id,$crs_id,$idm_id,$nivel,322)");
			echo "<script>alert('Inscrição manual realizada com sucesso para ". $rg ."');location='?id=$id'</script>";
		}
                
                if($acao == "manut_usuario"){
                    $nome = strip_tags($_POST['nome']);
                    $idusuario = strip_tags($_POST['idusuario']);
                    $idperfil = strip_tags($_POST['idperfil']);
                    $omse = strip_tags($_POST['omse']);
                    $login = strip_tags($_POST['login']);
                    $email = strip_tags($_POST['email']);
                    $usuarioNovo =  $_POST['idusuario'] == -1 ;
                    $gerarsenha = $usuarioNovo || key_exists('gerarsenha', $_POST);
                    
                    if ( isset($_POST['idiomas']) ){
                        $idiomas = $_POST['idiomas'];
                        $posto = $_POST['postogrduacao'];

                        $idiomas = join(',', $idiomas);
                        $posto = join(',', $posto);
                    }else{
                        $idiomas = null;
                        $posto = null;

                    }
                    
                    $senha = substr(str_shuffle("abcdefghijkLmnopqrstuvwxyz0123456789"), 0, 4);//nao tem O maiusculo                                        
                    $senha_crypt = encripta($senha);
                    $texto = 'Usuário atualizado com sucesso.';
                    if ( $usuarioNovo  ){                      
                        $strSQL = "insert into user(u_nome,u_pass,u_ip,u_login, idperfil, omse,postograduacaoliberados,idiomasliberados, email) values
                                                   ('{$nome}','{$senha_crypt}','', '{$login}', '{$idperfil}', '{$omse}', '{$posto}','{$idiomas}', '{$email}')";
                    }else{                        
                        $novasenha = ($gerarsenha) ? ", u_pass = '{$senha_crypt}'" : '';
                        $strSQL = "update user
                                    set u_nome = '{$nome}', 
                                        u_login = '{$login}', 
                                        idperfil = '{$idperfil}',  
                                        omse = '{$omse}', 
                                        postograduacaoliberados = '{$posto}', 
                                        idiomasliberados = '{$idiomas}',
                                        email    = '{$email}' $novasenha
                                  where u_id = {$idusuario}";
                    }
                   /* var_dump($strSQL);
                    echo "<br>";                                        
                    die;*/
                    if ( execSQL($strSQL) ) {
                       if ($gerarsenha ){
                           /*Envia Email com a senha*/                           
                           $corpo = MailClass::getMensagemPadrao("<a href='{$_SERVER['SERVER_NAME']}/cidex/epl'>Link para acesso ({$_SERVER['SERVER_NAME']}/cidex/epl ) </a> <br> 
                                                                  Usuário para acesso: {$login} <br> 
                                                                  Senha para acesso: {$senha}");
                           $resposta = MailClass::enviaEmailSecretaria($email, 'Cadastro na adminiostração do EPL CIDEx', $corpo);
                           $texto = ($resposta) ? "Foram enviados para o e-mail {$email} os dados de acessos na administração do EPL " : "Ocorreu um errp ao enviar o e-mail";
                    
                       } 
                    }
                    
                    echo "<script>alert('{$texto}');location='?id=$id'</script>";
                }
                
                if($acao == "manut_periodo"){                  
                    $novo = $_POST['cp_id'] == "";
                    $localportaria = $_POST['localportaria'];
                    if (count($_FILES) > 0 ){
                      $localportaria = '/var/www/html/cidex/portarias/'.$_FILES['portaria_file']['name'];  
                      move_uploaded_file($_FILES['portaria_file']['tmp_name'], $localportaria);  
                   } 
                   $cp_nome = $_POST['ano'].'.'.$_POST['turma'];                   
                   
                   
                   if ( key_exists('cp_pesquisa', $_POST ) ){
                     $cp_pesquisa = $_POST['cp_pesquisa'] ;
                   } else {
                       $cp_pesquisa = 'NÃO';
                   }                             
                   if ( $novo ){
                        $strSQL = "INSERT INTO curso_periodo(cp_nome, cp_ini, cp_cor, cp_pesquisa, data_ini_inc_centralizada, data_fim_inc_centralizada, 
                                                             data_segunda_convocacao, data_divulgacao, data_local_exame, data_fim_periodo, desc_portaria, hora_ini_periodo, 
                                                             localportaria, texto_gru, linkresultado) 
			       			     VALUES ('{$cp_nome }', '{$_POST['cp_ini']}', 
                                                             '{$_POST['cp_cor']}' , '{$cp_pesquisa}', '{$_POST['data_ini_inc_centralizada']}', '{$_POST['data_fim_inc_centralizada']}', 
                                                             '{$_POST['data_segunda_convocacao']}','{$_POST['data_divulgacao']}', 
                                                             '{$_POST['data_local_exame']}', '{$_POST['data_fim_periodo']}', '{$_POST['desc_portaria']}', '{$_POST['hora_ini_periodo']}', '{$localportaria}',
                                                             '{$_POST['texto_gru']}','{$linkresultado}')";
                    } else{
                        $strSQL =   "UPDATE curso_periodo 
                                        SET cp_nome= '{$cp_nome }',
                                                cp_ini= '{$_POST['cp_ini']}',
                                                cp_cor= '{$_POST['cp_cor']}',
                                                cp_pesquisa ='{$cp_pesquisa}',
                                                data_ini_inc_centralizada= '{$_POST['data_ini_inc_centralizada']}',
                                                data_fim_inc_centralizada= '{$_POST['data_fim_inc_centralizada']}',
                                                data_segunda_convocacao = '{$_POST['data_segunda_convocacao']}',
                                                data_divulgacao = '{$_POST['data_divulgacao']}',
                                                data_local_exame = '{$_POST['data_local_exame']}',
                                                data_fim_periodo = '{$_POST['data_fim_periodo']}',
                                                desc_portaria = '{$_POST['desc_portaria']}',
                                                hora_ini_periodo = '{$_POST['hora_ini_periodo']}',
                                                localportaria = '{$localportaria}',
                                                texto_gru = '{$_POST['texto_gru']}',
                                                linkresultado = '{$_POST['linkresultado']}'
                                     WHERE cp_id= {$_POST['cp_id']}";
                    }
                   
                    if ( execSQL($strSQL) ){
               
                    }
                    echo "<script>alert('Sucesso!');location='?id=$id'</script>";
                    
                }
                
                if ($acao == "baixapagamento"  ){
                    $baixa = new BaixaPagamentos();
                    $dataIni = new DateTime( $_POST['dataini'] );
                    $dataFim = new DateTime( $_POST['datafim'] );
                    $baixa->procecessaPagamentos( $baixa->getPagamentos($dataIni, $dataFim) );
                    die;
                   // echo "<script>alert('Sucesso!');location='?id=$id'</script>";
                }
                
		
	}else{//se nao token
		echo "<script>location='?id=100'</script>";
	}//fim token
}//fim se acao
?>