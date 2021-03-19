<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($dispositivo))?'Editar':'Novo') }} dispositivo
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('dispositivos-listar') )
						<a href="<?php echo url('/dispositivos'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($dispositivo) ) 
						<form action="{{ url('/dispositivos/'.$dispositivo->id) }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/dispositivos') }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
					@endif
					@csrf					
					<div class="row">
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Nome</label>
								<input type="text" class="form-control" name="nome" value="{{(isset($dispositivo) and $dispositivo->nome)?$dispositivo->nome:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">code</label>
								<input type="text" class="form-control" name="code" value="{{(isset($dispositivo) and $dispositivo->code)?$dispositivo->code:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">mac</label>
								<input type="text" class="form-control" name="mac" value="{{(isset($dispositivo) and $dispositivo->mac)?$dispositivo->mac:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">ip</label>
								<input type="text" class="form-control" name="ip" value="{{(isset($dispositivo) and $dispositivo->ip)?$dispositivo->ip:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Ativo?</label><br>
								<input type="checkbox" name="ativo" 
								@if( isset( $dispositivo ) )
									{{ (($dispositivo->deleted_at)?'':'checked') }}
								@else
									checked
								@endif
								>
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Distribuidor</label>
								<select name="distribuidor_id" class="form-control select2">
									<option value=""></option>
									@forelse( $usuarios as $usuario )
									<option value="{{$usuario->id}}" 
										@if( isset($dispositivo) and $dispositivo->usuario_id == $usuario->id ) selected="selected" @endif
									>{{$usuario->name}}</option>
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