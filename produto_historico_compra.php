<?php
require_once('header.php');
require_once('function/functions_produto.php');
require_once('function/mysqli_fetch_all_mod.php');

$msg	  	  		= isset($_REQUEST['msg']) 				? $_REQUEST['msg'] 	 			: "";
$erro	  	  		= isset($_REQUEST['erro']) 				? $_REQUEST['erro'] 	 		: 0;
$action	  	  		= isset($_REQUEST['acao']) 				? $_REQUEST['acao'] 	 		: "";

//Filtro das compra de acesso
$nome		  		= isset($_REQUEST['nome']) 					? $_REQUEST['nome'] 	 			: "";
$periodo_historico 	= isset($_REQUEST['periodo_historico']) 	? $_REQUEST['periodo_historico'] 	: "";

switch ($action)
{
	case 'filtrar':
		//Formatando as datas pra busca no banco de dados;
		$periodo = explode(" - ", $periodo_historico);
		$data_inicial 	= date("Y-m-d", strtotime(str_replace("/", "-", $periodo[0]))) . " 00:00:00";
		$data_final	 	= date("Y-m-d", strtotime(str_replace("/", "-", $periodo[1]))) . " 23:59:59";

		$historico_compras = lista_compra_produtos($conn, false, $nome, null, null, null, $data_inicial, $data_final);
		$total 		 	  = total_compra_produtos($conn, $data_inicial, $data_final);

	case 'deletar':
		$id_venda_acesso = isset($_REQUEST['id_venda_acesso']) ? $_REQUEST['id_venda_acesso'] : null;
		if(!empty($id_venda_acesso))
		{
			$retorno = deletaVendaAcesso($conn, $exp_valor[0]);
			if($retorno != false)
			{
				$msg = "[AVISO] Venda de acesso deletado com sucesso!";
				header("location: produto_historico_compra.php?msg=$msg");
				die();
			}
			else
			{
				$msg = "[AVISO] Falha ao deletar a venda de acesso!";
				header("location: produto_historico_compra.php?msg=$msg&erro=1");
				die();
			}
		}
		break;

	default:
		$historico_compras = lista_compra_produtos($conn);
		$total 		 	 = total_compra_produtos($conn);
}
$total 		 = isset($total[0]['total_compra']) ? $total[0]['total_compra'] : 0;
$total_venda = number_format($total, 2, ',', '.');

?>
<script>

$( function() {

	$(".fecha-msg").click(function(){
		var parent = $(this).parent();

		parent.css("display", "none");
	});
	
	$('#example1').DataTable({
      "paging": 		true,
      "lengthChange": 	true,
      "searching": 		false,
      "ordering": 		false,
      "info": 			true,
      "autoWidth": 		false,
      "select": 		true
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
	  	             "Março",
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

	$(".deletar-acesso").click(function(){
		var id = $(this).data("id");
		$("#deleta_venda_acesso").attr("value", id);
	});
});
</script>

<style>
	table tr td .fa
	{
		cursor: pointer;
		font-size: 22px;
	}
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Histórico de Compra Produto
    <small>Compra de produtos</small>
  </h1>
</section>

<!-- Modal ALERTA -->
<div class="modal  modal-danger fade in" id="alerta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dispositivo" role="document">
    <div class="modal-content">
     <form action="produto_historico_compra.php" method="post">
     <input type="hidden" name="id_venda_acesso" id="deleta_venda_acesso">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Alerta!</h4>
      </div>
      <div class="modal-body">
        Você realmente deseja deletar essa compra de produto?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-success" name="acao" value="deletar">Prosseguir</button>
      </div>
     </form>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
        <div class="box box-danger">
        <div class="box-header with-border">
           <h3 class="box-title">Busca</h3>
        </div>
        
        <form action="produto_historico_compra.php" method="post">
	        <div class="box-body">
	          <div class="form-group col-lg-4 col-xs-12 col-sm-6">
	              <div class="form-group">
	                <label>Período:</label>
	
	                <div class="input-group">
	                  <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </div>
	                  <input type="text" name="periodo_historico" class="form-control pull-right" id="reservationtime" >
	                </div>
	              </div>
	          </div>
	        </div>
	
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
                <th style="text-align:center;">Data e Hora</th>
                <th style="text-align:center;">Produto</th>
                <th style="text-align:center;">Quantidade</th>
                <th style="text-align:center;">Preço da Compra</th>
              </tr>
            
            </thead>
            
            <tbody>
		            <?php
					if(is_array($historico_compras) && count($historico_compras) > 0)
					{
						foreach ($historico_compras as $historico)
						{
							$data_compra  = date("d/m/Y - H:i:s", strtotime($historico['data_hora']));
							$valor_compra  = "R$ " . number_format($historico['compra_produto'], 2, ',', '.'); 
						?>
		              <tr>
		                <td align="center"><?php echo $data_compra; ?></td>
		                <td align="center"><?php echo !empty($historico['nome_produto']) ? $historico['nome_produto'] : "-"; ?></td>
		                <td align="center"><?php echo $historico['quantidade']; ?></td>
		                <td align="right"><?php echo $valor_compra; ?></td>
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
	            	<th colspan="3" style="text-align:right;">Total</th>
	            	<th style="text-align:right;color: #2cb03c;">
	            	  	<i class="fa fa-usd"></i>
	            		<?php echo $total_venda; ?>
	            	</th>
	            </tr>
            </tbody>
          </table>
        </div>

         <div class="box-footer">
            
        </div>
      </div>
    </div>
  </div>
</section>
<?php
  require_once("footer.php");
?>