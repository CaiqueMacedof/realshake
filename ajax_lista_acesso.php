<?php
	
	require_once("function/conexao.php");
	require_once('function/mysqli_fetch_all_mod.php');
	require_once('function/cliente.php');
	
	$id_cliente = isset($_REQUEST['id_cliente']) ? $_REQUEST['id_cliente'] : 0;

	$clientes = listaCliente($conn, $id_cliente);
	if(is_array($clientes) && count($clientes) > 0)//Cliente existe
	{
		$shakes 	= listaAcesso($conn, $id_cliente, 1);//Consumo Shake;
		$sopas 		= listaAcesso($conn, $id_cliente, 2);//Consumo Sopa;
		$nutriSoups = listaAcesso($conn, $id_cliente, 3);//Consumo NutriSoup;
		
		if(!empty($shakes[0]) || !empty($shakes[1]))
			$shake = "$shakes[0]-$shakes[1]";
		else
			$shake = 0;
		
		if(!empty($sopas[0]) || !empty($sopas[1]))
			$sopa = "$sopas[0]-$sopas[1]";
		else
			$sopa = 0;
				
		if(!empty($nutriSoups[0]) || !empty($nutriSoups[1]))
			$nutriSoup = "$nutriSoups[0]-$nutriSoups[1]";
		else
			$nutriSoup = 0;
		
		$json_acessos = array(1 => $shake, 2 => $sopa, 3 => $nutriSoup);
		
		echo json_encode($json_acessos);
	}

?>