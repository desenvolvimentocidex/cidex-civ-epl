<?php
require  realpath(__DIR__ . DIRECTORY_SEPARATOR . '..').DIRECTORY_SEPARATOR .'candidato.php';
@header("Content-Type: text/html; charset=utf-8");


ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

define('PERFIL_USUARIO_ADM', 'admin');
define('PERFIL_USUARIO_SECRETARIA', 'secretaria');

$dbstr ="(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.67.7.129)(PORT = 1521))
(CONNECT_DATA =
(SERVER = DEDICATED)
(SERVICE_NAME = ebcorp)
))";

$db_username = "ceadex";
$db_password = "brasil_ceadex_23422";

// CONEXAO DGP ORACLE
$oci_connect = oci_connect($db_username,$db_password, $dbstr,'AL32UTF8');

@ $rg = addslashes((int)$_POST["rg"]);//IDENTIFICADOR_COD (DGP)
@ $iid = addslashes((int)$_GET["iid"]);//idioma_id

//Codigo abaixo para o Servidor 162
$banco = "db_cidex_inscricao"; 
$con = mysqli_connect("127.0.0.1","root","", $banco,'3306') or die("ERRO: ". mysqli_error());
//Codigo abaixo para o Servidor 95
//$banco = "db_cidex_inscricao"; 
//$con = mysqli_connect("10.166.64.145","cidex95","ciadex#01@_", $banco,'3306') or die("ERRO: ". mysqli_error());
  
//$db = mysqli_select_db($banco,$con);
mysqli_query($con, "SET NAMES 'utf8'");
mysqli_query($con, "SET character_set_connection=utf8");
mysqli_query($con, "SET character_set_client=utf8");
mysqli_query($con, "SET character_set_results=utf8");

@ $sis_nome = "Centro de Idiomas do Exército";
@ $sis_sigla = "CIDEx";

//declaracao
@session_start();
set_time_limit(0);
$custo = "08";
$salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 20);
$bgcolor1 = "#FFFFFF";
$bgcolor2 = "";
$hoje = date("Y-m-d");//data atual
@ $id = addslashes((int)$_GET["id"]);
@ $cid = addslashes((int)$_GET["cid"]);//curso id
@ $a = addslashes((int)$_GET["a"]);//link aluno
@ $iid = addslashes((int)$_GET["iid"]);//idioma id
@ $eid = addslashes((int)$_GET["eid"]);//exame_id
@ $nid = addslashes((int)$_GET["nid"]);//nivel_id
@ $oid = addslashes((int)$_GET["oid"]);//om_id
@ $nid = addslashes((int)$_GET["nid"]);//noticia_id (VER)
@ $fid = addslashes((int)$_GET["fid"]);//fotos_id
foreach($_POST as $campo => $valor){
	if($valor==''){$valor = 0;}
	$$campo = (is_array($valor))? $valor : addslashes($valor); //se array, não faz addslashes
}//campos de formulario
//foreach($_POST as $campo => $valor){$$campo =  addslashes(htmlentities($valor, ENT_QUOTES));}//campos de formulario
@ $cad_login = addslashes((int)$_POST["cad_login"]);

#images checked e nochecked para formularios
$img_checked = "<img src='imagens/icon_checked.png' class='check'/>";
$img_nochecked = "<img src='imagens/icon_nochecked.png' class='check'/>";

#ini token
require_once("csrf.class.php");//pagina cria token
$tok = Token::generate();//cria token
#fim token

if(empty($acao)){
	@$acao = addslashes($_GET["acao"]);
}

if($id == 100){
	session_destroy();
	@header("location:index.php");
}

//ini functions
function mask($val, $mask){//marcaras mask($cpf,'###.###.###-##')
	$maskared = '';
	$k = 0;
	for($i = 0; $i<=strlen($mask)-1; $i++){
		if($mask[$i] == '#'){
			if(isset($val[$k])){
				$maskared .= $val[$k++];
			}
		}else{
			if(isset($mask[$i])){
				$maskared .= $mask[$i];
			}
		}
	}
	return $maskared;
}

function formatdate($dt){//formatar datas do banco formato DATE (0000-00-00) para formato formulario (00/00/0000)
	$date = explode(" ",$dt);
	$date = $date[0];
	$date = explode("-",$date);
	$date = $date[2] ."/". $date[1] ."/". $date[0];
	echo $date;
}

function datetodb($dt){//formatar datas do formulario (00/00/0000) para formato DATE (0000-00-00)
	$date = explode("/",$dt);
	$date = $date[2] ."-". $date[1] ."-". $date[0];
	echo $date;
}

function linecolor($i){
	$li = ($i%2 == 0) ? "li1" : "li2";
	echo $li;
}
//fim functions

