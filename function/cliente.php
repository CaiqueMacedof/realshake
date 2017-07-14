<?php
function insertCliente($conn, $nome, $email, $celular, $data_nasc, $origem, $sexo)
{
	
	$query = sprintf("INSERT INTO cliente
				     (
					  nome, celular, data_aniversario, 
					  email, data_inicio, origem,
			          sexo
					 )
			 		 VALUES
					 (
					  '%s', '%s', '%s', 
					  '%s', NOW(), '%s',
					  %d
					  )", 
			
			mysqli_real_escape_string($conn, $nome),
			mysqli_real_escape_string($conn, $celular),
			mysqli_real_escape_string($conn, $data_nasc),
			
			mysqli_real_escape_string($conn, $email),
			mysqli_real_escape_string($conn, $origem),
			mysqli_real_escape_string($conn, $sexo));

	$result = mysqli_query($conn, $query);
	if($result)
		return true;
	else
		return false;
}


function listaCliente($conn, $id_cliente = NULL, $nome = NULL, $celular = NULL, $data_nasc_ini = NULL, $data_nasc_fim = NULL)
{
	$query = "SELECT 
				cli.*,
				tipo_contato.nome as TIPO_ORIGEM
			FROM cliente as cli
			INNER JOIN tipo_contato
			ON cli.origem = tipo_contato.id_tipo_contato
			
			WHERE 1 = 1";
	
	if($id_cliente != NULL)
		$query .= " AND cli.id_cliente = ". mysqli_real_escape_string($conn, $id_cliente);
	
	if($nome != NULL)
		$query .= " AND cli.nome LIKE '%". mysqli_real_escape_string($conn, $nome) . "%'";
	
	if($celular != NULL)
		$query .= " AND cli.celular LIKE '%" . mysqli_real_escape_string($conn, $celular) . "%'";
	
	if($data_nasc_ini != NULL && $data_nasc_fim)
	{
		$query .= " AND cli.data_aniversario BETWEEN '". mysqli_real_escape_string($conn, $data_nasc_ini) . "'".
				  " AND '" .  mysqli_real_escape_string($conn, $data_nasc_fim) . "'";
	}
	/*if($inicio !== FALSE && $fim !== FALSE)
	{
		$query .= sprintf(" LIMIT %d, %d" , 
				
				mysqli_real_escape_string($conn, $inicio),
				mysqli_real_escape_string($conn, $fim) );
		
	}*/
	//echo $query;die;
	$resultado = mysqli_query($conn, $query);

	if($resultado != false)
		return mysqli_fetch_all_mod($resultado);
	else 
		return false;
}

function atualizaCliente($conn, $id_cliente, $nome, $email, $celular, $data_nasc, $origem, $sexo)
{
	$query = sprintf("UPDATE cliente
				      SET nome 				= '%s',
					      celular			= '%s',
					      data_aniversario	= '%s',
					   	  email				= '%s', 
					      origem			= '%s',
						  sexo				= %d
			 		 WHERE
						  id_cliente 		=  %d",
				
			mysqli_real_escape_string($conn, $nome),
			mysqli_real_escape_string($conn, $celular),
			mysqli_real_escape_string($conn, $data_nasc),				
			mysqli_real_escape_string($conn, $email),
			mysqli_real_escape_string($conn, $origem),
			mysqli_real_escape_string($conn, $sexo),
			
			mysqli_real_escape_string($conn, $id_cliente) );
	
	//echo $query;die;
	$result = mysqli_query($conn, $query);
	if($result)
		return true;
	else
		return false;
}


