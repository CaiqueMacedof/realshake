<?php	
	require_once('header.php');
	require_once("function/functions_produto.php");
	require_once('function/mysqli_fetch_all_mod.php');
	
	$msg	  	  		= isset($_REQUEST['msg']) 				? $_REQUEST['msg'] 	 			: "";
	$erro	  	  		= isset($_REQUEST['erro']) 				? $_REQUEST['erro'] 	 		: 0;
	$action	  	  		= isset($_REQUEST['acao']) 				? $_REQUEST['acao'] 	 		: "";
	
	$id_produto  		= isset($_REQUEST['id_produto']) 		? $_REQUEST['id_produto'] 		: "";
	$imagem	  			= isset($_FILES['imagem']) 				? $_FILES['imagem'] 	 		: "";
	$nome		  		= isset($_REQUEST['nome']) 				? $_REQUEST['nome'] 	 		: "";
	$preco_compra 		= isset($_REQUEST['preco_compra']) 		? $_REQUEST['preco_compra'] 	: NULL;
	$preco_venda 	  	= isset($_REQUEST['preco_venda']) 		? $_REQUEST['preco_venda'] 	 	: NULL;
	$quantidade 	  	= isset($_REQUEST['quantidade']) 		? $_REQUEST['quantidade'] 		: 0;
	$descricao 	  		= isset($_REQUEST['descricao']) 		? $_REQUEST['descricao'] 	 	: "";
	
	switch ($action){
		case "salvar":
			$regex_preco = "/^(([0-9]{1,10})|([0-9]{1,10}[,][0-9]{1,2}))$/";
			
			$preco_venda_verifica = converteNumero($preco_venda);
			$preco_compra_verifica =  converteNumero($preco_compra);
			
			if($preco_venda_verifica < $preco_compra_verifica)
			{
				$msg = "[AVISO] O preço de venda não pode ser menor que o preço da compra!";
				header("location: produtos.php?msg=$msg&acao=editar&id_produto=$id_produto&erro=1");
				die();
			}
			else if(!preg_match($regex_preco, $preco_compra)
				|| !preg_match($regex_preco, $preco_venda))
			{
				$msg = "[AVISO] O campo preço de venda ou compra não esta no formato correto. Exemplo de formato correto: REAL(máximo 10 casas),CENTAVOS(máximo 2 casas)!";
				header("location: produto.php?msg=$msg&acao=editar&id_produto=$id_produto&erro=1");
				die();
			}
			
			if(!empty($nome) && !empty($preco_compra) && !empty($preco_venda) && $quantidade > 0 && !empty($descricao))
			{
				$retorno = altera_produto($conn, $id_produto, $nome, $descricao, str_replace(",", ".", $preco_compra), str_replace(",", ".", $preco_venda));
				if($retorno == true)
				{
					replace_estoque_produto($conn, $id_produto, $quantidade);
					$msg = "[AVISO] Produto cadastrado com sucesso!";
					header("location: produto.php?msg=$msg&acao=editar&id_produto=$id_produto");
					die();
				}
				else
				{
					$msg = "[AVISO] Erro ao cadastrar o produto!";
					header("location: produto.php?msg=$msg&acao=editar&id_produto=$id_produto&erro=1");
					die();
				}
			}
			else
			{
				$msg = "[AVISO] Por favor preencher todos os campos do cadastro!";
				header("location: produtos.php?msg=$msg&acao=editar&id_produto=$id_produto&erro=1");
				die();
			}
		break;
			
		case "editar":
			$produtos = lista_produtos($conn, $id_produto);

			if(is_array($produtos) && count($produtos) > 0)
			{
				$imagem 		= $produtos[0]['imagem'];
				$nome 			= $produtos[0]['nome'];
				$preco_compra	= $produtos[0]['preco_compra'];
				$preco_venda	= $produtos[0]['preco_venda'];
				$quantidade 	= $produtos[0]['quantidade'];
				$descricao 		= $produtos[0]['descricao'];
			}
		break;
	}

	function converteNumero($numero)
	{
		$numero = str_replace(".", "", $numero);
		$numero = str_replace(",", ".", $numero);
		
		return (float)$numero;
	}
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
 	 $(".fecha-msg").click(function(){
		var parent = $(this).parent();

		parent.css("display", "none");
	});
});

</script>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Atualizar Produto
    <small>Alteração nos dados do produto</small>
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
 
  <div class="row">
    <div class="col-xs-12">
        <div class="box box-danger">
        <div class="box-header with-border">
           <h3 class="box-title">Produto</h3>
        </div>
        
        <form action="produto.php" method="post">
        	<input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">
	        <div class="box-body">
              <div class="row">
              	<div class="form-group col-md-6 col-sm-12 col-xs-12">
                  <label>Imagem:</label>
                  <input class="form-control" type="file" name="imagem_produto" value="<?php echo $id_produto; ?>">
                </div>
                
	      		<div class="form-group col-md-6 col-sm-12 col-xs-12">
                  <label>Nome:*</label>
                  <input class="form-control" type="text" name="nome" value="<?php echo $nome; ?>">
                </div>

                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                  <label>Preço de Compra:*</label>
                  <input class="form-control" type="text" name="preco_compra" value="<?php echo str_replace(".", "", number_format($preco_compra, 2, ",", ".")); ?>" placeholder="R$ 0,00">
                </div>
                
                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                  <label>Preço de Venda:*</label>
                  <input class="form-control" type="text" name="preco_venda" value="<?php echo str_replace(".", "", number_format($preco_venda, 2, ",", ".")); ?>" placeholder="R$ 0,00">
                </div>
                
                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                  <label>Quantidade:*</label>
                  <input class="form-control" type="number" name="quantidade" value="<?php echo $quantidade; ?>">
                </div>
                
                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                  <label>Descrição:*</label>
                  <textarea name="descricao" class="form-control"><?php echo $descricao; ?></textarea>
                </div>
	          </div>
	        </div>

	        <div class="modal-footer">
	            <button type="submit" name="acao" class="btn btn-success pull-right btn-Maxwidth" value="salvar">
	              <i class="fa fa-check"></i>
	              Salvar
	            </button>
	            
	            <a href="produtos.php">
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