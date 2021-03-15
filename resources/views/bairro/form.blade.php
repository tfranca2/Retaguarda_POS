<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($bairro))?'Editar':'Novo') }} bairro
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('bairros-listar') )
						<a href="<?php echo url('/bairros'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($bairro) ) 
						<form action="{{ url('/bairros/'.$bairro->id) }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/bairros') }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
					@endif
					@csrf					
					<div class="row">
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Nome</label>
								<input type="text" class="form-control" name="nome" value="{{(isset($bairro) and $bairro->nome)?$bairro->nome:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Cidade</label>
								<select name="cidade_id" class="form-control select2CidadeAjax">
									<option value="">Selecione</option>
									@forelse( $cidades as $cidade )
										@if( isset($bairro) and $bairro->cidade_id == $cidade->id ) 
										@php
										$estado = $cidade->estado()->first();
										@endphp
										<option value="{{$cidade->id}}"selected="selected">{{$cidade->nome}} @if( $estado ) {{ '- '. $estado->uf }} @endif</option> 
										@endif
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