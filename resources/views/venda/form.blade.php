<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($venda))?'Editar':'Nova') }} venda
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
					<input type="hidden" name="etapa_id" value="{{ $etapa->id }}" >				
					<div class="row">
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Dispositivo</label>
								<select name="dispositivo_id" class="form-control select2" data-parsley-required="true" required="" >
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
							@if( !isset( $venda ) )
							<label>Tipo</label>
							<select name="quantidade" class="form-control select2" required="">
								@if( in_array( $etapa->tipo, [ 1, 4, 5 ] ) )
								<option value="1" {{ (( isset($venda) and $venda->quantidade == 1 )?'selected':'') }}>SIMPLES</option>
								@endif
								@if( in_array( $etapa->tipo, [ 2, 4 ] ) )
								<option value="2" {{ (( isset($venda) and $venda->quantidade == 2 )?'selected':'') }}>DUPLA</option>
								@endif
								@if( in_array( $etapa->tipo, [ 3, 5 ] ) )
								<option value="3" {{ (( isset($venda) and $venda->quantidade == 3 )?'selected':'') }}>TRIPLA</option>
								@endif
							</select>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 p-lr-o">
							<hr><label>Cliente</label><br>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 p-lr-o">
							<label>Nome Completo</label>
							<input type="text" class="form-control" name="nome" value="{{ (isset($venda)?$venda->nome:'') }}" data-parsley-required="true"  required="" >
						</div>
						<div class="col-md-4 p-lr-o">
							<label>CPF</label>
							<input type="text" class="form-control" name="cpf" value="{{ (isset($venda)?Helper::formatCpfCnpj($venda->cpf):'') }}" data-parsley-cpf="true" data-parsley-required="true" required="" >
						</div>
						<div class="col-md-4 p-lr-o">
							<label>Telefone</label>
							<input type="text" class="form-control" name="telefone" value="{{ (isset($venda)?$venda->telefone:'') }}" data-parsley-required="true"  required="" >
						</div>
					</div>
					<input type="hidden" name="confirmada" value="1">
					@if( isset($venda) and isset($venda->matrizes) and $venda->matrizes )
			
					<div class="row">
						<div class="col-md-12 p-lr-o">
							<hr><label>Título(s)</label><br>
						</div>
						<div class="col-md-12 p-lr-o">
							<textarea class="form-control disabled" rows="3" disabled="">@foreach( $venda->matrizes as $matriz )&bull; {{ $matriz->matriz->bilhete }}: {{ $matriz->matriz->combinacoes }}
@endforeach</textarea>
						</div>
					</div>
					@endif
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