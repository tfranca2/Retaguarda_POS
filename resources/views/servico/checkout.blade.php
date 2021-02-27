<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')

@if( $erros )
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-exclamation-circle"></i>&nbsp;Erros Ocorreram na Transação</h4>
        </div>
        <div class="modal-body">
        	<ul>
	        	@foreach( $erros as $erro )
	        	<li>{{ $erro }}</li>
	        	@endforeach
        	</ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        </div>
      </div>

    </div>
</div>
<!-- Button trigger modal -->
<button type="button" style="display: none;" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Launch demo modal</button>
<script>
	$('#myModal').on('shown.bs.modal', function () {
	  $('#myInput').trigger('focus')
	})
	$(window).on('load',function(){
        $('#myModal').modal('show');
    });
</script>
@endif

	<div class="row">
	    <div class="col-md-12">
	    	<div class="panel panel-card recent-activites">

	<div class="row">
	    <div class="col-md-12">
		    <br><br><br>
	        <form action="{{ url('/servicos/checkout') }}" method="post" class="" enctype="multipart/form-data" data-parsley-validate >
				@csrf
	        <input type="hidden" name="valor" value="{{ $valor }}" />
	        <input type="hidden" name="pedido_id" value="{{ $pedido_id }}" />
	        <input type="hidden" name="cliente_id" value="{{ $cliente->id }}" />
	        <div class="form-horizontal">
		        <div class="col-md-12">
		            <h5>Dados do pagamento</h5>
		            <hr>
		        </div>
				<div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Número do Cartão: </label>
	                    <div class="col-sm-4">
	                        <input data-validation="required" data-validation-error-msg="Número do cartão inválido." id="cartao" name="cartao" data-masked="" data-inputmask="'mask': '9999 9999 9999 9999'"  type="text" class="form-control" required>
	                    </div>
	                    <div class="col-sm-4">
	                    	<div id="cardimg"></div>
	                    </div>
	                </div>
	            </div>
				<div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Vencimento: </label>
	                    <div class="col-sm-1">
	                        <input data-validation="required" data-validation-error-msg="Mês inválido." id="mes"  name="mes" title="Mês" data-masked="" data-inputmask="'mask': '99'" type="text" class="form-control" required>
	                    </div>
	                    <div style="display: inline-block; float: left; padding-top: 3px; font-size: 14pt;">/</div>
	                    <div class="col-sm-2">
	                        <input data-validation="required" data-validation-error-msg="Ano inválido." id="ano" name="ano" title="Ano" data-masked="" data-inputmask="'mask': '9999'"  type="text" class="form-control" required>
	                    </div>
	                </div>
	            </div>
				<div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Código de Segurança: </label>
	                    <div class="col-sm-1">
	                        <input data-validation="required" data-validation-error-msg="Código de segurança inválido." data-masked="" data-inputmask="'mask': '999'" id="cvv" name="cvv" type="text" class="form-control" required>
	                    </div>
	                </div>
	            </div>
				<div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Total: </label>
	                    <div class="col-sm-2">
	                        <input disabled="disabled" type="text" value="{{ Helper::formatDecimalToView( $valor ) }}" class="form-control">
	                    </div>
	                </div>
	            </div>
                
                <input type="hidden" id="parcelamento" name="parcelamento" value="1" />
                <input type="hidden" id="parcelaValor" name="parcelaValor" value="{{ $valor }}" />
                <input type="hidden" id="plano" name="plano" value="0" />
                <center style="display: none;"><a href="#" id="parc" class="btn btn-info">PARCELAS</a></center>
				
				<input type="hidden" id="identificador" name="identificador" value="" />
				<input type="hidden" id="tokencartao" name="tokencartao" value="" />
		        <div class="col-md-12">
		            <h5>Confirme seus dados</h5>
		            <hr>
		        </div>
	            <div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Nome do Titular do Cartão: </label>
	                    <div class="col-sm-6">
	                        <input data-validation="required" data-validation-error-msg="Nome não válido." name="nome" value="{{ $cliente->nome }}" type="text" class="form-control" required>
	                    </div>
	                </div>
	            </div>
		        <div class="col-md-12">
		            <div class="form-group">
		                <label class="col-sm-4 control-label">Data de Nascimento: </label>
		                <div class="col-sm-6">
		                    <input name="data_nascimento" type="date" value="{{ $cliente->data_nascimento }}" class="form-control" required>
		                </div>
		            </div>
		        </div>
		        <div class="col-md-12">
		            <div class="form-group">
		                <label class="col-sm-4 control-label">CPF: </label>
		                <div class="col-sm-6">
		                    <input name="cpf" value="{{ $cliente->cpf }}" type="text" class="form-control" data-masked="" data-inputmask="'mask': '999.999.999-99'"  required data-validation="required" data-validation-error-msg="Insira um cpf.">
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

		        <div class="col-md-12">
		            <h5>Endereço</h5>
		            <hr>
		        </div>
		        <div class="col-md-12">
		            <div class="form-group">
		                <label class="col-sm-4 control-label">CEP: </label>
		                <div class="col-sm-6">
		                    <input id="cep" required data-validation="required" data-validation-error-msg="Insira um CEP." value="{{ $cliente->cep }}" name="cep" type="text" data-masked="" data-inputmask="'mask': '99999-999'" class="form-control">
		                </div>
		            </div>
		        </div>
		        <div class="col-md-12">
		            <div class="form-group">
		                <label class="col-sm-4 control-label">Endereço: </label>
		                <div class="col-sm-6">
		                    <input id="endereco" required data-validation="required" data-validation-error-msg="Insira um endereço." value="{{ $cliente->endereco }}" name="endereco" type="text" class="form-control">
		                </div>
		            </div>
		        </div>
		        <div class="col-md-12">
		            <div class="form-group">
		                <label class="col-sm-4 control-label">Número: </label>
		                <div class="col-sm-6">
		                    <input id="numero" required data-validation="required" data-validation-error-msg="Insira um número." value="{{ $cliente->numero }}" name="numero" type="text" class="form-control">
		                </div>
		            </div>
		        </div>
		        <div class="col-md-12">
		            <div class="form-group">
		                <label class="col-sm-4 control-label">Complemento: </label>
		                <div class="col-sm-6">
		                    <input id="complemento" value="{{ $cliente->complemento }}" name="complemento" type="text" class="form-control">
		                </div>
		            </div>
		        </div>
		        <div class="col-md-12">
		            <div class="form-group">
		                <label class="col-sm-4 control-label">Bairro: </label>
		                <div class="col-sm-6">
		                    <input id="bairro" required data-validation="required" data-validation-error-msg="Insira um Bairro válido." value="{{ $cliente->bairro }}" name="bairro" type="text" class="form-control">
		                </div>
		            </div>
		        </div>
		        <div class="col-md-12">
		            <div class="form-group">
		                <label class="col-sm-4 control-label">Estado: </label>
		                <div class="col-sm-6">
		                	<input type="text" id="uf" name="uf" value="{{ $cliente->estado }}" class="form-control">
		                </div>
		            </div>
		        </div>
		        <div class="col-md-12">
		            <div class="form-group">
		                <label class="col-sm-4 control-label">Cidade: </label>
		                <div class="col-sm-6">
		                	<input type="text" id="cidadenome" name="cidade" value="{{ $cliente->cidade }}" class="form-control" >
		                </div>
		            </div>
		        </div>

				<div class="col-md-12">
				    <div class="form-group">
				        <div class="col-sm-12">
				        	<br><br>
				        	<center>
				            <button type="submit" disabled="disabled" class="btn btn-primary btn-lg">PAGAR</button>
				        	</center>
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
<style>
	#cardimg {
		background-repeat: no-repeat;
		background-position: top center;
		background-size: contain;
		width: 50px;
		height: 50px;
		margin-top: -10px;
		position: absolute;
	}
