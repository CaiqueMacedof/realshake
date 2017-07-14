<?php 
require_once("header.php");
require_once("function/dashboard.php");

$estatisticas = estatistica_acesso_cliente($conn, 1, null, "2017-05-29", "2017-07-14");

$i 		= 1;
$labels = "";
$valor  = "";
foreach ($estatisticas as $estatistica)
{
	if($i < count($estatisticas))
	{
		$labels .=  "'".$estatistica["data_dia"] . "',";
		$valor 	.= $estatistica["total_freq_cliente"] . ",";
	}
	else
	{
		$labels .= "'".$estatistica["data_dia"] . "'";
		$valor  .= $estatistica["total_freq_cliente"];
	}
	
	$i++;
}

?>

<div style="margin: auto; width: 500px;">
	<canvas id="myChart" class="chartjs" ></canvas>
</div>

<script>

var ctx = document.getElementById("myChart");

new Chart(document.getElementById("myChart"),
	{
		"type":"line",
		"data":{
			"labels":[<?php echo $labels; ?>],
			"datasets":[{
					"label"		  :"Frequências",
					"data" 		  :[<?php echo $valor; ?>],
					"fill" 		  :false,
					"borderColor" :"#21a448",
					"lineTension" :0.1
				}]
		},
			"options":{
				}
		}
	);
	
</script>

<?php 

require_once("footer.php");