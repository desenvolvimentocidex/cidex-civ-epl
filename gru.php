<?php

echo "<script>alert('Sr. candidato.\\n\\nIMPORTANTE:\\nAplicativos para Smart Phones do BB NÃO realizam pagamentos de GRU (Opção de cobrança não disponível (G512-592)).\\n\\nAs GRUs geradas devem ser pagas, exclusivamente, no Banco do Brasil, das seguintes formas:\\n\\nCorrentistas do Banco do Brasil:\\n1. Pelo site do BB;\\n2. Pelo telefone do BB;\\n3. Caixa Eletrônico do BB; ou\\n\\nCorrentistas e não correntistas do Banco do Brasil:\\n1. No caixa da agência do BB dentro do horário bancário.\\n\\nAtenção com os campos Número de Referência e CPF. ')</script>";
include 'system/system.php';

if(Token::check($token)){    
	$cc = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso cc,cadastro cad,curso crs where cc.cc_id = $cc_id and cc.cad_id = cad.cad_id and cc.crs_id = crs.crs_id"));
	if($cc){
		foreach($cc as $campo => $valor){$$campo = addslashes($valor);}
		$ci = mysqli_fetch_array(mysqli_query($con, "select * from curso_idioma where crs_id = $crs_id and idm_id = $idm_id"));
		/*$val = ($cad_codpg <= 18)? $ci["ci_valof"] : $ci["ci_valpraca"];//se menor q 18: oficial, se não praca
		$val = str_replace(".",",",$val);*/
                
                $candidato = Candidato::getCandidato();
                $val = $candidato->getValorCurso( $crs_id );
		
		if( $val == 0 /*$_SESSION["aluno"] == "on" && ($idm_id == 2 || $idm_id == 4) */){//se for cadete e idioma for espanhol(2) ou ingles(4)
			$val = "0,00";
			$gra = mysqli_num_rows(mysqli_query($con, "select * from gratuidade where cc_id = $cc_id"));//verifica se existe solicitacao de gratuidade
			if($gra == 0){//se nao solicitou gratuidade
				mysqli_query($con, "insert into gratuidade (cc_id) values($cc_id)");
			}
//                        "<script>alert('Aviso: Não é necessário o pagamento de GRU para inscrição de Alunos nos idiomas: Inglês e Espanhol.');location='index.php?a=1'</script>";
		}
		
		#dados da GRU
		$cg = mysqli_fetch_array(mysqli_query($con, "select * from curso_gru where crs_id = $crs_id"));
		foreach($cg as $campo => $valor){$$campo = addslashes($valor);}

		$codigo_correlacao = "02349";
		$boleto = "3";
		$impressao = "SA";
		$pagamento = "1";
		$campo = "NRCR";
		$ind = "0";
		$nome_contribuinte = $cad_nome;
		$nome_favorecido = $cg_nome;
		$codigo_recolhimento = $cg_codrec;
		$bol_id = str_pad($cc_id, 6, "0", STR_PAD_LEFT);//999.999 registros
		$referencia = $idm_id.$nivel_id.$crs_id.$bol_id;
		
                if($crs_id == 1){
                    $venc = explode("-",$cc_vencimento);//vencimento na tabela cadastro_curso

                    /*** Verifica se a parcela é Inscrição ou Renovação, se for inscrição não permite que gere uma nova data. ***/    
                    if (($cc_parcela % 12) == 1) {
                        $inscricaoVencida = false;
                    } else {
                        $inscricaoVencida = true;
                    }
                    /** Fim **/
                    /*** Verifica se a GRU está vencida, 
                     * se estiver gera a GRU com prazo de 5 dias a partir da data atual, 
                     * não gera nova data para Inscrição **/
                    if(strtotime(date('Y-m-d')) > strtotime($cc_vencimento) && $inscricaoVencida){
                        $vencimento = date('d/m/Y', strtotime('+5 days'));//$venc[2] ."/". $venc[1] ."/". $venc[0];//data de vencimento
                    }else{
                        $vencimento = $venc[2] ."/". $venc[1] ."/". $venc[0];//data de vencimento
                    }
                        $competencia = $venc[1]."/".$venc[0];
                }else{
                    $venc = explode("-",$cg_vencimento);//vencimento na tabela curso_gru
                    $vencimento = $venc[2] ."/". $venc[1] ."/". $venc[0];//data de vencimento
                    $competencia = $venc[1]."/".$venc[0];
                }

		$cnpj_cpf = mask($cad_cpf,'###.###.###-##');
		$codigo_favorecido = $cg_favorecido;
		$gestao = $cg_gestao;
		$valorPrincipal = $val;
		$descontos = "";
		$deducoes = "";
		$multa = "";
		$juros = "";
		$acrescimos = "";
		$valorTotal = $val;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://pagtesouro.tesouro.gov.br/api/gru/portal/boleto-gru");
		curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, 
		          http_build_query(array('codigoUg' => $codigo_favorecido,
				  						 "nomeContribuinte"	 => $nome_favorecido ,
										 "codigoRecolhimento" =>$codigo_recolhimento,
										 "nomeRecolhimento" => "EXER/FDO-SERV EDUC PROFISSIONAL",
										 "numeroReferencia" => $bol_id ,
										 "competencia" => $competencia,
										 "vencimento" => $vencimento ,
										 "cpfCnpjContribuinte" => $cnpj_cpf,										 
										 "valorPrincipal" => $valorTotal,
										 "descontosAbatimentos" => 0,
										 "outrasDeducoes" => 0,
										 "moraMulta" => 0,
										 "jurosEncargos" => 0,
										 "outrosAcrescimos" => 0,
										 "valorTotal" => $valorTotal

				)));

		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		curl_close($ch);
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename=gru-CIDEX-'.$nome_favorecido.".pdf");
		header('Pragma: no-cache');
		readfile($server_output);

		
	}
}else{
	header("location:index.php");
}

/*echo "A geração da GRU será disponibilizada em breve.";*/
?>