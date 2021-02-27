<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($distribuidor))?'Editar':'Novo') }} distribuidor
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('distribuidores-listar') )
						<a href="<?php echo url('/distribuidores'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($distribuidor) ) 
						<form action="{{ url('/distribuidores/'.$distribuidor->id) }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/distribuidores') }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
					@endif
					@csrf
					
					<div class="row">

						<div class="col-md-6 p-lr-o">

							<div class="col-sm-12 p-0">
								<div class="form-group">
									<label for="">Nome</label>
									<input type="text" class="form-control" name="nome" placeholder="Nome" required="" value="{{ (isset($distribuidor)?$distribuidor->nome:'') }}" >
								</div>
							</div>
							
							<div class="col-sm-12 p-0">
								<div class="form-group">
									<label for="">Email</label>
									<input type="text" class="form-control" name="email" placeholder="Email" required="" value="{{ (isset($distribuidor)?$distribuidor->email:'') }}" >
								</div>
							</div>

						</div>
						<div class="col-md-6 p-lr-o">
							
							<div class="col-sm-12 p-0">
								<div class="form-group">
									<label for="">Imagem</label>
									<input type="file" class="form-control" name="imagem">
									@if( isset($distribuidor) and $distribuidor->imagem )
									<img src="{{ url('/public/images/'.$distribuidor->imagem ) }}" >
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
								<input type="text" class="form-control" name="cpf" placeholder="CPF" value="{{ (isset($distribuidor)? Helper::formatCpfCnpj($distribuidor->cpf):'') }}" data-parsley-cpf="true" data-parsley-required="true" required="" >
							</div>
						</div>

						<div class="col-md-5 p-lr-o">
							<div class="form-group">
								<label for="">Telefone</label>
								<input type="text" class="form-control telefone" name="telefone" placeholder="Telefone" value="{{ (isset($distribuidor)?$distribuidor->telefone:'') }}" data-parsley-required="true" required="" >
							</div>
						</div>
	
						<div class="col-md-1 p-lr-o">
							<div class="form-group text-center">
								<label for="">Ativo?</label><br>
								<input type="checkbox" name="ativo" 
								@if( isset( $distribuidor ) )
									{{ (($distribuidor->deleted_at)?'':'checked') }}
								@else
									checked
								@endif
								>
							</div>
						</div>

					</div>
					<div class="row">
						
						<div class="col-md-4 p-lr-o">
							<div class="form-group">
								<label for="">Condomínio</label>
								<input type="text" class="form-control" name="condominio" value="{{ ((isset($distribuidor))?$distribuidor->condominio:'') }}" placeholder="Condomínio" data-parsley-required="true" required="" >
							</div>
						</div>

						<div class="col-md-4 p-lr-o">
							<div class="form-group">
								<label for="">Bloco</label>
								<input type="text" class="form-control" name="bloco" value="{{ ((isset($distribuidor))?$distribuidor->bloco:'') }}" placeholder="Bloco" >
							</div>
						</div>

						<div class="col-md-4 p-lr-o">
							<div class="form-group">
								<label for="">Unidade</label>
								<input type="text" class="form-control" name="unidade" value="{{ ((isset($distribuidor))?$distribuidor->unidade:'') }}" placeholder="Unidade" data-parsley-required="true" required="" >
							</div>
						</div>

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