function deletaCliente($conn, $id_cliente)
{
	$query = sprintf("	DELETE FROM venda_acesso
						WHERE id_cliente = %d", mysqli_real_escape_string($conn, $id_cliente));
	
	$result = mysqli_query($conn, $query);
	
	if($result != false)
	{
		$query = sprintf("	DELETE FROM baixa_acesso
							WHERE id_cliente = %d", mysqli_real_escape_string($conn, $id_cliente));
		
		$result = mysqli_query($conn, $query);
		
		if($result != false)
		{
			$query = sprintf("	DELETE FROM cliente
								WHERE id_cliente = %d", mysqli_real_escape_string($conn, $id_cliente));
			
			$result = mysqli_query($conn, $query);
			
			if($result != false)
			{
				return $result;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
	
}

function insereAcesso($conn, $id_cliente, $id_tipo_acesso, $qtd_acesso, $total_venda_acesso)
{
	$query = sprintf("	INSERT INTO venda_acesso
						(data_venda, id_tipo_acesso, qtde_acesso, id_cliente, valor_venda_acesso)
						VALUES
						(NOW(), %d, %d, %d, %d)", 
	
	mysqli_real_escape_string($conn, $id_tipo_acesso),
	mysqli_real_escape_string($conn, $qtd_acesso),
	mysqli_real_escape_string($conn, $id_cliente),
	mysqli_real_escape_string($conn, $total_venda_acesso));

	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return $resultado;
	else
		return false;
	
}

function baixaAcesso($conn, $id_cliente, $id_tipo_acesso, $qtd_acesso)
{
	$query = sprintf("	INSERT INTO baixa_acesso
						(data_hora_baixa_acesso, id_cliente, id_tipo_acesso, qtde_acesso)
						VALUES
						(NOW(), %d, %d, %d)",

			mysqli_real_escape_string($conn, $id_cliente),
			mysqli_real_escape_string($conn, $id_tipo_acesso),
			mysqli_real_escape_string($conn, $qtd_acesso));

	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return $resultado;
		else
			return false;

}

function lista_tipo_acesso($conn, $id_tipo_acesso = null)
{
	$query = "SELECT id_tipo_acesso, nome, valor_tipo_acesso FROM tipo_acesso WHERE 1 = 1";
	
	if($id_tipo_acesso != null)
		$query .= sprintf(" AND id_tipo_acesso = %d", mysqli_real_escape_string($conn, $id_tipo_acesso));
		
	$query .= " ORDER BY nome";
	
	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
	{
		return mysqli_fetch_all_mod($resultado);
	}
	else
		return false;

}

function lista_tipo_contato($conn)
{
	$query = "SELECT id_tipo_contato, nome FROM tipo_contato";
	
	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
	{
		return mysqli_fetch_all_mod($resultado);
	}
	else
		return false;
	
}


function listaAcesso($conn, $id_cliente, $tipo_acesso)
{
	$query = sprintf("CALL consumoAcesso(%d, %d)", 
			
			mysqli_real_escape_string($conn, $id_cliente),
			mysqli_real_escape_string($conn, $tipo_acesso));
	
	$resultado = mysqli_query($conn, $query);
	mysqli_next_result($conn);//Aponta para o proxima busca na procedure;

	if($resultado != false)
	{
		$array = mysqli_fetch_all_mod($resultado);
		
		return $array[0];
	}
	else
		return false;

}

function listarHistoricoCliente($conn, $agrupar = FALSE, $nome = FALSE, $dataHora_inicio = FALSE, $dataHora_final = FALSE)
{
	$query = sprintf("	SELECT 
							cli.nome  as nome_cliente,
						    va.data_venda,
						    ta.nome  as nome_acesso,
						    qtde_acesso,
						    valor_venda_acesso
						    
						FROM venda_acesso as va
						INNER JOIN cliente as cli
						on va.id_cliente = cli.id_cliente
						INNER JOIN tipo_acesso as ta
						on va.id_tipo_acesso = ta.id_tipo_acesso
					 	
						where 1 = 1"
					);
	
	if($nome != FALSE)
	{
		$query .= " and cli.nome like '%" . mysqli_real_escape_string($conn, $nome)."%'";
	}
	
	if($dataHora_inicio != FALSE && $dataHora_final != FALSE)
	{
		$query .= " and va.data_venda between
				'" . $dataHora_inicio . "' and '" . $dataHora_final . "'";
	}
	
	if($agrupar != FALSE)
	{
		$query .= " " . $agrupar;	
	}
	//echo $query;die;
	$query .= " ORDER BY va.data_venda";
	
	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
	{
		return mysqli_fetch_all_mod($resultado);
	}
	else
		return false;
}

function totalQtdVendaAcesso($conn, $nome = FALSE, $dataHora_inicio = FALSE, $dataHora_final = FALSE)
{
	$query = sprintf("SELECT 
						    sum(va.qtde_acesso) as total_acesso,
						    sum(va.valor_venda_acesso) as total_venda
						    
						FROM venda_acesso as va
						INNER JOIN cliente as cli
						on va.id_cliente = cli.id_cliente
					");
	
	if($nome != FALSE)
	{
		$query .= " and cli.nome like '%" . mysqli_real_escape_string($conn, $nome)."%'";
	}
	
	if($dataHora_inicio != FALSE && $dataHora_final != FALSE)
	{
		$query .= " and va.data_venda between
				'" . $dataHora_inicio . "' and '" . $dataHora_final . "'";
	}
				
	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
	{
		$array = mysqli_fetch_all_mod($resultado);
	
		return $array[0];
	}
	else
		return false;
}

function buscarTopFrequencia($conn, $id_clientes = false, $inicio = false, $fim = false)
{
	$query = "	SELECT
					c.id_cliente as ID_CLIENTE,
					c.nome,
					c.sexo,
					COUNT(ba.id_cliente) AS total_frequencia
				FROM baixa_acesso  AS ba
				INNER JOIN cliente AS c
				ON ba.id_cliente = c.id_cliente
				WHERE 1 = 1";
	
	if($id_clientes != false)
		$query .= " AND c.id_cliente NOT IN($id_clientes)";
   
	if($inicio != false && $fim != false)
		$query .= " AND ba.data_hora_baixa_acesso BETWEEN '$inicio 00:00:00' AND '$fim' ";
	
	$query .= " GROUP BY id_cliente
			   ORDER BY total_frequencia";
	
	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return mysqli_fetch_all_mod($resultado);
	else
		return false;
}

function frequenciaHoje($conn, $dataHoje)
{
	$query = sprintf("	SELECT
							*
						FROM baixa_acesso
						WHERE data_hora_baixa_acesso
						BETWEEN '%s' AND '%s'
						GROUP BY id_cliente", 
			
			 mysqli_real_escape_string($conn, $dataHoje . " 00:00:00"),
			 mysqli_real_escape_string($conn, $dataHoje . " 23:59:59"));

	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return mysqli_fetch_all_mod($resultado);
	else
		return false;
}

function buscarNaoFrequencia($conn, $id_clientes = false)
{
	$query = "	SELECT
					c.id_cliente,
					c.nome,
					c.sexo
				FROM baixa_acesso  AS ba
				INNER JOIN cliente AS c
				ON ba.id_cliente = c.id_cliente
				WHERE 1 = 1";

	if($id_clientes != false)
		$query .= " AND c.id_cliente NOT IN($id_clientes)";
		 
	$query .= " GROUP BY id_cliente";
	
	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return mysqli_fetch_all_mod($resultado);
	else
		return false;
}

function concatenar($array)
{
	if(is_array($array) && count($array) > 0)
	{
		$i	 	 = 1;
		$retorno = "";
	
		foreach ($array as $a)
		{
			//Não coloca virgula no ultimo loop;
			if(count($array) == $i)
				$retorno .= $a['ID_CLIENTE'];
			else
				$retorno .= $a['ID_CLIENTE'] . ", ";
						
			$i++;
		}
	}
	else
		return false;
	
	return $retorno;
}