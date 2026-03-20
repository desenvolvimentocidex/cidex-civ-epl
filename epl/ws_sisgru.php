<?php

  class WS_SISGRU {
      public static function getGRUs($usuario, $senha,$proxy, $ugEmitente, DateTime $dtIni,DateTime $dtFim ){          
         $strdata = $dtIni->format('d/m/Y').'-'.$dtFim->format('d/m/Y'); 
         $strdata = str_replace( '/','%2F',$strdata);
         $url = "https://webservice.sisgru.tesouro.gov.br/sisgru/services/v1/grus?q=ugArrecadadora%3D{$ugEmitente}%26situacao%3D02%26dtEmissao%3D{$strdata}%26ugEmitente%3D{$ugEmitente}&fields=id,recolhimento,codigoRecolhedor,numReferencia,dataVencimento,numAutBancaria,vlPrincipal,vlTotal,observacao,dtEmissao,codigoPagamento";
         
         $ch = curl_init($url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
         if ( ! empty($proxy) ){
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
         }
         curl_setopt($ch, CURLOPT_USERPWD, $usuario.":".$senha);      
         $return = curl_exec($ch);
         if ( $return === false ){
             echo "erro: ";
             var_dump(curl_error( $ch ));
         }
         curl_close($ch);       
         $json = simplexml_load_string($return);
         $json = json_encode($json);
         return json_decode($json, true);
      }
  }
  
 //var_dump( WS_SISGRU::getGRUs('01900147769', 'brenda20', '160289', new DateTime('2021-07-01'), new DateTime('2021-07-31')) );