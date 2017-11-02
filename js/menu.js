$(document).ready(function(){
	$(".sidebar-toggle").click(function()
	{
		//EXPANDIR MENU
		if($(".sidebar-collapse").length)
			setCookie("max-min-menu", "");
		//DIMINUIR MENU
		else
			setCookie("max-min-menu", "sidebar-collapse");
	});
	
	var id_aba = getCookie("aba-ativa");

	//Deixa ativo a aba apos o caregamento da página
	if(id_aba != 'undefined')
	{
		//Submenu clicado
		if(id_aba.indexOf("-") >= 0)
		{
			var idMenu = id_aba.split('-')[0];
			$("#" + idMenu).attr("class", "treeview active");
			$("#" + id_aba).closest(".treeview-menu").attr("class", "treeview-menu menu-open");
			$("#" + id_aba).attr("class", "treeview active");
		}
		else
			//Apenas o menu clicado.
			$("#" + id_aba).attr("class", "treeview active");
	}
	else
	{
		//Aba padrão ao carregar o brownser sem nenhum cookie setado.
		$("#2").attr("class", "treeview active");
		$("#2-2").attr("class", "treeview active");
	}
	
	$(".treeview a, .treeview-menu li").click(function()
	{
		var idMenu 		= $(this).parent().attr("id");//ID menu
		var idSubmenu 	= $(this).attr("id");//ID submenu
		
		if(idMenu != undefined)
			setCookie("aba-ativa", idMenu);
		else
			setCookie("aba-ativa", idSubmenu);
	});
	
	//Desce e sobe o menu;
	$(".menu-aba").click(function()
	{
		var menuClicado = $(this).parent();
		var idClicado 	= $(this).parent().attr("id");
		var aba			= $(this);
		var	menu		= $(".menu");
		var	display	 	= menuClicado.find(".submenu").css("display");
		
		//Verifica se algum menu esta aberto;
		for(var i = 0; i < $(".menu").length; i++)
		{
			var ativado = menu.eq(i).attr("class").split(" ")[1];
			
			//Caso esteja algum submenu aberto tirando o que for clicado, ele some.
			if(i+1 != idClicado && ativado == "ativo")
			{
				menu.eq(i).find(".submenu").slideUp(400);
				menu.eq(i).find(".fa-angle-left").css("transform", "rotate(0deg)");
				menu.eq(i).attr("class", "menu");
			}
		}
		
		//Se o que for clicado estiver invisivel, ele mostrará, senão ele some.
		if(display == "none")
		{
			aba.find(".fa-angle-left").css("transform", "rotate(-90deg)");
			menuClicado.find(".submenu").slideDown(400);
			menuClicado.attr("class", "menu ativo");
		}
		else
		{
			aba.find(".fa-angle-left").css("transform", "rotate(0deg)");
			menuClicado.find(".submenu").slideUp(400);
			menuClicado.attr("class", "menu");
		}
	});
	
});

function getCookie(cname)
{
    var name 			= cname + "=";
    var decodedCookie 	= decodeURIComponent(document.cookie);
    var ca 				= decodedCookie.split(';');
	
    for(var i = 0; i <ca.length; i++)
    {
        var c = ca[i];
        while (c.charAt(0) == ' ')
        {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0)
        {
            return c.substring(name.length, c.length);
        }
    }

    return "";
}

function setCookie(cname, cvalue)
{
    document.cookie = cname + "=" + cvalue + ";";
}
