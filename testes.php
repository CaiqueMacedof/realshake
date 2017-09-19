<?php
require_once 'header.php';

function gerar_calendario($id_cliente, $ano_inical, $ano_final)
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
	
	$freq_cliente = array(0 => array("data" => "2017-01-1"), 1 => array("data" => "2017-09-1"), 2 => array("data" => "2017-09-22"), 3 => array("data" => "2017-09-25"));
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
								<th align='center' colspan='7' style='text-align: center; background: #00BCD4; color: white;'>
									<button type='button' class='btn-voltar'>
										<i class='fa fa-angle-double-right'></i>
									</button>
									$chave_mes de $chave_ano
									<button type='button' class='btn-proximo'>
										<i class='fa fa-angle-double-left'></i>
									</button>
								</th>
							</tr>";
			
			$html .= "		<tr>
								<th width='14.28%'>Domingo</th>
								<th width='14.28%'>Segunda</th>
								<th width='14.28%'>Terca</th>
								<th width='14.28%'>Quarta</th>
								<th width='14.28%'>Quinta</th>
								<th width='14.28%'>Sexta</th>
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
					$classe_freq = "freq-calendario";
					$x++;
				}
				else
				{
					$classe_freq = "";
				}
				
				$exp_semana = @explode("&", $chave_dia);
				$semana = $exp_semana[2];
				
				//cria tds vazias caso o dia primeiro não comece em um domingo, sendo assim o dia primeiro começa na td respectiva do dia da semana correta.
				if($dia == 1 && $semana == 1)
					$html .= criar_td($semana) . "<td class='$classe_freq' id='dia-$dia'>$dia</td>";
				else if($dia == 1 && $semana == 2)
					$html .= criar_td($semana) . "<td class='$classe_freq' id='dia-$dia'>$dia</td>";
				else if($dia == 1 && $semana == 3)
					$html .= criar_td($semana) . "<td class='$classe_freq' id='dia-$dia'>$dia</td>";
				else if($dia == 1 && $semana == 4)
					$html .= criar_td($semana) . "<td class='$classe_freq' id='dia-$dia'>$dia</td>";
				else if($dia == 1 && $semana == 5)
					$html .= criar_td($semana) . "<td class='$classe_freq' id='dia-$dia'>$dia</td>";
				else if($dia == 1 && $semana == 6)
					$html .= criar_td($semana) . "<td class='$classe_freq' id='dia-$dia'>$dia</td>";
				else
					$html .= "<td class='$classe_freq' id='dia-$dia'>$dia</td>";
				
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
	
	return $html;
}

function criar_td($semana)
{
	$tds = "";
	for($i = 0; $i < $semana; $i++)
		$tds .= "<td align='center'></td>";

		return $tds;
}
?>

<style>

	.tabela-calendario
	{
		width: 950px;
		min-height: 420px;
		border-collapse: collapse;
	    background: white;
	    margin-bottom: 15px;
	    position: absolute;
	}
	
	.tabela-calendario tr th
	{
	}
	
	.tabela-calendario tr td,
	.tabela-calendario tr th
	{
	    text-align: center;
	}
	
	.tabela-calendario tr th
	{
		color: #00BCD4;
		font-weight: bold;
		font-size: 16px;
	}
	
	.dia-ativo
	{
		background: #00BCD4;
	    color: white;
	    font-weight: bold;
	}
	
	.btn-proximo
	{
		padding: 0px 24px;
	    position: absolute;
	    right: 560px;
        background: transparent;
    	border: none;
	}
	
	.btn-voltar
	{
		padding: 0px 24px;
	    position: absolute;
	    left: 560px;;
        background: transparent;
    	border: none;
	}
	
	.freq-calendario
	{
		background: #21a448;
		color: white;
	    font-weight: bold;
	}
	
	.fa-angle-double-left,
	.fa-angle-double-right
	{
		font-size: 25px;
	}
</style>

<script>

$(document).ready(function(){
	
	/* Deixa o mês e o dia ja ativo referente a data atual */
	var d = new Date();

	var ano = d.getFullYear();
	var mes = d.getMonth()+1;
	var dia = d.getDate();

	$("#" + ano + "-" + mes).attr("style", "z-index: 1");
	$("#" + ano + "-" + mes).find("#dia-" + dia).attr("class", "dia-ativo");


	//PROXIMO E VOLTAR DO CALENDÁRIO 
	$(".btn-proximo").click(function(){
		var id_ano = $(this).closest('table').parent().closest('table').attr("id").split('-')[1];
		var id_mes = $(this).closest('table').attr("id").split('-')[1];
		var id_mes_proximo = parseInt(id_mes) + 1;
		var id_ano_proximo = id_mes_proximo == 13 ? parseInt(id_ano) + 1 : id_ano;
			
		$(".tabela-calendario").attr("style", "");
		
		if(id_mes_proximo == 13)
			$("#" + id_ano_proximo + "-1").attr("style", "z-index: 1");
		else
			$("#" + id_ano_proximo + "-" + id_mes_proximo).attr("style", "z-index: 1");
			
	});

	$(".btn-voltar").click(function(){
		var id_ano = $(this).closest('table').parent().closest('table').attr("id").split('-')[1];
		var id_mes = $(this).closest('table').attr("id").split('-')[1];
		var id_mes_anterior = parseInt(id_mes) - 1;
		var id_ano_anterior = id_mes_anterior == 0 ? parseInt(id_ano) - 1 : id_ano;

		$(".tabela-calendario").attr("style", "");
		if(id_mes_anterior == 0)
			$("#" + id_ano_anterior + "-12").attr("style", "z-index: 1");
		else
			$("#" + id_ano_anterior + "-" + id_mes_anterior).attr("style", "z-index: 1");
	});
	
});
</script>
<?php echo gerar_calendario(1, 2016, 2020);?>