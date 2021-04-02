
!function($) {
    "use strict";
	
	// MASKED
    $('[data-masked]').inputmask();
	$('.money').inputmask('decimal', {
		  radixPoint:",",
		  groupSeparator: ".",
		  autoGroup: true,
		  digits: 2,
		  digitsOptional: false,
		  placeholder: '0',
		  rightAlign: false,
		  prefix: 'R$ ',
		  onBeforeMask: function (value, opts) {
			return value;
		  }
	});
	
	$('.cnpj').inputmask({
		mask:'99.999.999/9999-99'
	});
	
	$('.cpf').inputmask({
		mask:'999.999.999-99'
	});
		
	//datepicker
	$('.datepicker').datetimepicker({
        language:  'pt-BR',
        format: "dd/mm/yyyy",
        weekStart: 1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
    });
	
	$('.datetime').datetimepicker({
        language:  'pt-BR',
        format: "dd/mm/yyyy hh:ii",
		startDate: new Date(),
        weekStart: 1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
	
    //------------- Fancy select -------------//
 //    $('.fancy-select').fancySelect();
 //    //custom templating
 //    $('.fancy-select1').fancySelect({
 //        optionTemplate: function (optionEl) {
 //            return optionEl.text() + '<i class="pull-left ' + optionEl.data('icon') + '"></i>';
 //        }
 //    });

 //    //------------- Select 2 -------------//
 //    $('.select2').select2();
	
	// // Multi select 
	// $('.multiselect').multiSelect();
	
 //    //minumum 2 symbols input
 //    $('.select2-minimum').select2({
 //        placeholder: 'Select state',
 //        minimumInputLength: 2
 //    });

 //    // BOOTSTRAP SLIDER CTRL
 //    $('[data-ui-slider]').slider();

}(window.jQuery);