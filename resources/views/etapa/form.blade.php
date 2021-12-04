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
						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">Nº Etapa</label>
								<input type="number" min="{{(isset($etapa) and $etapa->etapa)?$etapa->etapa:$prox_etapa}}" class="form-control" name="etapa" value="{{(isset($etapa) and $etapa->etapa)?$etapa->etapa:$prox_etapa}}" required="">
							</div>
						</div>
						<div class="col-md-5 p-lr-o">
							<div class="form-group">
								<label for="">Descrição</label>
								<input type="text" class="form-control" name="descricao" value="{{(isset($etapa) and $etapa->descricao)?$etapa->descricao:''}}" required="">
							</div>
						</div>
						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">Data</label>
								<input type="date" class="form-control" name="data" value="{{(isset($etapa) and $etapa->data)?$etapa->data:''}}" required="">
							</div>
						</div>
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">Código SUSEP</label>
								<input type="text" class="form-control" name="codigo_susep" value="{{(isset($etapa) and $etapa->codigo_susep)?$etapa->codigo_susep:$old_codigo_susep}}" required="">
							</div>
						</div>
					</div><!-- /row -->
					<div class="row">
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">Range Inicial</label>
								<input type="number" min="0" class="form-control" name="range_inicial" value="{{(isset($etapa) and $etapa->range_inicial)?$etapa->range_inicial:''}}">
							</div>
						</div>
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">Range Final</label>
								<input type="number" min="0" class="form-control" name="range_final" value="{{(isset($etapa) and $etapa->range_final)?$etapa->range_final:''}}">
							</div>
						</div>
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">Tipo</label>
								<select name="tipo" id="tipo" class="form-control" required="">
									<option value="">Selecione</option>
									<option value="1" @if( isset( $etapa ) and $etapa->tipo == 1 ) echo selected="selected" @endif >Simples</option>
									<option value="2" @if( isset( $etapa ) and $etapa->tipo == 2 ) echo selected="selected" @endif >Dupla</option>
									<option value="3" @if( isset( $etapa ) and $etapa->tipo == 3 ) echo selected="selected" @endif >Tripla</option>
									<option value="4" @if( isset( $etapa ) and $etapa->tipo == 4 ) echo selected="selected" @endif >Simples e Dupla</option>
									<option value="5" @if( isset( $etapa ) and $etapa->tipo == 5 ) echo selected="selected" @endif >Simples e Tripla</option>
								</select>
							</div>
						</div>
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">Intervalo</label>
								<input type="number" class="form-control" id="intervalo" name="intervalo" value="{{(isset($etapa) and $etapa->intervalo)?$etapa->intervalo:''}}">
							</div>
						</div>
					</div><!-- /row -->
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Valor Simples</label>
								<input type="text" class="form-control decimal" id="valor_simples" name="valor_simples" value="{{(isset($etapa) and $etapa->valor_simples)?\Helper::formatDecimalToView($etapa->valor_simples):''}}">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Valor Comissão Simples</label>
								<input type="text" class="form-control decimal" id="v_comissao_simples" name="v_comissao_simples" value="{{(isset($etapa) and $etapa->v_comissao_simples)?\Helper::formatDecimalToView($etapa->v_comissao_simples):''}}">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Valor Dupla</label>
								<input type="text" class="form-control decimal" id="valor_duplo" name="valor_duplo" value="{{(isset($etapa) and $etapa->valor_duplo)?\Helper::formatDecimalToView($etapa->valor_duplo):''}}">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Valor Comissão Dupla</label>
								<input type="text" class="form-control decimal" id="v_comissao_duplo" name="v_comissao_duplo" value="{{(isset($etapa) and $etapa->v_comissao_duplo)?\Helper::formatDecimalToView($etapa->v_comissao_duplo):''}}">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Valor Tripla</label>
								<input type="text" class="form-control decimal" id="valor_triplo" name="valor_triplo" value="{{(isset($etapa) and $etapa->valor_triplo)?\Helper::formatDecimalToView($etapa->valor_triplo):''}}">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Valor Comissão Tripla</label>
								<input type="text" class="form-control decimal" id="v_comissao_triplo" name="v_comissao_triplo" value="{{(isset($etapa) and $etapa->v_comissao_triplo)?\Helper::formatDecimalToView($etapa->v_comissao_triplo):''}}">
							</div>
						</div>
					</div><!-- /row -->
					<div class="row">
						<div class="col-md-12 p-lr-o">
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
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){

		function limpa(){

			$('#intervalo').removeAttr('required').parent().parent().hide();
			$('#valor_simples').removeAttr('required').parent().parent().hide();
			$('#valor_duplo').removeAttr('required').parent().parent().hide();
			$('#valor_triplo').removeAttr('required').parent().parent().hide();
			$('#v_comissao_simples').removeAttr('required').parent().parent().hide();
			$('#v_comissao_triplo').removeAttr('required').parent().parent().hide();
			$('#v_comissao_duplo').removeAttr('required').parent().parent().hide();

		}

		$("#tipo").on('change',function(){

			limpa();

			var tipo = $("#tipo option:selected").val();
			switch( tipo ){
				case "1": // simples
					$('#valor_simples').attr('required','true').parent().parent().show();
					$('#v_comissao_simples').attr('required','true').parent().parent().show();
				break;
			
				case "2": // dupla
					$('#valor_duplo').attr('required','true').parent().parent().show();
					$('#v_comissao_duplo').attr('required','true').parent().parent().show();
					$('#intervalo').attr('required','true').parent().parent().show();
				break;
			
				case "3": // tripla:
					$('#valor_triplo').attr('required','true').parent().parent().show();
					$('#v_comissao_triplo').attr('required','true').parent().parent().show();
					$('#intervalo').attr('required','true').parent().parent().show();
				break;

				case "4": // simples e dupla
					$('#valor_simples').attr('required','true').parent().parent().show();
					$('#v_comissao_simples').attr('required','true').parent().parent().show();
					$('#valor_duplo').attr('required','true').parent().parent().show();
					$('#v_comissao_duplo').attr('required','true').parent().parent().show();
					$('#intervalo').attr('required','true').parent().parent().show();
				break;

				case "5": // simples e tripla
					$('#valor_simples').attr('required','true').parent().parent().show();
					$('#v_comissao_simples').attr('required','true').parent().parent().show();
					$('#valor_triplo').attr('required','true').parent().parent().show();
					$('#v_comissao_triplo').attr('required','true').parent().parent().show();
					$('#intervalo').attr('required','true').parent().parent().show();
				break;
			}
		});

		$("#tipo").change();

	});
</script>
</script>
@endsection