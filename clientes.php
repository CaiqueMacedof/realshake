<?php
	require_once('header.php');
	require_once('function/cliente.php');
	require_once('function/criptografia.php');
	
	if(isset($_REQUEST['v']))
	{
		$array = descryt($_REQUEST['v']);
		
		$action	  	  		= isset($_REQUEST['acao']) 				? $_REQUEST['acao'] 	 		: "";
		$id_cliente   		= isset($_REQUEST['id_cliente']) 		? $_REQUEST['id_cliente'] 		: "";
		$nome		  		= isset($_REQUEST['nome']) 				? $_REQUEST['nome'] 	 		: "";
		$email 		  		= isset($_REQUEST['email']) 			? $_REQUEST['email'] 	 		: "";
		$celular 	  		= isset($_REQUEST['celular']) 			? $_REQUEST['celular'] 	 		: "";
		$data_nasc 	  		= isset($_REQUEST['data_nasc']) 		? $_REQUEST['data_nasc'] 		: "";
		$origem 	  		= isset($_REQUEST['origem']) 			? $_REQUEST['origem'] 	 		: "";
		$tipo_venda_baixa 	= isset($_REQUEST['tipo_venda_baixa']) 	? $_REQUEST['tipo_venda_baixa'] : 0;
		$tipo_venda_baixa 	= isset($_REQUEST['tipo_pagamento']) 	? $_REQUEST['tipo_pagamento'] 	: 1;
		
	}
	else
	{
		$msg	  	  		= isset($_REQUEST['msg']) 				? $_REQUEST['msg'] 	 			: "";
		$erro	  	  		= isset($_REQUEST['erro']) 				? $_REQUEST['erro'] 	 		: 0;
		$action	  	  		= isset($_REQUEST['acao']) 				? $_REQUEST['acao'] 	 		: "";
		$id_cliente   		= isset($_REQUEST['id_cliente']) 		? $_REQUEST['id_cliente'] 		: "";
	
		//Cadastro de cliente
		$nome		  		= isset($_REQUEST['nome']) 				? $_REQUEST['nome'] 	 		: "";
		$email 		  		= isset($_REQUEST['email']) 			? $_REQUEST['email'] 	 		: "";
		$celular 	  		= isset($_REQUEST['celular']) 			? $_REQUEST['celular'] 	 		: "";
		$data_nasc 	  		= isset($_REQUEST['data_nasc']) 		? $_REQUEST['data_nasc'] 		: "";
		$origem 	  		= isset($_REQUEST['origem']) 			? $_REQUEST['origem'] 	 		: "";
		$sexo	  			= isset($_REQUEST['sexo']) 				? $_REQUEST['sexo'] 	 		: "";
		
		//Cadastro de acesso
		$id_tipo_acesso 	= isset($_REQUEST['id_tipo_acesso']) 		? $_REQUEST['id_tipo_acesso'] 	: "";
		$qtd_acesso 		= isset($_REQUEST['qtd_acesso']) 			? $_REQUEST['qtd_acesso'] 		: "";
		$qtd_acesso_uso 	= isset($_REQUEST['qtd_acesso_uso']) 		? $_REQUEST['qtd_acesso_uso'] 	: null;
		$qtd_acesso_total 	= isset($_REQUEST['qtd_acesso_total']) 		? $_REQUEST['qtd_acesso_total'] : null;
		$tipo_venda_baixa 	= isset($_REQUEST['tipo_venda_baixa']) 		? $_REQUEST['tipo_venda_baixa'] : null;
		$tipo_pagamento 	= isset($_REQUEST['tipo_pagamento']) 		? $_REQUEST['tipo_pagamento'] 	: 0;
		$date 	  			= isset($_REQUEST['date']) 					? $_REQUEST['date'] 	 		: "";
		$time	  			= isset($_REQUEST['time']) 					? $_REQUEST['time'] 	 		: "";
	}
	
	
	//Lista do banco os tipo de contato. EXEMPLO: indica��o, facebook ...
	$tipo_contatos = lista_tipo_contato($conn);

	switch ($action){
		case "cadastrar":
			if($nome != "" && $origem != "" && $sexo != "")
			{
				if(!empty($data_nasc))
					$data_formatada = date("Y-m-d", strtotime(str_replace("/", "-", $data_nasc)));
				else
					$data_formatada = NULL;
				
				$resultado = insertCliente($conn, $nome, $email, $celular, $data_formatada, $origem, $sexo);
				if($resultado == true)
				{
					$msg = "[AVISO] Cliente cadastrado com sucesso!";
					header("location: clientes.php?msg=$msg");
					die();
				}
				else
				{
					$msg = "[AVISO] Erro ao cadastrar o cliente!";
					header("location: clientes.php?msg=$msg&erro=1");
					die();
				}
			}
			else
			{
				$msg = "[AVISO] Por favor preencher todos os campos do cadastro!";
				header("location: clientes.php?msg=$msg&erro=1");
				die();
			}
			
			break;
		
		case "editar":
			
			$clientes = listaCliente($conn, $id_cliente);
			
			if(is_array($clientes) && count($clientes) > 0)
			{
				$nome 			= $clientes[0]['NOME'];
				$email 			= $clientes[0]['EMAIL'];
				$celular		= $clientes[0]['CELULAR'];
				$contato_tipo 	= $clientes[0]['ORIGEM'];
				$data_nasc 		= $clientes[0]['DATA_ANIVERSARIO'];
			}
			
			break;
		
		case "Atualizar":
				
			if($nome != "" && $email !== "" && $celular != "" && $origem != "" && $data_nasc != "")
			{
				
				$data_formatada = date("Y-m-d", strtotime($data_nasc));

				$resultado = atualizaCliente($conn, $id_cliente, $nome, $email, $celular, $data_formatada, $origem);
				
				$requests = array("action" 		=> "editar",
								  "id_cliente" 	=> $id_cliente,
								  "nome" 		=> $nome,
								  "email" 		=> $email,
								  "celular" 	=> $celular,
								  "data_nasc" 	=> $data_nasc,
								  "origem"   	=> $origem );
				if($resultado)
				{
					$msg = "[AVISO] Cliente atualizado com sucesso!";
					header("location: cliente.php?v=" . encrypt($requests) . "&msg=$msg");
					die();
				}
				else
				{
					$msg = "[AVISO] Erro ao atualizar o cliente!";
					header("location: cliente.php?v=" . encrypt($requests) . "msg=$msg&erro=1");
					die();
				}
			}
			else
			{
				$msg = "[AVISO] Por favor preencher todos os campos do cadastro!";
				header("location: cliente.php?v=" . encrypt($requests) . "msg=$msg&erro=1");
				die();
			}
				
		break;

		case 'filtrar':
				
			//Formatando as datas pra busca no banco de dados;
			$clientes = listaCliente($conn, null, $nome, $celular);
			$total_registro = count(listaCliente($conn, null, $nome, $celular));
		
		break;
		
		case 'inserir':
			$id_cliente = isset($_REQUEST['id_cliente']) ? $_REQUEST['id_cliente'] : null;
			
			if(empty($tipo_pagamento) && $qtd_acesso_total > 0)
			{
				$msg = "[AVISO] Selecione uma forma de pagamento.";
				header("location: clientes.php?msg=$msg&erro=1");
				die();
			}
			else if($qtd_acesso_uso < 1 && $qtd_acesso_total < 1)
			{
				$msg = "[AVISO] Selecione a quantidade de acessos a ser inseridos.";
				header("location: clientes.php?msg=$msg&erro=1");
				die();
			}
			
			/***********************
			 **** VENDA DO ACESSO ****
			 ***********************/
			//$valor_taxa  	= retornar_valor_taxa($tipo_pagamento, $qtd_acesso_total[$i]);
			if(!empty($date) && !empty($time))
				$data_formatada = date("Y-m-d", strtotime(str_replace("/", "-", $date))) ." ". date("H:i:s", strtotime($time));
			else
				$data_formatada = NULL;
				
			if($qtd_acesso_total > 0)
			{
				$acesso 	 	= listaAcesso($conn, $id_cliente, $id_tipo_acesso);
				//somo o baixa acesso atual + o total que sera inserido, conseguindo saber se havera divergência
				$qtd_acessos = ($acesso['total'] + $qtd_acesso_total) - $acesso['consumido'];
				
				if($qtd_acessos < 0)
				{
					$qtd_acessos = $qtd_acessos * -1;
					$valor_total = retornar_preco_total_acesso($conn, $id_tipo_acesso, $qtd_acessos);
					
					replace_cliente_pendente($conn, $id_cliente, $id_tipo_acesso, $valor_total + $valor_taxa);
				}
				else
				{ 
					deleta_cliente_pendente($conn, $id_cliente, $id_tipo_acesso);
				}
	
				$tipo_acesso    = lista_tipo_acesso($conn, $id_tipo_acesso);
				$retorno_insere = insereAcesso($conn, $id_cliente, $id_tipo_acesso, $qtd_acesso_total, ($qtd_acesso_total * $tipo_acesso[0]['valor_tipo_acesso'] + $valor_taxa), $tipo_pagamento, $data_formatada);
			}
			
			/*************************
			 **** BAIXA DO ACESSO ****
			 *************************/
			if($qtd_acesso_uso > 0)
			{
				$acesso = listaAcesso($conn, $id_cliente, $id_tipo_acesso);
				//somo o baixa acesso atual + o total que sera inserido, conseguindo saber se havera divergência
				$qtd_acessos = $acesso['total'] - ($acesso['consumido'] + $qtd_acesso_uso);
				
				if($qtd_acessos < 0)
				{
					$qtd_acessos = $qtd_acessos * -1;
					$valor_total = retornar_preco_total_acesso($conn, $id_tipo_acesso, $qtd_acessos);
	
					replace_cliente_pendente($conn, $id_cliente, $id_tipo_acesso, $valor_total + $valor_taxa);
				}
				
				$retorno_baixa = baixaAcesso($conn, $id_cliente, $id_tipo_acesso, $qtd_acesso_uso, $data_formatada);
			}
				
			header("location: clientes.php?msg=Acesso inserido com sucesso!");
			exit();
				
			break;
		
		case 'deletar':
			
			$id_cliente = isset($_REQUEST['id_cliente']) ? $_REQUEST['id_cliente'] : null;
			
			if(!empty($id_cliente))
			{
				$retorno = deletaCliente($conn, $id_cliente);
				if($retorno != false)
				{
					$msg = "[AVISO] Cliente deletado com sucesso!";
					header("location: clientes.php?msg=$msg");
					die();
				}
				else
				{
					$msg = "[AVISO] Falha ao deletar o cliente!";
					header("location: clientes.php?msg=$msg&erro=1");
					die();
				}
			}
			
		default:
			$clientes = listaCliente($conn);
	}
	
	$tipos_acesso = lista_tipo_acesso($conn);
