<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($etapa))?'Editar':'Novo') }} etapa
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
								<input type="text" class="form-control" name="etapa" value="{{(isset($etapa) and $etapa->etapa)?$etapa->etapa:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Descrição</label>
								<input type="text" class="form-control" name="descricao" value="{{(isset($etapa) and $etapa->descricao)?$etapa->descricao:''}}">
							</div>
						</div>
						<div class="col-md-4 p-lr-o">
							<div class="form-group">
								<label for="">Data</label>
								<input type="date" class="form-control" name="data" value="{{(isset($etapa) and $etapa->data)?$etapa->data:''}}">
							</div>
						</div>
					</div><!-- /row -->
					<div class="row">
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">Range Inicial</label>
								<input type="text" class="form-control" name="range_inicial" value="{{(isset($range_inicial) and $etapa->range_inicial)?$etapa->range_inicial:''}}">
							</div>
						</div>
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">Range Final</label>
								<input type="text" class="form-control" name="range_final" value="{{(isset($range_final) and $etapa->range_final)?$etapa->range_final:''}}">
							</div>
						</div>
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">Tipo</label>
								<select name="tipo" class="form-control">
									<option value="">Selecione</option>
									<option value="1">Simples</option>
									<option value="2">Dupla</option>
									<option value="3">Tripla</option>
									<option value="4">Simples e Dupla</option>
									<option value="5">Simples e Tripla</option>
								</select>
							</div>
						</div>
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">Intervalo</label>
								<input type="text" class="form-control" name="intervalo" value="{{(isset($intervalo) and $etapa->intervalo)?$etapa->intervalo:''}}">
							</div>
						</div>
					</div><!-- /row -->
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label for="">valor Simples</label>
								<input type="text" class="form-control" name="valor_simples" value="{{(isset($valor_simples) and $etapa->valor_simples)?$etapa->valor_simples:''}}">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="">valor Duplo</label>
								<input type="text" class="form-control" name="valor_duplo" value="{{(isset($valor_duplo) and $etapa->valor_simples)?$etapa->valor_duplo:''}}">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="">valor Triplo</label>
								<input type="text" class="form-control" name="valor_triplo" value="{{(isset($valor_triplo) and $etapa->valor_triplo)?$etapa->valor_triplo:''}}">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="">valor comissão Simpl.</label>
								<input type="text" class="form-control" name="v_comissao_simples" value="{{(isset($v_comissao_simples) and $etapa->v_comissao_simples)?$etapa->v_comissao_simples:''}}">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="">valor comissão Dupla</label>
								<input type="text" class="form-control" name="v_comissao_dupla" value="{{(isset($v_comissao_dupla) and $etapa->v_comissao_dupla)?$etapa->v_comissao_dupla:''}}">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="">valor comissão Tripla</label>
								<input type="text" class="form-control" name="v_comissao_tripla" value="{{(isset($v_comissao_tripla) and $etapa->v_comissao_tripla)?$etapa->v_comissao_tripla:''}}">
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