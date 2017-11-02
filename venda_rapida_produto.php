<?php
	date_default_timezone_set("America/Sao_Paulo");	

	require_once("header.php");
	require_once('function/functions_produto.php');
	
	$action	  	  	= isset($_REQUEST['acao']) 				? $_REQUEST['acao'] 	 		: "";
	$erro	  	  	= isset($_REQUEST['erro']) 				? $_REQUEST['erro'] 	 		: "";
	$id_produto 	= isset($_REQUEST['id_produto']) 		? $_REQUEST['id_produto'] 		: NULL;
	$preco_venda_total 	= isset($_REQUEST['preco_venda']) 		? $_REQUEST['preco_venda'] 		: NULL;
	$quantidade 	= isset($_REQUEST['quantidade']) 		? $_REQUEST['quantidade'] 		: 0;
	$tipo_pagamento 	= isset($_REQUEST['tipo_pagamento']) 	? $_REQUEST['tipo_pagamento'] 		: 0;
	
	switch ($action)
	{
		case 'inserir':
			$produto_estoque = lista_estoque_produto($conn, $id_produto);
			if($quantidade <= $produto_estoque[0]["quantidade"])
			{
				$sem_cifrao = str_replace("R$ ", "", $preco_venda_total);
				$sem_ponto	= str_replace(".", "", $sem_cifrao);
				
				$retorno 	= insere_venda_produto($conn, $id_produto, $quantidade, $tipo_pagamento, $sem_ponto);
				if(is_numeric($retorno))
				{
					$quantidade_real = $produto_estoque[0]["quantidade"] - $quantidade;
					replace_estoque_produto($conn, $id_produto, $quantidade_real);
					
					$msg = "[AVISO] Venda de produto inserida com sucesso.";
					header("location: venda_rapida_produto.php?msg=$msg");
					die();
				}
				else
				{
					$msg = "[AVISO] Erro ao inserir a venda do produto";
					header("location: venda_rapida_produto.php?msg=$msg&erro=1");
					die();
				}
			}
			else
			{
				$msg = "[AVISO] A quantidade da venda é maior que a do estoque, por favor insira o valor maximo do produto que esta no estoque";
				header("location: venda_rapida_produto.php?msg=$msg&erro=1");
				die();
			}
			break;
		
		default: 
			$action = "";
	}
	
	$produtos = lista_produtos($conn);
?>
<style>
	
	.small-box
	{
	    height: 200px;
	    position: relative;
	    cursor: pointer;
	}
	.box-img-produto
	{
	    width: 28%;
	}
	
	.box-img-produto img
	{
	    height: 100%;
	    width: 100%;
	}
	
	.btn_add_quantidade
	{
		padding: 5px;
	}
	.verifica[readonly]
	{
		background: transparent!important;
	}

	
</style>

<script>

