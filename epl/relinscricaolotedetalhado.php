<?php

//ini verifica ultimo periodo para epl
$strSQL = "SELECT concat(om.om_sigla,'- ', om.om_nome) as omse,
                    idioma.idm_nome as idioma,
                    curso.crs_nome as curso,
                    cadastro_curso.nivel_id as nivel,
                    p.sigla,
                    count(*) qtd
               from cadastro_curso
              inner join (select curso_periodo.cp_id, curso_periodo.data_ini_inc_centralizada, curso_periodo.data_fim_inc_centralizada
                            from curso_periodo
                            where data_fim_inc_centralizada is not null
                           order by curso_periodo.data_fim_inc_centralizada desc
                           limit 1) curso_periodo
                 on (curso_periodo.cp_id = cadastro_curso.cp_id)
              inner join curso
                 on (curso.crs_id = cadastro_curso.crs_id)
              inner join idioma     
                 on (idioma.idm_id = cadastro_curso.idm_id)
              inner join curso_local
                 on (curso_local.cl_id = cadastro_curso.cl_id)
               inner join om  
                 on (om.om_id = curso_local.om_id)
	      inner join cadastro c 
                 on c.cad_id = cadastro_curso.cad_id
	      inner join  postograduacao p              
                 on p.codigodgp = c.cad_codpg AND
                    p.idpostograduacao =  case when c.cad_codpg = 64 and c.cad_qasqms = '-' then 36
                                                when c.cad_codpg = 64 and c.cad_qasqms <> '-' then 32
                                                else p.idpostograduacao  end
              where  curso_local.om_id in (SELECT DISTINCT om.om_id FROM user left join om on (om.om_id = user.omse) where idperfil = 2 )
			   and   c.cad_codpg in (SELECT DISTINCT postograduacao.codigodgp
                                        FROM user 
                                       inner join postograduacao
                                          on (FIND_IN_SET(postograduacao.idpostograduacao,  user.postograduacaoliberados) )
                                       where idperfil = 2 )
              group by 1,2,3,4,5
              order by 1,2,3,4,5";
 $dados = mysqli_query($con, $strSQL);
 
 ?>
<style>
    td{
        padding: 10px;
    }
</style>
<h1 style="text-align: center;" >Relatório de inscrições centralizadas detalhado</h1>
<table border="1px" style=" text-align: center;  width: 100%;">
    <thead>
        <tr>
            <th>OMSE</th>
            <th>Idioma</th>
            <th>Exame</th>
            <th>Nível</th>
			<th>PG</th>
            <th>Qtd</th>
        </tr>
    </thead>
    <tbody>        
        <?php 
         $totalgeral = 0;
         foreach ($dados as $item) {            
             echo "<tr>
                     <td>{$item['omse']}</td>
                     <td>{$item['idioma']}</td>
                     <td>{$item['curso']}</td>
                     <td>{$item['nivel']}</td>
					 <td>{$item['sigla']}</td>
                     <td>{$item['qtd']}</td>
                   </tr>";
            $totalgeral +=  $item['qtd'];        
         }
        ?>
        <tr>
            <td style=" font-size: 11pt; font-weight: 700;">Total geral</td>
            <td colspan="4"  style=" font-size: 11pt; font-weight: 700;" ><?= $totalgeral ?></td>
        </tr>
    </tbody>
</table>