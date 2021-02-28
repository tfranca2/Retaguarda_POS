<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($venda))?'Editar':'Novo') }} venda
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('vendas-listar') )
						<a href="<?php echo url('/vendas'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($venda) ) 
						<form action="{{ url('/vendas/'.$venda->id) }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/vendas') }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
					@endif
					@csrf					
					<div class="row">
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Dispositivo</label>
								<select name="dispositivo_id" class="form-control select2">
									<option value=""></option>
									@forelse( $dispositivos as $dispositivo )
									<option value="{{$dispositivo->id}}" 
										@if( isset($venda) and $venda->dispositivo_id == $dispositivo->id ) selected="selected" @endif
									>{{$dispositivo->nome}}</option>
									@empty
									@endforelse
								</select>
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Etapa</label>
								<select name="etapa_id" class="form-control select2">
									<option value=""></option>
									@forelse( $etapas as $etapa )
									<option value="{{$etapa->id}}" 
										@if( isset($venda) and $venda->etapa_id == $etapa->id ) selected="selected" @endif
									>{{$etapa->descricao}}</option>
									@empty
									@endforelse
								</select>
							</div>
						</div>
					</div>
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