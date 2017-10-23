<?php
require_once("header.php");
require_once("function/cliente.php");

$id_cliente = isset($_REQUEST['id']) ? $_REQUEST['id'] : header("location: clientes.php");
$cliente	= listaCliente($conn, $id_cliente);
?>

<style>

	.tabela-calendario
	{
		width: 100%;
		height: 710px;
		border-collapse: collapse;
	    background: white;
	    position: absolute;
	}
	
	.tabela-calendario tr td
	{
	    padding: 38px;
	}
	
	.tabela-calendario tr td,
	.tabela-calendario tr th
	{
	    text-align: left;
	    font-size: 20px;
    	color: #ccc;
    	font-weight: bold;
	    border-color: #dadada !important;
	    border-style: solid;
	    border-width: 1px;
	}
	
	.tabela-calendario tr th
	{
		padding: 6px 12px;
		font-size: 16px;
		color: #b3b1b1!important;
	}
	
	.dia-ativo
	{
	    background: rgba(229,229,229,0.4);
	}
	
	.btn
	{
		border-radius: 3px;
	    min-height: 36px;
	    background: rgb(234, 234, 234);
	    min-width: 88px;
	    text-align: center;
	    box-shadow: 0 3px 4px 0 rgba(0,0,0,0.26);
	}
	
	.box-freq-cli
	{
		background: rgba(0, 97, 212, 0.35)!important;
		color: white!important;
	}
	
	.fa-angle-double-left,
	.fa-angle-double-right
	{
		font-size: 25px;
	}
	
	.box-inativo
	{
	    background: rgba(97, 97, 97, 0.08);
	}
	
	/*.tabela-calendario tbody tr td:nth-child(1),
	.tabela-calendario tbody tr td:nth-child(7),
	.tabela-calendario tbody tr th:nth-child(1),
	.tabela-calendario tbody tr th:nth-child(7)
	{
	    background: rgba(255, 0, 0, 0.20);
	    color: white!important;
	}*/
	
	.box-info-calendar
	{
		display: inline-block;
		left: 0;
		width: 100%;
		display: flex;
		justify-content: flex-start;
		flex-wrap: wrap;
	}
	
	div.box-info-calendar > p
	{
	    display: block;
	    color: white;
	    margin: 5px;
	    padding: 1px 4px;
	    height: 22px;;
	}
</style>

<script>

$(document).ready(function(){
	
	/* Deixa o mês e o dia ja ativo referente a data atual */
	var d = new Date();

	var ano = d.getFullYear();
	var mes = d.getMonth()+1;
	var dia = d.getDate();

	$("#" + ano + "-" + mes).attr("style", "z-index: 1");
	
	if($("#" + ano + "-" + mes).find("#dia-" + dia).attr("class") == "")
		$("#" + ano + "-" + mes).find("#dia-" + dia).attr("class", "dia-ativo");


	//PROXIMO E VOLTAR DO CALENDÁRIO 
	$(".btn-proximo").click(function(){
		var id_ano = $(this).closest('table').parent().closest('table').attr("id").split('-')[1];
		var id_mes = $(this).closest('table').attr("id").split('-')[1];
		var id_mes_proximo = parseInt(id_mes) + 1;
		var id_ano_proximo = id_mes_proximo == 13 ? parseInt(id_ano) + 1 : id_ano;
			
		$(".tabela-calendario").attr("style", "");
		
		if(id_mes_proximo == 13)
			$("#" + id_ano_proximo + "-1").attr("style", "z-index: 1");
		else
			$("#" + id_ano_proximo + "-" + id_mes_proximo).attr("style", "z-index: 1");
			
	});

	$(".btn-voltar").click(function(){
		var id_ano = $(this).closest('table').parent().closest('table').attr("id").split('-')[1];
		var id_mes = $(this).closest('table').attr("id").split('-')[1];
		var id_mes_anterior = parseInt(id_mes) - 1;
		var id_ano_anterior = id_mes_anterior == 0 ? parseInt(id_ano) - 1 : id_ano;

		$(".tabela-calendario").attr("style", "");
		if(id_mes_anterior == 0)
			$("#" + id_ano_anterior + "-12").attr("style", "z-index: 1");
		else
			$("#" + id_ano_anterior + "-" + id_mes_anterior).attr("style", "z-index: 1");
	});
	
});
</script>

<div class="row" style="background:white;">
    <div class="col-xs-12">
		<div class="box-info-calendar">
			<p style="background: #ff0035;">SHAKE</p>
			<p style="background: #8b00ff;">SOPA</p>
			<p style="background: #009688;">NUTRISOUP</p>
			<p style="color: #5a5a5a;font-size: 18px;font-weight: 600;padding:0;">Cliente: <?php echo isset($cliente[0]['NOME']) ? $cliente[0]['NOME'] : ""; ?></p>
		</div>
		<?php echo gerar_calendario($conn,$id_cliente, 2015, 2017);?>
	</div>
</div>