$(document).ready(function(){
	$("[data-mask]").inputmask();
	
	$(".fecha-msg").click(function(){
		var parent = $(this).parent();
		parent.css("display", "none");
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
	
	$(".box-produtos").click(function(){
		$("#id_produto").attr("value", $(this).data("produto"));
		$("#valor_produto").attr("value", $(this).data("valor"));
		$("#myModalLabel").text($(this).data("nome"));
		
		$(".input-preco-total-compra").attr("value", "R$ "+ number_format($(this).data("valor"), 2, ",", "."))
	});
	
	$(".btn_add_quantidade").click(function(){
 		if($(".total").val() == "")
 			var qtd_input = 0;
 		else
 		{
 			var qtd_input = parseInt($(".total").val());
 		}

		var total = parseInt($(this).val()) + parseInt(qtd_input);
 		var total_venda = total * $("#valor_produto").val();
 		
 		$(".total").val(total);
 		$(".input-preco-total-compra").attr("value", "R$ " + number_format(total_venda, 2, ",", "."))
 		$(".total").css("width", ($(".total").val().length * 23) + "px");
	});
	
	$(".total").keyup(function(e)
	{
		var valor 		= $(this).val();
		var total_venda = valor * $("#valor_produto").val();
		$(".input-preco-total-compra").attr("value", "R$ " + number_format(total_venda, 2, ",", "."));
		$(".total").css("width", (($(".total").val().length * 23) + 20) + "px");
	});
	
	$(document).keyup(function(e)
	{
		if (e.keyCode === 27)// ESC
		{
		  	//reseta os campos do pop-up quando fechado
			$(".total").val("0");
			$(".input-preco-total-compra").attr("value","R$ 0,00");
			$('.stipo_pagamento option[value=0]').attr('selected',true);
		}
	});
	
	$(".btn_close").click(function(){
		//reseta os campos do pop-up quando fechado
		$(".total").val("0");
		$(".input-preco-total-compra").attr("value","R$ 0,00");
		$('.stipo_pagamento option[value=0]').attr('selected',true);
	});
});

function number_format( numero, decimal, decimal_separador, milhar_separador )
{ 
	    numero = (numero + '').replace(/[^0-9+\-Ee.]/g, '');
	    var n = !isFinite(+numero) ? 0 : +numero,
	        prec = !isFinite(+decimal) ? 0 : Math.abs(decimal),
	        sep = (typeof milhar_separador === 'undefined') ? ',' : milhar_separador,
	        dec = (typeof decimal_separador === 'undefined') ? '.' : decimal_separador,
	        s = '',
	        toFixedFix = function (n, prec) {
	            var k = Math.pow(10, prec);
	            return '' + Math.round(n * k) / k;
	        };
	    // Fix para IE: parseFloat(0.55).toFixed(0) = 0;
	    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	    if (s[0].length > 3) {
	        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	    }
	    if ((s[1] || '').length < prec) {
	        s[1] = s[1] || '';
	        s[1] += new Array(prec - s[1].length + 1).join('0');
	    }
	    return s.join(dec);
}

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

<!-- Modal ACESSOS -->
	<div class="modal fade in" id="venda_rapido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog modal-dispositivo" role="document" style="width: 380px;">
	    <div class="modal-content">
	     <form action="venda_rapida_produto.php" method="post">
     	<input type="hidden" name="valor_produto" id="valor_produto" />
	     <input type="hidden" name="id_produto" id="id_produto" />
	      <div class="modal-header">
	        <button type="button" class="close btn_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel" style="font-weight: 400;font-size: 25px;"></h4>
	      </div>
	      
	      <div class="modal-body">
	      	<div class="box-body">
      			<div class="row">
      				<div class="form-group col-xs-12" style="display: flex;">
      					<div style="display: flex;">
	              			<input class="form-control verifica input-qtd-acesso total" type="text" name="quantidade" value="0"
	                  			style="height: 50px;border: none;font-size: 45px;padding: 0;width: 30px;" />
							  <span class="erro-total" style="display:none;font-size: 35px;color: red;">*</span>
						  </div>
	                  		<input name="preco_venda" class="form-control input-preco-total-compra" type="text" value="R$ 0,00"
	                  			style="text-align: right;height: 50px;border: none;font-size: 45px;padding: 0;"/>
                  	</div>
                  	
                  	<div class="form-group col-xs-12">	
                  		<button type="button" class="col-lg-4 col-xs-4 col-sm-4 btn_add_quantidade" value="1">+1</button>
                  		<button type="button" class="col-lg-4 col-xs-4 col-sm-4 btn_add_quantidade" value="3">+3</button>
                  		<button type="button" class="col-lg-4 col-xs-4 col-sm-4 btn_add_quantidade" value="5">+5</button>
                  	</div>
                  	
                 	<div class="form-group col-xs-12">
	                  	<select class="tipo_pagamento form-control" name="tipo_pagamento">
		                  	<option>Formas de Pagamento</option>
		                  	<option value="1">Débito</option>
		                  	<option value="2">Dinheiro</option>
		                  	<!--option value="2">Cartão de Crédito</option>
		                  	<option value="3">Vale Refeição/Alimentação</option-->
	              		</select>
                	</div>
	      		</div>
	      	</div>
	      </div>
	      
	      <div class="modal-footer">
	        <button type="button" class="btn btn-danger btn_close" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-success" name="acao" value="inserir">Finalizar</button>
	      </div>
	     
	     </form>
	    </div>
	  </div>
	</div>
	
<section class="content-header">
  <h1>
    Venda Rápido
    <small class="text-small-content-header">Lista dos produtos no sistema</small>
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
	        	<!--div class="box-header with-border">
           			<h3 class="box-title">Buscar Produto</h3>
        		</div>
	        	<div class="box-body" style="padding-bottom: 0!important;">
			          <!-- Date dd/mm/yyyy -->
			          <!--div class="form-group col-md-4 col-sm-6 col-xs-12">
			            <label>Nome:</label>
			            <div class="input-group" style="width: 100%;">
			              <input type="text" name="nome" class="form-control" id="campo_nome">
			            </div>
			          </div>
    			</div-->
    			<div class="box-footer box_cliente">
		        <?php
				if(is_array($produtos) && count($produtos) > 0)
				{
					$i 			= 0;
					$limite 	= 60;
					$desabilita = "";
					$descricao 	= "";
					foreach ($produtos as $produto)
					{
						if($produto["quantidade"] < 1)
						{
							$desabilita = "opacity: 0.4;cursor:no-drop;";
							$id_popup 	= "";
						}
						else
						{
							$desabilita = "opacity: 1;";
							$id_popup 	= "#venda_rapido";
						}
							
						if(strlen($produto["descricao"]) >= $limite)
						{
							$descricao = substr($produto["descricao"], 0, $limite) . " ...";
						}
						else
						{
							$descricao = $produto["descricao"];
						}
				?>
						<div class='col-lg-6 col-xs-12 col-sm-12'>
							<div class="<?php echo $desabilita; ?>">
								<div data-toggle='modal' data-target='<?php echo $id_popup; ?>' class='small-box bg-aqua box-produtos' data-nome="<?php echo $produto["nome"]; ?>" data-produto='<?php echo $produto["id_produto"]; ?>'
									data-valor='<?php echo $produto["preco_venda"]; ?>' style='<?php echo $desabilita; ?>display: flex;justify-content: space-between;background:rgba(230, 230, 230, 0.42)!important;border: 1px solid #eaeaea;'>
									<div class='inner inner-mobile' style='width: 72%;color:#acacac;text-align: left;'>
										<h4 style="color:#0ea246;font-weight:bold;font-size: 24px;"><?php echo $produto["nome"]; ?></h4>
										<p style="font-size: 14px;" title="<?php echo $produto["descricao"]; ?>"><?php echo $descricao; ?></p>
										
										<div style="position: absolute;bottom: 10px;">
											<p style="margin: 0;font-weight: bold;color: #8c8c8c;"><span style="font-size: 30px;color: #8c8c8c;"><?php echo $produto["quantidade"]; ?></span> Unidades no Estoque</p>
											<p style="margin: 0;font-weight: bold;color: #8c8c8c;">Preço: <span style="font-size: 24px;color: #0ea246;">R$ <?php echo number_format($produto["preco_venda"], 2 , ",", "."); ?></span></p>
										</div>
									</div>
									<div class='box-img-produto'>
										<img src='<?php echo $produto["imagem"]; ?>'>
									</div>
								</div>
							</div>
						</div>
				<?php		
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