//ini area administrador
if($acao == "adm_login"){//ini efetua login

	if(Token::check($token)){
	//$secret = "6LcMFw0TAAAAAArOJLMtvniiykAucg1Y3Dbuyi8q";
     //   $secret = "6Lcurj4UAAAAANSTnRPXNAcd9tr2I-P56aEkZilb";
	
     //   $resposta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=".$captcha_data."&remoteip=".$_SERVER['REMOTE_ADDR']);
	//if($resposta.success){
//	echo "<script>alert('$login ------- $pass')</script>";
	
       $user = mysqli_fetch_array(mysqli_query($con, "select user.`u_id`, `u_nome`, `u_login`, `u_pass`, `u_ip`, `conexaoid`, user.`idperfil`, `omse`, `idiomasliberados`,
                                                                                perfil.nomeperfil,       
                                                                                GROUP_CONCAT(distinct postograduacao.codigodgp) as postograduacaoliberados
                                                                           from user 
                                                                          inner join perfil
                                                                             on (perfil.idperfil = user.idperfil)
                                                                           left join postograduacao
                                                                             on (find_in_set(postograduacao.idpostograduacao , user.postograduacaoliberados)   ) 
                                                                          where u_login =  '$login'
                                                                          group by user.`u_id`, `u_nome`, `u_login`, `u_pass`, `u_ip`, `conexaoid`, user.`idperfil`, `omse`,                `idiomasliberados`,
                                                                                perfil.nomeperfil"));																										   
			$u_pass = $user["u_pass"];
			if($user){ 
				if(encripta($pass) === $u_pass){
					$_SESSION["login"] = $user["u_login"];
					$_SESSION["pass"] = $pass;
					$_SESSION["loged"] = "adm_on";
                                        $_SESSION["perfil"] = $user["nomeperfil"];
					$_SESSION["omse"] = $user["omse"];
                                        $_SESSION["uid"] = $user["u_id"];
                                        $_SESSION["atualizado"] = "0";
                                        $_SESSION["postograduacaoliberados"] = $user["postograduacaoliberados"];
                                        $_SESSION["idiomasliberados"] = $user["idiomasliberados"];
                                        $_SESSION["omacess"] = $user["omse"];
                 			chmod("../capas/",0777);
					header("location:index.php");                                        
                                        
                                        
				}else{
					echo "<script>alert('Erro ao efetuar login.\\n\\nSenha incorreta.');location='index.php?id=100'</script>";
				}
			}else{
				echo "<script>alert('Erro ao efetuar login.\\n\\nDados de usuário incorretos ou usuário não cadastrado.');location='index.php?id=100'</script>";
			}
		//}else{
		//	echo "<script>alert('Não foi possível efetuar login.');location='index.php?id=100'</script>";
		//	exit;
		//}
	}
}//fim efetua login
if(@$_SESSION["loged"] == "adm_on"){
	@$login = $_SESSION["login"];
	$user = mysqli_fetch_array(mysqli_query($con, "select * from user where u_login = '$login'"));
	if($user){
		@$pass = $_SESSION["pass"];
		$u_pass = $user["u_pass"];
		if(encripta($pass) === $u_pass){
			$_SESSION["loged"] = "adm_on";
		}else{
			header("location:index.php?id=100");
		}
	}else{
		header("location:index.php?id=100");
	}
}

if(@$acao == "add" || @$acao == "onoff" || @$acao == "del" || @$acao == "update" || @$acao == "mkfotos" || @$acao == "sendfile" || @$acao == "delfoto"){//ini operacoes admin site
	if(Token::check($token)){//ini token
		switch($id){//ini variaveis
			case 1:
				@$titulo = @$dia .";". @$titulo;
				$tab = "avisos";
				$col_id = "avs_id";
				$col_titulo = "avs_titulo";
				$col_texto = "avs_texto";
				$col_status = "avs_status";
				$rsp = "Aviso";
				break;
			case 2:
				$tab = "noticias";
				$col_id = "not_id";
				$col_titulo = "not_titulo";
				$col_texto = "not_texto";
				$col_status = "not_status";
				$rsp = "Notícia";
				if($acao == "add" || ($acao == "update" && $_FILES['anexo']['name'])){include("send.php");}
				break;
			case 3:
				$tab = "capas";
				$col_id = "cps_id";
				$col_titulo = "cps_titulo";
				$col_texto = "cps_texto";
				$col_status = "cps_status";
				$rsp = "Capa";
				if($acao == "add" || ($acao == "update" && $_FILES['anexo']['name'])){include("send.php");}
				break;
			case 4:
				$tab = "fotos";
				$col_id = "fts_id";
				$col_titulo = "fts_titulo";
				$col_texto = "fts_texto";
				$texto = "";
				$col_status = "fts_status";
				$rsp = "Galeria";
				if(@$acao == "sendfile"){include("send.php");echo "<script>alert('Foto enviada com sucesso.');location='?id=$id'</script>";}
				$pasta = "../fotos/". $reg_id;
				if(@$acao == "del"){
					if(is_dir($pasta)){
						$diretorio = dir($pasta);
						while($arquivo = $diretorio->read()){
							if(($arquivo != '.') && ($arquivo != '..')){
								unlink($pasta."/".$arquivo);
							}
						}
						$diretorio->close();
					}
					rmdir($pasta);
				}
				if(@$acao == "delfoto"){
					unlink($pasta ."/". $foto);
					echo "<script>alert('Foto excluída com sucesso');location='?id=$id'</script>";
				}
				break;
			case 5:
				$tab = "youtube";
				$col_id = "ytb_id";
				$col_titulo = "ytb_titulo";
				$col_texto = "ytb_link";
				$col_status = "ytb_status";
				$rsp = "Vídeo";
				@$titulo = $tempo.";".$titulo;
				break;
			case 6:
				$tab = "conteudo";
				$col_id = "ctd_id";
				$col_titulo = "ctd_titulo";
				$col_texto = "ctd_texto";
				$rsp = "Conteúdo";
				break;
		}//fim variaveis
		
		if(@$acao == "add"){//adicionar
			mysqli_query($con, "insert into $tab ($col_titulo,$col_texto) values('$titulo','$texto')");
			echo "<script>alert('$rsp cadastrado com sucesso.');location='?id=$id'</script>";
		}

		if(@$acao == "onoff"){//ativar/desativar
			mysqli_query($con, "update $tab set $col_status = $onoff where $col_id = $reg_id");
			$act = ($onoff == 1)? "ativada" : "desativada";
			echo "<script>alert('$rsp $act com sucesso.');location='?id=$id'</script>";
		}

		if(@$acao == "del"){//excluir
			mysqli_query($con, "delete from $tab where $col_id = $reg_id");
			echo "<script>alert('$rsp excluída com sucesso.');location='?id=$id'</script>";
		}

		if(@$acao == "update"){//atualizar
			mysqli_query($con, "update $tab set $col_titulo = '$titulo',$col_texto = '$texto' where $col_id = $reg_id");
			echo "<script>alert('$rsp atualizada com sucesso.');location='?id=$id'</script>";
		}
		
		if(@$acao == "mkfotos"){//cria pasta foto
			mysqli_query($con, "insert into fotos(fts_titulo) values('$titulo')");
			$fts = mysqli_fetch_array(mysqli_query($con, "select * from fotos order by fts_id desc"));
			$pasta = $fts["fts_id"];
			if($pasta){
				mkdir("../fotos/". $pasta, 0777);
				echo "<script>alert('Pasta fotos/$pasta criada com sucesso');location='?id=$id'</script>";
			}
		}
	}else{//se token invalido
		echo "<script>location='index.php?id=100'</script>";
	}//fim token
}//fim operacoes admin site
//fim area administrador

//ini inscricoes aluno
if($acao == "cad_login"){//ini efetua login aluno
	
	if (isset($_POST['g-recaptcha-response'])) {
		$captcha_data = "6Lcurj4UAAAAANZ3xtKcKt7s6nCQ0N9EQqe9pFqP";
//                $captcha_data = $_POST['g-recaptcha-response'];
	}

	// Se nenhum valor foi recebido, o usuario não realizou o captcha
	//if (!$captcha_data) {
		//echo "<script  type='text/javascript'>alert('Selecione a caixa Não sou um robô.');setTimeout(function(){ location='?a=2'; }, 3000);</script>";
		//exit;
	//}
        
	if(Token::check($token)){
#		$secret = "6LcMFw0TAAAAAArOJLMtvniiykAucg1Y3Dbuyi8q";
		//$secret = "6Lcurj4UAAAAANSTnRPXNAcd9tr2I-P56aEkZilb";
		//$resposta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=".$captcha_data."&remoteip=".$_SERVER['REMOTE_ADDR']);
//		if($resposta.success){
//            exit;
            if (  in_array($cad_login, [ '0400015954','0205710973','0829969443','0434440145','0334363041','0478469539','0192282531','0195297031','0762069334','0420437345']) ) {
                    header('Location: index.php?blq=1');
                    exit;
                }
			$cad = mysqli_fetch_array(mysqli_query($con, "select * from cadastro where cad_login = $cad_login"));
			$cad_pass = $cad["cad_pass"];
			if($cad){//se aluno existe
                            
                                      // atualiza cadastro do militar no DGP
                                      $login = $cad["cad_login"];

//                                      $pessoa = mysqli_fetch_array(mysqli_query("select m.*,p.*,pg.*,q.*,o.CODOM as om_id, o.RM_COD as om_rm_id, o.SIGLA as om_sigla, o.NOME as om_desc, rm.CODIGO as rm_id, rm.CMA_CODIGO as rm_cma_id, cma.codigo as cma_id, rm.sigla as rm_sigla, rm.descricao as rm_desc, cma.sigla as cma_sigla, cma.descricao as cma_desc 
//                                      from MILITAR m
//                                      left join PESSOA p on m.PES_IDENTIFICADOR_COD = p.IDENTIFICADOR_COD
//                                      left join POSTO_GRAD_ESPEC pg on m.POSTO_GRAD_CODIGO = pg.CODIGO
//                                      left join QAS_QMS q on m.QQ_COD_QAS_QMS = q.COD_QAS_QMS
//                                      left join ORGAO o on o.codom = m.OM_CODOM
//                                      left join RM rm on o.rm_cod = rm.CODIGO
//                                      left join COMANDO_MILITAR_AREA cma on rm.CMA_CODIGO = cma.codigo
//                                      where m.PES_IDENTIFICADOR_COD = '$login'", $condgp));

                                    //Conexão com BD Oracle 
                                    $consultapessoa = oci_parse($oci_connect, "select m.*,p.*,pg.*,q.*,o.CODOM as om_id, o.RM_COD as om_rm_id, 
                                                                                      o.SIGLA as om_sigla, o.NOME as om_desc, rm.CODIGO as rm_id, 
                                                                                      rm.CMA_CODIGO as rm_cma_id, cma.codigo as cma_id, rm.sigla as rm_sigla, 
                                                                                      rm.descricao as rm_desc, cma.sigla as cma_sigla, 
                                                                                      cma.descricao as cma_desc, cidade.nome as cidade, uf.sigla as uf 
                                      from RH_QUADRO.MILITAR m
                                      left join RH_QUADRO.PESSOA p on m.PES_IDENTIFICADOR_COD = p.IDENTIFICADOR_COD
                                      left join RH_QUADRO.POSTO_GRAD_ESPEC pg on m.POSTO_GRAD_CODIGO = pg.CODIGO
                                      left join RH_QUADRO.QAS_QMS q on m.QQ_COD_QAS_QMS = q.COD_QAS_QMS
                                      left join RH_QUADRO.ORGAO o on o.codom = m.OM_CODOM
                                      left join RH_QUADRO.RM rm on o.rm_cod = rm.CODIGO
                                      left join RH_QUADRO.COMANDO_MILITAR_AREA cma on rm.CMA_CODIGO = cma.codigo
                                     left join rh_quadro.cidade
                                        on (cidade.codigo = o.cidade_cod)
                                      left join rh_quadro.uf
                                        on (rh_quadro.uf.codigo = cidade.uf_cod)
                                      where m.PES_IDENTIFICADOR_COD = '$login'");
                                    oci_execute($consultapessoa);
                                    $pessoa = oci_fetch_array($consultapessoa, OCI_ASSOC+OCI_RETURN_NULLS);
                                    //Conexão com BD Oracle 
                                    $codigo = "";
                                    
                                    if ($pessoa) {
                                        foreach ($pessoa as $campo => $valor) {
                                            $$campo = stripslashes($valor);
                                    }

                                        $pg_sigla = $SIGLA;
                                        $qasqms = $SIGLA_QAS_QMS . " - " . $DESC_QAS_QMS;
                                        $rm = $RM_SIGLA . " - " . $RM_DESC;
                                        $cma = $CMA_SIGLA . " - " . $CMA_DESC;
                                        $om = $OM_SIGLA . " - " . $OM_DESC;
										if ( empty($PREC_CP)  ){
										   $PREC_CP = 0;
										}

                                        $NOME = addslashes($NOME);
                                        $NOME_PAI = addslashes($NOME_PAI);
                                        $NOME_MAE = addslashes($NOME_MAE);
                                        $NOME_GUERRA = addslashes($NOME_GUERRA);
                                        $rm = addslashes($rm);
                                        $qasqms = addslashes($qasqms);
                                        $codigo_pg = addslashes ($POSTO_GRAD_CODIGO);										
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
							 cad_atualizado = 1,
                                                         cad_om_municipio = '{$CIDADE}',
                                                         cad_om_uf = '{$UF}',
                                                         cad_om_id = {$OM_ID}
					   	   where cad_login = '$login'";
                                        
                                        mysqli_query($con, $query);
                                    }
				// valida a senha do usuário
				//mudança na cripotografia da senha				
				$novoHash = encripta($pass);									
				if ( strpos($cad_pass, "$2a$") !== false ){
					header("location: index.php?a=atualizasenha");
					return;
				}
                if($novoHash  == $cad_pass ){//verifica se senha form = cadastrada
					$_SESSION["cad_id"] = $cad["cad_id"];
					$_SESSION["login"] = $cad["cad_login"];
					$_SESSION["pass"] = $pass;
					$_SESSION["loged"] = "on";
					$_SESSION["mail"] = $cad["cad_mail"];
					$_SESSION["codpg"] = $cad["cad_codpg"];
                			if($cad["cad_codpg"] == 54 || $cad["cad_codpg"] == 55 || $cad["cad_codpg"] == 56 || $cad["cad_codpg"] == 57 || $cad["cad_codpg"] == 62 || $cad["cad_codpg"] == 64){$_SESSION["aluno"] = "on";}//se for aluno
					$msg = "Prezado candidato EPLE/EPLO, para a realização do exame, é necessária a apresentação do cartão de confirmação de inscrição, com Situação: INSCRITO REGULARMENTE (PAGO). Cada cartão corresponde a um exame específico.";
					echo "<script type='text/javascript'>alert('Atenção! Bem-vindo, ". $cad["cad_postograd"] ." ". $cad["cad_nome"] ." ". $msg ."');</script>";
					echo "<script>alert('Sr candidato.\\n\\nPara realizar inscrição nos cursos/exames disponíveis o sr deve atualizar seus CONTATOS.'); location='?a=2'; ;</script>";
				}else{//se senha errada
					echo "<script type='text/javascript'>alert('Erro ao efetuar login. Senha incorreta.');setTimeout( function() {location='index.php?id=100'}, 3000);</script>";
				}
			}else{//se aluno nao existe
				echo "<script type='text/javascript'>alert('Erro ao efetuar login. Dados de usuário incorretos ou usuário não cadastrado.');setTimeout( function() {location='index.php?id=100'}, 3000);</script>";
			}
//		}else{
//			echo "<script>alert('Não foi possível efetuar login.');location='index.php?id=100'</script>";
//			exit;
//		}
	}
}
//fim efetua login aluno

if(@$_SESSION["loged"] == "on"){
	@$login = $_SESSION["login"];
	@$pass = $_SESSION["pass"];
	$cad = mysqli_fetch_array(mysqli_query($con, "select * from cadastro where cad_login = '$login'"));
	if($cad){
		$cad_pass = $cad["cad_pass"];
		if(encripta($pass) === $cad_pass){
			$_SESSION["cad_id"] = $cad["cad_id"];
			$_SESSION["loged"] = "on";
		}else{
			header("location:index.php?id=100");
		}
	}else{
		header("location:index.php?id=100");
	}
}

if(@$acao == "cad_aluno"){//ini cadastro aluno
	if(Token::check($token)){
		$cad = mysqli_fetch_array(mysqli_query($con, "select * from cadastro where cad_login = '$login'"));
		if($cad){
			echo "<script>alert('ERRO AO CADASTRAR\\n\\nO usuário $login já encontra-se em nossa base de dados.');</script>";
		}else{
			
			$_SESSION["loged"] = "on";
			$_SESSION["login"] = $login;
			$_SESSION["pass"] = $senha;
			$_SESSION["mail"] = $mail;
			$senha_crypt = encripta($senha);
			if($codpg == 54 || $codpg == 55 || $codpg == 56 || $codpg == 57 || $codpg == 62 || $codpg == 64){//ini se for aluno
				$_SESSION["aluno"] = "on";
				$_SESSION["codpg"] = $codpg;
			}//fim se for aluno
                        $strSQL = "insert into cadastro (cad_login,cad_nome,cad_pass,cad_mail,cad_pai,
                                                         cad_mae,cad_sexo,cad_nascimento,cad_cpf,cad_nomeguerra,
                                                         cad_postograd,cad_codpg,cad_qasqms,cad_preccp,cad_rm,
                                                         cad_cma,cad_om, cad_endereco,cad_tel,cad_cel,cad_status, cad_om_id ) 
                                                  values('$login','$nome','$senha_crypt','$mail','$pai','$mae',$sexo,'$nascimento',"
                                . "                      '$cpf','$nomeguerra','$postograd',$codpg,'$qasqms',$preccp,'$rm','$cma','$om','','','',0, $codom)";
                        mysqli_query($con, $strSQL);
                        
			echo "<script>alert('Cadastro realizado com sucesso.');location='?a=3&cid=". $_SESSION["crs"] ."'</script>";
		}
	}else{
		header("location:index.php?id=100");
	}
}//fim cadastro aluno

if(@$acao == "update_aluno"){//ini atualiza dados aluno
	if(Token::check($token)){
		if($_SESSION["cad_id"]){//se session[cad_id] ok: usuario logado
			$cad_id = $_SESSION["cad_id"];
			$pais = "Brasil";
			//$endereco = $rua .";". $nr .";". $comp .";". $bairro .";". $cidade .";". $uf .";". $pais .";". $cep;
			$tel = (int)$telddd .";". (int)$telnr;
			$cel = (int)$celddd .";". (int)$celnr;
			mysqli_query($con, "update cadastro set cad_tel = '$tel',cad_cel = '$cel', cad_mail = '$mail' where cad_id = $cad_id");
			echo "<script>alert('Dados atualizados com sucesso.');location='?a=$a'</script>";
		}
	}
}//fim atualiza dados aluno


if(@$acao == "add_curso"){//ini inscricao curso                            
	if(Token::check($token)){            
		if($_SESSION["cad_id"]){//se session[cad_id] ok: usuario logado
			$cad_id = $_SESSION["cad_id"];
			if(empty($cl_id)){$cl_id = 0;}//civ nao precisa de local, lc_id = 0

			//ini se civ / epl
			if($crs_id == 1){//se curso for civ
				$ci = mysqli_fetch_array(mysqli_query($con, "select * from curso_idioma where crs_id = 1 and idm_id = $idm_id"));
				$periodo = $ci["ci_periodo"];//qtd parcelas
				$vencimento = mysqli_fetch_array(mysqli_query($con, "select * from curso_gru where crs_id = $crs_id"));//vencimento da GRU
				$cg_vencimento = $vencimento["cg_vencimento"];//vencimento 1a parcela
				$vencimento1 = explode("-", $cg_vencimento); $vencimento1 = $vencimento1[0] ."-". $vencimento1[1] ."-05";//vencimento todo dia 05
				//ini gravar 12 parcelas
				$y = 0;
				for($x = 1;$x <= $periodo; $x++){				
					if($x == 1){//se 1a parcela, grava o valor do vencimento
						$vencimento = $cg_vencimento;
					}else{//se 2a parcela em diante, grava o todo dia 05 do mes seguinte da 1a parcela
						$y++;
						$vencimento = date("Y-m-d", strtotime("$vencimento1 +$y month"));
					}
					$insert = "insert into cadastro_curso (cad_id,crs_id,idm_id,nivel_id,cl_id,cc_parcela,cc_vencimento) values($cad_id,$crs_id,$idm_id,$nivel,$cl_id,$x,'$vencimento')";//insert
					mysqli_query($con, $insert);
				}
				//fim gravar 12 parcelas
			}else{//se curso for epl
                            //Conexão com BD Oracle 
                          /* $sql = "SELECT PROF.PES_IDENTIFICADOR_COD,IDI.DESCRICAO as IDIOMA, MAX(PROF.nivel_compr_auditiva) as COMP_AUD,MAX(PROF.nivel_expr_oral) as EXP_ORAL,MAX(PROF.nivel_compr_leitora) as COMP_LEIT,MAX(PROF.nivel_expr_escrita) as EXP_ESC 
                            FROM RH_QUADRO.INDICE_PROF_LINGUISTICA PROF 
                            INNER JOIN RH_QUADRO.IDIOMA_IPL IDI ON PROF.codigo_idioma = IDI.codigo 
                            WHERE PROF.PES_IDENTIFICADOR_COD = '$login' and PROF.codigo_idioma = ".$idm_id." GROUP BY PROF.PES_IDENTIFICADOR_COD,IDI.DESCRICAO";

                            $dados = ociparse($oci_connect,$sql);
                            ociexecute($dados);

                            $dado = oci_fetch_assoc($dados);
//                            var_dump(($nivel > $dado['COMP_AUD']));exit;
                            if($crs_id == 3 || $crs_id == 6){
                                if($nivel <= $dado['COMP_AUD'] && $nivel == ($dado['EXP_ORAL'])+1) {
                                    $testeNivel = true;
                                } else {
                                    $testeNivel = false;
                                }
                            } else {
                                $testeNivel = true;
                            }*/
                            $cadidato = Candidato::getCandidato(); 

							switch ($nivel){
								case $nivel == Candidato::MULTINIVEL: /*&& $crs_id == 5 : //EE*/
									$niveis = [1,2];
									break;
								/*case $nivel == Candidato::MULTINIVEL && $crs_id != 5 : //diferente de EE
   									    $niveis = [2];
										break;*/
		                        default: 
								   $niveis = [$nivel];		
							}
						

						    foreach( $niveis as $item_Nivel ) {
								$nivel =  $item_Nivel;
								
								if( $cadidato->verificaNivel($idm_id,$crs_id, $nivel)  ){
									
									$strSQL = "SELECT count(*) as qtd
												 FROM `cadastro_curso`
												WHERE cad_id = {$cad_id}
											      and crs_id = {$crs_id}
												  and idm_id = {$idm_id}
												  and nivel_id = {$nivel}
												  and cp_id = {$cp_id}";
									$qtd = getSQLMySQL($strSQL)[0]['qtd'];
									if ($qtd == 1){
										echo "<script>alert('Você já possui inscrição neste idioma.'); setTimeout(function(){ location='index.php?a=3' }, 1000);</script>"; 
										exit;
									}
									AtualizaConexaoId();
									$id_status = 11; // Aguardando pagamento
									if ( $cadidato->temGratuidade($crs_id ) ){
										$id_status = 1; // Pago
										
									}
									
									
									$insert = "insert into cadastro_curso (cad_id,crs_id,cp_id,idm_id,nivel_id,cl_id, ccs_id,cc_parcela,cc_vencimento, insc_centralizada) values($cad_id,$crs_id,$cp_id,$idm_id,$nivel,$cl_id,$id_status,0,null, {$_SESSION['ESCOLAR']} )";//insert                               								
									mysqli_query($con, $insert);	
								
									
									#verifica informacoes do curso
									$crs = mysqli_fetch_array(mysqli_query($con, "select * from curso, idioma where crs_id = $crs_id and idm_id = $idm_id"));//nome do curso
									$msg_nivel = ($crs_id == 1)? "" : " - Nível $nivel";
									$crs_nome = $crs["crs_nome"] ." - ". $crs["idm_nome"] .$msg_nivel;

									//ini verfica se o idioma para o curso esta na vaga (se 1:ativo; se 0:aguardando)
									if($crs_id == 1){//se civ
										$cc = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso where crs_id = $crs_id and idm_id = $idm_id and cc_parcela = 1 and ccs_id in(0,1,2,11)"));//verifica quantos cadastros tem nao pagos, pagos e aguardando cancelamento
									}else{//se epl
										$cc = mysqli_num_rows(mysqli_query($con, "select * from cadastro_curso where cp_id = $cp_id and crs_id = $crs_id and idm_id = $idm_id"));
										$ccRef = mysqli_fetch_assoc(mysqli_query($con, "select * from cadastro_curso where cad_id = $cad_id and cp_id = $cp_id and crs_id = $crs_id and idm_id = $idm_id and nivel_id = $nivel"));
									}
									$action_redirect = 10;                               
									if ( $cadidato->temGratuidade($crs_id ) ){
									$insert = "INSERT INTO pagamento(cc_id,pgt_valor,pgt_data,pgt_cpf) VALUES({$ccRef['cc_id']},0,CURRENT_DATE, '{$cadidato->getCPF()}' )";
									mysqli_query($con, $insert);
									mysqli_query($con, "insert into gratuidade (cc_id) values({$ccRef['cc_id']})");
									$action_redirect = 4;
									}
								
									$bol_id = str_pad($ccRef['cc_id'], 6, "0", STR_PAD_LEFT);//999.999 registros

									$vaga = mysqli_fetch_array(mysqli_query($con, "select * from curso_idioma where crs_id = $crs_id and idm_id = $idm_id"));//qtd de vaga para o idioma e curso
									if($vaga["ci_vagas"] >= $cc){                                    
										header('Location:?a='.$action_redirect.'&b=1&crsid='.$crs_id.'&cpid='.$cp_id.'&idmid='.$idm_id.'&ref='.$bol_id);

										#echo "<script>alert('INSCRIÇÃO REALIZADA COM SUCESSO\\n\\nCurso: $crs_nome\\n\\nRealize o pagamento da GRU dentro do prazo para garantir a sua vaga.');location='?a=1'</script>";
										#location='mailer.php?formail=". $_SESSION["mail"] ."&forbody=Sua solicitação de inscrição no ". $crs_nome ." foi realizada com sucesso.<br/><br/>Gere sua GRU e realize o pagamento dentro do vencimento para garantir a sua vaga.<br/><br/>Equipe CEADEx&forsubject=". $crs_nome ."&fornome=Candidato&a=1';
									}else{                
			//                            $pos = $cc - $vaga["ci_vagas"];
										header('Location:?a='.$action_redirect.'&b=2&crsid='.$crs_id.'&cpid='.$cp_id.'&idmid='.$idm_id);
			//                            echo "<script>alert('Sua solicitação de inscrição no ". $crs_nome ." foi realizada com sucesso, porém ultrapassou o número de vagas disponível.\\n\\nA sua posição é a ".$pos."ª da fila de espera.\\n\\nAguarde abertura de novas vagas.');location='?a=$a'</script>";
									}
								}else {
					
									echo "<script type='text/javascript'>alert('Nível selecionado está incorreto!');setTimeout( function() {location='?a=10'}, 30000) </script>";
	//                                header('Location:?a=10');
								}
						    }
                            
                        }
			//fim se civ / epl
		
		}
	}
}//fim inscricao curso

if(@$acao == "del_curso"){//ini deleta curso
	if(Token::check($token)){
		if($_SESSION["cad_id"]){//se session[cad_id] ok: usuario logado
			$cad_id = $_SESSION["cad_id"];
			#verifica informacoes
			$crs = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso cc,curso crs, idioma idm where cc.crs_id = crs.crs_id and cc.idm_id = idm.idm_id and cc.cc_id = $cc_id and cad_id = $cad_id"));//nome do curso
			foreach($crs as $campo => $valor){$$campo = stripslashes($valor);}
			$curso = $crs_nome ." - ". $idm_nome;
			if(in_array($ccs_id, [0,11] )){//aguardando inscricao deleta da tabela cadastro_curso e joga no cc_lixo
				$cc = mysqli_fetch_array(mysqli_query($con, "select * from cadastro_curso where cc_id = $cc_id"));
				foreach($cc as $campo => $valor){$$campo = stripslashes($valor);}
				mysqli_query($con, "insert into cadastro_curso_lixo (cad_id,cp_id,crs_id,idm_id,nivel_id,cl_id,ccs_id,cc_date) values($cad_id,$cp_id,$crs_id,$idm_id,$nivel_id,$cl_id,5,'$cc_date')");//copia para lixo
				mysqli_query($con, "delete from cadastro_curso where cc_id = $cc_id and cad_id = $cad_id");//deleta da tabela cadastro_curso
//				$msg = "Sua inscrição no ";
			}
			if($ccs_id == 1){//inscrito (altera aluno para aguardando delete)
				mysqli_query($con, "update cadastro_curso set ccs_id = 2 where cc_id = $cc_id and cad_id = $cad_id");
//				$msg = "Sua inscrição no ";
			}
			if($ccs_id == 2){//aguardando exlusao cancela exclusao
				mysqli_query($con, "update cadastro_curso set ccs_id = 1 where cc_id = $cc_id and cad_id = $cad_id");
//				$msg = "Sua inscrição no ";
			}
			echo "<script type='text/javascript'>alert('Inscrição cancelada com sucesso!'); setTimeout( function() {location='?a=1'}, 30000);</script>";
		}
	}
}//fim deleta curso

if(@$acao == "update_local"){//ini deleta curso
	if(Token::check($token)){
		if($_SESSION["cad_id"]){//se session[cad_id] ok: usuario logado
			mysqli_query($con, "update cadastro_curso set cl_id = $cl_id,nivel_id = $nivel where cc_id = $cc_id");
			$om = mysqli_fetch_array(mysqli_query($con, "select * from curso_local cl, om where cl.om_id = om.om_id and cl.cl_id = $cl_id"));
			$om_nome = $om["om_nome"] ." (". $om["om_sigla"] .") - ". $om["om_municipio"] ." - ". $om["om_uf"];
			echo "<script>alert('Dados atualizados com sucesso.\\n\\nNível: $nivel\\nOMSE: $om_nome');location='?a=$a'</script>";
		}
	}
}//fim deleta curso

if(@$acao == "update_pass"){//ini alterar senha
	if(Token::check($token)){
		$login = $_SESSION["login"];
		$user = mysqli_fetch_array(mysqli_query($con, "select * from cadastro where cad_login = '$login'"));
		$cad_pass = $user["cad_pass"];
		if($user){
			if(empty($pass1) || empty($pass2) || strlen($pass1) < 8){
				echo "<script>alert('Preencha os campos corretamente.\\n\\nA senha deve ser preencha com no mínimo 8 caracteres.');location='?a=5'</script>";
			}else{
				if(encripta($pass0) === $cad_pass){
					if($pass1 == $pass2){
						$senha_crypt = encripta($pass1);
						mysqli_query($con, "update cadastro set cad_pass = '$senha_crypt' where cad_login = '$login'");
						echo "<script>alert('A senha foi alterada com sucesso.\\n\\nAcesse sua conta com a nova senha.');location='?a=100'</script>";
					}else{
						echo "<script>alert('A nova senha e a confirmada não são iguais.');location='?a=5'</script>";
					}
				}else{
					echo "<script>alert('A senha atual não está correta.');location='?a=5'</script>";
				}
			}
		}
	}
}//fim alterar senha
function AtualizaConexaoId(){    
    global $con;
    if ( !empty( $_SESSION["uid"] )){        
       mysqli_query($con, "update user set conexaoid = CONNECTION_ID() where u_id = ". $_SESSION["uid"]);
    } 

    if ( !empty( $_SESSION["cad_id"] )){    
       mysqli_query($con, "update cadastro set conexaoid = CONNECTION_ID() where cad_id = ". $_SESSION["cad_id"]);
    } 
    
}
function getSQLMySQL($strSQL){
    global $con;
    $resposta = mysqli_query($con,$strSQL);
    $retorno =  mysqli_fetch_all($resposta, MYSQLI_ASSOC );     
    if ( empty($retorno) ){        
        $campos = mysqli_fetch_fields($resposta); 
        $retorno = [];
        foreach ($campos as $campo){
          $retorno[0][$campo->name] = "";  
        }
        
    }
    return $retorno ;
}
function execSQL($strSQL){
    global $con;    
    return mysqli_query($con,$strSQL);
}

AtualizaConexaoId();

function getUltimoPeriodo(){
   $strSQL = "SELECT `cp_id`, `cp_nome`, `cp_ini`, `cp_cor`, `cp_pesquisa`, `data_ini_inc_centralizada`, 
                     `data_fim_inc_centralizada`, `data_segunda_convocacao`, `data_divulgacao`, 
                     `data_local_exame`, `data_fim_periodo`, `desc_portaria`, `hora_ini_periodo`,
                     `localportaria`, `texto_gru`, escolar 
                FROM `curso_periodo` 
              /* WHERE cp_ini <= CURRENT_DATE*/
                order by cp_ini desc
                limit 1"; 
   return getSQLMySQL($strSQL);
}

function getPeriodoAnterior(){
   $strSQL = "SELECT `cp_id`, `cp_nome`,  linkresultado
                FROM `curso_periodo` 
                /*WHERE cp_ini <= CURRENT_DATE*/
                order by cp_ini desc
                limit 1,1"; 
   return getSQLMySQL($strSQL);
}


function formatData($data){
  $date = new DateTime($data);
  $formatter = new IntlDateFormatter('pt_BR',
                                    IntlDateFormatter::FULL,
                                    IntlDateFormatter::NONE,
                                    'America/Sao_Paulo',          
                                    IntlDateFormatter::GREGORIAN, 'd MMM Y');
  return  strtoupper($formatter->format($date));  
  
}
function formatHora($hora){
  $date = new DateTime($hora);
  return $date->format('H:i');  
}
function atualizaInfoPeriodo(){
    
    $periodo = getUltimoPeriodo()[0];	
    $periodoAnterior = getPeriodoAnterior()[0];
    $nome =  explode('.', $periodo['cp_nome']);
    $nomeperiodo = (count($nome) > 2 ? ' Escolar ' : 'Regular (Efetivo profissional/militar de carreira) ' ).$nome[0];
    $str_nome = $nome[1].'º EPLE/EPLO '.$nomeperiodo;
    
    $strsql = 'select cg_vencimento from curso_gru order by cg_id desc limit 1';
    $gru = getSQLMySQL($strsql);
    
    $_SESSION['DATA_SEGUNDA_CONVOCACAO'] = formatData($periodo['data_segunda_convocacao']);
    $_SESSION['DATA_DIVULGACAO'] =  formatData($periodo['data_divulgacao']);
    $_SESSION['DATA_GRU'] = formatData($gru[0]['cg_vencimento'] );
    $_SESSION['DATA_LOCAL_EXAME'] =  formatData($periodo['data_local_exame']);
    $_SESSION['NOME_PERIODO'] = $str_nome;
    $_SESSION['DATA_INI_PERIODO'] = formatData($periodo['cp_ini']);
    $_SESSION['DATA_FIM_PERIODO'] = formatData($periodo['data_fim_periodo'] );
    $_SESSION['PORTARIA'] = $periodo['desc_portaria'];
    $_SESSION['HORA_INI_PERIODO'] = formatHora( $periodo['hora_ini_periodo'] );    
    $_SESSION['LOCAL_PORTARIA'] = $periodo['localportaria'];
    $_SESSION['NOME_PORTARIA'] = $periodo['desc_portaria'];
    $nome =  explode('.', $periodoAnterior['cp_nome']);
    $str_nome = $nome[1].'º EPLE/EPLO '.$nome[0];
    $_SESSION['NOME_PERIODO_ANTERIOR'] = $str_nome;
    $_SESSION['RESULTADO_PERIODO_ANTERIOR'] = $periodoAnterior['linkresultado'];
	$_SESSION['ESCOLAR'] = $periodo['escolar'];
}

function getLinkPortaria(){
    return "<a href='{$_SESSION['LOCAL_PORTARIA']}'> {$_SESSION['NOME_PORTARIA']} </a>";
}

function  encripta($texto){
	return hash('sha256',$texto);										
}

atualizaInfoPeriodo();
//fim inscricoes aluno


?>