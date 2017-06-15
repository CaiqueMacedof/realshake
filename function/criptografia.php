<?php

function descryt($string)
{
	$string = base64_decode($string);

	if(!empty($string))
	{
		if(strpos($string, "&"))
		{
			$request_nome_value = explode("&", $string);
			$string = array();

			for($i = 0; $i < count($request_nome_value); $i++)
			{
				if(strpos($request_nome_value[$i], "="))
				{
					$request = explode("=", $request_nome_value[$i]);
					
					$string[$request[0]] = $request[1];
				}
			}
		}
	}
	else 
		return "String vazia.";

	return $string;
}

function encrypt($array)
{
	if(is_array($array))
	{
		$i 		 = 0;
		$string  = "";
			
		foreach ($array as $index => $value)
		{

			if(empty($string))//Se for a primeira vez
				$string = $index . "=" . $value . "&";
				else if($i == count($array) - 1)//Se estiver no final
					$string .= $index . "=" . $value;
					else
						$string .= $index . "=" . $value . "&";

			$i++;
		}

	}
	else
		return "Passar array por parâmetro";

		
	return base64_encode($string);
}