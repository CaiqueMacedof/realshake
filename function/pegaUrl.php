<?php


function pegaURL()
{
	if(strpos($_SERVER['REQUEST_URI'], '?') != false)
	{
		$expl = explode("?", $_SERVER['REQUEST_URI']);
		$url_request = $expl[1];
		
	}
	else 
		$url_request = "";
	
	return $url_request;//retorna todo o request do URL se houver;
	
}