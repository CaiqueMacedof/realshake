<?php

/* Função que retorna 3 tipos de estatítiscas referente ao cliente/acesso
 * @param $conn Conexão do banco
 * @param $tipo Tipo de estatísticas:
 * 1 = Frequência de clientes
 * 2 = Frequência da origem de cliente
 * 3 = Frequência de tipo de acessos
 * @param $cliente_id ID do cliente que sera buscado
 * @param $data_inicio Inicio do período a ser procurado, apenas 1 mês
 * @param $data_fim Fim do período a ser procurado, apenas 1 mês
 * @return Retorna uma array com as estatísticas
 */
function estatistica_acesso_cliente($conn, $tipo = 1, $cliente_id = null, $data_inicio = null, $data_fim = null)
{
	$query = "";
	
	if($tipo = 1)
	{
		$query = "	SELECT
						DATE_FORMAT(data_hora_baixa_acesso,'%d/%m/%Y') as data_dia,
						count(*) as total_freq_cliente
					FROM real_shake.baixa_acesso
					
					where 1 = 1";
				
		if(!empty($id_cliente))
			$query .= sprintf(" and id_cliente = %d", mysqli_real_escape_string($conn, $cliente_id));
	
		if(!empty($data_inicio) && ($data_fim))
		{
			$query .= sprintf(" 	and data_hora_baixa_acesso
								between '%s' and '%s'
								", 
					mysqli_real_escape_string($conn, $data_inicio),
					mysqli_real_escape_string($conn, $data_fim));
		}
		
		$query .= "  group by DATE_FORMAT(data_hora_baixa_acesso,'%d/%m/%Y')
					order by data_hora_baixa_acesso";
		
		$resultado = mysqli_query($conn, $query);
	}
	else if($tipo = 2)
	{
		$resultado = false;
	}
	else if($tipo = 3)
	{
		$resultado = false;
	}
	else
	{
		return false;
	}

	if($resultado != false)
		return mysqli_fetch_all_mod($resultado, MYSQLI_ASSOC);
	else
		return false;
}