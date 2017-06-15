<?php  

session_start();// startando uma sessão;

if(isset($_SESSION['id']))
{
	session_unset();
	header('location:login.php');
	die();
}
else// caso o usuário clicar para voltar na pagina e for em sair, ele sairá mesmo assim.
{
	header('location:login.php');
	die();
}
?>