<?php	
	require_once('header.php');
	require_once('function/usuario.php');
	
	$msg	  	= isset($_REQUEST['msg']) 		? $_REQUEST['msg'] 	 	 : "";
	$erro	  	= isset($_REQUEST['erro']) 		? $_REQUEST['erro'] 	 : 0;
	$action	  	= isset($_REQUEST['acao']) 		? $_REQUEST['acao'] 	 : "";
	$usuario	= isset($_REQUEST['usuario']) 	? $_REQUEST['usuario'] 	 : "";
	$senha 		= isset($_REQUEST['senha']) 	? $_REQUEST['senha'] 	 : "";
	
	switch ($action){
		case "inserir":
			
			if(empty($usuario))
			{
				$msg = "[AVISO] O campo usuário precisa ser preenchido.";
				header("location: usuario.php?msg=$msg&erro=1");
				die();
			}
			else if(empty($usuario))
			{
				$msg = "[AVISO] O campo senha precisa ser preenchido.";
				header("location: usuario.php?msg=$msg&erro=1");
				die();
			}
			
			$retorno = insertUsuario($conn, $usuario, $usuario);
			
			if($retorno)
			{
				$msg = "[AVISO] Usuário cadastrado com sucesso.";
				header("location: usuario.php?msg=$msg");
				die();
			}
			else
			{
				$msg = "[AVISO] Erro ao cadastrar o usuário.";
				header("location: usuario.php?msg=$msg&erro=1");
				die();
			}
			
		break;
	}
?>

<script>

$( function() {

	$(".fecha-msg").click(function(){
		var parent = $(this).parent();

		parent.css("display", "none");
	});

});

</script>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Cadastro de Usuários
    <small></small>
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
           <h3 class="box-title">Usuário</h3>
        </div>
        
        <form action="usuario.php" method="post">
	        <div class="box-body">
	          	<div class="form-group col-lg-6 col-xs-12 col-sm-12">
                  <label>Login/Usuário:</label>
                  <input class="form-control" type="text" name="usuario">
                </div>

                <div class="form-group col-lg-6 ccol-xs-12 col-sm-12">
                  <label>Senha:</label>
                  <input class="form-control" type="password" name="senha">
                </div>
	        </div><!-- /.box-body -->

	        <div class="modal-footer">
	            <button type="submit" name="acao" class="btn btn-success pull-right btn-Maxwidth" value="inserir">
	              <i class="fa fa-user-plus" aria-hidden="true"></i>
	              Criar
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