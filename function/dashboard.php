<?php

function frequenciaCliente($conn)
{
	$ano_inicio = date("Y", strtotime(date("Y") . " - 2 years"));
	$ano_final  = date("Y");
	
	while ($ano_inicio <= $ano_final)
	{
		$query = "	SELECT
						DATE_FORMAT(data_hora_baixa_acesso,'%d/%m/%Y') as data_periodo,
						count(DATE_FORMAT(data_hora_baixa_acesso,'%d/%m/%Y')) as total_acesso_dia
					FROM baixa_acesso
					where data_hora_baixa_acesso between '".$ano_inicio."-01-01 00:00:00' and '".$ano_inicio."-12-31 23:59:59'
					group by DATE_FORMAT(data_hora_baixa_acesso,'%m/%Y')
					order by data_hora_baixa_acesso";

		$resultado = mysqli_query($conn, $query);
		if($resultado != false)
			$anos[$ano_inicio]	= mysqli_fetch_all_mod($resultado, MYSQLI_ASSOC);
		else
			return false;
		
		$ano_inicio++;
	}
	
	return $anos;
	//QUERY QUE SOMA TOTAL DE ACESSO POR DIA
	/*$query = "	SELECT
					DATE_FORMAT(data_hora_baixa_acesso,'%d/%m/%Y') as data_periodo,
					count(DATE_FORMAT(data_hora_baixa_acesso,'%d/%m/%Y')) as total_acesso_dia
				FROM baixa_acesso
				group by DATE_FORMAT(data_hora_baixa_acesso,'%d/%m/%Y')
				order by data_hora_baixa_acesso";*/
}

function total_venda_baixa_acesso($conn)
{
	$query = "SELECT (
						SELECT
							SUM(ta.valor_tipo_acesso * ba.qtde_acesso)
			                FROM baixa_acesso as ba
						LEFT JOIN tipo_acesso as ta
			            on ba.id_tipo_acesso = ta.id_tipo_acesso
					) AS total_baixa_acesso,
			       
			       (
					SELECT
						SUM(valor_venda_acesso)
					FROM venda_acesso
			       )   AS total_venda_acesso";
	
	$resultado = mysqli_query($conn, $query);
	
	if($resultado != false)
		return mysqli_fetch_all_mod($resultado, MYSQLI_ASSOC);
	else
		return false;
}

function quantidade_origem_cliente($conn)
{
	$query = "	SELECT
					tc.id_tipo_contato,
					tc.nome, count(cli.origem) as total
				FROM cliente as cli
				right join tipo_contato as tc
				on cli.origem = tc.id_tipo_contato
				group by cli.origem, tc.id_tipo_contato
				order by tc.id_tipo_contato";
	
	$resultado = mysqli_query($conn, $query);
	
	if($resultado != false)
		return mysqli_fetch_all_mod($resultado, MYSQLI_ASSOC);
	else
		return false;
}

function quantidade_cliente($conn)
{
	$query = "	SELECT COUNT(*) as total_cliente FROM cliente WHERE id_cliente != 1";

	$resultado = mysqli_query($conn, $query);

	if($resultado != false)
		return mysqli_fetch_all_mod($resultado, MYSQLI_ASSOC);
	else
		return false;
}

function retornar_diff_baixa_acesso($conn)
{
	$query = "	SELECT
					ba.id_cliente,
					ba.id_tipo_acesso,
				    SUM(ba.qtde_acesso) * tp.valor_tipo_acesso as diff_baixa_acesso
				FROM baixa_acesso as ba
				LEFT JOIN tipo_acesso tp
				ON ba.id_tipo_acesso = tp.id_tipo_acesso
				GROUP BY id_cliente, id_tipo_acesso
				ORDER BY ba.id_cliente";
	
	$resultado = mysqli_query($conn, $query);
	
	if($resultado != false)
		return mysqli_fetch_all_mod($resultado, MYSQLI_ASSOC);
	else
		return false;
	
}

function retornar_diff_venda_acesso($conn)
{
	$query = "	SELECT
					va.id_cliente,
				    va.id_tipo_acesso,
					va.valor_venda_acesso as diff_venda_acesso
				FROM venda_acesso as va
				GROUP BY va.id_cliente,va.id_tipo_acesso
				ORDER BY va.id_cliente";

	$resultado = mysqli_query($conn, $query);

	if($resultado != false)
		return mysqli_fetch_all_mod($resultado, MYSQLI_ASSOC);
	else
		return false;

}

function consumoAcessoTotal($conn)
{
	$query = "	SELECT COUNT(*) as total_acessos FROM baixa_acesso";
	
	$resultado = mysqli_query($conn, $query);
	
	if($resultado != false)
		return mysqli_fetch_all_mod($resultado, MYSQLI_ASSOC);
	else
		return false;
}