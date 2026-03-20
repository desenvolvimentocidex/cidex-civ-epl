<?php
/**
 * Classe que obtém os detalhes e regras do candidato
 *
 * @author Sgt Raphael Mattos
 */
class Candidato {
        
    private $idCadastro;
    private $identidade;
    private $postograduacao;
    private $cod_dgp_postograduacao;
    private $codPostoGraduacao;
    private $qq_cod_qas_qms;
    private $nome;
    private $nomeguerra;
    private $om;
    private $ativo;
    private $cursosDGP = null;
    private $militarlocalizadoDGP;
    private $nascimento;
    private $sexo;
    private $cpf;
    private $nome_pai;
    private $nome_mae;
    private $prec_cp;
    private $rm;
    private $desc_qas_qms;
    private $sigla_qas_qms;
    private $sigla_Rm;
    private $sigla_cma;
    private $desc_cma;
    private $om_nome;
    private $tipocandidato;
    private $cursosbloqueados;
    private $tipomilitar;
    private $codOM;
    
    CONST TIPO_MILITAR_TEMPORARIO = 0; 
    CONST TIPO_MILITAR_CARREIRA  = 1;
    CONST TIPO_MILITAR_REINTEGRADO_DEFINITIVAMENTE = 2 ;
    CONST TIPO_MILITAR_EM_ANÁLISE = 9;
    
