<?php 
	
	if(!isset($_SESSION))
	session_start();
	
	if(strpos($_SERVER['REQUEST_URI'], '/') == 0)
	{
		$url = explode('/', $_SERVER['REQUEST_URI']);
		$url = $url[2];
		
		if(strpos($url, '.'))
		{
			$url = explode('.', $url);
			$url = $url[0];
		}
				
	}
	else 
		die('erro na URL');
		
?>
