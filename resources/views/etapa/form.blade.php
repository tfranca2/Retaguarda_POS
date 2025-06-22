<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($etapa))?'Editar':'Nova') }} etapa
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('etapas-listar') )
						<a href="<?php echo url('/etapas'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($etapa) ) 
						<form action="{{ url('/etapas/'.$etapa->id) }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/etapas') }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
					@endif
					@csrf					
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label for="">Nº Etapa</label>
								<input type="number" min="{{(isset($etapa) and $etapa->etapa)?$etapa->etapa:$prox_etapa}}" class="form-control" name="etapa" value="{{(isset($etapa) and $etapa->etapa)?$etapa->etapa:$prox_etapa}}" required="">
							</div>
						</div>
						<div class="col-md-10">
							<div class="form-group">
								<label for="">Descrição</label>
								<input type="text" class="form-control" name="descricao" value="{{(isset($etapa) and $etapa->descricao)?$etapa->descricao:''}}" required="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="">Data Sorteio</label>
								<input type="date" class="form-control" name="data" value="{{(isset($etapa) and $etapa->data)?$etapa->data:''}}" required="">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="">Frequência</label>
								<select name="frequencia" id="frequencia" class="form-control" required="">
									<option value="semanal" @if( isset( $etapa ) and $etapa->frequencia == 'semanal' ) echo selected="selected" @endif >Semanal</option>
									<option value="mensal" @if( isset( $etapa ) and $etapa->frequencia == 'mensal' ) echo selected="selected" @endif >Mensal</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="">Código SUSEP</label>
								<input type="text" class="form-control" name="codigo_susep" value="{{(isset($etapa) and $etapa->codigo_susep)?$etapa->codigo_susep:$old_codigo_susep}}" required="">
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-xs-4">
						</div>
						<div class="col-xs-4 text-center">
							<label>Ranges</label>
						</div>
						<div class="col-xs-4 text-right">
							<button class="btn btn-success" id="add-range" title="Adicionar range"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<br>
					<div id="ranges"></div>
					<hr>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<br><input type="submit" value="Salvar" class="btn btn-info pull-right">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="copy-range" style="display: none;">
	<div class="row range">

		<div class="col-xs-10 col-sm-10 col-md-2">
			<div class="form-group">
				<label for="">Chance</label>
				<select name="tipo[]" class="form-control" required="">
					<option value="">Selecione</option>
					@foreach( $tipos as $key => $tipo )
					<option value="{{ $key }}">{{ $tipo['quantidade'] .' - '. ucwords( strtolower( $tipo['descricao'] ) ) }}</option>
					@endforeach
				</select>
				<input type="hidden" name="chances[]">
			</div>
		</div>
		<div class="col-xs-1 col-sm-2 text-right visible-xs visible-sm">
			<br>
			<div class="btn btn-danger remove-range" title="Remover range"><i class="fa fa-trash"></i></div>
			<br><br>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label for="">Inicio</label>
				<input type="number" min="1" class="form-control" name="inicio[]" required="">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label for="">Final</label>
				<input type="number" min="1" class="form-control" name="final[]" required="">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label for="">Intervalo</label>
				<input type="number" class="form-control" name="intervalo[]" min="0" required="">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label for="">Valor</label>
				<input type="text" class="form-control decimal" name="valor[]" required="">
			</div>
		</div>
		<div class="col-md-1 pr-md-0">
			<div class="form-group">
				<label for="">Comissão</label>
				<input type="text" class="form-control decimal" name="comissao[]" required="">
			</div>
		</div>
		<div class="col-xs-1 text-right hidden-xs hidden-sm">
			<br>
			<div class="btn btn-danger remove-range" title="Remover range"><i class="fa fa-trash"></i></div>
		</div>

	</div>
</div>
@endsection
@section('scripts')
<style>
	.range {
		border: 1px solid #ccc;
		border-radius: 5px;
		margin-bottom: 5px;
		padding-top: 5px;
	}
	@media (min-width: 992px) {
		.pr-md-0 {
			padding-right: 0;
		}
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){

		$('#add-range').click(function(e){
			e.preventDefault();
			$('#ranges').append( $('#copy-range').html() );
		});

		@if( isset($etapa) )
		function preencher(){
			@forelse( $etapa->ranges as $range )

				$('#add-range').click();

				row = $('#ranges .range').last();
				$(row).find('select[name="tipo[]"]').val({{ $range->chances }}).change();
				$(row).find('input[name="inicio[]"]').val({{ $range->inicio }}).keyup();
				$(row).find('input[name="final[]"]').val({{ $range->final }});
				$(row).find('input[name="intervalo[]"]').val({{ $range->intervalo }});
				$(row).find('input[name="valor[]"]').val('{{ \Helper::formatDecimalToView($range->valor) }}');
				$(row).find('input[name="comissao[]"]').val('{{ \Helper::formatDecimalToView($range->comissao) }}');
			@empty
				$('#add-range').click();
			@endforelse
		}
		preencher();
		@else
		if( $('#ranges .range').length == 0 )
			$('#add-range').click();
		@endif

	});

	$(document).on('click', '.remove-range', function(){
		$(this).closest('.range').remove();

		if( $('#ranges .range').length == 0 )
			$('#add-range').click();
	});

	$(document).on('change', 'select[name="tipo[]"]', function(){
		$(this).parent().find('input[name="chances[]"]').val( $(this).val() );

		intervalo = $(this).parent().parent().parent().find('input[name="intervalo[]"]');
		$(intervalo).attr('required', true).removeClass('disabled');
		if( $(this).val() == 1 )
			$(intervalo).removeAttr('required').addClass('disabled').val(0);
	});

	$(document).on('keyup', 'input[name="inicio[]"]', function(){
		$(this).parent().parent().parent().find('input[name="final[]"]').attr('min', parseInt( $(this).val() ) + 1 );
	});
</script>
</script>
@endsection