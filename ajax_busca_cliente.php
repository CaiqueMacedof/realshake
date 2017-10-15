<?php
@session_start();
require_once("access.php");
require_once('function/mysqli_fetch_all_mod.php');
require_once("function/conexao.php");
require_once('function/cliente.php');
require_once('function/buscaleatoria.php');

$nome 	 = isset($_REQUEST['nome']) 	? $_REQUEST['nome'] 	: "";
$celular = isset($_REQUEST['celular'])	? $_REQUEST['celular']  : "";

$frequencias = buscarFrequencias($conn, $nome, $celular);
if(is_array($frequencias) && count($frequencias) > 0)
{
	echo "<div class='col-lg-4 col-xs-12 col-sm-6'>
				<div data-toggle='modal' data-target='#acesso_rapido' class='small-box bg-aqua' data-cliente='0' style='background:rgba(0,0,0,0.20)!important'>
					<div class='inner' style='max-width: 70%;'>
						<h4 style='color:aliceblue;font-weight:bold;'>AVULSO</h4>
					</div>
					<div class='box-img'>
						<img src='img/avatares/neutro.png'>
					</div>
				</div>
			</div>";
	
	$i = 0;
	foreach ($frequencias as $topFrequencia)
	{
		//Busca os avatares masculinos, senão busca os feminino
		$imagem = buscarAvatar($topFrequencia['sexo']);
		//Busca as cores
		$cor = buscarCor($i);
		
		if($topFrequencia['total_frequencia'] == 1)
			$texto = "1 Frequência";
		else
			$texto = $topFrequencia['total_frequencia'] . " Frequências";
		
		echo "	<div class='col-lg-4 col-xs-12 col-sm-6'>
					<div data-toggle='modal' data-target='#acesso_rapido' class='small-box bg-aqua' data-cliente='$topFrequencia[ID_CLIENTE]' style='background: $cor!important'>
						<div class='inner' style='max-width: 70%;'>
							<h4>$topFrequencia[nome]</h4>
							<p>$texto</p>
						</div>
						<div class='box-img'>
							<img src='img/avatares/$imagem'>
						</div>
					</div>
				</div>";
		$i++;
	}
}
?>