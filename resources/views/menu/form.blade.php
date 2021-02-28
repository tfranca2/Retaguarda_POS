<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($menu))?'Editar':'Novo') }} menu
				<div class="pull-right">
					<div class="btn-group">
						<a href="<?php echo url('/menus'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($menu) ) 
						<form action="{{ url('/menus/'.$menu->id) }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/menus') }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
					@endif
					@csrf					
					<div class="row">
						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">Icon</label>
								<input type="text" class="form-control" name="icon" value="{{(isset($menu) and $menu->icon)?$menu->icon:''}}">
							</div>
						</div>
						<div class="col-md-4 p-lr-o">
							<div class="form-group">
								<label for="">Label</label>
								<input type="text" class="form-control" name="label" value="{{(isset($menu) and $menu->label)?$menu->label:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Link</label>
								<input type="text" class="form-control" name="link" value="{{(isset($menu) and $menu->link)?$menu->link:''}}">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Permission</label>
								<input type="text" class="form-control" name="permission" value="{{(isset($menu) and $menu->permission)?$menu->permission:''}}">
							</div>
						</div>
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">Parent</label>
								<select name="parent" class="form-control select2">
									<option value=""></option>
									@forelse( $menus as $m )
									<option value="{{$m->id}}" 
										@if( isset($menu) and $menu->parent == $m->id ) selected="selected" @endif
									>{{$m->label}}</option>
									@empty
									@endforelse
								</select>
							</div>
						</div>
						<div class="col-md-3 p-lr-o">
							<label for="">Ordem</label>
							<input type="text" class="form-control" name="ordem" value="{{(isset($menu))?$menu->ordem:''}}">
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