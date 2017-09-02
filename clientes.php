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
		$id_tipo_acesso 	= isset($_REQUEST['id_tipo_acesso']) 	? $_REQUEST['id_tipo_acesso'] 	: "";
		$qtd_acesso 		= isset($_REQUEST['qtd_acesso']) 		? $_REQUEST['qtd_acesso'] 		: "";
		$tipo_venda_baixa 	= isset($_REQUEST['tipo_venda_baixa']) 	? $_REQUEST['tipo_venda_baixa'] : null;
	}
	
	
	//Lista do banco os tipo de contato. EXEMPLO: indicação, facebook ...
	$tipo_contatos = lista_tipo_contato($conn);
	
	switch ($action){
		case "cadastrar":
			
			// VALIDAÇÕES
			//TODO: arrumar essa gambiarra;
			
			$celular = str_replace("(", "", $celular);
			$celular = str_replace(")", "", $celular);
			$celular = str_replace(" ", "", $celular);
			$celular = str_replace("-", "", $celular);
			$celular = str_replace("_", "", $celular);
			
			
			if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$msg = "[AVISO] E-mail inválido, preencha um email correto!";
				header("location: clientes.php?msg=$msg&erro=1");
				die();
			}
			
			if(strlen($celular) > 0)
			{
				if(strlen($celular) != 11)
				{
					$msg = "[AVISO] Preencha corretamente o campo celular!";
					header("location: clientes.php?msg=$msg&erro=1");
					die();
				}
			}
			
			if($nome != "" && $email !== "" && $celular != "" && $origem != "" && $data_nasc != "" && $sexo != "")
			{
				$data_formatada = date("Y-m-d", strtotime($data_nasc));

				$resultado = insertCliente($conn, $nome, $email, $celular, $data_formatada, $origem, $sexo);

				if($resultado)
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
	
			if($tipo_venda_baixa == "" || empty($id_tipo_acesso)|| empty($qtd_acesso))
			{
				$msg = "[AVISO] Por favor preencher todos os campos do cadastro!";
				header("location: clientes.php?msg=$msg&erro=1");
				die();
			}
			
			if($qtd_acesso <= 0)
			{
				$msg = "[AVISO] Quantidade de acesso deve ser maior que 0.";
				header("location: clientes.php?msg=$msg&erro=1");
				die();
			}
			
			//INSERE ACESSO
			if($tipo_venda_baixa == 0)
			{
				$acesso = listaAcesso($conn, $id_cliente, $id_tipo_acesso);
				
				$qtd_acessos = ($acesso['total'] + $qtd_acesso) - $acesso['consumido'];

				if($qtd_acessos < 0)
				{
					$qtd_acessos = $qtd_acessos * -1;
					$valor_total = retornar_preço_total_acesso($conn, $id_tipo_acesso, $qtd_acessos);
					
					replace_cliente_pendente($conn, $id_cliente, $id_tipo_acesso, $valor_total);
				}
				else
				{
					deleta_cliente_pendente($conn, $id_cliente);
				}

				$tipo_acesso    = lista_tipo_acesso($conn, $id_tipo_acesso);
				$retorno_insere = insereAcesso($conn, $id_cliente, $id_tipo_acesso, $qtd_acesso, $qtd_acesso * $tipo_acesso[0]['valor_tipo_acesso']);
			}
			//BAIXA ACESSO
			else if($tipo_venda_baixa == 1)
			{
				$acesso = listaAcesso($conn, $id_cliente, $id_tipo_acesso);
				
				//somo o baixa acesso atual + o total que sera inserido, conseguindo saber se havera divergência
				$qtd_acessos = $acesso['total'] - ($acesso['consumido'] + $qtd_acesso);
				
				if($qtd_acessos < 0)
				{
					$qtd_acessos = $qtd_acessos * -1;
					$valor_total = retornar_preço_total_acesso($conn, $id_tipo_acesso, $qtd_acessos);

					replace_cliente_pendente($conn, $id_cliente, $id_tipo_acesso, $valor_total);
				}
				
				$retorno_baixa = baixaAcesso($conn, $id_cliente, $id_tipo_acesso, $qtd_acesso);
			}
				
				
			if(isset($retorno_insere) && $retorno_insere != false)
			{
				if(!empty($paginacao))
					header("location: clientes.php?msg=Acesso inserido com sucesso!&" . $paginacao);
					else
						header("location: clientes.php?msg=Acesso inserido com sucesso!");
		
						exit();
			}
			else if(isset($retorno_baixa) && $retorno_baixa != false)
			{
				if(!empty($paginacao))
					header("location: clientes.php?msg=[AVISO] Baixa no acesso com sucesso!&" . $paginacao);
					else
						header("location: clientes.php?msg=Baixa no acesso com sucesso!");
		
						exit();
			}
			else
			{
				if(!empty($paginacao))
					header("location: clientes.php?msg=[AVISO] Erro ao inserir/baixar o acesso!&erro=1&" . $pagi);
					else
						header("location: clientes.php?msg=[AVISO]Erro ao inserir/baixar o acesso!&erro=1");
							
						exit();
			}
				
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
  $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
  //Money Euro
  $("[data-mask]").inputmask();

  //Date picker
  $('#datepicker').datepicker({
    autoclose: true
  });
	
	$(".deletar-cliente").click(function(){
		var id = $(this).data("id");

		$("#deleta_cliente").attr("value", id);		
	});
	
	$(document).keyup(function(e)
	{
		  if (e.keyCode === 13)// enter
			  $(".pop-up-tabela #1, .pop-up-tabela #2, .pop-up-tabela #3").remove();//Remove as linhas criadas no ajax;   
		  if (e.keyCode === 27)// esc
			  $(".pop-up-tabela #1, .pop-up-tabela #2, .pop-up-tabela #3").remove();   
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
	
						//Não exibo se nao houver consumido e nem comprado o acesso;
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
		
		//Remove as linhas criadas no ajax;
		$(".pop-up-tabela #1, .pop-up-tabela #2, .pop-up-tabela #3").remove();
	});

	$(".fecha-msg").click(function(){
		var parent = $(this).parent();

		parent.css("display", "none");
	});

	$(".btn_add_acesso").click(function(){
		var qtd_botao = $(this).val();
		var qtd_input = parseInt($(".input-qtd-acesso").val()) || 0;

		var total = parseInt(qtd_botao) + parseInt(qtd_input);
		//SETO OS VALORES NO INPUT DOS ACESSO
		$(".input-qtd-acesso").val(total);
	});

	$(".btn-consumo").click(function(){
		if($(this).hasClass("btn-selected"))
		{
			$(this).removeClass("btn-selected");
			$(".input_hidden").attr("value", '');
		}
		else
		{
			if($(".btn-consumo").next().hasClass("btn-selected"))
			{
				$(".btn-consumo").next().removeClass("btn-selected")
				$(".input_hidden").attr("value", '');
			}
			else if($(".btn-consumo").before().hasClass("btn-selected"))
			{
				$(".btn-consumo").before().removeClass("btn-selected");
				$(".input_hidden").attr("value", '');
			}
				
			$(this).addClass("btn-selected");
			$(".input_hidden").attr("value", $(this).val());
		}
	});
});
</script>

