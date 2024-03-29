<?php 
	use App\Helpers; 

	$empresa = \DB::table('empresa')->first();
?>
@extends('layouts.public')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center">
			<b><i class="fa fa-shopping-cart"></i> TOTAL: R$ <span class="preco"></span></b><br>
			cartela <span class="index_cartela"></span> / <span class="count_cartela"></span><br><br>
		</div>
		<div class="col-md-12 text-center">
			<div class="destaque"><b>Número do título: <span class="numero_cartela"></span></b></div>
			<div class="destaque-sub">Números para participações</div>
		</div>


		<div class="col-md-12" style="position: relative;">
			<div class="loading"><img src="{{ asset('assets/imgs/loading.gif') }}"></div>
			<div class="prev"><i class="fa fa-chevron-left"></i></div>
			<div class="next"><i class="fa fa-chevron-right"></i></div>
			<div class="round-case">
				@foreach( range(1,20) as $i )<div class="round"><br></div>@endforeach
			</div>
		</div>
		<div class="col-md-12 text-center">
			<br><a href="#" id="gerar" class="btn btn-success"><i class="fa fa-refresh"></i> Gerar Novas Cartelas</a><br><br>
		</div>
	</div>
	<div class="col-md-12 text-center">
		<h2><b>DADOS OBRIGATÓRIOS PARA <span class="text-danger">RECEBER O PRÊMIO</span></b></h2>
		<small>Cartela <b class="numero_cartela"></b> - R$ <span class="preco"></span></small><br>
	</div>

	<div class="col-md-12">
		<form class="form-edit" method="post" action="{{ url('/prevenda') }}">
			@csrf
			<div class="row">
				<div class="col-md-6">
					<label for="">CPF</label>
					<input type="text" class="form-control" id="cpf" name="cpf" data-parsley-cpf="true" data-parsley-required="true" required="">
				</div>
				<div class="col-md-6">
					<label for="">Nome</label>
					<input type="text" class="form-control" id="nome" name="nome" data-parsley-required="true" required="">
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<label for="">E-mail</label>
					<input type="email" class="form-control" id="email" name="email" data-parsley-required="true" required="">
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<label for="">Telefone</label>
					<input type="text" class="form-control" id="telefone" name="telefone" data-parsley-required="true" required="">
				</div>
				<div class="col-md-4">
					<label for="">Cidade</label>
					<input type="text" class="form-control" id="cidade" name="cidade" data-parsley-required="true" required="">
				</div>
				<div class="col-md-4">
					<label for="">Estado</label>
					<select id="uf" name="uf" class="form-control select2" data-parsley-required="true" required="">
						<option value="">Selecione o Estado</option>
						@foreach( $estados as $estado )
						<option value="{{ $estado->uf }}">{{ $estado->nome }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-center">
					<input type="hidden" name="key" id="key" value="">
					<input type="hidden" name="timeleft" id="timeleft" value="">
					<br><button id="finalizar" class="btn btn-primary">Finalizar Pedido</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div id="countdown" class="progress"><div class="progress-bar"></div></div>
<br>
@endsection
@section('css')
<style>
	.destaque, .round, .prev, .next, #finalizar {
		background: {{ $empresa->menu_background }}; 
		color: {{ $empresa->menu_color }};
	}
	.destaque-sub {
		color: {{ $empresa->menu_background }}; 
	}
	.destaque, .destaque-sub {
		margin: auto;
		padding: 20px;
		border-radius: 5px 5px 0 0;
	}
	.destaque, .destaque-sub, #gerar, #finalizar {
		width: 300px;
		max-width: 100%;
	}
	.round {
		width: 35px;
		height: 35px;
		padding: 5px 5px;
		border-radius: 100%;
		margin: 4px 2px;
		display: inline-block;
		text-align: center;
		font-weight: bold;
	}
	.round-case {
		width: 200px;
		max-width: 100%;
		margin: auto;
	}
	.prev, .next {
		display: flex;
		align-items: center;
		height: 100%;
		width: 30px;
		cursor: pointer;
		opacity: 0.3;
	}
	.prev:hover, .next:hover, .prev:active, .next:active, .prev:focus, .next:focus {
		opacity: 1;
		transition: opacity .15s linear;
	}
	.prev > i, .next > i {
		margin: auto;
	}

	.prev {
		float: left;
	}
	.next {
		float: right;
	}

	.loading img {
		width: 50px;
	}
	.loading {
		position: absolute;
		top: calc( 63% - 50px );
		left: calc( 53% - 50px );
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
	.swal-button-container {
		width: 100%;
	}
	.swal-button {
		margin: auto;
		display: block;
	}
	.swal-button:not([disabled]){ 
		background: #38c172;
	}
	.swal-button:not([disabled]):hover {
		background: #2fa360;
	}

	.lds-dual-ring {
		display: inline-block;
		width: 0px;
		height: 15px;
	}
	.lds-dual-ring:after {
		content: " ";
		display: block;
		width: 20px;
		height: 20px;
		margin-left: 5px;
		border-radius: 50%;
		border: 2px solid #fff;
		border-color: #fff transparent #fff transparent;
		animation: lds-dual-ring 1.2s linear infinite;
	}
	@keyframes lds-dual-ring {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}
</style>
@endsection
@section('scripts')
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
<script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
<script>
	var cartelas = [];
	function preencheCase(i){
		i = parseInt(i) - 1;
		if( i < 0 )
			i = cartelas.length - 1;
		if( i >= cartelas.length )
			i = 0;
		$('.index_cartela').html( i + 1 );
		$('.numero_cartela').html( cartelas[i].bilhete );
		$('.round-case').empty();
		separador = cartelas[i].combinacoes.match(/[^\d]/g)[1];
		bolas = cartelas[i].combinacoes.split(separador);
		$(bolas).each(function(i,bola){
			$('.round-case').append('<div class="round">'+bola+'</div>');
		});
	}
	function limparDadosCartela(){
		$('.round-case .round').html('<br>');
		$('.index_cartela').html('');
		$('.count_cartela').html('');
		$('.numero_cartela').html('');
		$('.preco').html('');
		$('#key').val('');
		$('#gerar').attr('disabled', true);
		$('#finalizar').attr('disabled',true);
	}

	var timeouts = [];
	function countdown( element, minutes, callback ){
		element.find('div').removeClass('bg-danger');
		for (var i=0; i<timeouts.length; i++) {
			clearTimeout(timeouts[i]);
		}
		timeouts = [];

		seconds = 60*minutes;

		function progress( timeleft, timetotal, element, callback ){
		    var progressBarWidth = timeleft * element.width() / timetotal;
		    element.find('div').animate({ width: progressBarWidth }, 500, function(){
		    	if( timeleft == 0 )
		    	 	timeouts.push(setTimeout(function(){ callback(); }, 500));
		    });

		    percent = (timeleft/timetotal)*100;
		    if( percent <= 10 )
		    	element.find('div').addClass('bg-danger');

		    $('#timeleft').val(timeleft);

		    if( timeleft > 0 )
		        timeouts.push(setTimeout(function(){ progress(timeleft - 1, timetotal, element, callback); }, 1000));
		}

		progress(seconds, seconds, element, callback);
	}

	$(document).ready(function(){
		$('#gerar').click(function(e){
			e.preventDefault();

			limparDadosCartela();
			$('.loading').show();
			
			grecaptcha.ready(function(){
				grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {action: 'homepage'}).then(function(token) {
					
					$.get( base_url+"/prevenda", function(prevenda){
						$('.preco').html(prevenda.valor);
						$('#key').val(prevenda.key);
						cartelas = prevenda.cartelas;
						$('.count_cartela').html( cartelas.length );
						preencheCase(1);

						countdown( $('#countdown'), 10, function(){
							limparDadosCartela();
							swal({
								icon: 'warning',
								title: 'Seu tempo acabou :(',
								text: 'Esta cartela não está mais disponível',
								button: "Gerar Nova Cartela",
								closeOnClickOutside: false,
								closeOnEsc: false,
								closeModal: true,
							}).then((value) => {
								$('#gerar').click();
							});
						});

					}).always(function() {
						$('.loading').hide();
						$('#gerar').removeAttr('disabled');
						$('#finalizar').removeAttr('disabled');
					});


				});
			});

		});
		$('#gerar').click();
		$('.next').click(function(){ preencheCase( parseInt( $('.index_cartela').html() ) + 1 ); });
		$('.prev').click(function(){ preencheCase( parseInt( $('.index_cartela').html() ) - 1 ); });

		$("#finalizar").click(function(e){
			e.preventDefault();
			$('form').parsley().validate();
			if( $('form').parsley().isValid() ){
				grecaptcha.ready(function(){
					grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {action: 'homepage'}).then(function(token) {
						$('form').prepend('<input type="hidden" name="g_recaptcha_response" value="'+ token +'">');
						$('#finalizar').addClass('disabled').attr('disabled', true).append('<div class="lds-dual-ring"></div>');
						$('form').submit();
					});
				});
			}
		});

		function lead(){
			$.ajax({
				type: "POST",
				url: base_url + '/lead',
				data: {
					key: $('#key').val(),
					cpf: $('#cpf').val(),
					nome: $('#nome').val(),
					email: $('#email').val(),
					telefone: $('#telefone').val(),
					cidade: $('#cidade').val(),
					uf: $('#uf option:selected').val(),
				},
			});
		}
		$('#cpf').on('input',function(e){ lead() });
		$('#nome').on('input',function(e){ lead() });
		$('#email').on('input',function(e){ lead() });
		$('#telefone').on('input',function(e){ lead() });
		$('#cidade').on('input',function(e){ lead() });
		$('#uf').change(function(){ lead() });

	});
</script>
@endsection