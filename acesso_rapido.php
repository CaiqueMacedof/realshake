<?php
	date_default_timezone_set("America/Sao_Paulo");	

	require_once("header.php");
	require_once('function/cliente.php');
	require_once('function/buscaleatoria.php');
	
	$action	  	  		= isset($_REQUEST['acao']) 				? $_REQUEST['acao'] 	 		: "";
	$erro	  	  		= isset($_REQUEST['erro']) 				? $_REQUEST['erro'] 	 		: "";
	$tipo 				= isset($_REQUEST['tipo']) 				? $_REQUEST['tipo'] 			: null;
	$tipo_venda_baixa 	= isset($_REQUEST['tipo_venda_baixa']) 	? $_REQUEST['tipo_venda_baixa'] : null;
	$qtd_acesso 		= isset($_REQUEST['qtd_acesso']) 		? $_REQUEST['qtd_acesso'] 		: "";
	
	switch ($action)
	{
		case 'inserir':
				
			$id_cliente = isset($_REQUEST['id_cliente']) ? $_REQUEST['id_cliente'] : null;
			
				
			if($tipo_venda_baixa == "" || empty($tipo)|| empty($qtd_acesso))
			{
				$msg = "[AVISO] Por favor preencher todos os campos do cadastro!";
				header("location: acesso_rapido.php?msg=$msg&erro=1");
				die();
			}
				
			if($qtd_acesso <= 0)
			{
				$msg = "[AVISO] Quantidade de acesso deve ser maior que 0.";
				header("location: acesso_rapido.php?msg=$msg&erro=1");
				die();
			}
			
			//INSERE ACESSO
			if($tipo_venda_baixa == 0)
			{
				$tipo_acesso    = lista_tipo_acesso($conn, $id_tipo_acesso);
				$retorno_insere = insereAcesso($conn, $id_cliente, $id_tipo_acesso, $qtd_acesso, $qtd_acesso * $tipo_acesso[0]['valor_tipo_acesso']);
			}
			//BAIXA ACESSO
			else if($tipo_venda_baixa == 1)
			{
				$retorno_baixa = baixaAcesso($conn, $id_cliente, $id_tipo_acesso, $qtd_acesso);
			}
		
		
			if(isset($retorno_insere) && $retorno_insere != false)
			{
				if(!empty($paginacao))
					header("location: acesso_rapido.php?msg=Acesso inserido com sucesso!&" . $paginacao);
					else
						header("location: acesso_rapido.php?msg=Acesso inserido com sucesso!");
		
						exit();
			}
			else if(isset($retorno_baixa) && $retorno_baixa != false)
			{
				if(!empty($paginacao))
					header("location: acesso_rapido.php?msg=[AVISO] Baixa no acesso com sucesso!&" . $paginacao);
					else
						header("location: acesso_rapido.php?msg=Baixa no acesso com sucesso!");
		
						exit();
			}
			else
			{
				if(!empty($paginacao))
					header("location: acesso_rapido.php?msg=[AVISO] Erro ao inserir/baixar o acesso!&erro=1&" . $pagi);
					else
						header("location: acesso_rapido.php?msg=[AVISO]Erro ao inserir/baixar o acesso!&erro=1");
							
						exit();
			}
		
			break;
		
		default:
			
			break;
	}
		
	//Busca a frequencia de todos os clientes do dia atual;
	$frequenciaHoje = frequenciaHoje($conn, date("Y-m-d"));
	
	//Concatena todos os ids;
	$ids_cliente = concatenar($frequenciaHoje);
	//$PDS = primeiro dia da semana
	//Pega o numero da semana atual e depois calcula pra pegar a a data de segunda feira, toda semana.
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
	
	$topFrequencias = buscarTopFrequencia($conn, $ids_cliente, $inicio, $dataHoje);
	//Concatena todos os ids;
	$ids_cliente = concatenar($topFrequencias);
	$nãoFrequencias = buscarNaoFrequencia($conn, $ids_cliente);

	$inicioSemana   = date("d/m/Y", strtotime($inicio));
	$atualSemana    = date("d/m/Y", strtotime($dataHoje));
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
		$(".small-box").click(function(){

			//Seta o id do cliente no input type='hidden'.
			$("#cliente").attr("value", $(this).data("cliente"));
		});
	});
</script>

<!-- Modal inserir Acesso rapido -->
	<div class="modal  modal-default fade in" id="acesso_rapido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document" style="width: 20%">
	    <div class="modal-content">
	     <form action="acesso_rapido.php" method="post">
	     <input type="hidden" name="id_cliente" id="cliente">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Acesso Rapido</h4>
	      </div>
	      	<div class="modal-body" style="padding: 0;">
	      	<div class="box-body">
      			<div class="row">
      				<div class="form-group col-xs-12" style="margin-bottom: 20px;">
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
                  	</div>
             	</div>
             </div>
             </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-success" name="acao" value="inserir">Prosseguir</button>
	      </div>
	     </form>
	    </div>
	  </div>
	</div>

<section class="content-header">
  <h1>
    Acesso Rápido
    <small>Frequências semanais do dia <span style="color: #4a4a4a;font-weight:bold;"><?php echo "$inicioSemana à $atualSemana"?></span></small>
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
	<section class="content">
		<?php 
			if(is_array($topFrequencias) && count($topFrequencias) > 0)
			{
				$i = 0;
				foreach ($topFrequencias as $topFrequencia)
				{
					//Busca os avatares masculinos, senão busca os feminino
					$imagem = buscarAvatar($topFrequencia['sexo']);
					//Busca as cores
					$cor = buscarCor($i);
					
					if($topFrequencia['total_frequencia'] == 1)
						$texto = "1 Frequência";
					else
						$texto = $topFrequencia['total_frequencia'] . " Frequências";
					
					echo "	<div class='col-lg-3 col-xs-6'>
								<div data-toggle='modal' data-target='#acesso_rapido' class='small-box bg-aqua' data-cliente='$topFrequencia[ID_CLIENTE]' style='background: $cor!important'>
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
			
			if(is_array($nãoFrequencias) && count($nãoFrequencias) > 0)
			{
				$i = 0;
				foreach ($nãoFrequencias as $nãoFrequencia)
				{
					//Busca os avatares masculinos, senão busca os feminino
					$imagem = buscarAvatar($nãoFrequencia['sexo']);
						
					echo "	<div class='col-lg-3 col-xs-6'>
							<div data-toggle='modal' class='small-box bg-aqua' data-cliente='$nãoFrequencia[id_cliente]' style='background: #d4d4d4!important;cursor: auto;'>
							<div class='inner' style='max-width: 70%;'>
							<h4>$nãoFrequencia[nome]</h4>
							</div>
							<div class='box-img' style='opacity: 0.5;'>
							<img src='img/avatares/$imagem'>
							</div>
							</div>
							</div>";
								
							$i++;
				}
			}
		?>
	</section>
</section>
<?php 
require_once("footer.php");
?>