<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				Contratação de serviço
			</div>
			<div class="panel-body">

				<form action="{{ url('/servicos/contrato') }}" method="post" class="form-edit" enctype="multipart/form-data" data-parsley-validate >
						@csrf
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="">Cliente</label>
								<select id="cliente_id" name="cliente_id" class="form-control select2" required="">
									<option value=""></option>
									@forelse( $clientes as $cliente )
									<option value="{{ $cliente->id }}">{{ $cliente->nome }} - {{ Helper::formatCpfCnpj( $cliente->cpf ) }}</option>
									@empty
									@endforelse
								</select>
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-2">
							<label for="">CEP</label>
							<input type="text" class="form-control" id="cep" name="cep" required="">
							<input type="hidden" id="latitude" name="latitude" value="">
							<input type="hidden" id="longitude" name="longitude" value="">
						</div>
						<div class="col-md-8">
							<label for="">Endereço</label>
							<input type="text" class="form-control" id="endereco" name="endereco" required="">
						</div>
						<div class="col-md-2">
							<label for="">Número</label>
							<input type="text" class="form-control" id="numero" name="numero" required="">
						</div>

					</div>
					<div class="row">

						<div class="col-md-5">
							<label for="">Bairro</label>
							<input type="text" class="form-control" id="bairro" name="bairro" required="">
						</div>
						<div class="col-md-5">
							<label for="">Cidade</label>
							<input type="text" class="form-control" id="cidade" name="cidade" required="">
						</div>
						<div class="col-md-2">
							<label for="">Estado</label>
							<input type="text" class="form-control" id="estado" name="estado" required="">
						</div>

					</div>
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<label for="">Serviço</label>
								<select name="servico_id" id="servico_id" class="form-control select2" required="">
									<option value=""></option>
									@forelse( $servicos as $servico )
									<option value="{{ $servico->id }}">{{ $servico->nome }}</option>
									@empty
									@endforelse
								</select>
							</div>
						</div>
						
					</div>
					<div class="row">

						<div id="campos"></div>

					</div>
					<div class="row">
						
						<!-- <div class="col-md-3">
							<div class="form-group">
								<label for="">Tipo do imóvel</label><br>
								<input type="radio" name="tipo_imovel" value="residencial" required=""> Residencial
								<input type="radio" name="tipo_imovel" value="comercial" required=""> Comercial
							</div>
						</div> -->

						<div class="col-md-8">
							<div class="form-group">
								<label for="">Prestador</label>
								<select name="prestador_id" id="prestadores" class="form-control select2">
									<option value=""></option>
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="">Observações</label>
								<textarea name="observacoes" rows="1" class="form-control"></textarea>
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-12">
							<div class="form-group recorrencia">
								<label for="">Recorrência do serviço</label><br>
								<div class="col">
									<input type="radio" name="recorrencia" required="" value="limpeza_avulsa"> Avulso
								</div>
								<div class="col">
									<input type="radio" name="recorrencia" required="" value="uma_x_por_quinzena"> 1 vez por Quinzena
								</div>
								<div class="col">
									<input type="radio" name="recorrencia" required="" value="uma_x_por_semana"> 1 vez por Semana
								</div>
								<div class="col">
									<input type="radio" name="recorrencia" required="" value="tres_x_por_semana"> 3 vezes por Semana
								</div>
								<div class="col">
									<input type="radio" name="recorrencia" required="" value="cinco_x_por_semana"> 5 vezes por Semana
								</div>
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-3">
							<div class="form-group">
								<label for="">Inicio da contratação</label>
								<div class="col-sm-7 p-0">
									<input type="date" id="datainicio" min="<?=date('Y-m-d',strtotime('+ 1 day'))?>" name="data[]" class="form-control" required="">
								</div>
								<div class="col-sm-5 p-0">
									<input type="time" id="horainicio" name="hora[]" class="form-control" required="">
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Fim da contratação</label>
								<div class="col-sm-7 p-0">
									<input type="date" id="datafima" class="form-control" disabled="">
									<input type="hidden" id="datafimb" name="data[]">
								</div>
								<div class="col-sm-5 p-0">
									<input type="time" id="horafima" class="form-control" disabled="">
									<input type="hidden" id="horafimb" name="hora[]" >
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label for="">Tamanho do apartamento</label>
								<input type="text" id="m2" class="form-control" disabled="">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Tempo de execução</label>
								<input type="text" id="tempo" class="form-control" disabled="">
							</div>
						</div>

					</div>
					<div class="row text-center">

						<div class="col-md-4 col-md-offset-4">
							<br>
							<a id="calcular" href="#" class="btn btn-info btn-block">Calcular valor do serviço</a> <br>
						</div>

					</div>
					<div class="row">

						<div class="col-md-4 col-md-offset-4 text-center">
							<div class="form-group">
								<label for="">Valor cobrado</label>
								<input type="text" id="valor" class="form-control text-center" disabled="">
								<input type="hidden" id="valorb" name="valor">
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<button type="submit" class="btn btn-info pull-right" disabled="">Salvar</button>
							</div>
						</div>

					</div>
				</form>

			</div>
		</div>
	</div>