    CONST FORMA_OBRIGATORIO_TODOS = 'T';
    CONST FORMA_OBRIGATORIO_QUALQUER_UM = 'U';
    CONST MULTINIVEL = 99;
    
    
    function __construct($identidade) {
        global $con;
        global $oci_connect;
        
        /*Obter dados do candidato no DGP e alimentar variaveis*/
        $strSQL = "SELECT cad_login, cad_id
                     FROM cadastro
                    WHERE cad_login = '".$identidade."' ";
        $cad = mysqli_query($con, $strSQL); 
        $cad = mysqli_fetch_array($cad); 
        
        $this->setIdentidade( $identidade );
        $this->setIdCadastro( $cad['cad_id'] );
        
        $strSQL = "select  PESSOA.NOME,MILITAR.NOME_GUERRA, MILITAR.OM_CODOM, 
                           QQ_COD_QAS_QMS, RH_QUADRO.MILITAR.POSTO_GRAD_CODIGO, 
                           RH_QUADRO.MILITAR.STATUS, ORGAO.SIGLA as OM, POSTO_GRAD_ESPEC.SIGLA as POSTO,
                           PESSOA.DT_NASCIMENTO, PESSOA.SEXO, PESSOA.CPF, PESSOA.NOME_PAI, PESSOA.NOME_MAE,
                           PESSOA.PREC_CP, RM.DESCRICAO as RM,QAS_QMS.DESC_QAS_QMS , QAS_QMS.SIGLA_QAS_QMS,
                           RM.SIGLA SIGLA_RM, comando_militar_area.SIGLA AS SIGLA_CMA, comando_militar_area.DESCRICAO AS DESC_CMA,
                           orgao.NOME as OM_NOME, MIL_TYPE
                      from RH_QUADRO.MILITAR
                     inner join  RH_QUADRO.ORGAO
                        on (MILITAR.OM_CODOM =  ORGAO.CODIGO)
                     inner join RH_QUADRO.PESSOA
                        on (PESSOA.IDENTIFICADOR_COD = MILITAR.PES_IDENTIFICADOR_COD)
                     inner join RH_QUADRO.POSTO_GRAD_ESPEC
                        on (POSTO_GRAD_ESPEC.CODIGO = RH_QUADRO.MILITAR.POSTO_GRAD_CODIGO) 
                      left join RH_QUADRO.RM
                        on (RM.CODIGO = ORGAO.RM_COD)
                      left join RH_QUADRO.QAS_QMS
                        on (QAS_QMS.COD_QAS_QMS = MILITAR.QQ_COD_QAS_QMS) 
                      LEFT JOIN RH_QUADRO.comando_militar_area
                        ON (comando_militar_area.CODIGO = ORGAO.CMDO_MIL_AREA_COD)  
                     where to_number(militar.PES_IDENTIFICADOR_COD) = '{$this->getIdentidade()}' ";
                     
      //Conexão com BD Oracle             
       $pessoa_res = oci_parse($oci_connect, $strSQL);
       oci_execute($pessoa_res);
       $pessoa = oci_fetch_array($pessoa_res, OCI_ASSOC+OCI_RETURN_NULLS);
       oci_close($oci_connect); // Fecha a Conexão oracle            
       //var_dump($pessoa);
       if ($pessoa == false ){
           $this->setMilitarlocalizadoDGP( false );
           return;
       }
       
       $this->setMilitarlocalizadoDGP( true );       
       $this->setCod_dgp_postograduacao($pessoa['POSTO_GRAD_CODIGO']);
       $this->setNome($pessoa['NOME']);
       $this->setNomeguerra($pessoa['NOME_GUERRA']);
       $this->setOm($pessoa['OM']);
       $this->setQq_cod_qas_qms($pessoa['QQ_COD_QAS_QMS']);
       $this->setPostograduacao($pessoa['POSTO']);
       
       $this->setNascimento($pessoa['DT_NASCIMENTO']);
       $this->setSexo($pessoa['SEXO']);
       $this->setcPF($pessoa['CPF']);
       $this->setNome_pai($pessoa['NOME_PAI']);
       $this->setNome_mae($pessoa['NOME_MAE']);
       $this->setPrec_cp($pessoa['PREC_CP']);
       $this->setRm($pessoa['RM']);
       $this->setDesc_qas_qms($pessoa['DESC_QAS_QMS']);
       $this->setSigla_qas_qms($pessoa['SIGLA_QAS_QMS']);
       $this->setSigla_Rm($pessoa['SIGLA_RM']);
       $this->setSigla_cma($pessoa['SIGLA_CMA']);
       $this->setDesc_cma($pessoa['DESC_CMA']);
       $this->setOm_nome($pessoa['OM_NOME']);
       $this->setTipomilitar($pessoa['MIL_TYPE']);
       $this->setCodOM($pessoa['OM_CODOM']);
       
       $this->setAtivo($pessoa['STATUS'] == 1);
       if ($pessoa['POSTO_GRAD_CODIGO'] == 64 || $pessoa['POSTO_GRAD_CODIGO'] == 60){ // Aluno CFS ou aluno EXPCEx
           
           if (in_array($pessoa['OM_CODOM'], [49510,49502,15438,49528] ) ){ //EsSLog, ESA,CIAvEx. Se o estiver nesse nessas OM, o aluno é do 2º ano
             $this->setCodPostoGraduacao(32);  
           } 
           else if (in_array($pessoa['OM_CODOM'], [49403] ) )
           {
              $this->setCodPostoGraduacao(28);   
           }
           else {
              $this->setCodPostoGraduacao(36);   
           }
           $this->setTipocandidato('ALUNO');
       } else{
           $qms = "0";
           if (in_array($pessoa['QQ_COD_QAS_QMS'], ['AAA1', 'AAA2', 'AAA4']) && $pessoa['POSTO_GRAD_CODIGO'] != 63 ) {
               $qms = $pessoa['QQ_COD_QAS_QMS'];
           }
           $strSQL = "select idpostograduacao , upper( tipocandidato.descricao ) as tipocandidato
                        from postograduacao 
                       inner join tipocandidato
                          on (tipocandidato.idtipocandidato = postograduacao.idtipocandidato  )
                       where codigodgp = ".$pessoa['POSTO_GRAD_CODIGO'].
                    "    and qq_cod_qas_qms = '".$qms."' ";
           $posto_res = mysqli_query($con, $strSQL); 
           //var_dump($strSQL);
           $posto = mysqli_fetch_array($posto_res);
           
           $this->setCodPostoGraduacao($posto['idpostograduacao']);
           $this->setTipocandidato($posto['tipocandidato']);
           
       }
       
    }
    
    public static function getCandidato(){                
        if ( empty($_SESSION['CANDIDATO']) ){            
          $candidato = new Candidato( $_SESSION['login'] );  
          $_SESSION['CANDIDATO'] = serialize($candidato);
        }
       $candidato = unserialize($_SESSION['CANDIDATO']); 
       return $candidato;  
    }
    
    function getCursosCursando(){
        global $con;
        /*Lista os cursos que a pessoa está fazendo*/
        $strSQL = "SELECT case when  curso.crs_cod = 6 then 3 else  curso.crs_cod end crs_cod , curso.crs_nome AS curso, cadastro_curso.idm_id, 
                          cadastro_curso.nivel_id, idioma.idm_nome AS idioma
                     FROM cadastro_curso
                    INNER JOIN curso
                       ON (curso.crs_id = cadastro_curso.crs_id ) 
                    INNER JOIN idioma
                       ON (idioma.idm_id = cadastro_curso.idm_id )
                    WHERE cad_id = {$this->getIdCadastro()}
                      AND cp_id IN ( SELECT cp_id  FROM ( SELECT cp_id FROM curso_periodo ORDER BY cp_ini DESC LIMIT 1)x )
                      AND ccs_id = 1";
       $curso_res = mysqli_query($con, $strSQL); 
       $cursos = mysqli_fetch_all($curso_res, MYSQLI_ASSOC);       
       $resposta = [];
       foreach ($cursos as $curso) {
          $resposta[] = [ "crs_cod" => $curso['crs_cod'],
                          "curso" => $curso['curso'],
                          "idm_id" => $curso['idm_id'],
                          "nivel" => $curso['nivel_id'],  
                          "idioma" => $curso['idioma'],  
                       ];
       }
       return $resposta;
    }
    
