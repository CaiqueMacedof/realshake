<?php

function insertUsuario($conn, $login, $senha)
{
	$query = sprintf("INSERT INTO usuario
					 (login, senha, tipo)
					  VALUES
					 ('%s', MD5('%s'), 1)", 
			
			 mysqli_real_escape_string($conn, $login),
			 mysqli_real_escape_string($conn, $senha));
	
	$retorno = mysqli_query($conn, $query);
	
	if($retorno == true)
		return $retorno;
	else 
		return false;
}