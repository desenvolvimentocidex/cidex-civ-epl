<?php if(isset($_SESSION['login'])){ ?>        
<?php 
        $hojeDtTime = date("Y-m-d H:i:s");
        $crs = mysqli_query($con, "select * from curso where crs_status = 1 and ('$hojeDtTime' between crs_dtinicio and crs_dttermino) order by crs_id");
        $crs_lista = mysqli_fetch_assoc($crs);
        if($crs_lista != NULL){
        ?>
        <div id="aviso" style="margin-left: 200px; font-family:arial, helvetica, verdana; color: blue; padding: 10px; font-size: 17px;  text-align: justify;">
            <b style="color: red">ATENÇÃO:</b><br/>
            
        </div>
        <?php } ?>
        <div id="aviso" style="margin-left: 200px;font-family:arial, helvetica, verdana;color: black;/* background-color: #000; *//* border-color: blue; */padding: 10px;border: 1px solid;font-size: 17px;text-align: justify;">
           
            <div style="text-align: center;">
                <span style="padding: 1.5%;color: blue;font-weight: bolder;font-size: 14pt;">INFORMAÇÕES SOBRE O 2° EPLE/ EPLO (CA) ESCOLAR 2025 </span>    
            </div>
			<ul>
			  <li>
				  <ul>
    <li>&nbsp; &nbsp; Port/DECEx nº 844, de 17 DEZ 24.
        <a href="https://www.cidex.eb.mil.br/images/X_normas_para_o_subsistema_de_certificacao_de_proficiencia_linguistica_scpl_portaria_1.pdf?csrt=11061179168288944659">Clique aqui</a>&nbsp;
        <ul>
            <li>Inscrições do 2° EPLE/ EPLO (CA) Escolar 2025
                 das 10:00 horas do dia 30 JUL 2025 às 16:00 horas do dia 06 AGO 2025; </li>
            <li>Público alvo:  Cadetes da AMAN, Al da EsPCEx, Al CFGS. </li>
        </ul>
    </li>




</ul>

<p><u><span class="" style="color: rgb(239, 69, 64);">Padrões de Inscrição</span></u><span class="" style="color: rgb(239, 69, 64);">:</span></p>

<p><br></p>
<ol style="list-style-type: decimal-leading-zero;">
    <li>&nbsp;<b>Alunos dos CFGS</b>:</li>

    <ul>
        <ul>
            <ul>
               <li> 
                   inscrições para as habilidades de CA e CL, nível 1. Os Al CFGS devem se inscrever apenas para as habilidades nas quais ainda não tenham obtido o IPL exigido (1-0-1-0).
               </li>
            </ul>
        </ul>
    </ul>
    <li>&nbsp;  <b>Cadetes e Al EsPCEx</b>:
        inscrições para as habilidades de CA, CL e EE, no formato multinível. Os Cadetes e Al EsPCEx devem se inscrever apenas para as habilidades nas quais ainda não tenham obtido o IPL exigido (2-1-2-2) e farão as provas dos níveis 1 e 2 (formato multinível) dessas habilidades.
    </li>
    
</ol>
<p style = 'align:"center";'>Em caso de <b>dúvidas ou intercorrências</b>, contatar a
    Secretaria da Divisão de Certificação do CIdEx, através do <b>e-mail </b><a href="mailto:secrctf@cidex.eb.mil.br">secrctf@cidex.eb.mil.br</a><a href="mailto:secrctf@cidex.eb.mil.br"> </a>ou através do
    <b>WhatsApp (21) 97479-186</b>1.
</p>

<p style = 'align:"center";'>&nbsp;</p>

<p style = 'align:"center";'><b><u>Horário de
            atendimento da SCrt:</u></b><u></u></p>

<p>-
    Segunda a quinta: 7h30 às 12h e das 13h às 16h.</p>

<p>-
    Sexta: 7h30 às 12h.</p><br>
<p></p>
         </li>         
       </ul>
      </ul>  
        </div>
        <?php } ?>