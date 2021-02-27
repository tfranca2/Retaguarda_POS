<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($cliente))?'Editar':'Novo') }} cliente
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('clientes-listar') )
						<a href="<?php echo url('/clientes'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($cliente) ) 
						<form action="{{ url('/clientes/'.$cliente->id) }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/clientes') }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
					@endif
					@csrf
					
					<div class="row">

						<div class="col-md-6 p-lr-o">

							<div class="col-sm-12 p-0">
								<div class="form-group">
									<label for="">Nome</label>
									<input type="text" class="form-control" name="nome" placeholder="Nome" required="" value="{{ (isset($cliente)?$cliente->nome:'') }}" >
								</div>
							</div>
							
							<div class="col-sm-12 p-0">
								<div class="form-group">
									<label for="">Email</label>
									<input type="text" class="form-control" name="email" placeholder="Email" required="" value="{{ (isset($cliente)?$cliente->email:'') }}" >
								</div>
							</div>

						</div>
						<div class="col-md-6 p-lr-o">
							
							<div class="col-sm-12 p-0">
								<div class="form-group">
									<label for="">Imagem</label>
									<input type="file" class="form-control" name="imagem">
									@if( isset($cliente) and $cliente->imagem )
									<img src="{{ url('/public/images/'.$cliente->imagem ) }}" >
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
								<input type="text" class="form-control" name="cpf" placeholder="CPF" value="{{ (isset($cliente)? Helper::formatCpfCnpj($cliente->cpf):'') }}" data-parsley-cpf="true" data-parsley-required="true" required="" >
							</div>
						</div>

						<div class="col-md-5 p-lr-o">
							<div class="form-group">
								<label for="">Telefone</label>
								<input type="text" class="form-control telefone" name="telefone" placeholder="Telefone" value="{{ (isset($cliente)?$cliente->telefone:'') }}" data-parsley-required="true" required="" >
							</div>
						</div>
	
						<div class="col-md-1 p-lr-o">
							<div class="form-group text-center">
								<label for="">Ativo?</label><br>
								<input type="checkbox" name="ativo" 
								@if( isset( $cliente ) )
									{{ (($cliente->deleted_at)?'':'checked') }}
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
								<input type="text" class="form-control" name="condominio" value="{{ ((isset($cliente))?$cliente->condominio:'') }}" placeholder="Condomínio" data-parsley-required="true" required="" >
							</div>
						</div>

						<div class="col-md-4 p-lr-o">
							<div class="form-group">
								<label for="">Bloco</label>
								<input type="text" class="form-control" name="bloco" value="{{ ((isset($cliente))?$cliente->bloco:'') }}" placeholder="Bloco" >
							</div>
						</div>

						<div class="col-md-4 p-lr-o">
							<div class="form-group">
								<label for="">Unidade</label>
								<input type="text" class="form-control" name="unidade" value="{{ ((isset($cliente))?$cliente->unidade:'') }}" placeholder="Unidade" data-parsley-required="true" required="" >
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">CEP</label>
								<input type="text" class="form-control" name="cep" id="cep" value="{{ ((isset($cliente))?$cliente->cep:'') }}" placeholder="CEP" data-parsley-required="true" required="" >
							</div>
						</div>
						<div class="col-md-8 p-lr-o">
							<div class="form-group">
								<label for="">Endereço</label>
								<input type="text" class="form-control" name="endereco" id="endereco" value="{{ ((isset($cliente))?$cliente->endereco:'') }}" placeholder="Endereço" data-parsley-required="true" required="" >
							</div>
						</div>

						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">Número</label>
								<input type="text" class="form-control" name="numero" id="numero" value="{{ ((isset($cliente))?$cliente->numero:'') }}" placeholder="Número" >
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Bairro</label>
								<input type="text" class="form-control" name="bairro" id="bairro" value="{{ ((isset($cliente))?$cliente->bairro:'') }}" placeholder="Bairro" data-parsley-required="true" required="" >
							</div>
						</div>

						<div class="col-md-4 p-lr-o">
							<div class="form-group">
								<label for="">Cidade</label>
								<input type="text" class="form-control" name="cidade" id="cidade" value="{{ ((isset($cliente))?$cliente->cidade:'') }}" placeholder="Cidade" data-parsley-required="true" required="" >
							</div>
						</div>
						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">Estado</label>
								<input type="text" class="form-control" name="estado" id="estado" value="{{ ((isset($cliente))?$cliente->estado:'') }}" placeholder="Estado" data-parsley-required="true" required="" >
							</div>
						</div>

						<input type="hidden" name="latitude" id="latitude" value="{{ ((isset($cliente))?$cliente->latitude:'') }}" >
						<input type="hidden" name="longitude" id="longitude" value="{{ ((isset($cliente))?$cliente->longitude:'') }}" >

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