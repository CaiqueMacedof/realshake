<?php

function paginacao($inicio_pagina, $total_registro, $limite_pagina, $url_pagina, $action)
{
	$pagina_atual 	  = ($inicio_pagina / $limite_pagina) + 1;
	$pagina_anterior  = $inicio_pagina - $limite_pagina;
	$proxima_pagina   = $inicio_pagina + $limite_pagina;
	
	//$total recebe o total da paginação
	$total = $total_registro/$limite_pagina;
	/*
	 Se o valor for double ele acrescenta +1 no total das paginações,
	 consequentemente ele vai mais um loop e lista os registros restantes.
	 */
	if(is_double($total))
		$total = (int)$total+1;

	if($action == "Filtrar")
	{
		$parametro = $_SERVER['REQUEST_URI'];

		$parametros = explode("nome", $parametro);
		$parametros = $parametros[1];
		
		$parametros =  "nome". $parametros;
	}
	
	if($total_registro > 4)
	{
		if($pagina_atual > 1)
		{
			echo "<a class='pagina_anterior' href='$url_pagina?inicio=$pagina_anterior'> Anterior </a>";
		}
		
		if($pagina_atual >= $total - 3 && $total <= 3)
		{
			$total = $total;
			$i = $total - 2;
		}
		else if($pagina_atual >= $total - 3)
		{
			$total = $total;
			$i = $total - 3;
		}
		else if($pagina_atual >= 3)
		{
			$i = $pagina_atual - 3;
			$total = $pagina_atual + 3;
		}
		else
		{
			$i = 1;
			$total = 4;
		}
	}
	else
		$i = 1;
	
	for(; $i <= $total; $i++)
	{
		
		if($i == (int)$total && is_double($total))
		{
			$inicio = ($i - 1) * $limite_pagina;//calculo do inicio a cada página.
			
			if($action == "Filtrar")
				$parametro = 'inicio=' . $inicio . '&' . $parametros;
			else 
				$parametro = 'inicio=' . $inicio;
			
			echo "<a class='url_paginacao' id='$i' href='$url_pagina?$parametro'>$i</a>";
		}
		else
		{
			$inicio = ($i - 1) * $limite_pagina;
			
			if($action == "Filtrar")
				$parametro = 'inicio=' . $inicio . '&' . $parametros;
			else
				$parametro = 'inicio=' . $inicio;
			
			
			echo "<a class='url_paginacao' id='$i' href='$url_pagina?$parametro'>$i</a> ";
			
		}
	}
	
	if($total_registro > 4)
	{	
		if($pagina_atual < $total)
			echo "<a class='proxima_pagina' href='$url_pagina?inicio=$proxima_pagina'> proximo </a>";
	}
}