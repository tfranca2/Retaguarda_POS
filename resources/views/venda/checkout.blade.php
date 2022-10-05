<?php use App\Helpers; ?>
@guest
    @php
        $empresa = \DB::table('empresa')->first();
    @endphp
@else
    @php
        $empresa = \Auth::user()->empresa();
    @endphp
@endguest
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $empresa->nome }}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- <link rel="shortcut icon" type="image/png" href="<?php echo url('/'); ?>/assets/imgs/favicon.png" /> -->
        <link rel="shortcut icon" type="image/png" href="{{ url('/public/images/'. $empresa->favicon ) }}" />

        <!-- inject:css -->
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/weather-icons/css/weather-icons.min.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/themify-icons/css/themify-icons.css">
        <!-- endinject -->

        <!--Data Table-->
        <link href="<?php echo url('/'); ?>/assets/bower_components/datatables/media/css/jquery.dataTables.css" rel="stylesheet">
        <link href="<?php echo url('/'); ?>/assets/bower_components/datatables-tabletools/css/dataTables.tableTools.css" rel="stylesheet">
        <link href="<?php echo url('/'); ?>/assets/bower_components/datatables-colvis/css/dataTables.colVis.css" rel="stylesheet">
        <link href="<?php echo url('/'); ?>/assets/bower_components/datatables-responsive/css/responsive.dataTables.scss" rel="stylesheet">
        <link href="<?php echo url('/'); ?>/assets/bower_components/datatables-scroller/css/scroller.dataTables.scss" rel="stylesheet">
        <!-- <link href="<?php echo url('/'); ?>/assets/bower_components/jqBootstrapValidation.js" rel="stylesheet"> -->

        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/switchery/dist/switchery.min.css">

        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/dist/css/main.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/dist/css/lightbox.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/dist/css/jquery-ui.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/switchery/dist/switchery.min.css">

        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/css/toastr.min.css">
        
        <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

        <script src="<?php echo url('/'); ?>/assets/js/modernizr-custom.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/jquery-3.3.1.min.js"></script>
        <!-- <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script> -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>

        <link href="https://fonts.googleapis.com/css2?family=Exo:wght@300;600&display=swap" rel="stylesheet"> 
        
        <style>

            * {
                font-family: 'Exo', sans-serif;
            }

            .lb-data .lb-close {
              background: url('{{ url('/').'/assets/imgs/close.png' }}') no-repeat center;
            }
            #aside {
                background: #e2262e;
                color: #fff;
            }
        </style>
    </head>
    <body style="background: #fff; max-width: 100%; overflow-x: hidden;">

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

	<div class="row" style="margin: 0px;">
	    <div class="col-md-12">
	    	<div class="panel panel-card recent-activites" style="margin-bottom: 0px;padding-bottom: 20px;">

	<div class="row">
	    <div class="col-md-12">
		    <br>
	        <form id="check" action="{{ url('/servicos/checkout') }}" method="post" class="" enctype="multipart/form-data" data-parsley-validate >
				@csrf
				<?php
					$v = $valor;
					if( $gorjeta )
						$v += Helper::formatDecimalToDb( $gorjeta );
				?>
	        <input type="hidden" name="valor" value="<?php echo $v; ?>" />
	        <input type="hidden" name="pedido_id" value="{{ $pedido_id }}" />
	        <input type="hidden" name="cliente_id" value="{{ $cliente->id }}" />
	        <div class="form-horizontal">
		        <div class="col-md-12">
		            <h4>Dados do pagamento</h4>
		            <hr>
		        </div>
				<div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Número do Cartão: </label>
	                    <div class="col-sm-6">
	                        <input data-validation="required" data-validation-error-msg="Número do cartão inválido." id="cartao" name="cartao" data-masked="" data-inputmask="'mask': '9999 9999 9999 9999'"  type="text" class="form-control" placeholder="Número do Cartão" required>
	                    </div>
	                    <div class="col-sm-1">
	                    	<div id="cardimg"></div>
	                    </div>
	                </div>
	            </div>
				<div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label" style="display: block;">Vencimento: </label>
	                    <div class="col-sm-6">
	                    <div style="width: 35%; display: inline-block;">
							<input data-validation="required" data-validation-error-msg="Mês inválido." id="mes"  name="mes" title="Mês" data-masked="" data-inputmask="'mask': '99'" type="text" class="form-control" placeholder="Mês" required>
						</div>
						<div style="width: 7%; display: inline-block; padding-top: 3px; font-size: 14pt; text-align: center;">/</div>
						<div style="width: 55%; display: inline-block;">
							<input data-validation="required" data-validation-error-msg="Ano inválido." id="ano" name="ano" title="Ano" data-masked="" data-inputmask="'mask': '9999'"  type="text" class="form-control" placeholder="Ano" required>
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
	            @if( $pedido_id )
				<div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Valor: </label>
	                    <div class="col-sm-6">
	                        <input disabled="disabled" type="text" value="{{ Helper::formatDecimalToView( $valor ) }}" class="form-control">
	                    </div>
	                </div>
	            </div>
				<div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Gorjeta: </label>
	                    <div class="col-sm-6">
	                        <input type="text" id="gorjeta" name="gorjeta" value="{{ ($gorjeta)?Helper::formatDecimalToView( $gorjeta ):'' }}" maxlength="8" class="form-control decimal" placeholder="0,00">
	                    </div>
	                </div>
	            </div>
	            @endif
				<div class="col-md-12">
	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Total: </label>
	                    <div class="col-sm-6">
	                        <input disabled="disabled" type="text" id="total" value="{{ Helper::formatDecimalToView( $valor + Helper::formatDecimalToDb( $gorjeta ) ) }}" class="form-control">
	                    </div>
	                </div>
	            </div>
                
                <input type="hidden" id="parcelamento" name="parcelamento" value="1" />
                <input type="hidden" id="parcelaValor" name="parcelaValor" value="{{ $valor }}" />
                <input type="hidden" id="plano" name="plano" value="0" />
                <center style="display: none;"><a href="#" id="parc" class="btn btn-info">PARCELAS</a></center>
				
				<input type="hidden" id="identificador" name="identificador" value="" />
				<input type="hidden" id="tokencartao" name="tokencartao" value="" />

				<div class="col-md-12" <?php echo ((env('PAGSEGURO_COMPRADOR_EMAIL'))?'':'style="display: none;"'); ?> >
					<div class="form-group" >
						<label class="col-sm-4 control-label">Sender Hash: </label>
						<div class="col-sm-6">
							<input type="text" class="form-control" disabled="disabled" id="identificadorcp" value="" >
						</div>
					</div>
				</div>
				<div class="col-md-12" <?php echo ((env('PAGSEGURO_COMPRADOR_EMAIL'))?'':'style="display: none;"'); ?> >
					<div class="form-group" >
						<label class="col-sm-4 control-label">Token Cartão: </label>
						<div class="col-sm-6">
							<input type="text" class="form-control" disabled="disabled" id="tokencartaocp" value="" >
						</div>
					</div>
				</div>

		        <div class="col-md-12">
		        	<br>
		            <h4>Confirme seus dados</h4>
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

		        <div class="col-md-12">
		        	<br>
		            <h4>Endereço</h4>
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
		                    <input id="numero" required data-validation="required" data-validation-error-msg="Insira um número." value="{{ $cliente->numero }}" name="numero" type="text" class="form-control" required="required">
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
					<br>
					<input type="checkbox" checked="checked" required="required" data-validation="required" name="termodeuso" value="1" > Estou ciente e aceito os <a href="https://prosecurity.com.br/termo-de-uso-helpty" style="color: <?php echo $empresa->menu_background?>;"><b>termos de uso</b></a> do aplicativo.
					<br><br>
		        </div>

				<div class="col-md-12">
				    <div class="form-group">
				        <div class="col-sm-4 col-md-offset-4">
				        	<br><button type="submit" disabled="disabled" class="btn btn-primary btn-lg btn-block disabled" style="color: <?php echo $empresa->menu_color?>;background: <?php echo $empresa->menu_background?>; border-color: <?php echo $empresa->menu_background?>;">PAGAR</button>
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
<script type="text/javascript" src="https://stc.<?php echo ((env('PAGSEGURO_COMPRADOR_EMAIL'))?'sandbox.':''); ?>pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		PagSeguroDirectPayment.setSessionId('<?php echo $sessionID; ?>');

		$('#parcelamento').change(function(){
			$('#parcelaValor').val( $('#parcelamento option:selected').data('valor') );
		});

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
							$('#identificadorcp').val( identificador );
					    	tokencartao = response.card.token;
					    	$('#tokencartao').val(tokencartao);
					    	$('#tokencartaocp').val(tokencartao);
					    	$('button[type="submit"]').removeAttr('disabled');
					    	$('button[type="submit"]').removeClass('disabled');
					    }
					});
			    }
			});
		});

		$('#gorjeta').keyup(function(e){
			gorjeta = $('#gorjeta').val().replaceAll('.','').replace(',','.');
			if( !gorjeta ) gorjeta = 0;
			total = parseFloat( $('input[name=parcelaValor]').val() ) + parseFloat(gorjeta);
			$('#total').val( total.toFixed(2) );
			$('input[name=valor]').val( total.toFixed(2) );
		});

		$('form#check').submit(function(){
		    $(this).find('button[type=submit]').prop('disabled','disabled');
		    $(this).find('button[type=submit]').addClass('disabled');
		});

	});

	function numberToReal(numero) {
	    var numero = numero.toFixed(2).split('.');
	    numero[0] = "R$ " + numero[0].split(/(?=(?:...)*$)/).join('.');
	    return numero.join(',');
	}
