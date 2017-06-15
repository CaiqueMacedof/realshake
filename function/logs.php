<?php

function log_login($conn, $id_user, $descricao)
{
	$query = sprintf("INSERT INTO log
					 (id_usuario, datahora, descricao)
					  VALUES
					 (%d, NOW() , '%s')", 
			
			mysqli_real_escape_string($conn, $id_user),
			mysqli_real_escape_string($conn, $descricao));
	
	//echo $query;
	if(mysqli_query($conn, $query) !== false)
		return true;
	else
		return "Erro ao inserir um LOG " . mysqli_error($conn);
		
	
}