    public function getCursosNivelDGP(){
       if ( empty($this->cursosDGP) ) { 
            global $oci_connect;
            $strSQL = "select CODIGO_IDIOMA, 
                              idioma_ipl.descricao as IDIOMA,
                              max(coalesce(NIVEL_COMPR_AUDITIVA,0)) as NIVEL_COMPR_AUDITIVA , 
                              max(coalesce(NIVEL_COMPR_LEITORA,0)) as NIVEL_COMPR_LEITORA, 
                              max(coalesce(NIVEL_EXPR_ESCRITA,0)) as NIVEL_EXPR_ESCRITA, 
                              max(coalesce(NIVEL_EXPR_ORAL,0)) as NIVEL_EXPR_ORAL
                         from RH_QUADRO.indice_prof_linguistica
                        inner join RH_QUADRO.idioma_ipl
                           on ( idioma_ipl.codigo = indice_prof_linguistica.CODIGO_IDIOMA) 
                        where PES_IDENTIFICADOR_COD = '{$this->getIdentidade()}' 
                        group by CODIGO_IDIOMA, idioma_ipl.descricao" ;
            //Conexão com BD Oracle             
            $cursos_res = oci_parse($oci_connect, $strSQL);
            oci_execute($cursos_res);       
            oci_fetch_all($cursos_res, $cursos,null, null, OCI_FETCHSTATEMENT_BY_ROW  );       
            oci_close($oci_connect); // Fecha a Conexão oracle   
            $this->cursosDGP =  $cursos;            
       } 
       return $this->cursosDGP;
    }
    
    public function getIPL($ididioma){
        $cursoscursados = $this->getCursosDGP();
       
        $cursoidioma = ["NIVEL_COMPR_AUDITIVA" => 0,
                        "NIVEL_COMPR_LEITORA" => 0,
                        "NIVEL_EXPR_ESCRITA" => 0,   
                        "NIVEL_EXPR_ORAL" => 0    ];
        foreach ($cursoscursados as $curso){
           if ($curso['CODIGO_IDIOMA'] == $ididioma ){
              $cursoidioma = $curso; 
              break;
           }
       } 
       return $cursoidioma;
    }
    
    private function getRegras($idCurso, $idIdioma = null){
      global $con;  
      $idioma = ( ! empty($idIdioma)? " and idioma.idm_id = {$idIdioma} ": "" );
      
      $strSQL =   "SELECT postograduacao.descricao AS posto, curso.crs_cod AS curso, idioma.idm_nome AS idioma,
                            regrapostograduacao.bloqueado, regracurso.valor ,
                            regracurso.idregranivelminimo,coalesce(regracurso.qtdmax,0)as qtdmax,
                            regracurso.array_idm_obrigatorios,
                            regracurso.flagommilitaromse,regracurso.idomse,
                            regracurso.tipoobrigacaoidm,
                            (SELECT GROUP_CONCAT( idm_nome)
                              FROM idioma
                             WHERE FIND_IN_SET(idm_id, regracurso.array_idm_obrigatorios ) )idm_obrigatorios,
                             regracursonivel.nivel as niveis_autorizados
                       FROM regrapostograduacao
                      INNER JOIN curso
                         ON (regrapostograduacao.idcurso = curso.crs_id)
                      INNER JOIN idioma
                         ON (regrapostograduacao.ididioma = idioma.idm_id)
                      INNER JOIN postograduacao
                         ON (regrapostograduacao.idpostograduacao =  postograduacao.idpostograduacao  )
                      INNER JOIN regracurso
                         ON (regracurso.idregracurso = regrapostograduacao.idregracurso )   
                      left join regracursonivel
                         on (regracursonivel.idregracurso = regracurso.idregracurso and regracursonivel.idcurso = curso.crs_id  )
                     WHERE postograduacao.idpostograduacao = {$this->getCodPostoGraduacao()}
                       AND curso.crs_id = {$idCurso} {$idioma} ";                    
    
        $regras_res = mysqli_query($con, $strSQL); 
        $regras = mysqli_fetch_all($regras_res, MYSQLI_ASSOC);  
        return $regras;
    }
    
