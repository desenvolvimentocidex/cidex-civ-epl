<?php if(isset($_SESSION['login'])){ ?>        
<?php 
if (isset($_POST) && isset($_POST['preparatorio'])) {
    
    $preparatorio = $_POST['preparatorio'];
    
    $idt = $_SESSION['login'];
    $dadosUsuario = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM cadastro WHERE cad_login = $idt"));
    $dadosPeriodo = mysqli_fetch_assoc(mysqli_query($con, "SELECT * 
                                                             FROM curso_periodo 
                                                            WHERE cp_pesquisa = 'SIM'
                                                              and cp_ini <=CURRENT_DATE
                                                            order by cp_ini DESC    
                                                            limit 1"));
    
    $cadastroId = $dadosUsuario['cad_id'];
    $peridodoId = $dadosPeriodo['cp_id'];
    
    
    if($preparatorio == "SIM") {
        foreach ($_POST["idioma"] as $key) {
            
            $idmId = $key['id-idioma'];
            $modalidade = $key['modalidade-idioma'];
            $uf = $key['uf-idioma'];
            $cidade = $key['cidade-idioma'];
            $instituicao = $key['instituicao-idioma'];
            
            if ($key["seleciona-idioma"] == "SIM") {
                $sql = "INSERT INTO pesquisa (cad_id, cp_id, idm_id, modalidade, uf, cidade, instituicao, preparatorio) VALUES ('$cadastroId', '$peridodoId', '$idmId','$modalidade', '$uf', '$cidade', '$instituicao', '$preparatorio');";
                mysqli_query($con, $sql); 
            }
        }
        echo "<script>alert('Obrigado por responder a pesquisa.');setTimeout(function(){window.top.location='index.php?a=2'} , 3000);</script>";
        
    } else if($preparatorio == "NÃO") {
        $sql = "INSERT INTO pesquisa (cad_id, cp_id, preparatorio) VALUES ('$cadastroId', '$peridodoId', '$preparatorio');";
        mysqli_query($con, $sql); 
        echo "<script>alert('Obrigado por responder a pesquisa.');setTimeout(function(){window.top.location='index.php?a=2'} , 3000);</script>";
    }
}
        
        ?>
        <div id="aviso" style="margin-left: 0px;font-family:arial, helvetica, verdana;color: black;/* background-color: #000; *//* border-color: blue; */padding: 10px;border: 1px solid;font-size: 17px;text-align: justify;">
            <div style="text-align: center;">
                <span style="padding: 1.5%;color: blue;font-weight: bolder;font-size: 14pt;">PESQUISA OBRIGATÓRIA</span>    
            </div>
            <form id="form-idioma" method="post" action="" />
            <div style="text-align: center;">
                <span>Realizou algum curso preparatório para o exame?</span>
                <span>
                    <select id="preparatorio" name="preparatorio" required>
                        <option value="NÃO">NÃO</option>
                        <option value="SIM">SIM</option>
                    </select> 
                </span>
            </div>
            <div id="idiomas" hidden>
                <?php 
                $idiomas = mysqli_query($con, "select * from idioma where idm_id in(2,4) union select * from idioma where idm_id not in(2,4)");
                $idiomaLista = mysqli_fetch_all($idiomas, MYSQLI_ASSOC);
                foreach ($idiomaLista as $idioma) {
//                    var_dump($idioma);exit;
//                $siglaIdm[] = $idioma['idm_sigla'];
                ?>
                

                <div id="idioma-<?= $idioma['idm_sigla']?>" style="border: 1px solid #00420C; padding: 5px;">
                    <span><?= $idioma['idm_nome']?>:</span>
                    <span>
                        <input type="hidden" value="<?= $idioma['idm_id']?>" name="idioma[<?= $idioma['idm_sigla']?>][id-idioma]" />
                        <select name="idioma[<?= $idioma['idm_sigla']?>][seleciona-idioma]" idioma="<?= $idioma['idm_sigla']?>" class="<?= $idioma['idm_sigla']?> seleciona-idioma" disabled required />
                            <option value="NÃO">NÃO</option>
                            <option value="SIM">SIM</option>
                        </select> 
                    </span>
                    <div id="itens-<?= $idioma['idm_sigla']?>" style="margin-top: 10px;"  hidden>
                        <div style="margin-left: 20px">
                            <span>Modalidade:</span>
                            <select name="idioma[<?= $idioma['idm_sigla']?>][modalidade-idioma]" class="modalidade-<?= $idioma['idm_sigla']?> " disabled>
                                <option value="EAD">EAD</option>
                                <option value="PRESENCIAL">PRESENCIAL</option>
                            </select> 
                        </div>
                        <div style="margin-left: 20px; margin-top: 10px;">
                            <span>Instituição:</span>
                            <input type="text" name="idioma[<?= $idioma['idm_sigla']?>][instituicao-idioma]" class="instituicao-<?= $idioma['idm_sigla']?> " disabled/>
                        </div>
                        <div style="margin-left: 20px; margin-top: 10px;">
                            <span>UF:</span>
                            <select name="idioma[<?= $idioma['idm_sigla']?>][uf-idioma]" class="uf-<?= $idioma['idm_sigla']?>" disabled>
                                <option value="">Selecione</option>
                                <option value="AC">AC</option>
                                <option value="AL">AL</option>
                                <option value="AP">AP</option>
                                <option value="AM">AM</option>
                                <option value="BA">BA</option>
                                <option value="CE">CE</option>
                                <option value="DF">DF</option>
                                <option value="ES">ES</option>
                                <option value="GO">GO</option>
                                <option value="MA">MA</option>
                                <option value="MS">MS</option>
                                <option value="MT">MT</option>
                                <option value="MG">MG</option>
                                <option value="PA">PA</option>
                                <option value="PB">PB</option>
                                <option value="PR">PR</option>
                                <option value="PE">PE</option>
                                <option value="PI">PI</option>
                                <option value="RJ">RJ</option>
                                <option value="RN">RN</option>
                                <option value="RS">RS</option>
                                <option value="RO">RO</option>
                                <option value="RR">RR</option>
                                <option value="SC">SC</option>
                                <option value="SP">SP</option>
                                <option value="SE">SE</option>
                                <option value="TO">TO</option>
                            </select> 
                        </div>
                        <div style="margin-left: 20px; margin-top: 10px;">
                            <span>Cidade:</span>
                            <input type="text" name="idioma[<?= $idioma['idm_sigla']?>][cidade-idioma]" class="cidade-<?= $idioma['idm_sigla']?>" disabled/>
                        </div>
                    </div>
                </div>
<?php } ?>
            </div>
            <!--<input type="hidden" id="sigla-idm" value="" />-->
            <div><input type="submit" id="enviar" value="Enviar" /></div>
                </form>
           
        </div>
        <?php } ?>