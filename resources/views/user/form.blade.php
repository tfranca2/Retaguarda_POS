<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($user))?'Editar':'Novo') }} usuário
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('usuarios-listar') )
						<a href="<?php echo url('/usuarios'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($user) ) 
						<form action="{{ url('/usuarios/'.$user->id) }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/usuarios') }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
					@endif
					@csrf
					
					<div class="row">

						<div class="col-md-6 p-lr-o">

							<div class="col-sm-12 p-0">
								<div class="form-group">
									<label for="">Nome</label>
									<input type="text" class="form-control" name="name" placeholder="Nome" required="" value="{{ (isset($user)?$user->name:'') }}" >
								</div>
							</div>
							
							<div class="col-sm-12 p-0">
								<div class="form-group">
									<label for="">Email</label>
									<input type="text" class="form-control" name="email" placeholder="Email" required="" value="{{ (isset($user)?$user->email:'') }}" >
								</div>
							</div>

						</div>
						<div class="col-md-6 p-lr-o">
							
							<div class="col-sm-12 p-0">
								<div class="form-group">
									<label for="">Imagem</label>
									<input type="file" class="form-control" name="imagem">
									@if( isset($user) and $user->imagem )
									<img src="{{ url('/public/images/'.$user->imagem ) }}" >
									@else
									<img src="{{ url('/public/images/avatar.png' ) }}" >
									@endif
								</div>
							</div>

						</div>



					</div>

					<div class="row">

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">CPF</label>
								<input type="text" class="form-control" name="cpf" placeholder="CPF" value="{{ (isset($user)? Helper::formatCpfCnpj($user->cpf):'') }}" data-parsley-cpf="true" data-parsley-required="true" required="" >
							</div>
						</div>
						@if( Helper::temPermissao('usuarios-gerenciar') and isset($user) and $user->id != Auth::user()->id )
						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">Ativo?</label><br>
								<input type="checkbox" name="ativo" 
								@if( isset( $user ) )
									{{ (($user->deleted_at)?'':'checked') }}
								@else
									checked
								@endif
								>
							</div>
						</div>
						@endif

					</div>
					<div class="row">
						
						@if( Helper::temPermissao('usuarios-gerenciar') )

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Perfil</label>
								<select name="perfil_id" id="perfil_id" class="form-control">
									@forelse( $perfis as $perfil )
									<option value="{{ $perfil->id }}" <?php if( isset($user) and $perfil->id == $user->perfil_id ) echo 'selected'; ?>>{{ $perfil->nome }}</option>
									@empty
									<option>Sem itens para listar</option>
									@endforelse
								</select>
							</div>
						</div>

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Empresa</label>
								<select name="empresa_id" id="empresa_id" class="form-control">
									@forelse( $empresas as $empresa )
									<option value="{{ $empresa->id }}" <?php if( isset($user) and $empresa->id == $user->empresa_id ) echo 'selected'; ?>>{{ $empresa->nome }}</option>
									@empty
									<option>Sem itens para listar</option>
									@endforelse
								</select>
							</div>
						</div>
						@else
						<input type="hidden" name="perfil_id" id="perfil_id" value="{{$user->perfil_id}}">
						<input type="hidden" name="empresa_id" id="empresa_id" value="{{$user->empresa_id}}">
						@endif

					</div>
					<div class="row">

						<div class="col-md-12 p-lr-o">
							<div class="form-group">
								<hr>
								<label for="">Segurança</label>
							</div>
						</div>

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Nova senha</label>
								<input type="password" class="form-control" id="password" name="password" placeholder="Nova senha" data-parsley-minlength="6" {{ ((isset($user))?'':'data-parsley-required="true" required=""') }} >
							</div>
						</div>

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Repita a senha</label>
								<input type="password" class="form-control" name="password_confirmation" placeholder="Nova senha" data-parsley-equalto="#password" {{ ((isset($user))?'':'data-parsley-required="true" required=""') }} >
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