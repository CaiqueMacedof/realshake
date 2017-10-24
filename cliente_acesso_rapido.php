<?php
	date_default_timezone_set("America/Sao_Paulo");	

	require_once("header.php");
	require_once('function/cliente.php');
	require_once('function/buscaleatoria.php');
	
	$action	  	  		= isset($_REQUEST['acao']) 				? $_REQUEST['acao'] 	 		: "";
	$erro	  	  		= isset($_REQUEST['erro']) 				? $_REQUEST['erro'] 	 		: "";
	$id_tipo_acesso 	= isset($_REQUEST['id_tipo_acesso']) 	? $_REQUEST['id_tipo_acesso'] 	: null;
	$qtd_acesso 		= isset($_REQUEST['qtd_acesso']) 		? $_REQUEST['qtd_acesso'] 		: 1;
	$tipo_pagamento 	= isset($_REQUEST['tipo_pagamento']) 	? $_REQUEST['tipo_pagamento'] 	: 0;
	
	switch ($action)
	{
		case 'inserir':
				
			$id_cliente = isset($_REQUEST['id_cliente']) ? $_REQUEST['id_cliente'] : null;
			
			if(empty($id_tipo_acesso))
			{
				$msg = "[AVISO] Por favor preencher o tipo de acesso!";
				header("location: cliente_acesso_rapido.php?msg=$msg&erro=1");
				die();
			}
				
			/*if($qtd_acesso <= 0)
			{
				$msg = "[AVISO] Quantidade de acesso deve ser maior que 0.";
				header("location: cliente_acesso_rapido.php?msg=$msg&erro=1");
				die();
			}*/
			
			$tipo_acesso    = lista_tipo_acesso($conn, $id_tipo_acesso);
			//CLIENTE AVULSO
			if($id_cliente == 1)
			{
				insereAcesso($conn, $id_cliente, $id_tipo_acesso, $qtd_acesso, ($qtd_acesso * $tipo_acesso[0]['valor_tipo_acesso'] + $valor_taxa), $tipo_pagamento);
				baixaAcesso($conn, $id_cliente, $id_tipo_acesso, $qtd_acesso);
			}
			else
			{
				$acesso = listaAcesso($conn, $id_cliente, $id_tipo_acesso);
				//somo o baixa acesso atual + o total que sera inserido, conseguindo saber se havera divergência
				$qtd_acessos = $acesso['total'] - ($acesso['consumido'] + $qtd_acesso);
			
				if($qtd_acessos < 0)
				{
					$qtd_acessos = $qtd_acessos * -1;
					$valor_total = retornar_preco_total_acesso($conn, $id_tipo_acesso, $qtd_acessos);
	
					replace_cliente_pendente($conn, $id_cliente, $id_tipo_acesso, $valor_total + $valor_taxa);
				}
				
				baixaAcesso($conn, $id_cliente, $id_tipo_acesso, $qtd_acesso);
			}
		
			header("location: cliente_acesso_rapido.php?msg=Baixa no acesso com sucesso!");
			exit();
		
			break;
		
		default:
			
			break;
	}
	$dias_semana = array(0 => "Domingo",
						 1 => "Segunda-feira",
						 2 => "Terça-feira",
						 3 => "Quarta-feira",
						 4 => "Quinta-feira",
						 5 => "Sexta-feira",
						 6 => "Sabado");
	
	if(date("w", strtotime(date("Y-m-d"))) == 0)
	{
		$dataHoje  = date("Y-m-d h:m:s");
		$inicio    = date("Y-m-d", strtotime($dataHoje . " -6 days"));
	}
	else
	{
		$diaNumSemana = date("w") - 1;
		$dataHoje  = date("Y-m-d h:m:s");
		$inicio    = date("Y-m-d", strtotime($dataHoje . " -$diaNumSemana days"));
	}
	
	$inicioSemana   = date("d/m/Y", strtotime($inicio));
	$atualSemana    = date("d/m/Y", strtotime($dataHoje));
	$dia_inicio 	= date("w", strtotime($inicio));
	$dia_final  	= date("w", strtotime($dataHoje));
	
	//Busca a frequencia de todos os clientes do dia atual;
	$frequenciaHoje = frequenciaHoje($conn, date("Y-m-d"));
	
	//Concatena todos os ids;
	$ids_cliente = concatenar($frequenciaHoje);
	$topFrequencias = buscarTopFrequencia($conn, $ids_cliente, $inicio, $dataHoje);
	//Concatena todos os ids;
	$ids_cliente = concatenar($topFrequencias);
	$frequencias = buscarFrequencias($conn);
	$tipos_acesso = lista_tipo_acesso($conn);