<style>
	.btn_add_acesso, .btn-consumo
	{
		padding: 5px;
		font-weight: bold;
	}
</style>

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
	  <div class="modal-dialog" role="document">
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
	  <div class="modal-dialog" role="document" style="width: 380px;">
	    <div class="modal-content">
	     <form action="clientes.php" method="post">
	     <input type="hidden" name="id_cliente" class="id_cliente" />
	      <div class="modal-header">
	        <button type="button" class="close btn_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Cadastro de Acesso</h4>
	      </div>
	      
	      <div class="modal-body">
	      	<div class="box-body">
      			<div class="row">
      				<div class="form-group col-xs-12">
                  		<!-- <label>Quantidade:</label> -->
                  		<input class="form-control verifica input-qtd-acesso" type="text" name="qtd_acesso" value="0"
                  			style="border: none;font-size: 45px;padding: 5px 0;margin-bottom: 30px;" />
                  			
                  		<button type="button" class="col-lg-4 col-xs-4 col-sm-4 btn_add_acesso" value="1">+1</button>
                  		<button type="button" class="col-lg-4 col-xs-4 col-sm-4 btn_add_acesso" value="3">+3</button>
                  		<button type="button" class="col-lg-4 col-xs-4 col-sm-4 btn_add_acesso" value="5">+5</button>
                  	</div>
                  	
	                <div class="form-group col-xs-12">
	                  <label>Tipo de acesso:</label>
	                  <select class="tipo_acesso form-control select2 verifica" name="id_tipo_acesso" style="width: 100%;">
	                  	<option></option>
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
	                
	                <div class="form-group col-xs-12">
	                	<label>
		                  Acesso
		                </label>
	                	
	                	<div>
	                		<input type="hidden" name="tipo_venda_baixa" class="input_hidden">
	                		<button type="button" value="0" class="col-lg-6 col-xs-6 col-sm-6 btn-consumo text-green"><i class="fa fa-caret-up text-green"></i>&nbsp Consumo</button>
                  			<button type="button" value="1" class="col-lg-6 col-xs-6 col-sm-6 btn-consumo text-red"><i class="fa fa-caret-down text-red"></i>&nbsp Baixa</button>
	                	</div>
              		</div>
              		
	                <div class="form-group col-xs-12" style="margin: 15px 0 0 0;">
	                  	<b>Cliente: <span class="nome_cliente">Caique Fialho</span></b>
	                </div>
	                
	                <div class="box-body col-xs-12">
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
	        <button type="submit" class="btn btn-success" name="acao" value="inserir">Prosseguir</button>
	      </div>
	     
	     </form>
	    </div>
	  </div>
	</div>
	
	<!-- Modal PADRÃO -->
	<div class="modal fade in" id="cadastro-cliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	     <form action="clientes.php" method="post">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Cadasto de Cliente</h4>
	      </div>
	      <div class="modal-body">
	      <input type="hidden" name="id_cliente" id="id_cliente"/>
          	<div class="box-body">
              <div class="row">
	      		<div class="form-group col-xs-6">
                  <label>Nome:</label>
                  <input class="form-control" type="text" name="nome" class="form-control">
                </div>

                <div class="form-group col-xs-6">
                  <label>E-mail:</label>
                  <input class="form-control" type="text" name="email" class="form-control">
                </div>
                
                <div class="form-group col-xs-6">
                  <label>Celular:</label>
                  <input class="form-control" type="text" name="celular" class="form-control" data-inputmask="'mask': '(99) 99999-9999'" data-mask>
                </div>
                
                <div class="form-group col-xs-6">
                  <label>Sexo:</label>
	                  <select class="form-control select2" name="sexo" style="width: 100%;">
		                  <option selected="selected" disabled>Selecione o sexo</option>
		                  <option value="0">Masculino</option>
		                  <option value="1">Feminino</option>
              		  </select>
                </div>
                
                <div class="form-group col-xs-6">
                  <label>Origem:</label>
                  <select class="form-control select2" name="origem" style="width: 100%;">
                  <option selected="selected" disabled>Selecione uma origem</option>
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
                
                <div class="form-group col-xs-6">
                  <label>Data de Nascimento:</label>
                  <input class="form-control" type="text" name="data_nasc" class="form-control" id="datepicker" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask>
                </div>
	          </div>
	        </div>
	      </div>
	      
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
					
					$requests["id_cliente"] = $cliente['ID_CLIENTE'];
					$encrypt = encrypt($requests);
				?>
              <tr>
                <td><?php echo $cliente['NOME']; ?></td>
                <td align="center" class="esconde_coluna"><?php echo $cliente['CELULAR']; ?></td>
                <td align="center">
                	<i class="fa fa-calendar style_icon" aria-hidden="true"></i>
                </td>
                <td align="center">
                	<i class="fa fa-list-alt style_icon" aria-hidden="true" data-toggle="modal" data-target="#cadastro-acesso"
                		data-nome="<?php echo $cliente['NOME']; ?>" data-cliente="<?php echo $cliente['ID_CLIENTE']; ?>"></i>
                </td>
                
                <td align="center">
                	<a href="cliente.php?acao=editar&id_cliente=<?php echo $cliente['ID_CLIENTE'];?>">
                	<i class="fa fa-pencil style_icon" aria-hidden="true"></i>
                </td>
                
                <td align="center">
                	<i class="fa fa-times deletar-cliente style_icon" style="color: red;" aria-hidden="true"  data-toggle="modal" data-target="#alerta" 
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
