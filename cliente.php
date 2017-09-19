<?php	
	require_once('header.php');
	require_once('function/cliente.php');
	require_once('function/criptografia.php');
	require_once('function/mysqli_fetch_all_mod.php');
	
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

		//Atualização do cliente
		$nome		  		= isset($_REQUEST['nome']) 				? $_REQUEST['nome'] 	 		: "";
		$email 		  		= isset($_REQUEST['email']) 			? $_REQUEST['email'] 	 		: "";
		$celular 	  		= isset($_REQUEST['celular']) 			? $_REQUEST['celular'] 	 		: "";
		$data_nasc 	  		= isset($_REQUEST['data_nasc']) 		? $_REQUEST['data_nasc'] 		: "";
		$origem 	  		= isset($_REQUEST['origem']) 			? $_REQUEST['origem'] 	 		: "";
		$sexo	  			= isset($_REQUEST['sexo']) 				? $_REQUEST['sexo'] 	 		: "";
	}
	
	
	//Lista do banco os tipo de contato. EXEMPLO: indicação, facebook ...
	$tipo_contatos = lista_tipo_contato($conn);
	
	switch ($action){
		case "salvar":
			
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
				header("location: cliente.php?acao=editar&id_cliente=$id_cliente&msg=$msg&erro=1");
				die();
			}
				
			if(strlen($celular) > 0)
			{
				if(strlen($celular) != 11)
				{
					$msg = "[AVISO] Preencha corretamente o campo celular!";
					header("location: cliente.php?acao=editar&id_cliente=$id_cliente&msg=$msg&erro=1");
					die();
				}
			}
			
			
			if($nome != "" && $email !== "" && $celular != "" && $origem != "" && $data_nasc != "")
			{
				$data_formatada = date("Y-m-d", strtotime($data_nasc));
				
				$resultado = atualizaCliente($conn, $id_cliente, $nome, $email, $celular, $data_formatada, $origem, $sexo);

				$requests = array(	"action" 		=> "editar",
									"id_cliente" 	=> $id_cliente,
									"nome" 			=> $nome,
									"email" 		=> $email,
									"celular" 		=> $celular,
									"data_nasc" 	=> $data_nasc,
									"origem"   		=> $origem );
				if($resultado)
				{
					$msg = "[AVISO] Cliente atualizado com sucesso!";
					header("location: cliente.php?msg=$msg&id_cliente=$id_cliente&acao=editar");
					die();
				}
				else
				{
					$msg = "[AVISO] Erro ao atualizar o cliente!";
					header("location: cliente.php?msg=$msg&id_cliente=$id_cliente&acao=editar&erro=1");
					die();
				}
			}
			else
			{
				$msg = "[AVISO] Por favor preencher todos os campos do cadastro!";
				header("location: cliente.php?msg=$msg&id_cliente=$id_cliente&acao=editar&erro=1");
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
				$sexo 			= $clientes[0]['SEXO'];
				$data_nasc 		= $clientes[0]['DATA_ANIVERSARIO'];
				$data_nasc 	= date("m/d/Y", strtotime($clientes[0]['DATA_ANIVERSARIO']));
			}
			
			
		break;
	}
	
	$tipos_acesso = lista_tipo_acesso($conn);
	
?>
<style>
	.fecha-msg
	{
		font-size: 16px;
		font-weight: bold;
		position: absolute;
		right: 12px;
		top:6px;
		cursor: pointer;
	}
	
</style>

<script>
$(document).ready(function(){
  //Money Euro
  $("[data-mask]").inputmask();
  $("#datepicker").inputmask("mm/dd/yyyy");

	//Date picker
  	$('#datepicker').datepicker({
    	autoclose: true
  	});

 	 $(".fecha-msg").click(function(){
		var parent = $(this).parent();

		parent.css("display", "none");
	});
	
});

</script>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Atualizar Cliente
    <small></small>
  </h1>
</section>


<!-- Main content -->
<section class="content">

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
 
  <div class="row">
    <div class="col-xs-12">
        <div class="box box-danger">
        <div class="box-header with-border">
           <h3 class="box-title">Cliente</h3>
        </div>
        
        <form action="cliente.php" method="post">
        	<input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>">
	        <div class="box-body">
	          	<div class="form-group col-lg-6 col-xs-12 col-sm-12">
                  <label>Nome:</label>
                  <input class="form-control" type="text" name="nome" value="<?php echo $nome; ?>" class="form-control">
                </div>

                <div class="form-group col-lg-6 ccol-xs-12 col-sm-12">
                  <label>E-mail:</label>
                  <input class="form-control" type="text" name="email" value="<?php echo $email; ?>" class="form-control">
                </div>
                
                <div class="form-group col-lg-6 col-xs-12 col-sm-12">
                  <label>Celular:</label>
                  <input class="form-control" type="text" name="celular" value="<?php echo $celular; ?>" class="form-control" data-inputmask="'mask': '(99) 99999-9999'" data-mask>
                </div>
                
                <div class="form-group col-lg-6 col-xs-12 col-sm-12">
                  <label>Sexo:</label>
	                  <select class="form-control select2" name="sexo" style="width: 100%;">
		                  <option selected="selected" disabled>Selecione o sexo</option>
		                  <?php 
		                  	if($sexo == 0)
		                  	{
		                  		echo "<option value='0' selected>Masculino</option>";
		                  		echo "<option value='1'>Feminino</option>";
		                  	}
		                  	else
		                  	{
		                  		echo "<option value='0'>Masculino</option>";
		                  		echo "<option value='1' selected>Feminino</option>";
		                  	}
		                  ?>
              		  </select>
                </div>
                
                <div class="form-group col-lg-6 col-xs-12 col-sm-12">
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
              
                <div class="form-group col-lg-6 col-xs-12 col-sm-12">
                  <label>Data de Nascimento:</label>
                  <input class="form-control" type="text" name="data_nasc" value="<?php echo $data_nasc;?>"
                  		class="form-control" id="datepicker" data-mask>
                </div>
              
	          	
	        </div><!-- /.box-body -->

	        <div class="modal-footer">
	            <button type="submit" name="acao" class="btn btn-success pull-right btn-Maxwidth" value="salvar">
	              <i class="fa fa-check"></i>
	              Salvar
	            </button>
	            
	            <a href="clientes.php">
		            <button type="button" class="btn btn-default pull-right btn-Maxwidth" value="salvar"
		            	style="margin-right: 12px;">
		              Voltar
		            </button>
	            </a>
	        </div>
        	
        </form>
      </div>
    </div>
  </div>
</section>

<?php
  require_once("footer.php");
?>