</style>
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
	});
</script>
<script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		PagSeguroDirectPayment.setSessionId('{{ $sessionID }}');

		$('#parcelamento').change(function(){
			$('#parcelaValor').val( $('#parcelamento option:selected').data('valor') );
		});
		// $('#estado').change(function(){
		// 	$('#uf').val( $('#estado option:selected').html() );
		// });
		// $('#cidade').change(function(){
		// 	$('#cidadenome').val( $('#cidade option:selected').html() );
		// });

		$('#cvv').keyup(function(e){
			if( $(this).val().length == 3 )
				$('#parc').click();				
		});

		$('#parc').click(function(e){
			e.preventDefault();

			numb = $('#cartao').val().match(/\d/g);
			numb = numb.join("");

			// CARREGA DADOS DA BANDEIRA DO CARTAO
			PagSeguroDirectPayment.getBrand({
			    cardBin: numb,
			    success: function(response) { 
			    	bandeira = response.brand.name;

					// CARREGA O TOKEN DO CARTAO
					PagSeguroDirectPayment.createCardToken({
						cardNumber: numb,
					    brand: bandeira,
					    cvv: $('#cvv').val(),
					    expirationMonth: $('#mes').val(),
					    expirationYear: $('#ano').val(),
					    success: function(response) {

							identificador = PagSeguroDirectPayment.getSenderHash();
							$('#identificador').val( identificador );
 
					    	tokencartao = response.card.token;
					    	$('#tokencartao').val(tokencartao);

					    	@if( true )
					    	// CARREGA AS OPÇÕES DE PARCELAMENTO
					    	PagSeguroDirectPayment.getInstallments({
							    amount: {{ $valor }}, // valor total da compra
							    maxInstallmentNoInterest: {{ env('PAGSEGURO_NUM_PARCELAS_SEM_JUROS', '0') }}, // parcelas sem juros
							    brand: bandeira,
							    success: function(response) { 
							    	parcelas = response.installments[ bandeira ];
							    	$('#parcelamento option').remove();
							    	$.each( parcelas, function( i, parcela ){
										option = '<option value="'+ parcela.quantity +'" data-valor="'+ parcela.installmentAmount +'" >';
										option += parcela.quantity +' X '+ numberToReal( parcela.installmentAmount );
										if( parcela.interestFree ) option += ' - Sem Juros';
										option += '</option>';
							    		$('#parcelamento').append(option);
							    	});
							    	$('#parcelaValor').val( parcelas[0].installmentAmount );

							    	$('button[type="submit"]').removeAttr('disabled');
							    }
							});
							@else
							$('button[type="submit"]').removeAttr('disabled');
					    	@endif

					    }
					});
			    }
			});
		});


	});

	$(window).on('load', function () {
		$('#parc').click();
		// $('#uf').val( $('#estado option:selected').html() );
		// $('#cidadenome').val( $('#cidade option:selected').html() );
	});

	function numberToReal(numero) {
	    var numero = numero.toFixed(2).split('.');
	    numero[0] = "R$ " + numero[0].split(/(?=(?:...)*$)/).join('.');
	    return numero.join(',');
	}
</script>
@endsection