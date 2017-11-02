<?php

function insere_produto($conn, $nome, $descricao, $preco_compra, $preco_venda, $caminho)
{
	$query = sprintf("INSERT INTO produtos
					     (
						  nome, descricao, preco_compra, preco_venda, imagem
						 )
				 		 VALUES
						 (
						  '%s', '%s', %f, %f, '%s'
						  )", 
				
				mysqli_real_escape_string($conn, $nome),
				mysqli_real_escape_string($conn, $descricao),
				mysqli_real_escape_string($conn, $preco_compra),
				mysqli_real_escape_string($conn, $preco_venda),
				mysqli_real_escape_string($conn, $caminho));

	$result = mysqli_query($conn, $query);
	if($result)
		return mysqli_insert_id($conn);
	else
		return mysqli_error($conn);
}

function altera_produto($conn, $id_produto, $nome = NULL, $descricao = NULL, $preco_compra = NULL, $preco_venda = NULL)
{
	$query = sprintf("UPDATE produtos SET id_produto = %d", mysqli_real_escape_string($conn, $id_produto));
	
	if(!empty($nome))
		$query .= sprintf(",nome = '%s'", mysqli_real_escape_string($conn, $nome));
	
	if(!empty($descricao))
		$query .= sprintf(",descricao = '%s'", mysqli_real_escape_string($conn, $descricao));

	if(is_numeric($preco_compra) && isset($preco_compra))
		$query .= sprintf(",preco_compra = %f", mysqli_real_escape_string($conn, $preco_compra));
	
	if(is_numeric($preco_venda) && isset($preco_compra))
		$query .= sprintf(",preco_venda = %f", mysqli_real_escape_string($conn, $preco_venda));

	$query .= sprintf(" WHERE id_produto = %d", mysqli_real_escape_string($conn, $id_produto));
	//echo $query;die;
	$result = mysqli_query($conn, $query);
	if($result)
		return true;
	else
		return mysqli_error($conn);

}

function lista_produtos($conn, $id_produto = NULL, $nome = NULL, $descricao = NULL, $preco_compra = NULL, $preco_venda = NULL)
{
	$query = "SELECT p.id_produto, p.*, ep.id_produto as id_produto_estoque, ep.quantidade FROM produtos as p LEFT JOIN estoque_produto as ep ON p.id_produto = ep.id_produto WHERE 1 = 1";
	
	if(!empty($id_produto))
		$query .= " AND p.id_produto = ". mysqli_real_escape_string($conn, $id_produto);
	
	if(!empty($nome))
		$query .= " AND p.nome LIKE '%". mysqli_real_escape_string($conn, $nome) . "%'";
	
	if(!empty($descricao))
		$query .= " AND p.descricao LIKE '%" . mysqli_real_escape_string($conn, $descricao) . "%'";
	
	if(is_numeric($preco_compra) && isset($preco_compra))
		$query .= " AND p.preco_compra = " . mysqli_real_escape_string($conn, $preco_compra);
	
	if(is_numeric($preco_venda) && isset($preco_venda))
		$query .= " AND p.preco_venda = " . mysqli_real_escape_string($conn, $preco_venda);
	//echo $query;die;
	$resultado = mysqli_query($conn, $query);

	if($resultado != false)
		return mysqli_fetch_all_mod($resultado);
	else 
		return mysqli_error($conn);
}

