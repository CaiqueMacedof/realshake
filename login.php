<?php
	
	require_once("function/conexao.php");
	require_once("function/mysqli_fetch_all_mod.php");
	
	
	//Caso nao exista uma sessão, ele irá iniciar;
	if(session_status() == PHP_SESSION_NONE)
    	session_start();
    
    $login    = isset($_REQUEST['login']) 	 		  ? $_REQUEST['login'] 	  		   : '';
    $password = isset($_REQUEST['password']) 		  ? $_REQUEST['password'] 		   : '';
    $msg 	  = isset($_REQUEST['msg']) 	 		  ? $_REQUEST['msg'] 	  		   : '';
    $action	  = isset($_REQUEST['action'])			  ? $_REQUEST['action'] 	 	   : '';
    $remember = isset($_REQUEST['remember_password']) ? $_REQUEST['remember_password'] : '';
	
    	
    if($action == 'Entrar')
    {
    	if($remember == 1)
    	{
    		if(isset($login) && isset($password))
    		{
    			setcookie("login", $login, time() + 3600);//Cookie ativo por 2 horas;
    			setcookie("password", $password, time() + 3600);//Cookie ativo por 2 horas;
    		}
    	
    	}
    	
    	if($login == '' || $password == '')
	    {
	    	sleep(2);
	    	header('location: login.php?msg=Usuário e/ou senha incorreto(s)');   	
	 		die();
	    }
	    else
	    {
	    	//Faz uma busca pelo login e senha;
	    	$query = sprintf(" SELECT 
	    						 * 
	    					   FROM usuario 
	    					   WHERE login = '%s' AND senha = '%s'; ", 
	    			
	    			mysqli_real_escape_string($conn, $login),
	    			mysqli_real_escape_string($conn, md5($password)) );
	    	
	    	$result   = mysqli_query($conn, $query);
	    	$registro = mysqli_num_rows($result);
	    	
	    	if($registro > 0)// Login e usuário cadastrado;
	    	{
	    		$registro 	= mysqli_num_rows($result);
	    		$result 	= mysqli_fetch_all_mod($result);
	    		
	    		$_SESSION['id']   	= $result[0]['id_usuario'];
	    		$_SESSION['nome'] 	= $result[0]['login'];
	    		$_SESSION['tipo'] 	= $result[0]['tipo'];
	    		
	    		sleep(2);
	    		header("location: index.php");
	    		die();
	    	}
	    	else
	    	{
	    		sleep(2);
	    		header("location: login.php?msg=Usuário e/ou senha incorreto(s)");
	    		die();
	    	}
	    	
	   	
	    }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-08">
	<title>Real Shake</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="font-awesome/css/font-awesome.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<script src="js/jquery.js"></script>
</head>

<script>

$(document).ready(function(){

	//Padrão
	$(".img-load").hide();

	$("#btn-entrar").click(function(){

		$(".img-load").show();

	});

});


</script>

<body style="font-size: 0.8em">
	<div style="width: 100%; height: 100vh; background: url(img/paisagem1.jpg);background-size: cover;">
		
		<div class="box">
		<!-- 	<div class="box-logo" style="margin-bottom: 25px;">
				 <img src="img/logo/logo.png" style="width: 50%;">
			</div>-->
			<div class="box-login">
				
				<div class="box-login-form">
					<div class="titulo-login">
						<h2>Bem Vindo!</h2>
						<p>Por favor, digite o seu usuário e senha.</p>
					</div>
					
					<form action="" method="get" id="teste">
						<div class="box-input">
							<input type="text" name="login" placeholder="Usuário" value="<?php echo isset($_COOKIE["login"]) ? $_COOKIE["login"] : ""; ?>">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</div>
						
						<div class="box-input">
							<input type="password" name="password" placeholder="Senha" value="<?php echo isset($_COOKIE["password"]) ? $_COOKIE["password"] : ""; ?>">
							<i class="fa fa-lock fa-lock-edit" aria-hidden="true"></i>
						</div>
						
						<input type="checkbox" name="remember_password" value="1"> Lembrar usuário e senha?
						
						<div class="box-login-msg">
						<?php 
							if(!empty($msg))
								echo "<span class='msg-erro'>$msg</span>"; 
						?>
						</div>
						
						<div class="btn-login" style="margin-top: 15px;">
							<input type="submit" name="action" value="Entrar" id="btn-entrar" >
							<img src="img/load.gif" class="img-load">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>