?>
<style>
	
	.small-box
	{
		height: 112px;
	    position: relative;
	    cursor: pointer;
	}
	.box-img
	{
	    height: 100%;
	    position: absolute;
	    top: 0;
	    right: 0;
	}
	
	.box-img img
	{
	    height: 100%;
	    position: absolute;
	    top: 0;
	    right: 0;
	}
	
	
</style>

<script>

$(document).ready(function(){
	$("[data-mask]").inputmask();
	
	$(".fecha-msg").click(function(){
		var parent = $(this).parent();
		parent.css("display", "none");
	});
	
	$(document).on("click", ".small-box", function(){
		//Seta o id do cliente no input type='hidden'.
		$("#cliente").attr("value", $(this).data("cliente"));
	});

	$(".bota_add_acesso").click(function(){
		var qtd_botao = $(this).val();
		var qtd_input = parseInt($(".input-qtd-acesso").val()) || 0;

		var total = parseInt(qtd_botao) + parseInt(qtd_input);
		//SETO OS VALORES NO INPUT DOS ACESSO
		$(".input-qtd-acesso").val(total);
	});
	
	$("#campo_nome").keyup(function(){
		buscaEscreveCliente($(this).val(), $("#campo_cel").val());
	});
	
	$("#campo_cel").keyup(function(){
		buscaEscreveCliente($("#campo_nome").val(), $(this).val());
	});
	
	$(".box-clientes").click(function(){
		console.log($(this).data("cliente"));
		if($(this).data("cliente") == "1")
			$(".tipo_pagamento").show();
		else
			$(".tipo_pagamento").hide();
	});
});

function buscaEscreveCliente(nome, celular)
{
	var retirar = ["(", ")", "_", "-", " "];
	for(var i=0;i<retirar.length;i++) {
		celular = celular.replace(retirar[i],"");
	}
	
	$.ajax
	({
		url: "ajax_busca_cliente.php",
		data: {nome: nome,celular: celular},
		dataType: 'html',
		cache: false,
		success: function(r)
		{
			$(".box-footer").children(".box_cliente div").remove();
			$(r).appendTo(".box_cliente");
			//console.log(r);
		}
	});
}

</script>

<!-- Modal inserir Acesso r�pido -->
	<div class="modal  modal-default fade in" id="acesso_rapido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document" style="width: 20%">
	    <div class="modal-content">
	     <form action="cliente_acesso_rapido.php" method="post">
	     <input type="hidden" name="id_cliente" id="cliente">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Acesso Rápido</h4>
	      </div>
	      	<div class="modal-body">
		      	<div class="box-body">
	      			<div class="row">
	      				<!--div class="form-group col-xs-12">
	                  		<!-- <label>Quantidade:</label> -->
	                  		<!--input class="form-control verifica input-qtd-acesso" type="text" name="qtd_acesso" value="0"
	                  			style="border: none;font-size: 45px;padding: 5px 0;margin-bottom: 30px;" />
	                  			
	                  		<button type="button" class="col-lg-4 col-xs-4 col-sm-4 bota_add_acesso" value="1">+1</button>
	                  		<button type="button" class="col-lg-4 col-xs-4 col-sm-4 bota_add_acesso" value="3">+3</button>
	                  		<button type="button" class="col-lg-4 col-xs-4 col-sm-4 bota_add_acesso" value="5">+5</button>
	                  	</div-->
	                  	
		                <div class="form-group col-xs-12">
		                  <select class="tipo_acesso form-control select2 verifica" name="id_tipo_acesso" style="width: 100%;">
		                  	<option>Tipo de acesso</option>
							<?php 
								if(is_array($tipos_acesso) && count($tipos_acesso) > 0)
								{
									foreach ($tipos_acesso as $tipo_acesso)
									{
										
							?>
										<option value="<?php echo $tipo_acesso['id_tipo_acesso'];?>">
											<?php echo $tipo_acesso['nome']; ?>
										</option>
							<?php 		
									}
								}
							?>
		              	</select>
		                </div>
		                
		                <div class="form-group col-xs-12 tipo_pagamento" style="display: none">
		                  	<select class="tipo_pagamento form-control" name="tipo_pagamento" style="width: 100%;">
			                  	<option>Formas de Pagamento</option>
			                  	<option value="1">Cartão de Débito/Dinheiro</option>
			                  	<!--option value="2">Cartão de Crédito</option>
			                  	<option value="3">Vale Refeição/Alimentação</option-->
		              		</select>
	                	</div>
		                
		                <!--div class="form-group col-xs-12">
		                	<label>
			                  Acesso
			                </label>
		                	
		                	<div>
			                  	<input type="radio" name="tipo_venda_baixa" value="0" class="minimal verifica" style="width:20px;height:20px;">
				                Compra
			                  	
			                  	<input type="radio" name="tipo_venda_baixa" value="1" class="minimal verifica" style="width:20px;height:20px;margin-left: 20px;">
		                  		Consumo
		                	</div>
	              		</div-->
	      				<!-- <div class="form-group col-xs-12" style="margin-bottom: 20px;">
		                  	<input type="radio" name="tipo_venda_baixa" value="0" class="minimal verifica" style="width:20px;height:20px;">
			                Compra
		                  	
		                  	<input type="radio" name="tipo_venda_baixa" value="1" class="minimal verifica"  style="width:20px;height:20px;margin-left: 20px;" checked>
	                  		Baixa
	              		</div>
	      			
		                <div class="form-group col-xs-12" style="display: flex;justify-content: space-around;">
				            <input type="checkbox" name="tipo" value="3" class="minimal verifica" style="width:16px;height:16px;">
				            NutriSoup
			                  	
			                <input type="checkbox" name="tipo" value="1" class="minimal verifica" style="width:16px;height:16px;margin-left: 20px;">
				            Shake
				            
				            <input type="checkbox" name="tipo" value="2" class="minimal verifica" style="width:16px;height:16px;margin-left: 20px;">
				            Sopa
	             		</div>
	             		
	             		<div class="form-group col-xs-12">
	                  		<input class="form-control" type="number" name="qtd_acesso" class="form-control" placeholder="Quantidade de acesso">
	                  	</div>-->
	             	</div>
	             </div>
             </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-success" name="acao" value="inserir">Confirmar</button>
	      </div>
	     </form>
	    </div>
	  </div>
	</div>