function deleta_produto($conn, $id_produto)
{
	$query = sprintf("DELETE FROM produtos
						WHERE id_produto = %d", mysqli_real_escape_string($conn, $id_produto));

	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return true;
	else
		return false;
}

function insere_compra_produto($conn, $id_produto, $quantidade, $tipo_pagamento, $preco_compra)
{
	$query = sprintf("INSERT INTO compra_produtos
					     (id_produto, quantidade, tipo_pagamento, preco_compra, data_hora)
				 		 VALUES
						 (%d, %d, %d, %f, NOW())", 
				
				mysqli_real_escape_string($conn, $id_produto),
				mysqli_real_escape_string($conn, $quantidade),
				mysqli_real_escape_string($conn, $tipo_pagamento),
				mysqli_real_escape_string($conn, $preco_compra));

	$result = mysqli_query($conn, $query);
	if($result)
		return mysqli_insert_id($conn);
	else
		return mysqli_error($conn);
}

function lista_compra_produtos($conn, $id_compra = NULL, $id_produto = NULL, $quantidade = NULL, $tipo_pagamento = NULL, $preco_compra = NULL, $data_inicio = NULL, $data_fim = NULL)
{
	$query = "SELECT p.nome as nome_produto, cp.*, cp.preco_compra as compra_produto FROM compra_produtos as cp LEFT JOIN produtos as p ON cp.id_produto = p.id_produto WHERE 1 = 1";
	
	if(!empty($id_compra))
		$query .= " AND id_compra = ". mysqli_real_escape_string($conn, $id_compra);
	
	if(!empty($id_produto))
		$query .= " AND id_produto = ". mysqli_real_escape_string($conn, $id_produto);
	
	if(!empty($quantidade))
		$query .= " AND quantidade = ". mysqli_real_escape_string($conn, $quantidade);
	
	if(is_numeric($tipo_pagamento) && isset($tipo_pagamento))
		$query .= " AND tipo_pagamento = " . mysqli_real_escape_string($conn, $tipo_pagamento);
	
	if(is_numeric($preco_compra) && isset($preco_compra))
		$query .= " AND preco_compra = " . mysqli_real_escape_string($conn, $preco_compra);
	
	if(!empty($data_inicio) && !empty($data_fim))
	{
		$query .= " AND data_hora BETWEEN '". mysqli_real_escape_string($conn, $data_inicio) . "'".
				  " AND '" .  mysqli_real_escape_string($conn, $data_fim) . "'";
	}
	
	$query .= " ORDER BY data_hora DESC";
	
	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return mysqli_fetch_all_mod($resultado);
	else 
		return mysqli_error($conn);
}

function total_compra_produtos($conn, $data_inicio = NULL, $data_fim = NULL)
{
	$query = "select sum(preco_compra) as total_compra from compra_produtos WHERE 1 = 1";
	
	if(!empty($data_inicio) && !empty($data_fim))
	{
		$query .= " AND data_hora BETWEEN '". mysqli_real_escape_string($conn, $data_inicio) . "'".
				  " AND '" .  mysqli_real_escape_string($conn, $data_fim) . "'";
	}
	
	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return mysqli_fetch_all_mod($resultado);
	else 
		return mysqli_error($conn);
}

function deleta_compra_produto($conn, $id_compra)
{
	$query = sprintf("DELETE FROM compra_produtos
						WHERE id_compra = %d", mysqli_real_escape_string($conn, $id_compra));

	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return true;
	else
		return false;
}

function insere_venda_produto($conn, $id_produto, $quantidade, $tipo_pagamento, $preco_total)
{
	$query = sprintf("INSERT INTO venda_produto
					     (id_produto, quantidade, tipo_pagamento, preco_total, data_hora)
				 		 VALUES
						 (%d, %d, %d, %f, NOW())", 
				
				mysqli_real_escape_string($conn, $id_produto),
				mysqli_real_escape_string($conn, $quantidade),
				mysqli_real_escape_string($conn, $tipo_pagamento),
				mysqli_real_escape_string($conn, $preco_total));

	$result = mysqli_query($conn, $query);
	if($result)
		return mysqli_insert_id($conn);
	else
		return mysqli_error($conn);
}

function lista_venda_produtos($conn, $id_compra = NULL, $id_produto = NULL, $quantidade = NULL, $tipo_pagamento = NULL, $preco_total = NULL, $data_inicio = NULL, $data_fim = NULL)
{
	$query = "SELECT * FROM venda_produto WHERE 1 = 1";
	
	if(!empty($id_compra))
		$query .= " AND id_venda = ". mysqli_real_escape_string($conn, $id_compra);
	
	if(!empty($id_produto))
		$query .= " AND id_produto = ". mysqli_real_escape_string($conn, $id_produto);
	
	if(!empty($quantidade))
		$query .= " AND quantidade = ". mysqli_real_escape_string($conn, $quantidade);
	
	if(is_numeric($tipo_pagamento) && isset($tipo_pagamento))
		$query .= " AND tipo_pagamento = " . mysqli_real_escape_string($conn, $tipo_pagamento);
	
	if(is_numeric($preco_total) && isset($preco_total))
		$query .= " AND preco_total = " . mysqli_real_escape_string($conn, $preco_total);
	
	if(!empty($data_inicio) && !empty($data_fim))
	{
		$query .= " AND data_hora BETWEEN '". mysqli_real_escape_string($conn, $data_inicio) . "'".
				  " AND '" .  mysqli_real_escape_string($conn, $data_fim) . "'";
	}
	
	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return mysqli_fetch_all_mod($resultado);
	else 
		return mysqli_error($conn);
}

function deleta_venda_produto($conn, $id_venda)
{
	$query = sprintf("DELETE FROM venda_produto
						WHERE id_venda = %d", mysqli_real_escape_string($conn, $id_venda));

	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return true;
	else
		return false;
}

function insere_estoque_produto($conn, $id_produto, $quantidade)
{
	$query = sprintf("	INSERT INTO estoque_produto
						    (id_produto, quantidade)
					 		VALUES
							(%d, %d)
						ON DUPLICATE KEY UPDATE
						quantidade = quantidade + %d;",
				
			mysqli_real_escape_string($conn, $id_produto),
			mysqli_real_escape_string($conn, $quantidade),
			mysqli_real_escape_string($conn, $quantidade));

	$result = mysqli_query($conn, $query);
	if($result)
		return true;
	else
		return mysqli_error($conn);
	
}

function replace_estoque_produto($conn, $id_produto, $quantidade)
{
	$query = sprintf("REPLACE INTO estoque_produto
				     (id_produto, quantidade)
			 		 VALUES
					 (%d, %d)",
				
			mysqli_real_escape_string($conn, $id_produto),
			mysqli_real_escape_string($conn, $quantidade));

	$result = mysqli_query($conn, $query);
	if($result)
		return true;
	else
		return mysqli_error($conn);
	
}

function lista_estoque_produto($conn, $id_produto = NULL, $quantidade = NULL)
{
	$query = "SELECT * FROM estoque_produto WHERE 1 = 1";
	
	if(!empty($id_produto))
		$query .= " AND id_produto = ". mysqli_real_escape_string($conn, $id_produto);
	
	if(!empty($quantidade))
		$query .= " AND quantidade = ". mysqli_real_escape_string($conn, $quantidade);
	//echo $query;die;
	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return mysqli_fetch_all_mod($resultado);
	else 
		return mysqli_error($conn);
}

function deleta_estoque_produto($conn, $id_produto)
{
	$query = sprintf("DELETE FROM estoque_produto
						WHERE id_produto = %d", mysqli_real_escape_string($conn, $id_produto));

	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return true;
	else
		return false;
}