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
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Descrição</label>
								<input type="text" class="form-control" name="descricao" value="{{(isset($etapa) and $etapa->descricao)?$etapa->descricao:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Data</label>
								<input type="date" class="form-control" name="data" value="{{(isset($etapa) and $etapa->data)?$etapa->data:''}}">
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