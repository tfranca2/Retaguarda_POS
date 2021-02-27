$(document).on('change', '#estado', function() {
	var estado_id = $(this).val();
	var url = $("#base_url").val();
	
	$("#cidade").attr("disabled", true);
	
	jQuery.ajax({
		type: "POST",
		url: url+"/api/ajax/popula_cidade/"+estado_id,
		data: estado_id,
		
		success: function( data )
		{
			$("#cidade").html(data);
			$("#cidade").removeAttr("disabled");
		},
		error: function()
		{
			$("#cidade").removeAttr("disabled");
		}
	});
});
	
$(document).on('blur', '#cep', function() { 

	var cep = $(this).val().replace(/\D/g, '');
	
	$("#endereco").attr("disabled", true);
	$("#bairro").attr("disabled", true);
	$("#cidade").attr("disabled", true);
	$("#estado").attr("disabled", true);

	jQuery.ajax({
		type: "GET",
		url: "http://api.postmon.com.br/v1/cep/"+cep,
		
		success: function( dados )
		{						
			$("#endereco").val(dados.logradouro);
			$("#bairro").val(dados.bairro);
			
			$("#estado option[selected='selected']").removeAttr("selected");
			$('#estado option').filter(function () { return $(this).html() == dados.estado_info.nome; }).prop("selected", true);
			$('#estado option').filter(function () { return $(this).html() == dados.estado_info.nome; }).attr("selected", true);
			
			var estado_id = $('#estado option').filter(function () { return $(this).html() == dados.estado_info.nome; }).val();
							$('#cidade option').filter(function () { return $(this).html() == dados.cidade; }).attr("selected", true);
			var url = $("#base_url").val();
			
			jQuery.ajax({
				type: "POST",
				url: url+"/api/ajax/popula_cidade/"+estado_id,
				data: estado_id,
				
				success: function( data )
				{
					$("#cidade").html(data);
					$('#cidade option').filter(function () { return $(this).html() == dados.cidade; }).attr("selected", true);
				}
			});
			$("#estado").hide();
			$("#estado").show();

			$("#endereco").removeAttr("disabled");
			$("#bairro").removeAttr("disabled");
			$("#cidade").removeAttr("disabled");
			$("#estado").removeAttr("disabled");
		},
		error: function()
		{
			$("#endereco").removeAttr("disabled");
			$("#bairro").removeAttr("disabled");
			$("#cidade").removeAttr("disabled");
			$("#estado").removeAttr("disabled");
		}
	});
});