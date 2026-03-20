<?php include'system/system.php'; ?>

<?php
$query = mysqli_query($con, "select cad_login, cad_codpg, cad_postograd, cad_status from cadastro where cad_login = '0112684576'");


while ($cadastro = mysqli_fetch_array($query)) {
    $username = $cadastro["cad_login"];
    $cod_pg = $cadastro["cad_codpg"];
    $nome_postograduacao = $cadastro["cad_postograd"];
    $status = $cadastro["cad_status"];

//    echo $username;;;
//    echo $cod_pg;
//    echo $nome_postograduacao;
//    echo $status;

//    $pessoa = mysql_query("SELECT * FROM MILITAR M INNER JOIN POSTO_GRAD_ESPEC E ON M.POSTO_GRAD_CODIGO = E.codigo where M.PES_IDENTIFICADOR_COD = $username", $-condgp);
    //Conexão com BD Oracle 
    $stid = oci_parse($oci_connect, "SELECT * FROM RH_QUADRO.MILITAR M INNER JOIN RH_QUADRO.POSTO_GRAD_ESPEC E ON M.POSTO_GRAD_CODIGO = E.codigo where M.PES_IDENTIFICADOR_COD = $username");
    oci_execute($stid);

    printf($stid);
    
    $nrows = oci_fetch_all($stid, $results);
    
    if ($nrows > 0) {
        //Conexão com BD Oracle 
//            $consulta = "SELECT * FROM RH_QUADRO.MILITAR M INNER JOIN RH_QUADRO.POSTO_GRAD_ESPEC E ON M.POSTO_GRAD_CODIGO = E.codigo where M.PES_IDENTIFICADOR_COD = $username";
//            $stid = oci_parse($conexao, $consulta) or die ("erro");
            oci_execute($stid);
            while ($militar = oci_fetch_array($stid)) {
                $username = $militar["PES_IDENTIFICADOR_COD"];
                $cod_pg = $militar["POSTO_GRAD_CODIGO"];
                $nome_postograduacao = $militar["SIGLA"];
                $status = $militar["STATUS"];
                $nome_querra = $militar["NOME_GUERRA"];
                ?>

                <div>
                    <table>
                        <tr>
                            <th>usuario</th>
                            <th>codigo posto novo</th>
                            <th>nome posto novo</th>
                            <th>status</th>
                            <th>nome e querra</th>

                        </tr>
                        <tbody>
                            <tr>
                                <td><?= $username ?></td>
                                <td><?= $cod_pg ?></td>
                                <td><?= $nome_postograduacao ?></td>
                                <td><?= $status ?></td>
                                <td><?= $nome_querra ?></td>
                            </tr>
                        </tbody>

                    </table>

                </div>


                <?php
                $result = mysqli_query($con, "UPDATE cadastro set cad_nome = '$nome_querra', cad_postograd = '$nome_postograduacao', cad_codpg = '$cod_pg', cad_status = $status where cad_login = $username");
                if (!$result) {
                    die('Invalid query: ' . mysqli_error());
                } else {
                    print 'Atualizado';
                }
            }
        }
    }

?>