</div>
<style>
	.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
		margin-top: 10px;
		margin-bottom: 10px;
	}

	.form-group {
		margin-bottom: 0;
	}

	.recorrencia .col {
		float: left;
		width: 20%;
		text-align: center;
	}

	@media (max-width: 768px) {
		.recorrencia .col {
			width: 100%;
			text-align: left;
		}
	}
</style>
<script>
	$(document).ready(function(){

		$('#cliente_id').change(function(){
			var id = $('#cliente_id option:selected').val();
			if( id ){
				$.get( "{{ url('/clientes') }}/"+id, function(e){
					$('#cep').val(e.cep);
					$('#endereco').val(e.endereco);
					$('#numero').val(e.numero);
					$('#bairro').val(e.bairro);
					$('#cidade').val(e.cidade);
					$('#estado').val(e.estado);
					$('#latitude').val(e.latitude);
					$('#longitude').val(e.longitude);
				});
			}
		});

		$('#servico_id').change(function(){
			var id = $('#servico_id option:selected').val();
			if( id ){

				$.get( "{{ url('/servicos') }}/"+id+"/carregarCampos", function(e){
					$('#campos').html(e);
				});

				$.ajax({
					method: 'GET',
					url: "{{ url('/api/prestador') }}?servico_id="+id,
					headers: {
						'Authorization':'Bearer {{ \Auth::user()->api_token }}'
					},
					success: function(e){
						$('#prestadores').empty();
						$('#prestadores').append('<option value=""></option>');
						if(e.total){
							$(e.data).each(function(i,obj){
								if( obj.usuario )
									$('#prestadores').append('<option value="'+ obj.id +'">'+ obj.usuario.name +' - '+ obj.usuario.cpf +'</option>');
							});
						}
					}
				});

			}
		});

		$('#valor').mask('0.000,00',{reverse: true});

		$("#calcular").click(function(e){
			e.preventDefault();

			id = $('#servico_id option:selected').val();

			if( id ){
				$.ajax({
					method: 'POST',
					url: "{{ url('/servicos') }}/"+id+"/valor",
					data: $('.form-edit').serialize(),
					success: function(e){
						
						$('#valor').val( e.value );
						$('#valorb').val( e.value );

 						$('#datafima').val( $('#datainicio').val() );
 						$('#datafimb').val( $('#datainicio').val() );
						var hora = $('#horainicio').val();
 						var resultado = moment(hora, 'hh:mm').add(e.run_time, 'minutes').format('hh:mm');
 						$('#horafima').val( resultado );
 						$('#horafimb').val( resultado );

 						$('#m2').val(e.m2 + ' M²' );
 						$('#tempo').val(e.run_time + ' Minutos' );

						$('button[type="submit"]').removeAttr('disabled');
					},
			        complete: function( data ){

			            console.log( data );

			            var errorString = '';
			            
			            if( typeof data.responseJSON.error === 'object' ){

			                errorString += '<ul>';
			                $.each( data.responseJSON.error, function( key, value ){
			                    errorString += '<li>'+ value +'</li>';
			                });
			                errorString += '</ul>';
			                toastr.error( errorString );

			            } else if( typeof data.responseJSON.error === 'string' ){

			                errorString = data.responseJSON.error;
			                toastr.error( errorString );

			            } else if( data.responseJSON.hasOwnProperty('exception') ){
			                errorString = 'Exception: ' + data.responseJSON.exception 
			                            + '<br><br>' + data.responseJSON.message 
			                            + '<br><br>' + data.responseJSON.file + ' on line: ' + data.responseJSON.line;
			                toastr.error( errorString );
			            }

			        }
				});
			} else
				toastr.error( 'Serviço é um campo obrigatório!' );

		});

	});
</script>
@endsection