</script>

 <script src="{{asset('assets/bower_components/jquery/dist/jquery.min.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>
        <!-- inject:js -->
        <script src="<?php echo url('/'); ?>/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/jquery.nicescroll/dist/jquery.nicescroll.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/autosize/dist/autosize.min.js"></script>
        <!-- endinject -->

        <!--Data Table-->
        <script src="<?php echo url('/'); ?>/assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/datatables-tabletools/js/dataTables.tableTools.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/datatables-colvis/js/dataTables.colVis.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/datatables-responsive/js/dataTables.responsive.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/datatables-scroller/js/dataTables.scroller.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/init-datatables.js"></script>
        <script src="<?php echo url('/'); ?>/assets/dist/js/main.js"></script>
        <script src="<?php echo url('/'); ?>/assets/dist/js/lightbox.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/validator.js"></script>
        <!-- <script src="<?php echo url('/'); ?>/assets/bower_components/switchery/dist/switchery.min.js"></script> -->
        <!-- <script src="<?php echo url('/'); ?>/assets/js/init-switchery.js"></script> -->
        <script src="<?php echo url('/'); ?>/assets/bower_components/input-mask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/bootstrap-filestyle/bootstrap-filestyle.js"></script>
        <link rel="stylesheet" href="{{asset('assets/bower_components/fullcalendar/dist/fullcalendar.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/bower_components/fullcalendar/dist/fullcalendar.print.min.css')}}" media="print">

        <script src="<?php echo url('/'); ?>/assets/dist/js/custom.js"></script>
        <script src="<?php echo url('/'); ?>/assets/dist/js/ajax.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/Chart.js"></script>
        <!-- <script src="<?php echo url('/'); ?>/assets/js/init-chartjs.js"></script> -->


        <script src="{{asset('assets/bower_components/jquery/dist/jquery.min.js')}}"></script>
        <script src="{{asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
        <!-- endinject -->
        <script src="{{asset('assets/js/validator.js')}}"></script>
        <script src="{{asset('assets/dist/js/site.js')}}"></script>


        <script type="text/javascript" src="{{asset('assets/bower_components/datetimepicker/bootstrap-datetimepicker.js')}}"></script>
        <script type="text/javascript" src="{{asset('assets/bower_components/datetimepicker/bootstrap-datetimepicker.pt-BR.js')}}"></script>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>
        <script src="{{asset('assets/bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
        <script src="{{asset('assets/bower_components/autosize/dist/autosize.min.js')}}"></script>
        <script src="{{asset('assets/bower_components/fullcalendar/dist/fullcalendar.js')}}"></script>
        <script type="text/javascript" src="{{asset('assets/bower_components/fullcalendar/dist/locale/pt-br.js')}}"></script>
         <!-- <script src="<?php echo url('/'); ?>/assets/bower_components/switchery/dist/switchery.min.js"></script> -->
        <!-- <script src="{{asset('assets/js/init-calendar.js')}}"></script> -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.6/angular.min.js"></script> -->
         <!-- <script type="text/javascript" src="{{asset('assets/js/custom-advanced-form.js')}}"></script> -->

         <!-- <script src="<?php echo url('/'); ?>/assets/dist/js/custom.js"></script> -->
         <script src="<?php echo url('/'); ?>/assets/dist/js/ajax.js"></script>

        <script src="{{asset('assets/js/init-calendar.js')}}"></script>

        <!-- <script src="{{asset('assets/js/init-datepicker.js')}}"></script> -->

        <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.7.0/dist/sweetalert2.all.min.js"></script> -->
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/parsley.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/parsley.pt-br.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/jquery.mask.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/toastr.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/tagsinput.js"></script>
        <link href="<?php echo url('/'); ?>/assets/css/tagsinput.css" rel="stylesheet" />

        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

        <script>
            var base_url = "{{ url('/') }}";
            var google_maps_api_key = "{{ $empresa->google_maps_api_key }}";
        </script>

        <script src="{{asset('assets/js/custom.js')}}"></script>

    </body>
</html>
