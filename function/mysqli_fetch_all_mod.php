<?php

function mysqli_fetch_all_mod($result, $assoc = NULL)
{
	$retorno = array();

	if ($assoc == MYSQLI_ASSOC)
	{
		while($linha = mysqli_fetch_array($result, MYSQLI_ASSOC))
			$retorno[] = $linha;
	}
	else if ($assoc == MYSQLI_NUM)
	{
		while($linha = mysqli_fetch_array($result, MYSQLI_NUM))
			$retorno[] = $linha;
	}
	else
	{
		while($linha= mysqli_fetch_array($result, MYSQLI_BOTH))
			$retorno[] = $linha;
	}
	return $retorno;
}