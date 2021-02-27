<?php 
	use App\Helpers; 

	$cliente = $contratacao->cliente()->first();
	$servico = $contratacao->servico()->first();

	$campos = array();
	foreach( json_decode( $contratacao->campos ) as $campo ){
		$campos[$campo->nome] = $campo->valor;
	}

?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				Editar de serviço
			</div>
			<div class="panel-body">

				<form action="{{ url('contratacao/'.$contratacao->id) }}" method="post" class="form-edit" enctype="multipart/form-data" data-parsley-validate >
						@csrf
						@method('PUT')
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="">Cliente</label>
								<input type="text" value="{{ $cliente->nome }}" class="form-control" readonly="readonly">
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-2">
							<label for="">CEP</label>
							<input type="text" value="{{ ((isset($campos['cep']))?$campos['cep']:'') }}" class="form-control" readonly="readonly">
						</div>
						<div class="col-md-8">
							<label for="">Endereço</label>
							<input type="text" value="{{ ((isset($campos['endereco']))?$campos['endereco']:'') }}" class="form-control" readonly="readonly">
						</div>
						<div class="col-md-2">
							<label for="">Número</label>
							<input type="text" value="{{ ((isset($campos['numero']))?$campos['numero']:'') }}" class="form-control" readonly="readonly">
						</div>

					</div>
					<div class="row">

						<div class="col-md-5">
							<label for="">Bairro</label>
							<input type="text" value="{{ ((isset($campos['bairro']))?$campos['bairro']:'') }}" class="form-control" readonly="readonly">
						</div>
						<div class="col-md-5">
							<label for="">Cidade</label>
							<input type="text" value="{{ ((isset($campos['cidade']))?$campos['cidade']:'') }}" class="form-control" readonly="readonly">
						</div>
						<div class="col-md-2">
							<label for="">Estado</label>
							<input type="text" value="{{ ((isset($campos['estado']))?$campos['estado']:'') }}" class="form-control" readonly="readonly">
						</div>

					</div>
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<label for="">Serviço</label>
								<input type="text" value="{{ $servico->nome }}" class="form-control" readonly="readonly">
							</div>
						</div>
						
					</div>
					<div class="row">

						<div id="campos">
						@forelse( $campos as $key => $value )
							@if( !in_array( $key, ['cep', 'endereco', 'numero', 'bairro', 'cidade', 'estado', 'recorrencia', 'latitude', 'longitude']) )
							<div class="col-md-4">
								<div class="form-group">
									<label>{{ $key }}</label>
									<input type="text" value="{{ $campos[$key] }}" class="form-control" readonly="readonly">
								</div>
							</div>
							@endif
						@empty
						@endforelse
						</div>

					</div>
					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="">Tipo do imóvel</label>
								<input type="text" value="{{ ucfirst( $contratacao->tipo_imovel ) }}" class="form-control" readonly="readonly">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group recorrencia">
								<label for="">Recorrência do serviço</label>
								<input type="text" value="{{ ((isset($campos['recorrencia']))?$campos['recorrencia']:'') }}" class="form-control" readonly="readonly">
							</div>
						</div>

					</div>
					<div class="row">
						
						<div class="col-md-6">
							<div class="form-group">
								<label for="">Prestador</label>
								<select name="prestador_id" id="prestadores" class="form-control select2">
									<option value=""></option>
									@forelse( $prestadores as $prestador )
									@php
										$p = $prestador->usuario()->first();
									@endphp
									<option value="{{ $prestador->id }}" {{ (( $prestador->id == $contratacao->prestador_id )?'selected=selected':'') }} >{{ $p->name .' - '. $p->cpf }}</option>
									@empty
									@endforelse
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="">Observações</label>
								<textarea rows="1" class="form-control" readonly="">{{ $contratacao->observacoes }}</textarea>
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-3">
							<div class="form-group">
								<label for="">Inicio da contratação</label>
								<div class="col-sm-7 p-0">
									<input type="date" name="data[]" value="{{ date( 'Y-m-d', strtotime( $contratacao->inicio ) ) }}" min="<?=date('Y-m-d',strtotime('+ 1 day'))?>" class="form-control">
								</div>
								<div class="col-sm-5 p-0">
									<input type="text" name="hora[]" value="{{ date( 'H:i', strtotime( $contratacao->inicio ) ) }}" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Fim da contratação</label>
								<div class="col-sm-7 p-0">
									<input type="date" name="data[]" value="{{ date( 'Y-m-d', strtotime( $contratacao->fim ) ) }}" min="<?=date('Y-m-d',strtotime('+ 1 day'))?>" class="form-control">
								</div>
								<div class="col-sm-5 p-0">
									<input type="text" name="hora[]" value="{{ date( 'H:i', strtotime( $contratacao->fim ) ) }}" class="form-control">
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="">Valor cobrado</label>
								<input type="text" value="R$ {{ Helper::formatDecimalToView($contratacao->valor) }}" class="form-control" readonly="readonly">
							</div>
						</div>
						
					</div>
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<button type="submit" class="btn btn-info pull-right">Salvar</button>
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
</style>
@endsection
