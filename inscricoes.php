<?php
$hojeDtTime = date("Y-m-d H:i:s");
if (isset($_SESSION["cad_id"])) {
	echo "<script>alert('O Sr já está está logado.\\n\\nPara novas inscrições, clique no link Novas inscrições na Área do Aluno.');location='index.php?a=3'</script>";
}

//verifica se o curso chamado esta no periodo de inscricao
if ($cid <> 0 && $cid <> 9999) {
	$crs = mysqli_num_rows(mysqli_query($con, "select * from curso where crs_id = $cid and crs_status = 1 and ('$hojeDtTime' between crs_dtinicio and crs_dttermino)"));
	if ($crs == 0) {
		echo "<script>alert('Curso fora do período de inscrição.');location='?id=5'</script>";
	}
}
?>
<div id="inscricoes">
	<?php
	if ($rg) {
		if (Token::check($token)) { //ini token
			$cad = mysqli_num_rows(mysqli_query($con, "select * from cadastro where cad_login = '$rg'"));
			if ($cad >= 1) { //se pessoa ja existir no cadastro do ceadex
				$cad_login = "";
				echo "<script>alert('ERRO AO LOCALIZAR IDENTIDADE.\\n\\nNr de Identidade Militar já cadastrado.\\n\\nEntre com sua identidade e senha na ÁREA DO ALUNO.');setTimeout(function(){ location='index.php'; }, 3000);</script>";
				exit;
			}
			$candidato = new Candidato($rg);
			if (! $candidato->getMilitarlocalizadoDGP()) {
				echo "<script>alert('Nr de Identidade Militar não localizado.');setTimeout(function(){ location='index.php?id=5&cid=9999'; }, 3000);</script>";
				exit;
			}
			/* var_dump($candidato->getTipocandidato());
                var_dump($candidato->getTipomilitar());
                var_dump($candidato->getAtivo());
                
                exit;*/
			if (
				($candidato->getTipomilitar() != Candidato::TIPO_MILITAR_CARREIRA &&
					$candidato->getTipocandidato() != 'ALUNO') ||
				(! $candidato->getAtivo() &&
					$candidato->getTipomilitar() == Candidato::TIPO_MILITAR_CARREIRA) ||
				$candidato->todosCursosBloqueados()
			) {
				echo "<script>alert('Militar não autorizado a realizar o cadastro.');setTimeout(function(){ location='index.php?id=5&cid=9999'; }, 3000);</script>";
				exit;
			}

			/*
		if($aluno == 0){//ini se não for aluno de escolas, consulta bd dgp
			$_SESSION["aluno"] = "off";
			//			$pessoa = mysql_fetch_array(mysql_query("select * from MILITAR m left join QAS_QMS q on q.cod_qas_qms = m.QQ_COD_QAS_QMS where m.PES_IDENTIFICADOR_COD = $rg",$-condgp));//status=1(ativa);mil_type=1(carreira)
                        
                        //Conexão com BD Oracle 
                        $consultapessoa = oci_parse($oci_connect, "select * from RH_QUADRO.MILITAR m left join RH_QUADRO.QAS_QMS q on q.cod_qas_qms = m.QQ_COD_QAS_QMS where m.PES_IDENTIFICADOR_COD = '$rg'");
                        oci_execute($consultapessoa);
                        $pessoa = oci_fetch_array($consultapessoa, OCI_ASSOC+OCI_RETURN_NULLS);
                        //Conexão com BD Oracle 
                        
			if($pessoa){//se pessoa existir
				foreach($pessoa as $campo => $valor){$$campo = stripslashes($valor);}
				if (($MIL_TYPE == 1 && $STATUS = 1) || ($POSTO_GRAD_CODIGO == 54 || $POSTO_GRAD_CODIGO == 55 || $POSTO_GRAD_CODIGO == 56 || $POSTO_GRAD_CODIGO == 57 || $POSTO_GRAD_CODIGO == 64) || ($COD_QAS_QMS == "AAA1" || $COD_QAS_QMS == "AAA2" || $COD_QAS_QMS == "AAA4")){ //AAA1 São alunos da ALUNO CFO/QCO (EsFCEx) / AAA2 São alunos ALUNO CFO/SAUDE (ESSEX) / AAA4 ALUNO IME 5 ANO /CFRM/1º TEN R/2 QUADRO MATERIAL BÉLICO
					//ini dados militar
					//$militar = mysqli_fetch_array(mysqli_query($con, "select * from PESSOA p,MILITAR m,POSTO_GRAD_ESPEC pg,QAS_QMS q where m.PES_IDENTIFICADOR_COD = $PES_IDENTIFICADOR_COD and p.IDENTIFICADOR_COD = m.PES_IDENTIFICADOR_COD and m.POSTO_GRAD_CODIGO = pg.CODIGO and m.QQ_COD_QAS_QMS = q.COD_QAS_QMS",$-condgp));
//					$militar = mysqli_fetch_array(mysqli_query($con, "select * from PESSOA p,MILITAR m,POSTO_GRAD_ESPEC pg where m.PES_IDENTIFICADOR_COD = $PES_IDENTIFICADOR_COD and p.IDENTIFICADOR_COD = m.PES_IDENTIFICADOR_COD and m.POSTO_GRAD_CODIGO = pg.CODIGO",$-condgp));
                                    //Conexão com BD Oracle 
                                        $consultamilitar = oci_parse($oci_connect, "select * from RH_QUADRO.PESSOA p,RH_QUADRO.MILITAR m,RH_QUADRO.POSTO_GRAD_ESPEC pg where m.PES_IDENTIFICADOR_COD = '$PES_IDENTIFICADOR_COD' and p.IDENTIFICADOR_COD = m.PES_IDENTIFICADOR_COD and m.POSTO_GRAD_CODIGO = pg.CODIGO");
                                        oci_execute($consultamilitar);
                                        $militar = oci_fetch_array($consultamilitar, OCI_ASSOC+OCI_RETURN_NULLS);
                                        //Conexão com BD Oracle 
					if($militar){
						foreach($militar as $campo => $valor){$$campo = stripslashes($valor);}
					}
					//ini qms
//					$qms = mysqli_fetch_array(mysqli_query($con, "select * from MILITAR m,QAS_QMS q where m.PES_IDENTIFICADOR_COD = $rg and m.QQ_COD_QAS_QMS = q.cod_qas_qms",$-condgp));
                                        //Conexão com BD Oracle 
                                        $consultaqms = oci_parse($oci_connect, "select * from RH_QUADRO.MILITAR m,RH_QUADRO.QAS_QMS q where m.PES_IDENTIFICADOR_COD = '$rg' and m.QQ_COD_QAS_QMS = q.cod_qas_qms");
                                        oci_execute($consultaqms);
                                        $qms = oci_fetch_array($consultaqms, OCI_ASSOC+OCI_RETURN_NULLS);
                                        //Conexão com BD Oracle 
                                        
					if($qms){
						foreach($qms as $campo => $valor){$$campo = stripslashes($valor);}
					}
					//ini om militar
//					$om = mysqli_fetch_array(mysqli_query($con, "select o.CODOM as om_id, o.RM_COD as om_rm_id, o.SIGLA as om_sigla, o.NOME as om_desc, rm.CODIGO as rm_id, rm.CMA_CODIGO as rm_cma_id, cma.codigo as cma_id, rm.sigla as rm_sigla, rm.descricao as rm_desc, cma.sigla as cma_sigla, cma.descricao as cma_desc from ORGAO o, RM rm, COMANDO_MILITAR_AREA cma where o.codom = $OM_CODOM and o.rm_cod = rm.CODIGO and rm.CMA_CODIGO = cma.codigo",$-condgp));
                                        
                                        //Conexão com BD Oracle 
                                        $consultaom = oci_parse($oci_connect, "select o.CODOM as om_id, o.RM_COD as om_rm_id, o.SIGLA as om_sigla, o.NOME as om_desc, rm.CODIGO as rm_id, rm.CMA_CODIGO as rm_cma_id, cma.codigo as cma_id, rm.sigla as rm_sigla, rm.descricao as rm_desc, cma.sigla as cma_sigla, cma.descricao as cma_desc from RH_QUADRO.ORGAO o, RH_QUADRO.RM rm, RH_QUADRO.COMANDO_MILITAR_AREA cma where o.codom = $OM_CODOM and o.rm_cod = rm.CODIGO and rm.CMA_CODIGO = cma.codigo");
                                        oci_execute($consultaom);
                                        $om = oci_fetch_array($consultaom, OCI_ASSOC+OCI_RETURN_NULLS);
                                        //Conexão com BD Oracle 
					if($om){
						foreach($om as $campo => $valor){$$campo = stripslashes($valor);}
					}
				}else{
					$cad_login = "";
					echo "<script>alert('Nr de Identidade Militar não localizado ou militar não autorizado a realizar o cadastro.');setTimeout(function(){ location='index.php?id=5&cid=9999'; }, 3000);</script>";
					exit;
				}
			}else{
				$cad_login = "";
				echo "<script>alert('Nr de Identidade Militar não localizado ou militar não autorizado a realizar o cadastro.');setTimeout(function(){ location='index.php?id=5&cid=9999';</script>";
				exit;
			}
		}else{//se for aluno
			$_SESSION["aluno"] = "on";
			$IDENTIFICADOR_COD = $rg;
			if($aluno == 1){
				$codigo = 57;//codigo do cadete
				$SEXO = 1;
				$descricao = "Cadete";
			}
			if($aluno == 2){
				$codigo = 63;//codigo do aluno 4o ano do ime
				$SEXO = 1;
				$descricao = "Aluno IME 4º Ano";
			}
			if($aluno == 3){
				$codigo = 64;//codigo do aluno esc formacao de sargento
				$SEXO = 1;
				$descricao = "Aluno Esc Formação de Sargento";
			}	
		}//fim se não for aluno de escolas, consulta bd dgp
   oci_close($oci_connect); // Fecha a Conexão oracle*/

	?>
			<fieldset>
				<legend>Preencha os dados</legend>
				<form method="post" onsubmit="return fix(2)">
					<input type="hidden" name="token" value="<?= $tok ?>" />
					<input type="hidden" name="acao" value="cad_aluno" />
					<input type="hidden" name="pai" value="<?= $candidato->getNome_pai() ?>" />
					<input type="hidden" name="mae" value="<?= $candidato->getNome_mae() ?>" />
					<input type="hidden" name="sexo" value="<?= $candidato->getSexo() ?>" />
					<input type="hidden" name="nascimento" value="<?= $candidato->getNascimento() ?>" />
					<input type="hidden" name="cpf" value="<?= $candidato->getCpf() ?>" />
					<input type="hidden" name="nomeguerra" value="<?= $candidato->getNomeguerra() ?>" />
					<input type="hidden" name="postograd" value="<?= $candidato->getPostograduacao() ?>" />
					<input type="hidden" name="qasqms" value="<?= $candidato->getSigla_qas_qms() . " - " . $candidato->getDesc_qas_qms() ?>" />
					<input type="hidden" name="preccp" value="<?= $candidato->getPrec_cp() ?>" />
					<input type="hidden" name="rm" value="<?= $candidato->getSigla_Rm() . " - " . $candidato->getRm() ?>" />
					<input type="hidden" name="cma" value="<?= $candidato->getSigla_cma() . " - " . $candidato->getDesc_cma() ?>" />
					<input type="hidden" name="om" value="<?= $candidato->getOm() . " - " . $candidato->getOm_nome() ?>" />
					<input type="hidden" name="codpg" value="<?= $candidato->getCod_dgp_postograduacao() ?>" />
					<input type="hidden" name="codom" value="<?= $candidato->getCodOM() ?>" />
					<ul>
						<li><label>Nome:</label><input class="w300" type="text" name="nome" value="<?= $candidato->getNome() ?>" <?php if ($_SESSION["aluno"] == "off") {
																																		echo "readonly";
																																	} ?> /><br /></li>
						<li><label>Identidade:</label><input class="w300" type="text" name="login" value="<?= $candidato->getIdentidade() ?>" readonly /></li>
						<?php if (empty($candidato->getCpf())) { ?><li><label>CPF:</label><input class="w300" type="text" name="cpf" maxlength="11" /><?php } ?>
							<li><label>E-mail:</label><input id="mail" class="w300" type="text" name="mail" onblur="checkmail(this)" /> <span id="msgmail"></span></li>
							<li><label>Senha:</label><input id="senha" type="password" name="senha" onkeyup="force_check()" onkeypress="return keyban(event)" onblur="checkpass(this)" maxlength="8" /> <input id="bar" disabled /><input id="force_text" type="hidden" /> (alfanumérico de 8 caracteres)</li>
							<li><label>Confirme:</label><input id="senha2" type="password" name="senha2" onblur="checkconfirm(this)" maxlength="8" /> <span id="msgpass"></span></li>
							<li class="alignc"><input class="botao" type="submit" value="Cadastrar" onclick="return check_pass()" /></li>
					</ul>
				</form>
			</fieldset>
		<?php
		} else { //se token for errado
			echo "<script>location='index.php?id=5'</script>";
		} //fim token
	} else { //se ainda nao consultou RG
		if (!$cid) {
		?>
			<ul>
				<?php
				$hojeDtTime = date("Y-m-d H:i:s");
				$crs = mysqli_query($con, "select * from curso where crs_status = 1 and ('$hojeDtTime' between crs_dtinicio and crs_dttermino) order by crs_id"); //lista cursos
				while ($crs_lista = mysqli_fetch_array($crs)) {
					foreach ($crs_lista as $campo => $valor) {
						$$campo = stripslashes($valor);
					}
				?>
					<li><a href="?id=5&amp;cid=<?= $crs_id ?>"><?= $crs_cod ?> - <?= $crs_nome ?></a></a>
					<?php } ?>
			</ul>
			<br /><br />
			<b>Informações:</b><br />
			3 - Acessar o menu <b>IDIOMAS --> Exames EPL --> Inscrições</b> e realizar seu cadastro pessoal assim como as inscrições nos exames desejados.<br />
			4 - Maiores esclarecimentos contactar o CIDEx - Centro de Idiomas do Exército pelos seguintes canais de atendimento.<br />
			Telefones:
			Secretaria EPL: (021)3223-5054 - Chefia - 2519-4663<br />
			E-mail EPL: secrctf@cidex.eb.mil.br<br />
		<?php
		} else {
			$_SESSION["crs"] = $cid;
			$_SESSION["login"] = ""; //limpa outro usuario ja logado
		?>
			<fieldset>
				<legend>Nr Identidade Militar</legend>
				<form method="post" action="?id=5">
					<input type="hidden" name="token" value="<?= $tok ?>" />
					<ul>
						<li><label>Identidade:</label><input type="text" name="rg" onkeypress="return isNumberKey(event)" maxlength="10" /></li>
						<li><input type="radio" name="aluno" value="0" checked> Sou Militar de carreira e estou na ativa</li>
						<!--<li><input type="radio" name="aluno" value="1"> Sou cadete da AMAN</li>
		<li><input type="radio" name="aluno" value="2"> Sou aluno do IME (último ano)</li>
		<li><input type="radio" name="aluno" value="3"> Sou aluno do CFS (período de qualificação)</li>-->
						<li class="alignc"><input class="botao" type="submit" value="Continuar" /></li>
					</ul>
				</form>
			</fieldset>
	<?php
		} //fim selecionou curso
	} //fim rg
	?>
</div>
<?php @$_SESSION["token"] = $tok ?>