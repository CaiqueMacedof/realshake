<?php
function insertCliente($conn, $nome, $email, $celular, $data_nasc, $origem, $sexo)
{
	if(!empty($data_nasc))
	{
		$query = sprintf("INSERT INTO cliente
					     (
						  nome, celular, data_aniversario, 
						  email, data_inicio, origem,
				          sexo
						 )
				 		 VALUES
						 (
						  '%s', '%s', '".mysqli_real_escape_string($conn, $data_nasc)."', 
						  '%s', NOW(), '%s',
						  %d
						  )", 
				
				mysqli_real_escape_string($conn, $nome),
				mysqli_real_escape_string($conn, $celular),
				
				mysqli_real_escape_string($conn, $email),
				mysqli_real_escape_string($conn, $origem),
				mysqli_real_escape_string($conn, $sexo));
		
	}
	else
	{
		$query = sprintf("INSERT INTO cliente
			     (
				  nome, celular, data_aniversario, 
				  email, data_inicio, origem,
		          sexo
				 )
		 		 VALUES
				 (
				  '%s', '%s', NULL, 
				  '%s', NOW(), '%s',
				  %d
				  )", 
		
		mysqli_real_escape_string($conn, $nome),
		mysqli_real_escape_string($conn, $celular),
		
		mysqli_real_escape_string($conn, $email),
		mysqli_real_escape_string($conn, $origem),
		mysqli_real_escape_string($conn, $sexo));
	}
	
	$result = mysqli_query($conn, $query);
	if($result)
		return true;
	else
		return mysqli_error($conn);
}


function listaCliente($conn, $id_cliente = NULL, $nome = NULL, $celular = NULL, $data_nasc_ini = NULL, $data_nasc_fim = NULL)
{
	$query = "SELECT 
				cli.*,
				tipo_contato.nome as TIPO_ORIGEM
			FROM cliente as cli
			INNER JOIN tipo_contato
			ON cli.origem = tipo_contato.id_tipo_contato
			
			WHERE 1 = 1 AND cli.id_cliente != 1 AND cli.deletado = 0";
	
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
	// $query;die;
	$resultado = mysqli_query($conn, $query);

	if($resultado != false)
		return mysqli_fetch_all_mod($resultado);
	else 
		return false;
}

function atualizaCliente($conn, $id_cliente, $nome, $email, $celular, $data_nasc, $origem, $sexo)
{
	//TODO: DEPOIS ARRUMAR A GAMBIARA ABAIXO!
	if(!empty($data_nasc))
	{
		$query = sprintf("UPDATE cliente
				      SET nome 				= '%s',
					      celular			= '%s',
					      data_aniversario	= '".mysqli_real_escape_string($conn, $data_nasc)."',
					   	  email				= '%s', 
					      origem			= '%s',
						  sexo				= %d
			 		 WHERE
						  id_cliente 		=  %d",
				
			mysqli_real_escape_string($conn, $nome),
			mysqli_real_escape_string($conn, $celular),
			mysqli_real_escape_string($conn, $email),
			mysqli_real_escape_string($conn, $origem),
			mysqli_real_escape_string($conn, $sexo),
			
			mysqli_real_escape_string($conn, $id_cliente) );
	}
	else
	{
		$query = sprintf("UPDATE cliente
					      SET nome 				= '%s',
						      celular			= '%s',
						   	  data_aniversario	= NULL,
						   	  email				= '%s',
						      origem			= '%s',
							  sexo				= %d
				 		 WHERE
							  id_cliente 		=  %d",
					
				mysqli_real_escape_string($conn, $nome),
				mysqli_real_escape_string($conn, $celular),
				mysqli_real_escape_string($conn, $email),
				mysqli_real_escape_string($conn, $origem),
				mysqli_real_escape_string($conn, $sexo),
				
				mysqli_real_escape_string($conn, $id_cliente) );
	}
	
	$result = mysqli_query($conn, $query);
	if($result)
		return true;
	else
		return false;
}

