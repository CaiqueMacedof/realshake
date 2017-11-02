<?php
	require_once("header.php");
	require_once("function/functions_produto.php");
	require_once("function/criptografia.php");
	
	$msg	  	  		= isset($_REQUEST['msg']) 				? $_REQUEST['msg'] 	 			: "";
	$erro	  	  		= isset($_REQUEST['erro']) 				? $_REQUEST['erro'] 	 		: 0;
	$action	  	  		= isset($_REQUEST['acao']) 				? $_REQUEST['acao'] 	 		: "";
	$id_cliente   		= isset($_REQUEST['id_cliente']) 		? $_REQUEST['id_cliente'] 		: "";

	//Cadastro de produto
	$nome		  		= isset($_REQUEST['nome']) 				? $_REQUEST['nome'] 	 		: "";
	$preco_compra 		= isset($_REQUEST['preco_compra']) 		? $_REQUEST['preco_compra'] 	: NULL;
	$preco_venda 	  	= isset($_REQUEST['preco_venda']) 		? $_REQUEST['preco_venda'] 	 	: NULL;
	$quantidade 	  	= isset($_REQUEST['quantidade']) 		? $_REQUEST['quantidade'] 		: 0;
	$descricao 	  		= isset($_REQUEST['descricao']) 		? $_REQUEST['descricao'] 	 	: "";
	
	switch ($action){
		case "cadastrar":
			$regex_preco = "/^(([0-9]{1,10})|([0-9]{1,10}[,][0-9]{1,2}))$/";
			
			if($preco_venda < $preco_compra)
			{
				$msg = "[AVISO] O preço de venda não pode ser menor que o preço da compra!";
				header("location: produtos.php?msg=$msg&erro=1");
				die();
			}
			else if(!preg_match($regex_preco, $preco_compra)
				|| !preg_match($regex_preco, $preco_venda))
			{
				$msg = "[AVISO] O campo preço de venda ou compra não esta no formato correto. Exemplo de formato correto: REAL(máximo 10 casas),CENTAVOS(máximo 2 casas)!";
				header("location: produtos.php?msg=$msg&erro=1");
				die();
			}
			
			if(!empty($nome) && !empty($preco_compra) && !empty($preco_venda) && $quantidade > 0 && !empty($descricao))
			{
				if(!empty($_FILES['imagem_produto']['name']))
				{
					$extensao = pathinfo($_FILES['imagem_produto']['name'], PATHINFO_EXTENSION);
					if(strtolower($extensao) == "jpg" || strtolower($extensao) == "png")
					{
						$caminho  = "img/produtos/" . md5(time()) . "." . $extensao;
						move_uploaded_file($_FILES['imagem_produto']['tmp_name'], $caminho);
					}
					else
					{
						$msg = "[AVISO] Extensão incorreta do arquivo, são validos apenas arquivos com a extenão .JPG ou .PNG";
						header("location: produtos.php?msg=$msg&erro=1");
						die();
					}

				}
				
				$id_produto = insere_produto($conn, $nome, $descricao, str_replace(",", ".", $preco_compra), str_replace(",", ".", $preco_venda), $caminho);
				if(is_numeric($id_produto))
				{
					replace_estoque_produto($conn, $id_produto, $quantidade);
					$msg = "[AVISO] Produto cadastrado com sucesso!";
					header("location: produtos.php?msg=$msg");
					die();
				}
				else
				{
					$msg = "[AVISO] Erro ao cadastrar o produto!";
					header("location: produtos.php?msg=$msg&erro=1");
					die();
				}
			}
			else
			{
				$msg = "[AVISO] Por favor preencher todos os campos do cadastro!";
				header("location: produtos.php?msg=$msg&erro=1");
				die();
			}
			
			break;

		case 'filtrar':
			$produtos 		= lista_produtos($conn, null, $nome);
			$total_registro = count($produtos);
		break;
		
		case 'inserir':
			$id_produtos 			= isset($_REQUEST['id_produto']) ? $_REQUEST['id_produto'] : null;
			$quantidade_produtos 	= isset($_REQUEST['qtd_produto']) ? $_REQUEST['qtd_produto'] : null;

			if(is_array($id_produtos) && count($id_produtos) > 0)
			{
				$i = 0;
				foreach ($id_produtos as $id_produto)
				{
					insere_estoque_produto($conn, $id_produto, $quantidade_produtos[$i]);
					$produto = lista_produtos($conn, $id_produto);
					insere_compra_produto($conn, $id_produto, $quantidade_produtos[$i], 0, $produto[0]["preco_compra"]);
					$i++;
				}
				
				$msg = "[AVISO] Reposição dos produtos com sucesso!";
				header("location: produtos.php?msg=$msg");
				die();
			}
			else
			{
				$msg = "[AVISO] Não foi possível repor os produtos selecionados!";
				header("location: produtos.php?msg=$msg&erro=1");
				die();
			}

			break;
		
		case 'deletar':
			
			$id_produto = isset($_REQUEST['id_produto']) ? $_REQUEST['id_produto'] : null;
			if(is_numeric($id_produto))
			{
				$retorno = deleta_produto($conn, $id_produto);
				if($retorno)
				{
					deleta_estoque_produto($conn, $id_produto);
					$msg = "[AVISO] Produto deletado com sucesso!";
					header("location: produtos.php?msg=$msg");
					die();
				}
				else
				{
					$msg = "[AVISO] Falha ao deletar o produto!";
					header("location: produtos.php?msg=$msg&erro=1");
					die();
				}
			}
			
		default:
			$produtos = lista_produtos($conn);
	}

