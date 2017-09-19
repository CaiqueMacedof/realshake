<?php
require_once('header.php');
require_once('function/cliente.php');
require_once('function/criptografia.php');
require_once('function/mysqli_fetch_all_mod.php');

$msg	  	  		= isset($_REQUEST['msg']) 				? $_REQUEST['msg'] 	 			: "";
$erro	  	  		= isset($_REQUEST['erro']) 				? $_REQUEST['erro'] 	 		: 0;
$action	  	  		= isset($_REQUEST['acao']) 				? $_REQUEST['acao'] 	 		: "";

//Filtro das compra de acesso
$nome		  		= isset($_REQUEST['nome']) 					? $_REQUEST['nome'] 	 			: "";
$periodo_historico 	= isset($_REQUEST['periodo_historico']) 	? $_REQUEST['periodo_historico'] 	: "";


//Lista do banco os tipo de contato. EXEMPLO: indicaÃ§Ã£o, facebook ...
$tipo_contatos = lista_tipo_contato($conn);

switch ($action){
	case 'filtrar':
		//Formatando as datas pra busca no banco de dados;
		$periodo = explode(" - ", $periodo_historico);
		$data_inicial 	= date("Y-m-d", strtotime(str_replace("/", "-", $periodo[0]))) . " 00:00:00";
		$data_final	 	= date("Y-m-d", strtotime(str_replace("/", "-", $periodo[1]))) . " 23:59:59";

		$historico_venda = listarHistoricoCliente($conn, false, $nome, $data_inicial, $data_final);
		$total 		 	 = totalQtdVendaAcesso($conn, $nome, $data_inicial, $data_final);

		break;

	default:
		$historico_venda = listarHistoricoCliente($conn);
		$total 		 	 = totalQtdVendaAcesso($conn);
}

$total_venda = number_format($total['total_venda'], 2, ',', '.');

?>
<script>

$( function() {

	$('#example1').DataTable({
      "paging": 		true,
      "lengthChange": 	true,
      "searching": 		false,
      "ordering": 		true,
      "info": 			true,
      "autoWidth": 		false,
      "select": 		true
    });
    
    var availableTags = [
    <?php 
    
    $historicos = listarHistoricoCliente($conn, " GROUP BY cli.nome");
    $total  	= count($historicos);
    $i 			= 1;

	foreach ($historicos as $historico)
	{
		if($i < $total)
			echo '"' . $historico['nome_cliente'] . '",';
		else
			echo '"' . $historico['nome_cliente'] . '"';
		$i++;	
	}
    ?>
    ];
    
    $( "#tags" ).autocomplete({
      source: availableTags
    });
} );
  
$(document).ready(function(){

  //Datemask dd/mm/yyyy
  $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
  //Datemask2 mm/dd/yyyy
  $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
  //Money Euro
  $("[data-mask]").inputmask();

  //Date picker
  $('#datepicker').datepicker({
    autoclose: true
  });

 var d = new Date();

  var mes_atual = d.getMonth()+1;
  var dia_atual = d.getDate(); 

  $('#reservationtime').daterangepicker('setDate', '');
  //Date range picker
  $('#reservation').daterangepicker();
  //Date range picker with time picker
  $('#reservationtime').daterangepicker(
  {
	  "autoApply": true,
	  "opens": "right",
	  "locale": {
		  		"format": 'DD/MM/YYYY',
		        "applyLabel": "Aplicar",
		        "cancelLabel": "Fechar",
		        "daysOfWeek":[
	  	             "Dom",
	  	             "Seg",
	  	             "Ter",
	  	             "Quar",
	  	             "Quin",
	  	             "Sex",
	  	             "Sab"
		  	    ],
  	            "monthNames":[
	  	             "Janeiro",
	  	             "Fevereiro",
	  	             "MarÃ§o",
	  	             "Abril",
	  	             "Maio",
	  	             "Junho",
	  	             "Julho",
	  	             "Agosto",
	  	             "Setembro",
	  	             "Outobro",
	  	             "Novembro",
	  	             "Dezembro"
  	           ]
  	  },
	  startDate: '01/' + mes_atual + '/2017',
	  endDate: dia_atual + '/' + mes_atual + '/2017'
  });

});
</script>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Histórico de Compras
    <small>Compra de acessos</small>
  </h1>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
        <div class="box box-danger">
        <div class="box-header with-border">
           <h3 class="box-title">Busca</h3>
        </div>
        
        <form action="clientes_historico.php" method="post">
	        <div class="box-body">
	          <!-- Date dd/mm/yyyy -->
	          <div class="form-group col-lg-4 col-xs-12 col-sm-6">
	            <label>Nome:</label>
	            <div class="input-group" style="width: 100%;">
	              <input type="text" name="nome" class="form-control" id="tags">
	            </div>
	            <!-- /.input group -->
	          </div>
	
	          <div class="form-group col-lg-4 col-xs-12 col-sm-6">
	              <div class="form-group">
	                <label>Período:</label>
	
	                <div class="input-group">
	                  <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </div>
	                  <input type="text" name="periodo_historico" class="form-control pull-right" id="reservationtime" >
	                </div>
	                <!-- /.input group -->
	              </div>
	            
	          </div><!-- /.form-group-->

	        </div><!-- /.box-body -->
	
	        <div class="box-footer">
	            <button type="submit" name="acao" class="btn btn-primary pull-right btn-Maxwidth" value="filtrar">
	              <i class="fa fa-search"></i>
	              Buscar
	            </button>
	        </div>
        	
        </form>
      </div>
    </div>
  </div>
  <!-- /.row -->
   <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Histórico</h3>
        </div>
        
        <div class="box-body">
          <table id="example1" class="table table-bordered table-hover">
            <thead>
            
              <tr>
                <th>Nome</th>
                <th style="text-align:center;">Data</th>
                <th style="text-align:center;">Tipo de Acesso</th>
                <th style="text-align:center;">Quantidade</th>
                <th style="text-align:center;">Valor</th>
              </tr>
            
            </thead>
            
            <tbody>
		            <?php
					if(is_array($historico_venda) && count($historico_venda) > 0)
					{
						foreach ($historico_venda as $historico)
						{
							$data_venda  = date("d/m/Y - h:m:s", strtotime($historico['data_venda']));
							$valor_venda = "R$ " . number_format($historico['valor_venda_acesso'], 2, ',', '.'); 
						?>
		              <tr>
		                <td><?php echo $historico['nome_cliente']; ?></td>
		                <td align="center"><?php echo $data_venda; ?></td>
		                <td align="center"><?php echo $historico['nome_acesso']; ?></td>
		                <td align="center"><?php echo $historico['qtde_acesso']; ?></td>
		                <td align="right"><?php echo $valor_venda; ?></td>
		              </tr>
		            <?php 
						}
					}
					else
					{
					
		            ?>
		            <tr>
		            	<td colspan="6" align="center" style="color: #FF0000;font-weight: bold;">Nenhum histórico encontrado.</td>
		            </tr>
		            
		            <?php 
					}
		            ?>
            </tbody>
            <tbody style="border-top: none;">
	            <tr>
	            	<th colspan="4" style="text-align:right;">Total</th>
	            	<th colspan="4" style="text-align:right;color: #2cb03c;">
	            	  	<i class="fa fa-usd"></i>
	            		<?php echo $total_venda; ?>
	            	</th>
	            </tr>
            </tbody>
          </table>
        </div>

         <div class="box-footer">
            
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
</section>
<?php
  require_once("footer.php");
?>