<?php 
	use App\Helpers; 
	$empresa = \DB::table('empresa')->first();
?>
@extends('layouts.public')
@section('content')
	<div class="row" style="margin: 0px;">
	    <div class="col-md-12">
	    	<div class="panel panel-card recent-activites" style="margin-bottom: 0px;padding-bottom: 20px;">

	<div class="row">
	    <div class="col-md-12">
		    <br>
	        <form id="check" action="{{ url('/checkout') }}" method="post" class="" enctype="multipart/form-data" data-parsley-validate >
				@csrf
	        <input type="hidden" name="valor" value="{{ $valor }}" />
	        <input type="hidden" name="pedido" value="{{ $pedido }}" />
	        <input type="hidden" name="cliente_id" value="{{ $cliente->id }}" />
	        <div class="form-horizontal">
		        <div class="col-md-12">
		            <img src="{{ url('assets/imgs/creditcard.png') }}" style="width: 40px; float: left; margin-top: -10px; margin-right: 5px;">
		            <h4>
		            	<a href="{{ url('/checkout/'. $pedido .'/pix') }}" class="btn text-center" style="float: right; margin-top: -12px;"><img src="{{ url('assets/imgs/pix.png') }}" style="width: 40px; margin-right: 5px;">Quero pagar com PIX</a>
		            	Cartão de crédito
		            </h4>
		            <hr>
		        </div>
				<div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Número do Cartão de Crédito: </label>
	                    <div class="col-sm-6">
	                        <input data-validation="required" data-validation-error-msg="Número do cartão inválido." id="cartao" name="cartao" data-masked="" data-inputmask="'mask': '9999 9999 9999 9999'"  type="text" class="form-control" placeholder="Número do Cartão de Crédito" required>
	                    </div>
	                    <div class="col-sm-1">
	                    	<div id="cardimg"></div>
	                    </div>
	                </div>
	            </div>
				<div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label" style="display: block;">Vencimento: </label>
	                    <div class="col-sm-6" style="padding: 0;">
	                    	<div class="col-xs-4">
								<input data-validation="required" data-validation-error-msg="Mês inválido." id="mes"  name="mes" title="Mês" data-masked="" data-inputmask="'mask': '99'" type="text" class="form-control" placeholder="Mês" min="01" max="12" required>
	                    	</div>
	                    	<div class="col-xs-1" style="padding: 0;"><span class="btn">/</span></div>
	                    	<div class="col-xs-7">
								<input data-validation="required" data-validation-error-msg="Ano inválido." id="ano" name="ano" title="Ano" data-masked="" data-inputmask="'mask': '9999'"  type="text" class="form-control" placeholder="Ano" min="{{ date('Y') }}" required>
	                    	</div>

	                    </div>
	                </div>
	            </div>
				<div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Código de Segurança: </label>
	                    <div class="col-sm-6">
	                        <input data-validation="required" data-validation-error-msg="Código de segurança inválido." data-masked="" data-inputmask="'mask': '999'" id="cvv" name="cvv" type="text" class="form-control" placeholder="CVV" required>
	                    </div>
	                </div>
	            </div>
	            @if( $pedido )
				<div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Valor: </label>
	                    <div class="col-sm-6">
	                        <input disabled="disabled" type="text" value="{{ Helper::formatDecimalToView( $valor ) }}" class="form-control">
	                    </div>
	                </div>
	            </div>
	            @endif

		        <div class="col-md-12">
		        	<br>
		            <h4>Dados do Titular do Cartão de Crédito</h4>
		            <hr>
		        </div>
	            <div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Nome: </label>
	                    <div class="col-sm-6">
	                        <input data-validation="required" data-validation-error-msg="Nome não válido." name="nome" value="{{ $cliente->nome }}" type="text" class="form-control" required>
	                    </div>
	                </div>
	            </div>
		        <div class="col-md-12">
		            <div class="form-group">
		                <label class="col-sm-4 control-label">CPF: </label>
		                <div class="col-sm-6">
		                    <input name="cpf" value="{{ Helper::onlyNumbers($cliente->cpf) }}" type="text" class="form-control" required="required" data-validation="required" data-validation-error-msg="Insira um cpf.">
		                </div>
		            </div>
		        </div>
	            <div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Email: </label>
	                    <div class="col-sm-6">
	                        <input name="email" value="{{ $cliente->email }}" type="email" class="form-control" data-validation="email"  data-validation-error-msg="Email não válido." required>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Telefone: </label>
	                        <div class="col-sm-6">
	                         <input name="telefone" value="{{ $cliente->telefone }}" type="text" data-masked="" data-inputmask="'mask': '(99) 99999-9999'" class="form-control" required data-validation="required" data-validation-error-msg="Insira um telefone.">
	                     </div>
	                 </div>
	            </div>

				<div class="col-md-6 col-sm-offset-3 text-center">
					<br>
					<div class="form-group">
						<label for="termodeuso" style="font-weight: normal;"><input type="checkbox" checked="checked" required="required" data-validation="required" id="termodeuso" name="termodeuso" value="1" > Estou ciente e aceito os <a href="{{ env('LINK_TERMO_DE_USO','#') }}" target="_blank" style="color: <?php echo $empresa->menu_background?>;"><b>termos de uso</b></a>.</label>
					</div>
		    </div>

				<div class="col-md-12">
				    <div class="form-group">
				        <div class="col-sm-4 col-sm-offset-4">
				        	<button type="submit" class="btn btn-primary btn-lg btn-block" style="color: <?php echo $empresa->menu_color?>;background: <?php echo $empresa->menu_background?>; border-color: <?php echo $empresa->menu_background?>;">PAGAR</button>
				        </div>
				    </div>
				</div>
			</div>
			</form>
		</div>
	</div>