?>
<script>

$( function() {

	$('#tabela_produtos').DataTable({
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
    
    $produtos_busca = lista_produtos($conn);
    $total  = count($produtos_busca);
    $i 		= 1;

	foreach ($produtos_busca as $produto)
	{
		if($i < $total)
			echo '"' . $produto['nome'] . '",';
		else
			echo '"' . $produto['nome'] . '"';
		$i++;
	}
    ?>
    ];
    
    $( "#tags" ).autocomplete({
      source: availableTags
    });
    
    $(".btn_add_carrinho").click(function(){
	 	var id_produto 	= $(".tipo_produto").val();
	 	var produto 	= $(".tipo_produto option:selected").text();
	 	var quantidade 	= $(".total").val();
	 	var erro		= false;
	 	//RESETA A COR
	 	$(".tipo_produto").attr("style", "border: 1px solid #d2d6de");
	 	$(".erro-total").css("display", "none");
	 	$(".total").css("color", "#555");
	 	
	 	if(id_produto == 0)
	 	{
	 		$(".tipo_produto").attr("style", "border: 2px solid rgb(255, 118, 118)");
	 		erro = true;
	 	}
	 	if(quantidade < 1)
	 	{
	 		$(".total").css("color", "red");
	 		$(".erro-total").css("display", "block");
	 		erro = true;
	 	}
	 	
	 	if(erro)
	 		return false;
	 		
	 	//RESWETO OS CAMPOS
		$(".total").val(0);
		$(".tipo_produto option:first").prop("selected", true);
		
		if($(".tr_carrinho_acesso .tr_vazio").length == 1)
			$(".tr_vazio").remove();
			
	 	$(".tr_carrinho_acesso").append("	<tr>"+
	 											"<td>"+
	 												"<input type='hidden' name='id_produto[]' value='"+ id_produto +"'/>"
	 												+ produto +
 												"</td>"+
 												
 												"<td align='center'>"+
	 												"<input type='hidden' name='qtd_produto[]' value='"+ quantidade +"'/>"
	 												+ quantidade +
	 											"</td>"+
 												"<td align='center'><i class='fa fa-remove icon_remover' style='color: red'></i></td>"+
											"</tr>");
	});
    
    $(document).on("click", ".icon_remover", function()
	 {
		$(this).closest("tr").remove();
		if($(".tr_carrinho_acesso tr").length == 0)
		{
			$(".tr_preco_total").remove();
			$(".tr_carrinho_acesso").append("<tr class='tr_vazio'><td align='center' colspan='3'><b style='color:red;'>Carrinho vazio.<b></td></tr>");
		}
	 });
	 
    $(".btn_add_quantidade").click(function(){
 		if($(".total").val() == "")
 			var qtd_input = 0;
 		else
 		{
 			var qtd_input = parseInt($(".total").val());
 			
 			$(".erro-total").css("display", "none");
	 		$(".total").css("color", "#555");
 		}

		var total = parseInt($(this).val()) + parseInt(qtd_input);
 		
 		$(".total").val(total);
 		$(".total").css("width", ($(".total").val().length * 23) + "px");
	});
	
    $(".deletar-produto").click(function(){
		var id = $(this).data("id");

		$("#deleta_produto").attr("value", id);
	});
	
	$(".total").keydown(function()
	{
		$(".total").css("width", (($(".total").val().length * 23) + 20) + "px");
	});
	
	$(".tipo_produto").change(function(){
		if($(this).val() != 0)
			$(this).attr("style", "border: 1px solid #d2d6de");
	});
	
	$(".fecha-msg").click(function(){
		var parent = $(this).parent();

		parent.css("display", "none");
	});
} );

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
	
	if(f.preco_venda.value < f.preco_compra.value)
		ret = exibeMensagem(f.preco_venda, "[ATENÇÃO]\nO preço de venda não pode ser menor que o preço da compra!", true)
		
	if(f.preco_compra.value == "")
		ret = exibeMensagem(f.preco_compra, "Por favor, escolha um sexo!")
	
	if(f.preco_venda.value == "")
		ret = exibeMensagem(f.preco_venda, "Por favor, escolha uma origem!")
		
	if(f.quantidade.value < 1)
		ret = exibeMensagem(f.quantidade, "Por favor, escolha uma origem!")
		
	if(f.descricao.value == "")
		ret = exibeMensagem(f.descricao, "Por favor, escolha a descrição do produto!")
	
	return ret;
}