    private function temIdiomaObrigatorio($regras, $cursos, $idIdioma){
        if ( ! empty($regras["array_idm_obrigatorios"]) ){
              $idiomasfeitos = array_column($cursos, 'idm_id');              
              $idiomasexigidos = explode(',', $regras["array_idm_obrigatorios"] );
              $temidioma = false;
              $strcondicional = "";
              switch ( $regras['tipoobrigacaoidm'] ) {
                  case self::FORMA_OBRIGATORIO_QUALQUER_UM:
                            $strcondicional = " e/ou ";
                            if ( in_array($idIdioma, $idiomasexigidos)   ) {
                                $temidioma = true;
                            } else {
                                  for ($index = 0; $index < count($idiomasfeitos); $index++ ){
                                      if ( in_array($idiomasfeitos[$index],$idiomasexigidos ) ){
                                          $temidioma = true;
                                          break;
                                      }
                                  }
                            }
                      break;
                  case self::FORMA_OBRIGATORIO_TODOS:
                      $contador = 0;
                      $strcondicional = " e ";
                      if ( in_array($idIdioma, $idiomasexigidos)   ) {
                                $temidioma = true;
                      } else {
                                for ($index = 0; $index < count($idiomasfeitos); $index++ ){
                                      if ( in_array($idiomasfeitos[$index],$idiomasexigidos ) ){
                                          $contador += 1;
                                      }
                                }
                                $temidioma = $contador >= count($idiomasexigidos);
                      }
                      
                      break;
                  default:
                      break;
              }
             
            if (! $temidioma){
               return ["podefazer" => false,"bloqueado" =>false, "msg" =>"O senhor(a), não pode fazer esse exame. <br>
                                                      Para isso, é necessário ter cursado os idiomas ". join($strcondicional,explode(',', $regras["idm_obrigatorios"])) ];   
            }  
          }
          return false;
         
    }
    private function temNivelMinOutroIdioma($regras, $idioma){              
       if (! empty( $regras["idregranivelminimo"] ) ) {
            global $con;
            $StrSQL =  "select regranivelminimoidioma.nivelmin_ca, 
                               regranivelminimoidioma.nivelmin_ee,
                               regranivelminimoidioma.nivelmin_eo,
                               regranivelminimoidioma.nivelmin_cl,
                               regranivelminimo.formaexigencianivelmin,
                               regranivelminimo.flag_ignorar_idiomas_listados,
                               regranivelminimoidioma.ididioma,
                               idioma.idm_nome as idioma 
                          from regranivelminimo
                         INNER JOIN regranivelminimoidioma
                            ON (regranivelminimoidioma.idregranivelminimo = regranivelminimo.idregranivelminimo)
                         inner join idioma 
                            on (idioma.idm_id = regranivelminimoidioma.ididioma)
                         where regranivelminimo.idregranivelminimo = ".$regras["idregranivelminimo"]; 
            $nivelmin_res = mysqli_query($con, $StrSQL); 
            $nivelmin = mysqli_fetch_all($nivelmin_res, MYSQLI_ASSOC);              
            
            $idiomasexigidos = array_column($nivelmin,'ididioma' );
            if ( $nivelmin[0]['flag_ignorar_idiomas_listados'] ){
                if ( in_array($idioma,$idiomasexigidos) ){
                    return false;
                }
            }

            $nomeidiomasexigidos = array_column($nivelmin,'idioma' );
            $regrasnivel = [];
            foreach ($nivelmin as $nivel){
              $regrasnivel[$nivel['ididioma']] = [ 'nivelmin_ca' => $nivel['nivelmin_ca'],
                                                   'nivelmin_ee' => $nivel['nivelmin_ee'], 
                                                   'nivelmin_eo' => $nivel['nivelmin_eo'], 
                                                   'nivelmin_cl' => $nivel['nivelmin_cl'], 
                                                 ];  
            }
           
            $temnivel = false;
            $idiomasfeitos = $this->getCursosNivelDGP();            
            /*Tratar resposta do DGP q pode ser null, 0 ou - */
            $idiomacursozerado = [ 
                              'CODIGO_IDIOMA' => 0,  
                              'IDIOMA' => "",  
                              'NIVEL_COMPR_AUDITIVA' => 0,
                              'NIVEL_COMPR_LEITORA'  => 0,
                              'NIVEL_EXPR_ESCRITA'   => 0 ,
                              'NIVEL_EXPR_ORAL'      => 0 ];    
            $strcondicao = "";
            $idiomacurso_temp = FALSE;
            foreach ($idiomasfeitos as $idioma){                
                if ( in_array($idioma['CODIGO_IDIOMA'], $idiomasexigidos)   ){                    
                    //Liberado pela portaria de 2025
                  /* if ( $regrasnivel[ $idioma['CODIGO_IDIOMA'] ]['nivelmin_ca'] <= $idioma['NIVEL_COMPR_AUDITIVA']  &&
                        $regrasnivel[ $idioma['CODIGO_IDIOMA'] ] ['nivelmin_cl'] <= $idioma['NIVEL_COMPR_LEITORA']   &&
                        $regrasnivel[ $idioma['CODIGO_IDIOMA'] ] ['nivelmin_ee'] <= $idioma['NIVEL_EXPR_ESCRITA']      )  {
                       //$temnivel = true;                       
                       $idiomacurso_temp[] = $idioma;
                       //break;
                   }  */
                  $idiomacurso_temp[] = $idioma;
                }
            }
            if  ( $idiomacurso_temp !== false ){
              $idiomacurso = $idiomacurso_temp;  
            } else {
              $idiomacurso[] = $idiomacursozerado;
            }
            
            switch ( $nivelmin[0]['formaexigencianivelmin'] ){
                case self::FORMA_OBRIGATORIO_QUALQUER_UM:
                    $temnivel = count($idiomacurso) >= 1;
                    $strcondicao = " e/ou ";
                    break;
                case self::FORMA_OBRIGATORIO_TODOS:
                    $temnivel = count($idiomacurso) >= count($idiomasexigidos);
                    
                    $strcondicao = " e ";
                    break;
            } 
            
            if ( ! $temnivel ){                   
               $msg = " <p> O senhor(a), não pode fazer esse exame. </p>
                        <p> Para isso, é necessário possuir indices nos seguintes idiomas: <strong>". join($strcondicao, $nomeidiomasexigidos).
                        "</strong></p><p> Com os níveis: </P> ";

                        foreach ($idiomasexigidos as $key => $codigoidioma) {
                            
                            /*pegar dos cursos feitos o custo exigido*/
                            $idm = array_filter($idiomasfeitos, function($var) use ($codigoidioma ){
                                           return $var['CODIGO_IDIOMA'] == $codigoidioma; } 
                                           );   
                                       
                            if ( empty($idm) ) {
                                $idm = $idiomacursozerado;
                                $idm['IDIOMA'] = $nomeidiomasexigidos[$key];                                
                            } else {                                     
                              $chave = array_keys($idm);                              
                              $idm = $idm[ $chave[0] ];                                
                            }                            
                            $msg .= "                             
                            <table class=tabelaalerta>
                            <caption> {$idm['IDIOMA']} </caption>
                            <thead>                                                            
                              <th> Exame </th>
                              <th> Nível exigido </th>
                              <th> Seu nível </th>
                            </thead>
                            <tr>
                              <td>Compreenção auditiva: </td> 
                              <td> {$regrasnivel[$codigoidioma]['nivelmin_ca']} </td> 
                              <td> {$idm['NIVEL_COMPR_AUDITIVA']} </td> 
                            </tr>  
                            <tr>
                              <td>Expreção oral: </td> 
                              <td> {$regrasnivel[$codigoidioma]['nivelmin_eo']} </td> 
                              <td> {$idm['NIVEL_EXPR_ORAL']} </td>     
                            </tr> 
                            <tr>
                              <td>Compreenção leitora: </td> 
                              <td> {$regrasnivel[$codigoidioma]['nivelmin_cl']} </td> 
                              <td> {$idm['NIVEL_COMPR_LEITORA']} </td> 
                            </tr> 
                            <tr>
                              <td>Expreção escrita: </td> 
                              <td> {$regrasnivel[$codigoidioma]['nivelmin_ee']} </td> 
                              <td> {$idm['NIVEL_EXPR_ESCRITA']} </td>      
                            </tr> 
                       </table> " ;
                        }
                           
                              
               return ["podefazer" => false,"bloqueado" =>false,
                        "msg" => $msg
                   ];    
            }
        }
        return false;
    }
    
    public function podeFazerCurso($idCurso, $idIdioma = null, $nivel = null){
              
        /* Verificar se pode fazer o curso  
         * retormar array [ podefazer => bool, bloqueado => bool, msg=> string,  valor=> float  ]
         */
       
        /* 1 - Verificar se o usuário já está fazendo o curso. */
        $cursos = $this->getCursosCursando();
        $idiomas_cursando = array_unique(array_column($cursos, 'idm_id'));
        if ( !empty( $idIdioma ) ) {
          $idiomas_cursando[] = $idIdioma;  
        }
        $idiomas_cursando = array_unique( $idiomas_cursando  );
                        
        $idcurso_temp = ($idCurso == 6 ? 3 : $idCurso);
        foreach ($cursos as $curso){
            if ( $curso['crs_cod'] == $idcurso_temp  && $curso['idm_id'] == $idIdioma &&  $curso['nivel'] == $nivel ){
                return ["podefazer" => false,"bloqueado" =>false, "msg" =>"Esse exame já está sendo feito." ];
            }
        }
        
        $regras = $this->getRegras($idCurso, $idIdioma)[0];    
        
        /*2 - Verificar se vai ultrapassar o limite de cursos. */
          if ( $regras["qtdmax"] > 0 && $regras["qtdmax"]  < count($idiomas_cursando) ){
            return ["podefazer" => false,"bloqueado" =>true, "msg" =>"Número máximo de idiomas atingidos. " ];  
          }
          
          
          $idiomasexigidos = explode(',', $regras["array_idm_obrigatorios"] );            
          $qtdextra = $regras["qtdmax"] - count($idiomasexigidos);
          $qtdextra = ( $qtdextra < 0 ) ? 0 : $qtdextra ;                    
         // $idiomas_cursando_atual = array_unique(array_column($cursos, 'idm_id'));
          $idiomasnaoobirgatorios = array_diff($idiomas_cursando, $idiomasexigidos);         
          if ( $qtdextra > 0 && $qtdextra < count($idiomasnaoobirgatorios) ){
             return ["podefazer" => false,"bloqueado" =>true, "msg" =>"Número máximo de idiomas atingidos. " ];            
          }           
        
         /* 3 - Verificar se o curso está bloqueado pra ele. */ 
          if (  $regras["bloqueado"] == 'S' && !empty($idIdioma) ){
              return ["podefazer" => false,"bloqueado" => true, "msg" =>"O senhor(a), não pode fazer esse exame. " ];  
          }
        
          
        /* 4 - Verificar Se tem idioma obrigatório */
          $idiomaObrigatorio = $this->temIdiomaObrigatorio($regras, $cursos, $idIdioma);
          if ( $idiomaObrigatorio !== false ){
              return $idiomaObrigatorio;
          }
                    
        /* 5 - Verificar se o curso exige nivel minimo em outro idioma */
         $temNivelMinOutroIdioma = $this->temNivelMinOutroIdioma($regras, $idIdioma);
         
         if ( $temNivelMinOutroIdioma !== false ) {
             return $temNivelMinOutroIdioma;
         }
         
         $niveis_autorizados = explode(',', $regras['niveis_autorizados']) ; 
         /*6 - Verifica se pode fazer aquele nivel de curso*/
         if ( ! empty($idIdioma) ){                  
            if (! empty($nivel) && !in_array($nivel, $niveis_autorizados) ){
                return ["podefazer" => false,"bloqueado" => true, "msg" =>"Esse nível não está liberado para o senhor(a) fazer. " ];   
            }
            $niveishabilitados = $this->getNiveis($idCurso, $idIdioma);            
            if ( empty( $niveishabilitados) ) {
               return ["podefazer" => false,"bloqueado" => true, "msg" =>"O senhor(a), não possui nível permitido para esse exame. " ];   
            }
         }
         
         /* 7 - Pegar o valor do curso*/           
        return ["podefazer" => true,"bloqueado" =>false, "valor" => number_format($regras['valor'],2,',','.') , 'podealteraromse' => $regras['flagommilitaromse'] == 'N','niveis_autorizados' => $niveis_autorizados ];
    }
    
    public function verificaNivel($ididioma, $idcurso, $nivel){
       //if ( in_array($this->getCodOM() , ['000109', '049403'] ) && $this->ehAluno()   ) return true;   //Liberado para Aluno
       $cursoscursados = $this->getCursosNivelDGP();
       $cursoidioma = ['NIVEL_COMPR_AUDITIVA' => 0,
                       'NIVEL_EXPR_ORAL' => 0,
                       'NIVEL_COMPR_LEITORA' => 0  ];
       foreach ($cursoscursados as $curso){
           if ($curso['CODIGO_IDIOMA'] == $ididioma ){
              $cursoidioma = $curso; 
              break;
           }
       }  
     
       if ( ! empty($cursoidioma) ){   
           return true; //Liberado pela portaria de 2025
           switch ($idcurso) {
                case $idcurso == 3 || $idcurso == 6: 					
                    if( $nivel <= $cursoidioma['NIVEL_COMPR_AUDITIVA'] && $nivel == ($cursoidioma['NIVEL_EXPR_ORAL'])+1) {
                        return true;
                    } else {
                        return  false;
                    }
                    break;
                case 5:                    
                    if($nivel <= $cursoidioma['NIVEL_COMPR_LEITORA'] ) {
                        return true;
                    } else {
                        return false;
                    }
                default: true;
           }        
       }
       
       return true;
       
    }
    public function getNiveis($idcurso, $ididioma){
        $niveis = [];  
        
       $cursoscursados = $this->getCursosNivelDGP();
       $cursoidioma = ['NIVEL_COMPR_AUDITIVA' => 0,
                       'NIVEL_EXPR_ORAL' => 0,
                       'NIVEL_COMPR_LEITORA' => 0,
                       'NIVEL_EXPR_ESCRITA' => 0  ];
       foreach ($cursoscursados as $curso){
           if ($curso['CODIGO_IDIOMA'] == $ididioma ){
              $cursoidioma = $curso; 
              break;
           }
       }      
       //$cursoidioma['NIVEL_COMPR_AUDITIVA'] = 3;
       $i_idcurso = (int)$idcurso;
     

        for ($nivel = 1; $nivel <=3; $nivel++){
            $podefazer = true ; // portaria de 2025
            // portaria de 2025
           /* $podefazer = false;                        
            switch ($i_idcurso){
                 case 2:
                    $podefazer = $nivel > $cursoidioma['NIVEL_COMPR_AUDITIVA'];
                    break;
                case $i_idcurso == 3 || $i_idcurso == 6:                    
                    $podefazer = $nivel <= $cursoidioma['NIVEL_COMPR_AUDITIVA'] && $nivel >= $cursoidioma['NIVEL_EXPR_ORAL']+1;
                    if ( in_array($this->getCodOM() , ['000109', '049403'] ) &&  $this->ehAluno()  ) $podefazer = true;   //cadetes da AMAN                            
                    break;
                case 4:
                    $podefazer = $nivel > $cursoidioma['NIVEL_COMPR_LEITORA'];
                    break;
                case 5:                                         
                    $podefazer = $nivel <= $cursoidioma['NIVEL_COMPR_LEITORA'] && $nivel > $cursoidioma['NIVEL_EXPR_ESCRITA']  ;   
                    if ( in_array($this->getCodOM() , ['000109', '049403'] ) &&  $this->ehAluno()  ) $podefazer = true;   //Sem pré-requisito para Aluno
                    break;
                default : 
                       $podefazer = true;    
                       break; 
            }*/
            
            if ( $podefazer && $this->verificaNivel($ididioma, $idcurso, $nivel) ) {
              $niveis[] = $nivel; 
            }
            
        }

        $niveis[] = self::MULTINIVEL;
       
        return $niveis;
    }
    public function getCursosBloqueados(){
        if ( empty( $this->cursosbloqueados ) ){
           global $con;              
           $strSQL = "SELECT distinct regrapostograduacao.idcurso
                        FROM regrapostograduacao
                       WHERE regrapostograduacao.idpostograduacao = {$this->getCodPostoGraduacao()}
                         AND  not exists ( SELECT 1
                                             FROM regrapostograduacao rp
                                            WHERE regrapostograduacao.idcurso = rp.idcurso 
                                              AND regrapostograduacao.idregracurso  = rp.idregracurso
                                              and rp.bloqueado = 'N'
                                              ) "; 
           $regras_res = mysqli_query($con, $strSQL); 
           $regras = mysqli_fetch_all($regras_res, MYSQLI_ASSOC);     
           $this->cursosbloqueados = [];
           foreach ( $regras as $regra  ){
               $this->cursosbloqueados[] = $regra["idcurso"];
           }
           
        }        
        if ( empty($this->cursosbloqueados) ) {
            for($curso = 2; $curso <=6; $curso++ ){
                $niveis = [];
                for($idioma = 1; $idioma <=6; $idioma++ ){
                    $niveis[] = $this->getNiveis($curso, $idioma);                        
                }                
                $niveis = array_unique($niveis,SORT_REGULAR);
               // var_dump($curso);
                //var_dump($niveis);
                if ( empty($niveis) || ( count($niveis) == 1 && empty($niveis[0]) ) ) {
                  $this->cursosbloqueados[] = $curso;
                }
                
            }
        }        
        return $this->cursosbloqueados;
    }
    
    function todosCursosBloqueados(){
        return count($this->getCursosBloqueados()) == 5;
    }
    
    public function getValorCurso($idcurso){
        $temp = $this->podeFazerCurso($idcurso);
        return $temp['valor'];
    }
    
     public function getOMSE($idcurso){
        $temp = $this->getRegras($idcurso)[0];           
        return $temp['idomse'];
    }
    
    public function temGratuidade($idcurso){
        return $this->getValorCurso($idcurso) == 0;
    }
    
    
    public function getIdCadastro() {
        return $this->idCadastro;
    }
    public function getIdentidade() {
        return $this->identidade;
    }

    public function setIdentidade($identidade) {
        $this->identidade = $identidade;
    }

    public function getPostograduacao() {
        return $this->postograduacao;
    }

    public function getCod_dgp_postograduacao() {
        return $this->cod_dgp_postograduacao;
    }

    public function getCodPostoGraduacao() {
        return $this->codPostoGraduacao;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getNomeguerra() {
        return $this->nomeguerra;
    }

    public function getOm() {
        return $this->om;
    }

    public function setIdCadastro($idCadastro) {
        $this->idCadastro = $idCadastro;
    }

    public function setPostograduacao($postograduacao) {
        $this->postograduacao = $postograduacao;
    }

    public function setCod_dgp_postograduacao($cod_dgp_postograduacao) {
        $this->cod_dgp_postograduacao = $cod_dgp_postograduacao;
    }

    public function setCodPostoGraduacao($codPostoGraduacao) {
        $this->codPostoGraduacao = $codPostoGraduacao;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setNomeguerra($nomeguerra) {
        $this->nomeguerra = $nomeguerra;
    }

    public function setOm($om) {
        $this->om = $om;
    }


    public function getQq_cod_qas_qms() {
        return $this->qq_cod_qas_qms;
    }

    public function setQq_cod_qas_qms($qq_cod_qas_qms) {
        $this->qq_cod_qas_qms = $qq_cod_qas_qms;
    }

    function getAtivo() {
        return $this->ativo;
    }

    function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    function getCursosDGP() {
        $cursoscursados = $this->cursosDGP;
         if ( $cursoscursados == null ){
            $cursoscursados = [];
        }

        return $cursoscursados;
    }

    function getMilitarlocalizadoDGP() {
        return $this->militarlocalizadoDGP;
    }

    function setCursosDGP($cursosDGP) {
        $this->cursosDGP = $cursosDGP;
    }

    function setMilitarlocalizadoDGP($militarlocalizadoDGP) {
        $this->militarlocalizadoDGP = $militarlocalizadoDGP;
    }
    function getNascimento() {
        return $this->nascimento;
    }

    function getSexo() {
        return $this->sexo;
    }

    function getCpf() {
        return $this->cpf;
    }

    function getNome_pai() {
        return $this->nome_pai;
    }

    function getNome_mae() {
        return $this->nome_mae;
    }

    function getPrec_cp() {
        return $this->prec_cp;
    }

    function getRm() {
        return $this->rm;
    }

    function getDesc_qas_qms() {
        return $this->desc_qas_qms;
    }

    function setNascimento($nascimento) {
        $this->nascimento = $nascimento;
    }

    function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    function setNome_pai($nome_pai) {
        $this->nome_pai = $nome_pai;
    }

    function setNome_mae($nome_mae) {
        $this->nome_mae = $nome_mae;
    }

    function setPrec_cp($prec_cp) {
        $this->prec_cp = $prec_cp;
    }

    function setRm($rm) {
        $this->rm = $rm;
    }

    function setDesc_qas_qms($desc_qas_qms) {
        $this->desc_qas_qms = $desc_qas_qms;
    }


    function getSigla_qas_qms() {
        return $this->sigla_qas_qms;
    }

    function setSigla_qas_qms($sigla_qas_qms) {
        $this->sigla_qas_qms = $sigla_qas_qms;
    }
  
    function getSigla_Rm() {
        return $this->sigla_Rm;
    }

    function setSigla_Rm($sigla_Rm) {
        $this->sigla_Rm = $sigla_Rm;
    }

    function getSigla_cma() {
        return $this->sigla_cma;
    }

    function getDesc_cma() {
        return $this->desc_cma;
    }

    function setSigla_cma($sigla_cma) {
        $this->sigla_cma = $sigla_cma;
    }

    function setDesc_cma($desc_cma) {
        $this->desc_cma = $desc_cma;
    }

    function getOm_nome() {
        return $this->om_nome;
    }

    function setOm_nome($om_nome) {
        $this->om_nome = $om_nome;
    }

    function getTipocandidato() {
        return $this->tipocandidato;
    }

    function setTipocandidato($tipocandidato) {
        $this->tipocandidato = $tipocandidato;
        if ( $tipocandidato == 'ALUNO' ) {
            $_SESSION["aluno"] = "on";
        } else {
           $_SESSION["aluno"] = "off"; 
        }
    }
    
    function getTipomilitar() {
        return $this->tipomilitar;
    }

    function setTipomilitar($tipomilitar) {
        $this->tipomilitar = $tipomilitar;
    }
    
    public function ehAluno(){
        return $this->getTipocandidato() == 'ALUNO';
    }


    function getCodOM() {
        return $this->codOM;
    }

    function setCodOM($codOM) {
        $this->codOM = $codOM;
    }



}
