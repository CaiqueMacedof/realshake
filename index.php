<?php 
require_once("header.php");
require_once('function/cliente.php');
require_once("function/dashboard.php");

$total 		 		 = totalQtdVendaAcesso($conn);
$total_venda 		 = number_format($total['total_venda'], 2, ',', '.');
$venda_baixa_acesso  = total_venda_baixa_acesso($conn);
$total_acesso 		 = consumoAcessoTotal($conn);
$total_pendente		 = total_pendente($conn);

$baixa_acessos = retornar_diff_baixa_acesso($conn);
$venda_acessos = retornar_diff_venda_acesso($conn);

$estatisticas_linhas = frequenciaCliente($conn);
$valor_periodo 		 = array();
//Vai de 1 atÃ© 12 dos mêses e de acordo com o mÃªs insere os dados;
for($key = 1; $key <= 12; $key++)
{
	foreach ($estatisticas_linhas as $estatistica_key => $estatistica)
	{
		foreach ($estatistica as $value)
		{
			//Verifico se o contador $key Ã© igual ao mÃªs, caso seja eu recebo o total dos acesso daquele mÃªs, senÃ£o eu entendo que nÃ£o existe e marco como 0 acessos.
			if($key == (int)substr($value['data_periodo'], 3, 2))
			{
				$valor_periodo[$estatistica_key][$key] = $value['total_acesso_dia'];
				break;
			}
			else
			{
				$valor_periodo[$estatistica_key][$key] = 0;
			}
		}
	}
}

//Inserindo os valores no formato do javascript;
$i		= 1;
$series = "";
foreach ($valor_periodo as $key => $valores)
{
	if($i < count($valor_periodo))
		$series .= "{name: $key, data: [" . implode(",", $valores) . "]},";
	else
		$series .= "{name: $key, data: [" . implode(",", $valores) . "]}";
	
	$i++;
}

$estatisticas_pie 	= quantidade_origem_cliente($conn);
$i 					= 1;
$pie_origem 		= "";
foreach ($estatisticas_pie as $estatistica)
{
	if($i < count($estatisticas_pie))
		$pie_origem .= "{'name': '".$estatistica['nome']."','y': $estatistica[total]},";
	else
		$pie_origem .= "{'name': '".$estatistica['nome']."','y': $estatistica[total]}";
				
	$i++;
}

$total_clientes = quantidade_cliente($conn);
?>
<!-- Styles -->
<style>

#chartdiv {
  width: 100%;
  height: 500px;
  font-size: 11px;
  color:red;
}

.amcharts-pie-slice {
  transform: scale(1);
  transform-origin: 50% 50%;
  transition-duration: 0.3s;
  transition: all .3s ease-out;
  -webkit-transition: all .3s ease-out;
  -moz-transition: all .3s ease-out;
  -o-transition: all .3s ease-out;
  cursor: pointer;
  box-shadow: 0 0 30px 0 #000;
}

.amcharts-pie-slice:hover {
  transform: scale(1.1);
  filter: url(#shadow);
}

a[href="http://www.amcharts.com/javascript-charts/"] {
    display:none!important;
}				
</style>

<script>
$(function () {

    Highcharts.chart('container', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Estatísticas Contato'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [<?php echo $pie_origem; ?>]
        }]
    });

	/* CONFIGUR��O GR�FICO HIGHTCHARTS */
	$('#id').highcharts({
	      chart: {
	          type: 'areaspline'
	      },
	      xAxis: {
	        tickWidth: 0,
	        categories: ['xaxis']
	      },
	      title: {
	          text: 'Frequências'
	      },
	      tooltip: {
	          shared: true,
	          valueSuffix: ' Acessos'
	      },
	      navigation: {
	        buttonOptions: {
	            enabled: false
	        }
	    },
	    xAxis: {
	        categories: ['Janeiro','Favereiro','Março','Abril', 'Maio', 'Junho', 'julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro']
	    },
	    yAxis: {
	        title: {
	            text: 'Quantidade'
	        }
	    },
	    credits: {
	        enabled: false
	    },
	    plotOptions: {
	        areaspline: {
	            fillOpacity: 0.5
	        }
	    },
	    series: [<?php echo $series; ?>]
	  });
});
</script>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Dashboard
    <small>Painel de Controle</small>
  </h1>
</section>
      
<section class="content">
	<div class="row">
		<div class="col-lg-3 col-xs-12 col-sm-6">
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3><?php echo $total_clientes[0]['total_cliente']; ?></h3>
					<p>Cientes Registratos</p>
				</div>
				
				<div class="icon">
					<i class="ion ion-person-add"></i>
				</div>
				
				<a href="#" class="small-box-footer"></a>
			</div>
		</div>
		
		<div class="col-lg-3 col-xs-12 col-sm-6">
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3><?php echo $total_acesso[0]['total_acessos']; ?></h3>
					<p>Consumos de Acessos</p>
				</div>
				
				<div class="icon">
					<i class=""></i>
				</div>
				
				<a href="#" class="small-box-footer"></a>
			</div>
		</div>
		
		<div class="col-lg-3 col-xs-12 col-sm-6">
			<div class="small-box bg-red">
				<div class="inner">
					<h3>R$ <?php echo number_format($total_pendente, 2, ",", "."); ?></h3>
					<p>Pendentes</p>
				</div>
				
				<div class="icon">
					<i class=""></i>
				</div>
				
				<a href="#" class="small-box-footer"></a>
			</div>
		</div>
		
		<div class="col-lg-3 col-xs-12 col-sm-6">
			<div class="small-box bg-green">
				<div class="inner">
					<h3>R$ <?php echo $total_venda; ?></h3>
					<p>Faturamento</p>
				</div>
				
				<div class="icon">
					<i class=""></i>
				</div> 
				
				<a href="#" class="small-box-footer"></a>
			</div>
		</div>
	</div>
      
	<div class="row">
	    <div class="col-md-8">
	        <div class="box">
	    		<div>
					<div id="id"></div>
				</div>
	    	</div>
		</div>
		
		<div class="col-md-4">
			<div class="box">
		   		<div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
		   		<!-- <div id="chartdiv"></div>-->
			</div>
		</div>
	</div>

	<div class="row">
		
	</div>
</section>


<?php 

require_once("footer.php");