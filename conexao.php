<?php  

function conectaDB($host = NULL, $user = NULL, $pass = NULL, $db = NULL)
{
	if(is_null($host))
		$host = "localhost";
	
	if(is_null($user))
		$user = "root";
	
	if(is_null($pass))
		$pass = "";
	
	if(is_null($db))
	{
		$db = "real_shake";
	}
	
	//Inicializa a conexão;
	$link = mysqli_init();
	if (!$link)
    	return 'Setting MYSQLI_INIT_COMMAND failed';
	
	//Faz a conexão com o banco, caso o retorno for false retornara um erro;
	if(!mysqli_real_connect($link, $host, $user, $pass, $db))
		return 'Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error();
	
	//Definindo o padrão da linguagem no banco;
	mysqli_set_charset($link, "utf8");
	

	return $link;
}

	$conn = conectaDB();

