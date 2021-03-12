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

$(document).on('change', '#especialidade', function() {
	var especialidade_id = $(this).val();
	var url = $("#base_url").val();
	
	$("#profissional").attr("disabled", true);
	
	jQuery.ajax({
		type: "POST",
		url: url+"site/cliente/popula_profissional/"+especialidade_id,
		data: especialidade_id,
		
		success: function( data )
		{
			$("#profissional").html(data);
			$("#profissional").removeAttr("disabled");
		},
		error: function()
		{
			$("#profissional").removeAttr("disabled");
		}
	});
});

$(document).ready(function() {
    $("input[type='number']").keydown(function (e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });


    $('.datepicker').datepicker({
	    dateFormat: 'dd/mm/yy',
	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
	    nextText: 'Próximo',
	    prevText: 'Anterior'
	});

});