</div>
</div>

</div>
<div id="countdown" class="progress"><div class="progress-bar"></div></div>
@endsection
@section('scripts')
<style>
	#cardimg {
		background-repeat: no-repeat;
		background-position: top center;
		background-size: contain;
		width: 50px;
		height: 50px;
		margin-top: -10px;
		position: absolute;
		right: 20px;
	}

	.btn-primary, 
	.btn-primary:hover,
	.btn-primary:active,
	.btn-primary:focus {
		color: <?php echo $empresa->menu_color?> !important;
		background: <?php echo $empresa->menu_background?> !important; 
		border-color: <?php echo $empresa->menu_background?> !important;
	}
	.btn-primary:hover,
	.btn-primary:active,
	.btn-primary:focus {
		opacity: 0.9;
	}

	.progress{
		position: fixed;
		bottom: 0;
		width: 100%;
		margin: 0;
		height: 5px;
		background: unset;
		box-shadow: unset;
	}
	.progress-bar {
		width: 100%;
	}
</style>
<script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
<script type="text/javascript">

	function getCreditCardLabel( cardNumber ) {

		var regexVisa = /^4[0-9]{12}(?:[0-9]{3})?/;
		var regexMaster = /^5[1-5][0-9]{14}/;
		var regexAmex = /^3[47][0-9]{13}/;
		var regexDiners = /^3(?:0[0-5]|[68][0-9])[0-9]{11}/;
		var regexDiscover = /^6(?:011|5[0-9]{2})[0-9]{12}/;
		var regexJCB = /^(?:2131|1800|35\d{3})\d{11}/;
		var regexElo = /^^(?:40117[8-9]|431274|438935|451416|457393|45763[1-2]|504175|627780|636297|636368|65500[0-1]|65165[2-4]|65048[5-8]|506699|5067[0-6]\d|50677[0-8]|509\d{3})\d{10}$/;
		var regexHiper = /^(606282\d{10}(\d{3})?)|(3841\d{15})$/;

		if( regexVisa.test( cardNumber ) ) return 'visa';
		if( regexMaster.test( cardNumber ) ) return 'master';
		if( regexAmex.test( cardNumber ) ) return 'amex';
		if( regexDiners.test( cardNumber ) ) return 'diners';
		if( regexDiscover.test( cardNumber ) ) return 'discover';
		if( regexJCB.test( cardNumber ) ) return 'jcb';
		if( regexElo.test( cardNumber ) ) return 'elo';
		if( regexHiper.test( cardNumber ) ) return 'hiper';
		return '';

	}

	$(document).ready(function(){

		$('#cartao').keyup(function(){
			var numb = $('#cartao').val().match(/\d/g);
			numb = numb.join("");
			bandeira = getCreditCardLabel( numb );
			url = '{{ url("/")."/assets/imgs/card/" }}'+ bandeira +'.png';

			if( bandeira.length > 1 && numb != 'null' )
				$('#cardimg').css({ 'background-image' : 'url("'+ url +'")' });
			else 
				$('#cardimg').css({ 'background-image' : 'none' });

		});

		$("form button[type='submit']").click(function(e){
			e.preventDefault();
			grecaptcha.ready(function(){
				grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {action: 'homepage'}).then(function(token) {
					$('form').prepend('<input type="hidden" name="g_recaptcha_response" value="'+ token +'">');
					$('form').submit();
				});
			});
		});

	});
</script>
@if( isset($erros) and $erros )
<script>
	const ul = document.createElement('ul');
	@foreach( $erros as $key => $erro )
	const li{{ $key }} = document.createElement('li');
	li{{ $key }}.innerHTML = '{{ $erro }}';
	ul.appendChild(li{{ $key }});
	@endforeach
	swal({
		icon: 'error',
		title: 'Erros Ocorreram na Transação',
		content: ul,
	});
</script>
@endif
<script type="text/javascript">
	$(document).ready(function(){
		$('[data-masked]').inputmask();

		function progress( timeleft, timetotal, element, callback ){
		    var progressBarWidth = timeleft * element.width() / timetotal;
		    element.find('div').animate({ width: progressBarWidth }, 500, function(){
		    	if( timeleft == 0 )
		    	 	setTimeout(function(){ callback(); }, 500);
		    });

		    percent = (timeleft/timetotal)*100;
		    if( percent <= 10 )
		    	element.find('div').addClass('bg-danger');

		    if( timeleft > 0 )
		        setTimeout(function(){ progress(timeleft - 1, timetotal, element, callback); }, 1000);
		}

		seconds = {{ session('timeleft', 0) }};
		if( seconds ){
			progress(seconds, 600, $('#countdown'), function(){
				swal({
					icon: 'warning',
					title: 'Seu tempo acabou :(',
					text: 'Esta cartela não está mais disponível',
					button: "Gerar Nova Cartela",
					closeOnClickOutside: false,
					closeOnEsc: false,
					closeModal: true,
				}).then((value) => {
					window.location.href = "{{ url('/cartela') }}";
				});
			});
		}

	});
</script>
@endsection