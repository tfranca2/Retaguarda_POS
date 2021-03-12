
function atualizaCoordenadas(){

	url = "https://maps.googleapis.com/maps/api/geocode/json?key="+ google_maps_api_key +"&address=" 
				 + $('#endereco').val() 
			+', '+ $('#bairro').val() 
			+', '+ $('#numero').val() 
			+', '+ $('#cidade').val() 
			+', '+ $('#estado').val();

	jQuery.ajax({
		type: "GET",
		url: url,
		success: function(data){
			$('#latitude').val( data.results[0].geometry.location.lat );
			$('#longitude').val( data.results[0].geometry.location.lng );
		}
	});
	
}
	
$(document).on('blur', '#cep', function() { 

	var cep = $(this).val().replace(/\D/g, '');
	
	$("#endereco").attr("disabled", true);
	$("#bairro").attr("disabled", true);
	$("#cidade").attr("disabled", true);
	$("#estado").attr("disabled", true);

	jQuery.ajax({
		type: "GET",
		url: "https://viacep.com.br/ws/"+ cep +"/json/",
		
		success: function( dados )
		{
			$("#endereco").val(dados.logradouro);
			$("#bairro").val(dados.bairro);
			$("#estado").val(dados.uf);
			$("#cidade").val(dados.localidade);

			atualizaCoordenadas();

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

$(document).ready(function(){

	$("#cep").blur(function(){
		setTimeout(function(){
			atualizaCoordenadas();
		}, 1000);
	});
	$('#endereco').blur(function(){
		atualizaCoordenadas();
	});
	$('#bairro').blur(function(){
		atualizaCoordenadas();
	});
	$('#cidade').change(function(){
		atualizaCoordenadas();
	});
	$('#estado').change(function(){
		atualizaCoordenadas();
	});

});