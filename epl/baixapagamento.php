<?php
  require_once '../system/system.php';
  require_once './ws_sisgru.php';
  
  class BaixaPagamentos{
     private function getDadosSISGRU(){
       $strSQL = "SELECT `sisgru_ug_emitente`, `sisgru_usuario`, `sisgru_senha`,sisgru_ultimoacesso, proxy 
                    FROM `configuracao`" ;
       return getSQLMySQL($strSQL)[0];
     }
         
     public function getPagamentosFullPeriodo(){         
          return $this->getPagamentos(new DateTime($_SESSION['DATA_INI_PERIODO']), (new DateTime($_SESSION['DATA_GRU']))->add(new DateInterval('P1D') )  );          
                   
     }
     
     public function getPagamentosIncrementalPeriodo(){
         $configuracoes = $this->getDadosSISGRU();
         $resposta = $this->getPagamentos(new DateTime($configuracoes['sisgru_ultimoacesso']), (new DateTime($_SESSION['DATA_GRU']))->add(new DateInterval('P1D') )  );
         $strSQL= "update configuracao set sisgru_ultimoacesso = current_date ";
         execSQL($strSQL);
         return $resposta;
     }
     
     public function getPagamentos(DateTime $dataIni, DateTime $dataFim){
       $configuracoes = $this->getDadosSISGRU();
       $resposta = WS_SISGRU::getGRUs($configuracoes['sisgru_usuario'], $configuracoes['sisgru_senha'], $configuracoes['proxy'],$configuracoes['sisgru_ug_emitente'], $dataIni, $dataFim);
       var_dump($resposta);
       return $resposta;
     }
     
     private function gravaRespostaWS($respostaWS) {          
        $respostaWS = json_encode($respostaWS); 
        $strSQL = "insert into respostawssisgru (dados) values('{$respostaWS}') ";
        var_dump( execSQL($strSQL) );
     }
     
     
     public function procecessaPagamentos( $pagamentos  ){
         $this->gravaRespostaWS($pagamentos);
         //execSQL('call sp_processapagamentos');
     }
     
 }