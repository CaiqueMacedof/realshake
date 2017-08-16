<?php
	
	require_once("function/conexao.php");
	require_once('function/mysqli_fetch_all_mod.php');
	require_once('function/cliente.php');
	
	$id_cliente = isset($_REQUEST['id_cliente']) ? $_REQUEST['id_cliente'] : 0;
	$clientes = listaCliente($conn, $id_cliente);

	if(is_array($clientes) && count($clientes) > 0)//Cliente existe
	{
		$colunaTH 	= "";
		$colunaTD 	= "";
		$retorno	= "";
		$shake 		= listaAcesso($conn, $id_cliente, 1);//Consumo Shake;
		$sopa 		= listaAcesso($conn, $id_cliente, 2);//Consumo Sopa;
		$nutriSoup 	= listaAcesso($conn, $id_cliente, 3);//Consumo NutriSoup;
		//var_dump($shake);die;
		if($nutriSoup[0] != 0 || $nutriSoup[1] != 0)
		{
			$colunaTH .= "NutriSoup";
			$colunaTD .= $nutriSoup[0] . "/" . $nutriSoup[1];
		}
		
		if($shake[0] != 0 || $shake[1] != 0)
		{
			if($colunaTH == "" || $colunaTD == "")
			{
				$colunaTH .=  "Shake";
				$colunaTD .=  $shake[0] . "/" . $shake[1];
			}
			else
				$colunaTH .= ",Shake";
				$colunaTD .= "," . $shake[0] . "/" . $shake[1];
		}
		
		if($sopa[0] != 0 || $sopa[1] != 0)
		{
			if($colunaTH == "" || $colunaTD == "")
			{
				$colunaTH .= "Sopa";
				$colunaTD .= $sopa[0] . "/" . $sopa[1];
			}
			else
			{
				$colunaTH .= ",Sopa";
				$colunaTD .= "," . $sopa[0] . "/" . $sopa[1];
			}
		}
		
		
		if(($sopa[0] == 0 && $sopa[1] == 0) &&
				($shake[0] == 0 && $shake[1] == 0) && 
					($nutriSoup[0] == 0 && $nutriSoup[1] == 0))
			$retorno = "";
		else 
			$retorno = $colunaTH . "@" . $colunaTD;

		echo $retorno;
		
	}

?>