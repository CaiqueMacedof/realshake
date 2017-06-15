<?php
function calendario($anos)
{
	$meses 		= $anos * 12;
	$month 		= 01;
	$year  		= 2010;
	$list  		= array();
	$calendario = array();
	
	for($y = 1; $y <= $meses ; $y++)
	{
		if($month > 12)
		{
			$year = $year + 1;
			$month = 01;
		}
	
		$ultimo_dia = date("t" ,strtotime($year . '-' . $month . '-' . 01));
	
		$dias_semanas = array(0 => "Domingo",
							  1 => "Segunda-feira",
							  2 => "Terça-feira",
							  3 => "Quarta-feira",
							  4 => "Quinta-feira",
							  5 => "Sexta-feira",
							  6 => "Sabado");
	
		$x = 0;
		for($d=1; $d<=$ultimo_dia; $d++)
		{
			$time = mktime(12, 0, 0, $month, $d, $year);
			if (date('m', $time)==$month)
				$list[$x] = date('Y-m-d/w', $time);
	
				foreach ($dias_semanas as $dias_semana => $nome_semana)
				{
					$xplode_data = explode("/", $list[$x]);
					if(strpos($xplode_data[1], (string)$dias_semana) !== false)
					{
						$semana = str_replace($dias_semana, $nome_semana, $xplode_data[1]);
						$list[$x] = $xplode_data[0] . "/" . $semana;
	
					}
	
				}
				$x++;
		}
	
		$month++;
	
		$calendario[$y] = $list;
		unset($list);
	}
	
	return $calendario;
}
