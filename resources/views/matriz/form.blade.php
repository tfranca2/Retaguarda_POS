<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($matriz))?'Editar':'Novo') }} matriz
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('matrizes-listar') )
						<a href="<?php echo url('/matrizes'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($matriz) ) 
						<form action="{{ url('/matrizes/'.$matriz->id) }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/matrizes') }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
					@endif
					@csrf					
					<div class="row">
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Bilhete</label>
								<input type="text" class="form-control" name="bilhete" value="{{(isset($matriz) and $matriz->bilhete)?$matriz->bilhete:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Combinações</label>
								<input type="text" class="form-control" name="combinacoes" value="{{(isset($matriz) and $matriz->combinacoes)?$matriz->combinacoes:''}}">
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