<?php

function buscarCor($numero)
{
	$cores = array(	0   => "#00c0ef", 1    => "#dd4b39",
					2   => "#444",	  3    => "#009688",
					4   => "#4caf50", 5    => "#e91e63",
					6   => "#ffc107", 7    => "#3f51b5",
					8   => "#9e9e9e", 9    => "#795548",
					10  => "#68a00f", 11   => "#795548",
					12  => "#4682B4", 13   => "#87CEFA",
					14  => "#D8BFD8", 15   => "#20B2AA",
					16  => "#FA8072", 17   => "#CD5C5C",
					18  => "#A52A2A", 19   => "#DC143C",
					20  => "#ADD8E6", 21   => "#7B68EE",
					22  => "#F4A460", 23   => "#6495ED",
					
	);
	
	$cor = $cores[$numero];
	
	return $cor;
}

function buscarAvatar($sexo)
{
	$avatares = array
	(
		"masculino" => array(
						0 => "homem1.png",
						1 => "homem2.png",
						2 => "homem3.png",
						3 => "homem4.png",
						4 => "homem5.png"
		),
		"feminino"  => array(
						0 => "mulher1.png",
						1 => "mulher2.png",
						2 => "mulher3.png",
						3 => "mulher4.png",
						4 => "mulher5.png"
		)
	);
	
	$numero_masc = rand(0, count($avatares['masculino']) - 1);
	$numero_femi = rand(0, count($avatares['feminino']) - 1);

	if($sexo == 0)
		return $imagem = $avatares['masculino'][$numero_masc];
	else
		return $imagem = $avatares['feminino'][$numero_femi];
	
	return "";
	
}