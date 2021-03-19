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
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">CPF</label>
								<input type="text" class="form-control" name="cpf" placeholder="CPF" value="{{ (isset($user)? Helper::formatCpfCnpj($user->cpf):'') }}" data-parsley-cpf="true" data-parsley-required="true" required="" >
							</div>
						</div>
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">RG</label>
								<input type="text" class="form-control" name="rg" placeholder="RG" value="{{ (isset($distribuidor)?$distribuidor->rg:'') }}">
							</div>
						</div>
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">Área</label>
								<input type="text" class="form-control" name="area" placeholder="Área" value="{{ (isset($distribuidor)?$distribuidor->area:'') }}" data-parsley-required="true" required="" >
							</div>
						</div>
						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">Telefone</label>
								<input type="text" class="form-control telefone" name="telefone" placeholder="Telefone" value="{{ (isset($distribuidor)?$distribuidor->telefone:'') }}" data-parsley-required="true" required="" >
							</div>
						</div>
						@if( Helper::temPermissao('usuarios-gerenciar') and isset($user) and $user->id != Auth::user()->id )
						<div class="col-md-1 p-lr-o">
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

						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">CEP</label>
								<input type="text" class="form-control" name="cep" id="cep" value="{{ ((isset($distribuidor))?$distribuidor->cep:'') }}" placeholder="CEP" data-parsley-required="true" required="" >
							</div>
						</div>
						<div class="col-md-8 p-lr-o">
							<div class="form-group">
								<label for="">Endereço</label>
								<input type="text" class="form-control" name="endereco" id="endereco" value="{{ ((isset($distribuidor))?$distribuidor->endereco:'') }}" placeholder="Endereço" data-parsley-required="true" required="" >
							</div>
						</div>

						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">Número</label>
								<input type="text" class="form-control" name="numero" id="numero" value="{{ ((isset($distribuidor))?$distribuidor->numero:'') }}" placeholder="Número" >
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Bairro</label>
								<input type="text" class="form-control" name="bairro" id="bairro" value="{{ ((isset($distribuidor))?$distribuidor->bairro:'') }}" placeholder="Bairro" data-parsley-required="true" required="" >
							</div>
						</div>

						<div class="col-md-4 p-lr-o">
							<div class="form-group">
								<label for="">Cidade</label>
								<input type="text" class="form-control" name="cidade" id="cidade" value="{{ ((isset($distribuidor))?$distribuidor->cidade:'') }}" placeholder="Cidade" data-parsley-required="true" required="" >
							</div>
						</div>
						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">Estado</label>
								<input type="text" class="form-control" name="estado" id="estado" value="{{ ((isset($distribuidor))?$distribuidor->estado:'') }}" placeholder="Estado" data-parsley-required="true" required="" >
							</div>
						</div>

						<input type="hidden" name="latitude" id="latitude" value="{{ ((isset($distribuidor))?$distribuidor->latitude:'') }}" >
						<input type="hidden" name="longitude" id="longitude" value="{{ ((isset($distribuidor))?$distribuidor->longitude:'') }}" >

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