function exibeMensagem(elemento, mensagem, mostra_msg = false)
{
	if(mostra_msg){alert(mensagem);}
	elemento.style.border = "2px solid #ff7676";
	return false;
}

</script>

<style>
	.btn_add_quantidade, .btn-consumo, .btn-acesso
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
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Produtos
    <small>listagem de produtos</small>
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
	     <form action="produtos.php" method="post">
	     <input type="hidden" name="id_produto" id="deleta_produto">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Alerta!</h4>
	      </div>
	      <div class="modal-body">
	        Você realmente deseja deletar esse produto?
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-success" name="acao" value="deletar">Prosseguir</button>
	      </div>
	     </form>
	    </div>
	  </div>
	</div>
		
	<!-- Modal CADASTRO PRODUTO PADRÃO -->
	<div class="modal fade in" id="cadastro-produto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	     <form action="produtos.php" method="post" onsubmit="return valida(this);" enctype="multipart/form-data">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Cadastro de Cliente</h4>
	      </div>
	      <div class="modal-body">
	      <input type="hidden" name="id_cliente" id="id_cliente"/>
          	<div class="box-body">
              <div class="row">
              	<div class="form-group col-md-12 col-sm-12 col-xs-12">
                  <label>Imagem:</label>
                  <input class="form-control" type="file" name="imagem_produto">
                </div>
                
	      		<div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>Nome:*</label>
                  <input class="form-control" type="text" name="nome">
                </div>

                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>Preço de Compra:*</label>
                  <input class="form-control" type="text" name="preco_compra" placeholder="R$ 0,00">
                </div>
                
                <div class="form-group ccol-md-6 col-sm-6 col-xs-12">
                  <label>Preço de Venda:*</label>
                  <input class="form-control" type="text" name="preco_venda" placeholder="R$ 0,00">
                </div>
                
                <div class="form-group ccol-md-6 col-sm-6 col-xs-12">
                  <label>Quantidade:*</label>
                  <input class="form-control" type="number" name="quantidade">
                </div>
                
                <div class="form-group ccol-md-12 col-sm-12 col-xs-12">
                  <label>Descrição:*</label>
                  <textarea name="descricao" class="form-control"></textarea>
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
  
  <!-- Modal ACESSOS -->
	<div class="modal fade in" id="repor_produtos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog modal-dispositivo" role="document" style="width: 380px;">
	    <div class="modal-content">
	     <form action="produtos.php" method="post">
	     <input type="hidden" name="id_cliente" class="id_cliente" />
	      <div class="modal-header">
	        <button type="button" class="close btn_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Reposição de Produtos</h4>
	      </div>
	      
	      <div class="modal-body">
	      	<div class="box-body">
      			<div class="row">
      				<div class="form-group col-xs-12" style="display: flex;">
      					<div style="display: flex;">
	              			<input class="form-control verifica input-qtd-acesso total" type="text" name="qtd_acesso_total" value="0"
	                  			style="height: 50px;border: none;font-size: 45px;padding: 0;width: 30px;" />
						  </div>
						  <span class="erro-total" style="display:none;font-size: 35px;color: red;">*</span>
                  	</div>
              		
                  	<div class="form-group col-xs-12">	
                  		<button type="button" class="col-lg-4 col-xs-4 col-sm-4 btn_add_quantidade" value="1">+1</button>
                  		<button type="button" class="col-lg-4 col-xs-4 col-sm-4 btn_add_quantidade" value="3">+3</button>
                  		<button type="button" class="col-lg-4 col-xs-4 col-sm-4 btn_add_quantidade" value="5">+5</button>
                  	</div>
                  	
	                <div class="form-group col-xs-12">
	                  <select class="tipo_produto form-control" name="id_produto">
	                  	<option value="0">Selecione um produto</option>
						<?php 
							if(is_array($produtos) && count($produtos) > 0)
							{
								foreach ($produtos as $produto)
								{
						?>
									<option value="<?php echo $produto['id_produto'];?>">
										<?php echo $produto['nome']; ?>
									</option>
						<?php 		
								}
							}
						?>
	              	</select>
	                </div>
	                
					<div class="form-group col-xs-12" style="margin: 15px 0 0 0;display: flex;justify-content: flex-end;">
						<button type="button" class="col-lg-4 col-xs-12 col-sm-12 btn_add_carrinho">+Add</button>
					</div>	                
	                <div class="box-body col-xs-12" style="max-height: 185px;overflow: auto;">
	                	<table class="table table-bordered">
			            <thead>
			              <tr style="background-color: #eee;font-size: 15px;">
			              	<th colspan="5"><i class="fa fa-shopping-cart" style="margin-right:3px;"></i>Carrinho de Compra</th>
			              </tr>
			              
			              <tr style="background-color: #eee;font-size: 12px;">
			              	<th>Produto</th>
			              	<th style="text-align: center;">Quantidade</th>
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
  
  <div class="row">
    <div class="col-xs-12">
        <div class="box box-danger">
        <div class="box-header with-border">
           <h3 class="box-title">Busca</h3>
        </div>
        
        <form action="produtos.php" method="post">
        	<input type="hidden" name="id_cliente" value="" />
	        <div class="box-body">
	          <div class="form-group col-md-4 col-sm-6 col-xs-12">
	            <label>Nome:</label>
	            <div class="input-group" style="width: 100%;">
	              <input type="text" name="nome" class="form-control" id="tags">
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
          <h3 class="box-title">Lista de Produtos</h3>
            <button type="button" class="btn bg-maroon pull-right btn-Maxwidth" data-toggle="modal" data-target="#repor_produtos"
           		style="margin-left:8px;">
              <i class="fa fa-cart-plus" aria-hidden="true"></i>
              Repor Produto
            </button>
            
           <button type="button" class="btn btn-success pull-right btn-Maxwidth" data-toggle="modal" data-target="#cadastro-produto">
              <i class="fa fa-plus" aria-hidden="true"></i>
              Add Produto
            </button>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table id="tabela_produtos" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Nome</th>
                <th style="text-align:center;">Preço de Compra<br>por Unid.</th>
                <th style="text-align:center;">Preço de Venda<br>por Unid.</th>
                <th style="text-align:center;">Quantidade</th>
                <th style="text-align:center;">Descrição</th>
                <th style="text-align:center;">Preço Total<br>de Venda</th>
				<th style="text-align:center;">Editar</th>
                <th style="text-align:center;">Deletar</th>
              </tr>
            
            </thead>
            
            <tbody>
            <?php
			$total_compra = 0;
			if(is_array($produtos) && count($produtos) > 0)
			{
				$limite 	= 40;
				$descricao 	= "";
				foreach ($produtos as $produto)
				{
					$total_compra += $produto['preco_venda']*$produto['quantidade'];
					
					$backgroundColor = "white";
					if($produto['quantidade'] == 0)
						$backgroundColor = "rgba(255, 153, 153, 0.59)";
						
					if(strlen($produto["descricao"]) >= $limite)
					{
						$descricao = substr($produto["descricao"], 0, $limite) . " ...";
					}
					else
					{
						$descricao = $produto["descricao"];
					}
				?>
              <tr style="background-color: <?php echo $backgroundColor; ?>">
                <td><?php echo $produto['nome']; ?></td>
                <td align="right"><?php echo "R$ " . number_format($produto['preco_compra'], 2, ",", "."); ?></td>
                <td align="right"><?php echo "R$ " . number_format($produto['preco_venda'], 2, ",", ".")?></td>
                <td align="center"><?php echo $produto['quantidade']; ?></td>
                <td align="center" title="<?php echo $produto['descricao'];?>"><?php echo $descricao;?></td>
                <td align="right"><?php echo "R$ " . number_format($produto['preco_venda'] * $produto['quantidade'], 2, ",", ".");?></td>
                <td align="center">
                	<a href="produto.php?acao=editar&id_produto=<?php echo $produto['id_produto'];?>">
                		<i class="fa fa-pencil" aria-hidden="true"></i>
                	</a>
                </td>
                <td align="center">
                	<i class="fa fa-times deletar-produto" style="color: red;" aria-hidden="true"  data-toggle="modal" data-target="#alerta" 
                		data-id="<?php echo $produto['id_produto']; ?>"></i>
                </td>
              </tr>
            <?php 
				}
			}
			else
			{
			
            ?>
            <tr>
            	<td colspan="8" align="center" style="color: #FF0000;font-weight: bold;">Nenhum Produto encontrado.</td>
            </tr>
            
            <?php 
			}
            ?>
            </tbody>
            <tbody style="border-top: none;">
	            <tr>
	            	<th colspan="5" style="text-align:right;">Total</th>
	            	<th style="text-align:right;color: #2cb03c;">
	            	  	<i class="fa fa-usd"></i>
	            		<?php echo number_format($total_compra, 2, ",", "."); ?>
	            	</th>
	            	<th colspan="2"></th>
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
