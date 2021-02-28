<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($cidade))?'Editar':'Novo') }} cidade
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('cidades-listar') )
						<a href="<?php echo url('/cidades'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($cidade) ) 
						<form action="{{ url('/cidades/'.$cidade->id) }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/cidades') }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
					@endif
					@csrf					
					<div class="row">
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Nome</label>
								<input type="text" class="form-control" name="nome" value="{{(isset($cidade) and $cidade->nome)?$cidade->nome:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">População</label>
								<input type="text" class="form-control" name="populacao" value="{{(isset($cidade) and $cidade->populacao)?$cidade->populacao:''}}">
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