?>
<script>


$( function() {

	$('#tabela_clientes').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "select": true
    });
    
    var availableTags = [
    <?php 
    
    $clientes_autocomplete = listaCliente($conn);
    $total  = count($clientes_autocomplete);
    $i 		= 1;

	foreach ($clientes_autocomplete as $cliente)
	{
		if($i < $total)
			echo '"' . $cliente['NOME'] . '",';
		else
			echo '"' . $cliente['NOME'] . '"';
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
  $("#datemask2").inputmask("dd/mm/yyyy", {"placeholder": "mm/dd/yyyy"});
  //Money Euro
  $("[data-mask]").inputmask();

  //Date picker
  $('#datepicker, #datepicker_acessos').datepicker({
      todayBtn: "linked",
      language: "it",
      autoclose: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
  });

	$(".deletar-cliente").click(function(){
		var id = $(this).data("id");

		$("#deleta_cliente").attr("value", id);		
	});
	
	$(document).keyup(function(e)
	{
		if (e.keyCode === 13)// ENTER
		{
			//reseta os campos do pop-up quando fechado
			$(".uso").val("0");
			$(".total").val("0");
			$(".input-preco-acesso").val("R$ 0,00");
			$("#datepicker_acessos").val("");
			$(".time").val("");
			$('.tipo_acesso option[value=0]').attr('selected',true);
			$('.select_tipo_pagamento option[value=0]').attr('selected',true);
			
			$(".pop-up-tabela #1, .pop-up-tabela #2, .pop-up-tabela #3").remove();//Remove as linhas criadas no ajax;   
		}
		  
		if (e.keyCode === 27)// ESC
		{
		  	//reseta os campos do pop-up quando fechado
			$(".uso").val("0");
			$(".total").val("0");
			$(".input-preco-acesso").val("R$ 0,00");
			$("#datepicker_acessos").val("");
			$(".time").val("");
			$('.tipo_acesso option[value=0]').attr('selected',true);
			$('.select_tipo_pagamento option[value=0]').attr('selected',true);
			
			$(".pop-up-tabela #1, .pop-up-tabela #2, .pop-up-tabela #3").remove();
		}
	});
	
	$(document).on("click", ".fa-list-alt", function(){
		var id   	 = $(this).data("cliente");
		var	nome 	 = $(this).data("nome");
		var produtos = ["Shake", "Sopa", "NutriSoup"];

		$(".id_cliente").attr("value", id);
		$(".nome_cliente").text(nome);
		
		$.ajax
		({
			url: "ajax_lista_acesso.php",
			data: {id_cliente: id},
			dataType: 'json',
			success: function(retorno)
			{
				if(retorno[1] !== 0 || retorno[2] !== 0 || retorno[3] !== 0)
				{
					var count = Object.keys(retorno).length;
					for(i = 1; i <= count; i++)
					{
						if(retorno[i] === 0)
							continue;
						
						var valores = retorno[i].split("-");
						
						consumo  	= parseInt(valores[0]);
						total    	= parseInt(valores[1]);
	
						//N�O exibo se nao houver consumido e nem comprado o acesso;
						if(consumo == 0 && total == 0)
							continue;
						
						if(consumo > total)
							corFundo = "rgba(255, 0, 0, 0.50)";
						else
							corFundo = "#eaeaea";
	
						$("<tr id=" + (i) + " style='background: " + corFundo + ";'>" +
								"<th width='5%'>"
									+ produtos[i-1] + 
						  		"</th>" +
	
						  		"<td>"
									+ consumo + "/" + total + 
					  			"</td>" +
						  "</tr>").appendTo(".pop-up-tabela");
					}
				}
				else
				{
					$("<tr id='1' style='background: #eaeaea;'>" +
							"<td colspan='2'>" + 
								"Cliente nao tem acessos." + 
					  		"</td>" +
					  "</tr>").appendTo(".pop-up-tabela");
				}
				
			}	
		});

		$(".tipo_acesso").focus();
		
	});

	$(".btn_close").click(function(){
		
		//reseta os campos do pop-up quando fechado
		$(".uso").val("0");
		$(".total").val("0");
		$(".input-preco-acesso").val("R$ 0,00");
		$("#datepicker_acessos").val("");
		$(".time").val("");
		$('.tipo_acesso option[value=0]').attr('selected',true);
		$('.select_tipo_pagamento option[value=0]').attr('selected',true);

		//Remove as linhas criadas no ajax;
		$(".pop-up-tabela #1, .pop-up-tabela #2, .pop-up-tabela #3").remove();
	});

	$(".fecha-msg").click(function(){
		var parent = $(this).parent();

		parent.css("display", "none");
	});

	$(".btn_add_acesso").click(function(){
		//USO
		if($(".btn-selected").val() == 0)
		{
			if($(".uso").val() == "")
	 			var qtd_input = 0;
	 		else
	 			var qtd_input = parseInt($(".uso").val());

			var total = parseInt($(this).val()) + parseInt(qtd_input);
			
	 		$(".uso").val(total);
	 		$(".uso").css("width", ($(".uso").val().length * 23) + "px");
		}
	 	else if($(".btn-selected").val() == 1)//TOTAL
	 	{
	 		if($(".total").val() == "")
	 		{
	 			var qtd_input = 0;
	 			$(".tipo_pagamento").hide();
	 		}
	 		else
	 		{
	 			var qtd_input = parseInt($(".total").val());
	 			$(".tipo_pagamento").show("fast");
	 		}

			var total = parseInt($(this).val()) + parseInt(qtd_input);
	 		
	 		$(".total").val(total);
	 		$(".total").css("width", ($(".total").val().length * 23) + "px");
	 	}
	});
	
	$(".btn-consumo").click(function()
	{
		// Exibe o campo preco total da compra e  tipo de pagamento apenas quando for compra de acesso
		if($(this).val() == 0)
			$(".input-preco-acesso, .tipo_pagamento").show("fast");
		else
			$(".input-preco-acesso, .tipo_pagamento").hide("fast");
	});

	$(".btn-consumo, .btn-acesso").click(function()
	{
		if($(this).hasClass("btn-selected"))
		{
			$(this).removeClass("btn-selected");
			$(".input_hidden").attr("value", '');
		}
		else
		{
			if($(".btn-consumo, .btn-acesso").next().hasClass("btn-selected"))
			{
				$(".btn-consumo, .btn-acesso").next().removeClass("btn-selected")
				$(".input_hidden").attr("value", '');
			}
			else if($(".btn-consumo, .btn-acesso").before().hasClass("btn-selected"))
			{
				$(".btn-consumo, .btn-acesso").before().removeClass("btn-selected");
				$(".input_hidden").attr("value", '');
			}
				
			$(this).addClass("btn-selected");
			$(".input_hidden").attr("value", $(this).val());
		}
	});
	
	$(".total").keydown(function()
	{
		$(".total").css("width", (($(".total").val().length * 23) + 20) + "px");
	});
	
	$(".uso").keydown(function()
	{
		$(".uso").css("width", (($(".uso").val().length * 23) + 20) + "px");
	});
	
	$(".tipo_acesso, .input-qtd-acesso").change(function(){
		var tipo 		 = $(".tipo_acesso").val();
		var preco_acesso = [15,16,17];
		var qtd_acesso 	 = $(".input-qtd-acesso").val();
		
		$(".input-preco-acesso").val("R$ " + number_format(preco_acesso[tipo-1]*qtd_acesso, 2, ",", "."));
	});

	 $(".btn_add_acesso").click(function(){
		var tipo 		 = $(".tipo_acesso").val();
		var preco_acesso = [15,16,17];
		var qtd_acesso 	 = $(".total").val();

		$(".input-preco-acesso").val("R$ " + number_format(preco_acesso[tipo-1]*qtd_acesso, 2, ",", "."));
	 })
	 
	 $(".input-qtd-acesso").change(function(){
	 	if($(this).val() > 0)
	 		$(".tipo_pagamento").show("fast");
 		else
 			$(".tipo_pagamento").hide();
	});
	
	 $(".btn_ver_acesso").click(function(){
	 	if($(".div_acessos").css("display") == "none")
	 		$(".div_acessos").slideDown("fast");
	 	else
	 		$(".div_acessos").slideUp("fast");
	 });
	 
	 $(document).on("click", ".icon_remover", function()
	 {
		$(this).closest("tr").remove();
		
		if($(".tr_carrinho_acesso tr").length == 1)
		{
			$(".tr_preco_total").remove();
			$(".tr_carrinho_acesso").append("<tr class='tr_vazio'><td align='center' colspan='5'><b style='color:red;'>Carrinho vazio.<b></td></tr>");
		}
		else
		{
			var total = 0;
			$(".valor").each(function(){
				total = parseFloat(total) + parseFloat($(this).text());
			});
		
			$(".tr_preco_total").remove();
			$(".tr_carrinho_acesso").append("<tr class='tr_preco_total'><td colspan='3' align='right'><b>Total:</b></td><td class='valor_total'>R$ "+number_format(total, 2, ",", ".")+"</td><td></td></tr>");
		}
	 });
	 
	 $(".btn_add_carrinho").click(function(){
	 	var tipo_acesso = $(".tipo_acesso").val();
	 	var uso = $(".uso").val();
	 	var total = $(".total").val();
	 	var preco_total = $(".input-preco-acesso").val().replace("R$ ","");
	 	var produtos = ["Shake", "Sopa", "NutriSoup"];
		
		if(tipo_acesso == 0 || uso == 0 && total == 0)
		{
			alert("Por favor, preencha os campos corretamente.");
			return false;
		}
		
		//RESWETO OS CAMPOS
		$(".uso").val(0);
		$(".total").val(0);
		$(".input-preco-acesso").val("R$ " + number_format(0, 2, ",", "."));
		$(".tipo_acesso option:first").attr('selected','selected');
		
		if($(".tr_carrinho_acesso .tr_vazio").length == 1)
			$(".tr_vazio").remove();

	 	$(".tr_carrinho_acesso").append("	<tr>"+
	 											"<td>"+
	 												"<input type='hidden' name='id_tipo_acesso[]' value='"+ tipo_acesso +"'/>"
	 												+ produtos[tipo_acesso-1] +
 												"</td>"+
 												
 												"<td>"+
	 												"<input type='hidden' name='qtd_acesso_uso[]' value='"+ uso +"'/>"
	 												+ uso +
	 											"</td>"+
	 											
	 											"<td>"+
	 												"<input type='hidden' name='qtd_acesso_total[]' value='"+ total +"'/>"
	 												+ total +
 												"</td>"+
 												
 												"<td class='valor'>"+preco_total+"</td>"+
 												
 												"<td align='center'><i class='fa fa-remove icon_remover' style='color: red'></i></td>"+
											"</tr>");
		
		var total = 0;
		$(".valor").each(function(){
			total = parseFloat(total) + parseFloat($(this).text());
		});
		
		$(".tr_preco_total").remove();
		$(".tr_carrinho_acesso").append("<tr class='tr_preco_total'><td colspan='3' align='right'><b>Total:</b></td><td class='valor_total'>R$ "+number_format(total, 2, ",", ".")+"</td><td></td></tr>");
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

function valida(f)
{
	$(".form-control").attr("style", "border: 1px solid #ccc!important");
	var ret;
	if(f.nome.value == "")
		ret = exibeMensagem(f.nome, "Por favor, preencha o campo nome!")
	
	if(f.sexo.value < 0)
		ret = exibeMensagem(f.sexo, "Por favor, escolha um sexo!")
	
	if(f.origem.value < 0)
		ret = exibeMensagem(f.origem, "Por favor, escolha uma origem!")
	
	return ret;
}

function exibeMensagem(elemento, mensagem, mostra_msg = false)
{
	if(mostra_msg){alert(mensagem);}
	elemento.style.border = "2px solid #ff7676";
	return false;
}

function resetaCor(elemento)
{
	elemento.style.border = "1px solid #ccc";
	return true;
}

</script>

<style>
	.btn_add_acesso, .btn-consumo, .btn-acesso
	{
		padding: 5px;
	}
	.verifica[readonly]
	{
		background: transparent!important;
	}
	
	a,
	a:hover
	{
		color: #333333;
		cursor: pointer;
	}
	
	i
	{
		cursor: pointer;
	}
	
	table tr td .fa
	{
		font-size: 22px;
	}
	
</style>
<script src="plugins/timepicker/bootstrap-timepicker.js"></script>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Clientes
    <small>listagem de clientes</small>
  </h1>
</section>


<!-- Main content -->
<section class="content">
	<?php 	
		if(!empty($msg))
		{
			$nomeClasse = ($erro == 0) ? "success" : "danger";
				
			echo "<div class='callout callout-$nomeClasse' style='position: relative;'>";
 			echo 	"<h4 style='font-weight: normal;'>$msg</h4>";
 			echo 	"<span class='fecha-msg'>X</span>";
			echo "</div>";
		}	
	?>
  	<!-- Modal ALERTA -->
	<div class="modal  modal-danger fade in" id="alerta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog modal-dispositivo" role="document">
	    <div class="modal-content">
	     <form action="clientes.php" method="post">
	     <input type="hidden" name="id_cliente" id="deleta_cliente">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Alerta!</h4>
	      </div>
	      <div class="modal-body">
	        Você realmente deseja deletar o cliente?
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-success" name="acao" value="deletar">Prosseguir</button>
	      </div>
	     </form>
	    </div>
	  </div>
	</div>
	
	<!-- Modal ACESSOS -->
	<div class="modal fade in" id="cadastro-acesso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog modal-dispositivo" role="document" style="width: 380px;">
	    <div class="modal-content">
	     <form action="clientes.php" method="post" class="form_reset">
	     <input type="hidden" name="id_cliente" class="id_cliente" />
	      <div class="modal-header">
	        <button type="button" class="close btn_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"><span class="nome_cliente" style="font-weight: 400;font-size: 25px;"></span></h4>
	      </div>
	      
	      <div class="modal-body">
	      	<div class="box-body">
      			<div class="row">
      				<div class="form-group col-xs-12" style="display: flex;">
      					<div style="display: flex;">
	                  		<input class="form-control verifica uso" type="text" name="qtd_acesso_uso" value="0"
	                  			style="height: 50px;border: none;font-size: 45px;padding: 0;width: 30px" />
	                  			<span style="font-size: 35px;width: 18px;">/</span>
	              			<input class="form-control verifica input-qtd-acesso total" type="text" name="qtd_acesso_total" value="0"
	                  			style="height: 50px;border: none;font-size: 45px;padding: 0;width: 30px;" />
						  </div>
                  		
                  		<input class="form-control verifica input-preco-acesso" type="text" value="R$ 0,00"
                  			style="text-align: right;height: 50px;border: none;font-size: 45px;padding: 0;" readonly="readonly"/>
                  	</div>
                  	
                  	<div class="form-group col-xs-12" style="margin-bottom: 10px;">
	                	<div>
	                		<input type="hidden" name="tipo_venda_baixa" class="input_hidden">
	                		<button type="button" value="0" class="col-lg-6 col-xs-6 col-sm-6 btn-acesso">USO</button>
                  			<button type="button" value="1" class="col-lg-6 col-xs-6 col-sm-6 btn-acesso btn-selected">TOTAL</button>
	                	</div>
              		</div>
              		
                  	<div class="form-group col-xs-12">	
                  		<button type="button" class="col-lg-4 col-xs-4 col-sm-4 btn_add_acesso" value="1">+1</button>
                  		<button type="button" class="col-lg-4 col-xs-4 col-sm-4 btn_add_acesso" value="3">+3</button>
                  		<button type="button" class="col-lg-4 col-xs-4 col-sm-4 btn_add_acesso" value="5">+5</button>
                  	</div>
                  	
	                <div class="form-group col-xs-12">
	                  <select class="tipo_acesso form-control select2 verifica" name="id_tipo_acesso" style="width: 100%;">
	                  	<option value="0">Tipos de acesso</option>
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
	                
	                <div class="form-group col-xs-12 tipo_pagamento" style="display:none;">
	                  	<select class="select_tipo_pagamento form-control" name="tipo_pagamento" style="width: 100%;">
		                  	<option value="0">Formas de Pagamento</option>
		                  	<option value="1">Cartão de Débito/Dinheiro</option>
		                  	<!--option value="2">Cartão de Crédito</option>
		                  	<option value="3">Vale Refeição/Alimentação</option-->
	              		</select>
	                </div>
	                
	                <div class="form-group col-md-12 col-sm-12 col-xs-12">
	                  <input class="form-control" type="text" name="date" class="form-control data" id="datepicker_acessos" data-inputmask="'alias': 'dd/mm/yyyy'" placeholder="Data" data-mask>
                	</div>
                	
	                <div class="form-group col-md-12 col-sm-12 col-xs-12">
	                    <input type="time" name="time" class="form-control time" placeholder="Hora">
                	</div>
					<!--div class="form-group col-xs-4" style="margin: 15px 0 0 0;">
						<button type="button" class="col-lg-12 col-xs-12 col-sm-12 btn_add_carrinho">+Add</button>
					</div>	                
	                <div class="box-body col-xs-12" style="max-height: 185px;overflow: auto;">
	                	<table class="table table-bordered">
			            <thead>
			              <tr style="background-color: #eee;font-size: 15px;">
			              	<th colspan="5"><i class="fa fa-shopping-cart" style="margin-right:3px;"></i>Carrinho de Compra</th>
			              </tr>
			              
			              <tr style="background-color: #eee;font-size: 12px;">
			              	<th>Produto</th>
			              	<th style="text-align: center;">Uso</th>
			              	<th style="text-align: center;">Total</th>
			              	<th style="text-align: center;">Valor</th>
			              	<th style="text-align: center;">Remover</th>
			              </tr>
			             </thead>
			             
			             <tbody class="tr_carrinho_acesso" style="font-size: 12px;">
			              	<tr class="tr_vazio">
								<td align="center" colspan="5"><b style="color:red;">Carrinho vazio.<b></b></b></td>
			             	</tr>
			             </tbody>
			            </table>
	                </div>
	                
	                <div class="box-body col-xs-12" style="padding-bottom: 0;">
	                	<button type="button" class="col-lg-12 col-xs-12 col-sm-12 btn_ver_acesso">VER ACESSOS<i class="fa fa-chevron-down" style="margin-left: 2px;"></i></button>
	                </div-->
	                
	                <div class="box-body col-xs-12 div_acessos" >
	                	<table class="table table-bordered table-hover pop-up-tabela" style="background-color: #eee;">
			            <thead>
			              <tr>
			              	<th colspan="2">[Total de Acessos]</th>
			              </tr>
			             </thead>
			            </table>
	                </div>
	      		</div>
	      	</div>
	      </div>
	      
	      <div class="modal-footer">
	        <button type="button" class="btn btn-danger btn_close" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-success" name="acao" value="inserir">Confirmar</button>
	      </div>
	     
	     </form>
	    </div>
	  </div>
	</div>
	
	<!-- Modal PADRÃO -->
	<div class="modal fade in" id="cadastro-cliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	     <form action="clientes.php" method="post" onsubmit="return valida(this);">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Cadastro de Cliente</h4>
	      </div>
	      <div class="modal-body">
	      <input type="hidden" name="id_cliente" id="id_cliente"/>
          	<div class="box-body">
              <div class="row">
	      		<div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>Nome:*</label>
                  <input class="form-control" type="text" name="nome" class="form-control">
                </div>

                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>E-mail:</label>
                  <input class="form-control" type="text" name="email" class="form-control">
                </div>
                
                <div class="form-group ccol-md-6 col-sm-6 col-xs-12">
                  <label>Celular:</label>
                  <input class="form-control" type="text" name="celular" class="form-control" data-inputmask="'mask': '(99) 99999-9999'" data-mask>
                </div>
                
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>Sexo:*</label>
	                  <select class="form-control select2" name="sexo" style="width: 100%;">
		                  <option value="-1" selected disabled>Selecione o sexo</option>
		                  <option value="0">Masculino</option>
		                  <option value="1">Feminino</option>
              		  </select>
                </div>
                
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>Origem:*</label>
                  <select class="form-control select2" name="origem" style="width: 100%;">
                  <option value="-1" selected="selected" disabled>Selecione uma origem</option>
					<?php
					if(is_array($tipo_contatos))
					{
						foreach($tipo_contatos as $tipo_contato)
						{
							if(isset($contato_tipo))
							{
								if($contato_tipo == $tipo_contato['id_tipo_contato'])
									echo "<option value='$tipo_contato[id_tipo_contato]' selected>$tipo_contato[nome]</option>";
								else
									echo "<option value='$tipo_contato[id_tipo_contato]'>$tipo_contato[nome]</option>";
								
							}
							else
								echo "<option value='$tipo_contato[id_tipo_contato]'>$tipo_contato[nome]</option>";
						}
					}
			  		?>
              	</select>
                </div>
                
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>Data de Nascimento:</label>
                  <input class="form-control" type="text" name="data_nasc" class="form-control" id="datepicker" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                </div>
	          </div>
	        </div>
	      </div>
	      
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-primary" value="cadastrar" name="acao">Cadastrar</button>
	      </div>
	      </form>
	    </div>
	  </div>
	</div>
  
  <div class="row">
    <div class="col-xs-12">
        <div class="box box-danger">
        <div class="box-header with-border">
           <h3 class="box-title">Busca</h3>
        </div>
        
        <form action="clientes.php" method="post">
        	<input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>" />
	        <div class="box-body">
	          <!-- Date dd/mm/yyyy -->
	          <div class="form-group col-md-4 col-sm-6 col-xs-12">
	            <label>Nome:</label>
	            <div class="input-group" style="width: 100%;">
	              <input type="text" name="nome" class="form-control" id="tags">
	            </div>
	            <!-- /.input group -->
	          </div>
	          <!-- /.form group -->
	
	          <!-- phone mask -->
	          <div class="form-group col-md-4 col-sm-6 col-xs-12">
	            <label>Celular:</label>
	
	            <div class="input-group" style="width: 100%;">
	              <input type="text" name="celular" class="form-control"  data-inputmask='"mask": "(99) 99999-9999"' data-mask>
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
          <h3 class="box-title">Lista de Clientes</h3>

           <button type="button" class="btn btn-success pull-right btn-Maxwidth" data-toggle="modal" data-target="#cadastro-cliente">
              <i class="fa fa-user-plus" aria-hidden="true"></i>
              Cliente
            </button>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table id="tabela_clientes" class="table table-bordered table-hover">
            <thead>
            
              <tr>
                <th>Nome</th>
                <th style="text-align:center;" class="esconde_coluna">Celular</th>
                <th style="text-align:center;">Frequências</th>
                <th style="text-align:center;">Acessos</th>
                <th style="text-align:center;">Editar</th>
                <th style="text-align:center;">Excluir</th>
              </tr>
            
            </thead>
            
            <tbody>
            <?php
			if(is_array($clientes) && count($clientes) > 0)
			{
				foreach ($clientes as $cliente)
				{
					$data_nascimento = date("d/m/Y", strtotime($cliente['DATA_ANIVERSARIO']));
				?>
              <tr>
                <td><?php echo $cliente['NOME']; ?></td>
                <td align="center" class="esconde_coluna"><?php echo $cliente['CELULAR']; ?></td>
                <td align="center">
                	<a href="cliente_frequencia.php?id=<?php echo $cliente['ID_CLIENTE']?>">
	                	<i class="fa fa-calendar" aria-hidden="true"></i>
            		</a>
                </td>
                <td align="center">
            		<i class="fa fa-list-alt" aria-hidden="true" data-toggle="modal" data-target="#cadastro-acesso"
                		data-nome="<?php echo $cliente['NOME']; ?>" data-cliente="<?php echo $cliente['ID_CLIENTE']; ?>"></i>
	            </td>
                
                <td align="center">
                	<a href="cliente.php?acao=editar&id_cliente=<?php echo $cliente['ID_CLIENTE'];?>">
                		<i class="fa fa-pencil" aria-hidden="true"></i>
                	</a>
                </td>
                
                <td align="center">
                	<i class="fa fa-times deletar-cliente" style="color: red;" aria-hidden="true"  data-toggle="modal" data-target="#alerta" 
                		data-id="<?php echo $cliente['ID_CLIENTE']; ?>"></i>
                </td>
              </tr>
            <?php 
				}
			}
			else
			{
			
            ?>
            <tr>
            	<td colspan="6" align="center" style="color: #FF0000;font-weight: bold;">Nenhum Cliente encontrado.</td>
            </tr>
            
            <?php 
			}
            ?>
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
