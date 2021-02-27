!function($) {
    "use strict";
	
	$(function(){
		$('#control-all').on('click', function ( event) {
			event.preventDefault();
			$(".permissoes").val("all");
		});
	});
		
	$(function(){
		$('.warning-alert').on('click', function ( event) {
			var r = confirm("Tem certeza que deseja excluir?");
			if (r == false) {
				event.preventDefault();
			}
		});
	});
	
	$('[data-masked]').inputmask();
	$('.money').inputmask('decimal', {
	  radixPoint:",",
	  groupSeparator: ".",
	  autoGroup: true,
	  digits: 2,
	  digitsOptional: false,
	  placeholder: '0',
	  rightAlign: false,
	  prefix: 'R$',
	  onBeforeMask: function (value, opts) {
		return value;
	  }
	});
	
	$(":file").filestyle({htmlIcon: '<i class="fa fa-photo"></i>',text: " Procurar",btnClass: "btn-info"});
}(window.jQuery);