<section class="content-header">
  <h1>
    Acesso Rápido
    <small class="text-small-content-header">Maiores frêquencias </small>
  </h1>
</section>

	<?php 	
		if(!empty($msg))
		{
			$nomeClasse = ($erro == 0) ? "success" : "danger";
				
			echo "<div class='callout callout-$nomeClasse' style='margin:8px 15px 0 15px;position: relative;'>";
 			echo 	"<h4 style='font-weight: normal;'>$msg</h4>";
 			echo 	"<span class='fecha-msg'>X</span>";
			echo "</div>";
		}	
	?>
<section class="content">
	<div class="row">
	    <div class="col-xs-12">
	        <div class="box box-danger" style="padding-top:15px;">
	        	<div class="box-header with-border">
           			<h3 class="box-title">Buscar Cliente</h3>
        		</div>
	        	<div class="box-body" style="padding-bottom: 0!important;">
			          <!-- Date dd/mm/yyyy -->
			          <div class="form-group col-md-4 col-sm-6 col-xs-12">
			            <label>Nome:</label>
			            <div class="input-group" style="width: 100%;">
			              <input type="text" name="nome" class="form-control" id="campo_nome">
			            </div>
			            <!-- /.input group -->
			          </div>
			          <!-- /.form group -->
			
			          <!-- phone mask -->
			          <div class="form-group col-md-4 col-sm-6 col-xs-12">
			            <label>Celular:</label>
			
			            <div class="input-group" style="width: 100%;">
			              <input type="text" name="celular" class="form-control"  data-inputmask='"mask": "(99) 99999-9999"' id="campo_cel" data-mask>
			            </div>
			          </div><!-- /.form-group-->
    			</div>
    			<div class="box-footer box_cliente">
    				<div class='col-lg-4 col-xs-12 col-sm-6'>
						<div data-toggle='modal' data-target='#acesso_rapido' class='small-box bg-aqua box-clientes' data-cliente='1' style='background:rgba(0,0,0,0.20)!important'>
							<div class='inner' style='max-width: 70%;'>
								<h4 style="color:aliceblue;font-weight:bold;text-shadow: -1px 0 #8c8c8c, 0 1px #8c8c8c, 1px 0 #8c8c8c, 0 -1px #8c8c8c;">AVULSO</h4>
							</div>
							<div class='box-img'>
								<img src='img/avatares/neutro.png'>
							</div>
						</div>
					</div>
		        <?php
				if(is_array($frequencias) && count($frequencias) > 0)
				{
					$i = 0;
					foreach ($frequencias as $topFrequencia)
					{
						//Busca os avatares masculinos, senão busca os feminino
						$imagem = buscarAvatar($topFrequencia['sexo']);
						//Busca as cores
						$cor = buscarCor($i);
						
						if($topFrequencia['total_frequencia'] == 1 || $topFrequencia['DELETADO'] == 1)
							$texto = "1 Frequência";
						else
							$texto = $topFrequencia['total_frequencia'] . " Frequências";
						
						echo "	<div class='col-lg-4 col-xs-12 col-sm-6'>
									<div data-toggle='modal' data-target='#acesso_rapido' class='small-box bg-aqua box-clientes' data-cliente='$topFrequencia[ID_CLIENTE]' style='background: $cor!important'>
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
				</div>
	        </div>
	    </div>
	</div>
</section>
<?php 
require_once("footer.php");
?>