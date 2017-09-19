<?php

	header("Content-Type: text/html; charset=ISO-8859-1");

	if(!isset($_SESSION))
	  session_start();
  
	//Se nao houve sessÃ£o quando logar, voltara para a tela de login;
	if(!isset($_SESSION['id']))
	{
		header("location: login.php");
		die();
	}
	  
	  
	/* Carrega buffer com os dados de saida */
	ob_start();
	
	require_once("access.php");
	require_once("function/conexao.php");
	require_once('function/mysqli_fetch_all_mod.php');
	  
	if(isset($_REQUEST['msg']))
	  $msg = $_REQUEST['msg'];
?>

<!DOCTYPE html>
<html>
<head>
	<title>Real Shake</title>
 
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" HTTP-EQUIV="Content-Type" CONTENT="charset=ISO-8859-1">
	<link rel="icon" href="img/logo/logo.png" type="image/gif" sizes="16x16">
	<!-- Meu CSS  -->
	<link rel="stylesheet" href="css/style.css">
	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
	<!-- Ionicons -->
 	<link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/AdminLTE.css">
	<!-- daterange picker -->
	<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
	<!-- bootstrap datepicker -->
	<link rel="stylesheet" href="plugins/datepicker/datepicker3.css">

	<link rel="stylesheet" href="dist/css/skins/skin-blue.css">
	<link rel="stylesheet" href="dist/css/skins/skin-red-light.css">
	<link rel="stylesheet" href="dist/css/skins/skin-red.css">
	
	<!-- jQuery 2.2.3 -->
	<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
	<!-- JqueryUI -->
	<script src="plugins/jQueryUI/jquery-ui.js"></script>
	<script src="plugins/fastclick/fastclick.js"></script>
	
	<!-- Menu -->
	<script src="js/menu.js"></script>
	
	<!-- DataTables -->
	<script src="plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

	<!-- GrÃ¡ficos CHARTJS -->
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js" />-->
	<!-- <script src="plugins/chartjs/Chart.min.js" /> -->
	<script src="js/hightcharts.js"></script>
	<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
	<script src="https://www.amcharts.com/lib/3/pie.js"></script>
	<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
	<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
	<script src="https://www.amcharts.com/lib/3/themes/dark.js"></script>
	
	
	<link rel="stylesheet" href="plugins/jQueryUI/jquery-ui.css"></script>
	<!-- Bootstrap 3.3.6 -->
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/app.min.js"></script>
	
	<script src="dist/js/demo.js"></script>
	
	<script src="plugins/daterangepicker/moment.min.js"></script>
	<script src="plugins/daterangepicker/daterangepicker.js"></script>
	<!-- bootstrap datepicker -->
	<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
	<!-- InputMask -->
	<script src="plugins/input-mask/jquery.inputmask.js"></script>
	<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
	<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
</head>

<body class="hold-transition skin-red sidebar-mini <?php echo isset($_COOKIE['max-min-menu']) ? $_COOKIE['max-min-menu'] : ""?>">
<div class="wrapper">
  <!-- Main Header -->
  <header class="main-header">

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>     
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
  
  <div class="box-logo">
     <img src="img/logo/logo.png" style="width: 70%;">
  </div>
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">Menu de Navegação</li>
        
    	<li id="1" class="treeview">
          <a href="index.php"><i class="fa fa-area-chart"></i> <span>Dashboard</span></a>
           
        </li>
    
    	<li id="2" class="treeview">
          <a href="#"><i class="fa fa-user"></i> <span>Clientes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="2-1"><a href="clientes.php">Clientes</a></li>
            <li id="2-2"><a href="acesso_rapido.php">Acesso rápido</a></li>
            <li id="2-3"><a href="clientes_historico.php">Histórico de Compra</a></li>
          </ul>
        </li>
    	
    	<li id="3" class="treeview">
          <a href="#"><i class="fa fa-shopping-bag"></i><span>Produtos</span></a>
        </li>
        
        <li id="3" class="treeview">
          <a href="#"><i class="fa fa-cog"></i><span>Configurações</span>
          	<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="3-1"><a href="usuario.php">Criar Usuário</a></li>
          </ul>
        </li>
        
      	<li class="treeview">
          <a href="logout.php"><i class="fa fa-close"></i> <span>Sair</span></a>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
