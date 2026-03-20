SELECT 
m.*,
p.*,
pg.*,
q.*,
o.CODOM AS om_id, 
o.RM_COD AS om_rm_id, 
o.SIGLA AS om_sigla, 
o.NOME AS om_desc, 
rm.CODIGO AS rm_id, 
rm.CMA_CODIGO AS rm_cma_id, 
cma.codigo AS cma_id, 
rm.sigla AS rm_sigla, 
rm.descricao AS rm_desc, 
cma.sigla AS cma_sigla, 
cma.descricao AS cma_desc, 
cidade.nome AS cidade, 
uf.sigla AS uf 
FROM RH_QUADRO.MILITAR m
LEFT JOIN RH_QUADRO.PESSOA p ON (m.PES_IDENTIFICADOR_COD = p.IDENTIFICADOR_COD)
LEFT JOIN RH_QUADRO.POSTO_GRAD_ESPEC pg ON (m.POSTO_GRAD_CODIGO = pg.CODIGO)
LEFT JOIN RH_QUADRO.QAS_QMS q ON (m.QQ_COD_QAS_QMS = q.COD_QAS_QMS)
LEFT JOIN RH_QUADRO.ORGAO o ON (o.codom = m.OM_CODOM)
LEFT JOIN RH_QUADRO.RM rm ON (o.rm_cod = rm.CODIGO)
LEFT JOIN RH_QUADRO.COMANDO_MILITAR_AREA cma ON (rm.CMA_CODIGO = cma.codigo)
LEFT JOIN rh_quadro.cidade ON (cidade.codigo = o.cidade_cod)
LEFT JOIN rh_quadro.uf ON (rh_quadro.uf.codigo = cidade.uf_cod)
WHERE 
m.PES_IDENTIFICADOR_COD = '0126191071';