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
					<input type="hidden" name="etapa_id" value="{{ $etapa_id }}" >				
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
							@if( $showQuantidade )
							<label>Quantidade</label>
							<input type="number" class="form-control" name="quantidade" min="1" max="{{ $showQuantidade }}" value="{{ (isset($venda)?$venda->quantidade:'1') }}" >
							@else
							<input type="hidden" name="quantidade" value="1">
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 p-lr-o">
							<br><hr><label>Cliente</label><br>
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
					@if( isset($venda) and isset($venda->matrizes) and $venda->matrizes )
			
					<div class="row">
						<div class="col-md-12 p-lr-o">
							<br><hr><label>Título(s)</label><br>
						</div>
						<div class="col-md-12 p-lr-o">
							<textarea class="form-control disabled" disabled="">@foreach( $venda->matrizes as $matriz )&bull; {{ $matriz->matriz->bilhete }}: {{ $matriz->matriz->combinacoes }}
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