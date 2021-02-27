<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				@if( Helper::temPermissao('faq-editar') )
				{{ ((isset($faq))?'Editar':'Novo') }}
				@else
				Ver
				@endif
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('faq-listar') )
						<a href="<?php echo url('/faq'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
				@if( Helper::temPermissao('faq-incluir') || Helper::temPermissao('faq-editar') )
				@if( isset($faq) ) 
				<form action="{{ url('/faq/'.$faq->id) }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
					@method('PUT') 
				@else
				<form action="{{ url('/faq') }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
				@endif
				@csrf
				@endif
					<div class="row">
						<div class="col-md-11">
							<div class="form-group">
								<label for="">Título</label>
								<input type="text" class="form-control" name="titulo" placeholder="Título" required="" value="{{ (isset($faq)?$faq->titulo:'') }}" >
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group text-center">
								<label for="">Ativo?</label><br>
								<input type="checkbox" name="ativo" 
								@if( isset( $faq ) )
									{{ (($faq->deleted_at)?'':'checked') }}
								@else
									checked
								@endif
								>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="">Descrição</label>
								<textarea class="form-control" name="descricao" placeholder="Descrição" id="" cols="30" rows="10">{{ (isset($faq)?$faq->descricao:'') }}</textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								@if( Helper::temPermissao('faq-editar') )
								<br><input type="submit" value="Salvar" class="btn btn-info pull-right">
								@endif
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>	
@endsection