function deletaCliente($conn, $id_cliente)
{
	$query = sprintf("	DELETE FROM cliente_pendente
							WHERE id_cliente = %d", mysqli_real_escape_string($conn, $id_cliente));
	
	$result = mysqli_query($conn, $query);
	if($result != false)
	{
		/*$query = sprintf("	DELETE FROM venda_acesso
							WHERE id_cliente = %d", mysqli_real_escape_string($conn, $id_cliente));
		
		$result = mysqli_query($conn, $query);
		if($result != false)
		{
			$query = sprintf("	DELETE FROM baixa_acesso
								WHERE id_cliente = %d", mysqli_real_escape_string($conn, $id_cliente));
			
			$result = mysqli_query($conn, $query);
			if($result != false)
			{*/
				$query = sprintf("	UPDATE cliente
									    SET deletado = 1
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
			/*}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}*/
	}
	else
	{
		return false;
	}
	
}

function deletaVendaAcesso($conn, $id_venda_acesso)
{
	$query = sprintf("DELETE FROM venda_acesso
						WHERE id_venda_acesso = %d", mysqli_real_escape_string($conn, $id_venda_acesso));
	
	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return true;
	else
		return false;
}

function deletaBaixaAcesso($conn, $id_baixa_acesso)
{
	$query = sprintf("DELETE FROM baixa_acesso
						WHERE id_baixa_acesso = %d", mysqli_real_escape_string($conn, $id_baixa_acesso));
	
	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return true;
	else
		return false;
}

function insereAcesso($conn, $id_cliente, $id_tipo_acesso, $qtd_acesso, $total_venda_acesso, $tipo_pagamento, $data_hora = NULL)
{
	if($data_hora == NULL){$data_hora = date("Y-m-d H:i:s");}

	$query = sprintf("	INSERT INTO venda_acesso
						(data_venda, id_tipo_acesso, qtde_acesso, id_cliente, valor_venda_acesso, tipo_pagamento)
						VALUES
						('%s', %d, %d, %d, %d, %d)", 
	
	mysqli_real_escape_string($conn, $data_hora),
	mysqli_real_escape_string($conn, $id_tipo_acesso),
	mysqli_real_escape_string($conn, $qtd_acesso),
	mysqli_real_escape_string($conn, $id_cliente),
	mysqli_real_escape_string($conn, $total_venda_acesso),
	mysqli_real_escape_string($conn, $tipo_pagamento));
	//echo$query;die;
	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
		return $resultado;
	else
		return false;
	
}

function baixaAcesso($conn, $id_cliente, $id_tipo_acesso, $qtd_acesso, $data_hora = NULL)
{
	if($data_hora == NULL){$data_hora = date("Y-m-d H:i:s");}

	$query = sprintf("	INSERT INTO baixa_acesso
						(data_hora_baixa_acesso, id_cliente, id_tipo_acesso, qtde_acesso)
						VALUES
						('%s', %d, %d, %d)",
			
			mysqli_real_escape_string($conn, $data_hora),
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
							va.id_venda_acesso,
							va.id_cliente,
							va.id_tipo_acesso,
							cli.nome  as nome_cliente,
						    va.data_venda,
						    ta.nome  as nome_acesso,
						    qtde_acesso,
						    valor_venda_acesso
						    
						FROM venda_acesso as va
							INNER JOIN cliente as cli
							on va.id_cliente = cli.id_cliente
							LEFT JOIN tipo_acesso as ta
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
	$query .= " ORDER BY va.data_venda DESC";
	
	$resultado = mysqli_query($conn, $query);
	if($resultado != false)
	{
		return mysqli_fetch_all_mod($resultado);
	}
	else
		return false;
}


function listarHistoricoClienteBaixa($conn, $agrupar = FALSE, $nome = FALSE, $dataHora_inicio = FALSE, $dataHora_final = FALSE)
{
	$query = sprintf("	SELECT 
							ba.id_baixa_acesso,
							ba.id_cliente,
							ba.id_tipo_acesso,
							cli.nome  as nome_cliente,
							cli.deletado,
						    ba.data_hora_baixa_acesso  as data_baixa,
						    ta.nome as nome_acesso,
						    qtde_acesso
						    
						FROM baixa_acesso as ba
							LEFT JOIN cliente as cli
							on ba.id_cliente = cli.id_cliente
							LEFT JOIN tipo_acesso as ta
							on ba.id_tipo_acesso = ta.id_tipo_acesso
					 	
						where 1 = 1"
					);
	
	if($nome != FALSE)
	{
		$query .= " and cli.nome like '%" . mysqli_real_escape_string($conn, $nome)."%'";
	}
	
	if($dataHora_inicio != FALSE && $dataHora_final != FALSE)
	{
		$query .= " and ba.data_hora_baixa_acesso between
				'" . $dataHora_inicio . "' and '" . $dataHora_final . "'";
	}
	
	if($agrupar != FALSE)
	{
		$query .= " " . $agrupar;
	}
	$query .= " ORDER BY data_baixa DESC";
	//echo $query;die;
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
						LEFT JOIN cliente as cli
						on va.id_cliente = cli.id_cliente
						WHERE 1 = 1
					");
	
	if($nome != FALSE)
	{
		$query .= " AND cli.nome like '%" . mysqli_real_escape_string($conn, $nome)."%'";
	}
	
	if($dataHora_inicio != FALSE && $dataHora_final != FALSE)
	{
		$query .= " AND va.data_venda between
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

function buscarFrequencias($conn, $nome=null, $celular=null)
{
	$query = "	SELECT
					cli.ID_CLIENTE,
					cli.nome,
					cli.sexo,
					count(*) AS total_frequencia,
					cli.DELETADO
				FROM baixa_acesso AS ba
				INNER JOIN cliente AS cli ON ba.ID_CLIENTE = cli.id_cliente
				WHERE 1=1 AND cli.id_cliente != 1 AND cli.DELETADO = 0";

	if(!empty($nome))
		$query .= " AND cli.nome LIKE '%".mysqli_real_escape_string($conn, $nome)."%'";

	if(!empty($celular))
		$query .= " AND cli.celular LIKE '".mysqli_real_escape_string($conn, $celular)."%'";

	$query .=	" GROUP BY ba.id_cliente
				  ORDER BY total_frequencia DESC;";
	//echo $query;die;
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

function replace_cliente_pendente($conn, $id_cliente, $id_tipo_acesso, $valor_total)
{
	$query = sprintf("REPLACE INTO cliente_pendente
				     (id_cliente, id_tipo_acesso, valor_total)
			 		 VALUES
					 (%d, %d, %d)",
				
			mysqli_real_escape_string($conn, $id_cliente),
			mysqli_real_escape_string($conn, $id_tipo_acesso),
			mysqli_real_escape_string($conn, $valor_total));

	$result = mysqli_query($conn, $query);
	if($result)
		return 	mysqli_insert_id($result);
	else
		return false;
	
}

function deleta_cliente_pendente($conn, $id_cliente, $tipo_acesso)
{
	$query = sprintf("DELETE FROM cliente_pendente WHERE id_cliente = %d AND id_tipo_acesso = %d",
	
			mysqli_real_escape_string($conn, $id_cliente),
			mysqli_real_escape_string($conn, $tipo_acesso));
	
	$result = mysqli_query($conn, $query);
	if($result)
		return 	true;
	else
		return false;
}

function retornar_preco_total_acesso($conn, $id_tipo_acesso, $total)
{
	$query  = sprintf("SELECT valor_tipo_acesso * %d as total_valor FROM tipo_acesso WHERE id_tipo_acesso = %d", 
			
			  mysqli_real_escape_string($conn, $total),
			  mysqli_real_escape_string($conn, $id_tipo_acesso));
	
	$resultado = mysqli_query($conn, $query);
	if($resultado !== false)
	{
		$array = mysqli_fetch_all_mod($resultado);
		return $array[0]['total_valor'];
	}
	else
	{
		return false;
	}
}

function retornar_valor_taxa($tipo_pagamento, $qtd_acesso)
{
	switch ($tipo_pagamento)
	{
		case 2:return $qtd_acesso * 0.70;//CARTAO DE CREDITO
		
		case 3:return $qtd_acesso * 0.90;//CARTAO DE CREDITO
		
		default: return 0;//CARTAO DE DEBITO OU DINHEIRO
	}
}

function total_pendente($conn)
{
	$query  = "SELECT SUM(valor_total) AS total FROM cliente_pendente";
	
	$resultado = mysqli_query($conn, $query);
	if($resultado !== false)
	{
		$array = mysqli_fetch_all_mod($resultado);
		return $array[0]['total'];
	}
	else
	{
		return false;
	}
}

function lista_pendente($conn)
{
	$query  = "SELECT cli.nome, ta.nome as tipo_acesso, cp.valor_total as total_pendentes FROM cliente_pendente as cp LEFT JOIN cliente as cli ON cp.id_cliente = cli.id_cliente LEFT JOIN tipo_acesso ta ON cp.id_tipo_acesso = ta.id_tipo_acesso";
	
	$resultado = mysqli_query($conn, $query);
	if($resultado !== false)
		return mysqli_fetch_all_mod($resultado, MYSQL_BOTH);
	else
		return false;
}

function listar_pendente_cliente($conn, $id_cliente)
{
	$query  = "SELECT valor_total AS total FROM cliente_pendente WHERE id_cliente = " . mysqli_real_escape_string($conn, $id_cliente);
	
	$resultado = mysqli_query($conn, $query);
	if($resultado !== false)
	{
		$array = mysqli_fetch_all_mod($resultado);
		return $array[0]['total'];
	}
	else
	{
		return false;
	}
}

function listar_cliente_freq($conn, $id_cliente)
{
	$id_cliente = mysqli_real_escape_string($conn, $id_cliente);
	$query 		= "	SELECT
						ba.id_cliente,
						ta.nome,
						ta.id_tipo_acesso,
						date_format(ba.data_hora_baixa_acesso, '%Y-%m-%d') as data,
						SUM(ba.qtde_acesso) as qtd_acesso
					FROM baixa_acesso as ba
					INNER JOIN tipo_acesso as ta
					on ba.id_tipo_acesso = ta.id_tipo_acesso
					WHERE id_cliente = $id_cliente
					GROUP BY date_format(ba.data_hora_baixa_acesso, '%Y-%m-%d'), ba.id_tipo_acesso 
					ORDER BY date_format(ba.data_hora_baixa_acesso, '%Y-%m-%d') ASC;";

	$resultado = mysqli_query($conn, $query);
	if($resultado !== false)
		return mysqli_fetch_all_mod($resultado);
	else
		return false;
}

function gerar_calendario($conn, $id_cliente, $ano_inical, $ano_final)
{
	$k 		  	 = 0;
	$nome_semana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
	$nome_mes 	 = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");

	// GERA UM ARRAY DE ARRAY COM VARIOS OBJETOS DO CALENDARIO
	for ($x = $ano_inical; $x <= $ano_final; $x++)
	{
		for ($i = 0; $i < 12; $i++)
		{
			$ultimo_dia = date("t", strtotime(date("1-".($i+1)."-".$x)));
			for ($k = 0; $k < $ultimo_dia; $k++)
			{
				$semana_dia = date("w", strtotime(date(($k+1)."-".($i+1)."-".$x)));
				$dias[($k+1)."&".$nome_semana[$semana_dia]."&".$semana_dia] = $k+1;
				$calendario[$x][$nome_mes[$i]] = $dias;
			}
			unset($dias);
		}
	}
	$freq_cliente 	= listar_cliente_freq($conn, $id_cliente);
	if(!empty($freq_cliente))
		$prod_cliente	= array_produtos($freq_cliente);
	// CRIA O HTML DO CALENDARIO COM BASE NA ARRAY CRIADA ACIMA.
	$id   	= 1;
	$html 	= "";
	$x		= 0;
	foreach ($calendario as $chave_ano => $ano)
	{
		$html .= "<table id='ano-$chave_ano' >";
		$html .= "	<tr>
						<td>";
		foreach ($ano as $chave_mes => $mes)
		{
			$html .= "	<table id='$chave_ano-$id' class='tabela-calendario $chave_mes'>";
			$html .= "		<tr>
								<th align='center' colspan='7' style='text-align: center;color: black!important;background: transparent!important;'>
									<div style='height: 36px;display: flex;justify-content: space-around;align-items: center;'>
										<button type='button' class='btn-voltar btn'>
											<i class='fa fa-angle-double-left' style='height: 100%;display: block;'></i>
										</button>
										<h2 style='margin:0;ont-weight: 400;line-height: 32px;font-size: 2.5rem; text-transform: uppercase;display:inline;'>
											$chave_mes de $chave_ano
										</h2>
										<button type='button' class='btn-proximo btn'>
											<i class='fa fa-angle-double-right' style='height: 100%;display: block;'></i>
										</button>
									</div>
								</th>
							</tr>";
			
			$html .= "		<tr>
								<th width='14.28%'>Domingo</th>
								<th width='14.28%'>Segunda-Feira</th>
								<th width='14.28%'>Terça-Feira</th>
								<th width='14.28%'>Quarta-Feira</th>
								<th width='14.28%'>Quinta-Feira</th>
								<th width='14.28%'>Sexta-Feira</th>
								<th width='14.28%'>Sabado</th>
							<tr>";
	
			foreach ($mes as $chave_dia => $dia)
			{
				if(isset($freq_cliente[$x]['data']))
					$exp = @explode("-", $freq_cliente[$x]['data']);
				else
					$exp = -1;
				
				if($chave_ano == $exp[0] && $chave_mes == $nome_mes[$exp[1]-1] && $dia == $exp[2])
				{
					$box_prod  = "";
					$cor_fundo = "";
					$y = 0;
					foreach ($prod_cliente[$freq_cliente[$x]['data']] as $value)
					{
						if($value['id_tipo_acesso'] == 1)//SHAKE
							$cor_fundo = "#ff0035";
						else if($value['id_tipo_acesso'] == 2)//SOPA
							$cor_fundo = "#8b00ff";
						else if($value['id_tipo_acesso'] == 3)//NUTRISOUP
							$cor_fundo = "#009688";
						else
							$cor_fundo = "#FFF";

						$box_prod .= "<p style='background: $cor_fundo; font-size: 12px;width: 20px;display: inline-block;text-align: center;margin: 0 2px;'>$value[qtd]</p>";
						$y++;
					}
					$classe_freq = "box-freq-cli";
					
					if($y > 0)
						$x = $x + $y;
					else
						$x++;
				}
				else
				{
					$y = 0;
					$box_prod 	 = "";
					$classe_freq = "";
				}
				
				$exp_semana = @explode("&", $chave_dia);
				$semana = $exp_semana[2];
				
				//cria tds vazias caso o dia primeiro não comece em um domingo, sendo assim o dia primeiro começa na td respectiva do dia da semana correta.
				if($dia == 1 && $semana == 1)
					$html .= criar_td($semana) . "<td class='$classe_freq' id='dia-$dia'>$dia <br>$box_prod</td>";
				else if($dia == 1 && $semana == 2)
					$html .= criar_td($semana) . "<td class='$classe_freq' id='dia-$dia'>$dia <br>$box_prod</td>";
				else if($dia == 1 && $semana == 3)
					$html .= criar_td($semana) . "<td class='$classe_freq' id='dia-$dia'>$dia <br>$box_prod</td>";
				else if($dia == 1 && $semana == 4)
					$html .= criar_td($semana) . "<td class='$classe_freq' id='dia-$dia'>$dia <br>$box_prod</td>";
				else if($dia == 1 && $semana == 5)
					$html .= criar_td($semana) . "<td class='$classe_freq' id='dia-$dia'>$dia <br>$box_prod</td>";
				else if($dia == 1 && $semana == 6)
					$html .= criar_td($semana) . "<td class='$classe_freq' id='dia-$dia'>$dia</td>";
				else
					$html .= "<td class='$classe_freq' id='dia-$dia'>$dia <br>$box_prod</td>";
				
				//Caso seja no final da semana(Sabado) ele fecha a tr e cria outra pra proxima linha.
				if($semana == 6)
					$html .= "</tr>";
			}
			$id++;
			
			$html .= "		</tr>";
			$html .= "		</table>";
		}
		$id = 1;
		$html .= "		</td>
					  </tr>";
		$html .= "	</table>";
	}
	
	mysqli_close($conn);
	return $html;
}

function array_produtos($ar)
{
	foreach ($ar as $key => $value)
	{
		$produtos[] = array("id_tipo_acesso" => $value['id_tipo_acesso'], "nome"=>$value['nome'], "qtd"=>$value['qtd_acesso']);
		$itens_produtos[$value['data']] = $produtos;

		if(isset($ar[$key+1]) && $ar[$key+1]['data'] != $value['data'])
			unset($produtos);
	}

	return $itens_produtos;
}

function criar_td($semana)
{
	$tds = "";
	for($i = 0; $i < $semana; $i++)
		$tds .= "<td align='center' class='box-inativo'></td>";

		return $tds;
}