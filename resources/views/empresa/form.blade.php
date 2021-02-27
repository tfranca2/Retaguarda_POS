<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($empresa))?'Editar':'Nova') }} empresa
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('empresas-listar') )
						<a href="<?php echo url('/empresas'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($empresa) ) 
						<form action="{{ url('/empresas/'.$empresa->id) }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/empresas') }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
					@endif
					@csrf
					
					<div class="row">
						
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Nome da Empresa</label>
								<input type="text" class="form-control" name="nome" placeholder="Nome" required="" value="{{ (isset($empresa)?$empresa->nome:'') }}" >
							</div>
						</div>

						<div class="col-md-4 p-lr-o">
							<div class="form-group">
								<label for="">CNPJ</label>
								<input type="text" class="form-control" name="cnpj" placeholder="CNPJ" value="{{ (isset($empresa)? Helper::formatCpfCnpj($empresa->cnpj):'') }}" data-parsley-cnpj="true" data-parsley-required="true" required="" >
							</div>
						</div>
						
						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">Ativo?</label><br>
								<input type="checkbox" name="ativo" 
								@if( isset( $empresa ) )
									{{ (($empresa->deleted_at)?'':'checked') }}
								@else
									checked
								@endif
								>
							</div>
						</div>

					</div>
					<div class="row">

	
						<div class="col-md-12 p-lr-o">
							<div class="form-group">
								<hr>
								<label for="">Aparência</label>
							</div>
						</div>

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Logo tela principal</label>
								@if( isset($empresa) )
								<img src="{{ url('/public/images/'.$empresa->main_logo ) }}" >
								@endif
								<input type="file" class="form-control" name="main_logo">
							</div>
						</div>

						

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Favicon</label>
								@if( isset($empresa) )
								<img src="{{ url('/public/images/'.$empresa->favicon ) }}" >
								@endif
								<input type="file" class="form-control" name="favicon">
							</div>
						</div>

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Logo Topo Menu</label>
								@if( isset($empresa) )
								<img src="{{ url('/public/images/'.$empresa->menu_logo ) }}" >
								@endif
								<input type="file" class="form-control" name="menu_logo">
							</div>
						</div>

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Logo Topo Menu contraído</label>
								@if( isset($empresa) )
								<img src="{{ url('/public/images/'.$empresa->contracted_menu_logo ) }}" >
								@endif
								<input type="file" class="form-control" name="contracted_menu_logo">
							</div>
						</div>

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Cor de Fundo do menu</label>
								<input type="color" class="form-control" name="menu_background" value="{{ (isset($empresa)?$empresa->menu_background:'') }}" required="" >
							</div>
						</div>

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Cor de texto do menu</label>
								<input type="color" class="form-control" name="menu_color" value="{{ (isset($empresa)?$empresa->menu_color:'') }}" required="" >
							</div>
						</div>

					</div>
					<div class="row">
							
						<div class="col-md-12 p-lr-o">
							<div class="form-group">
								<hr>
							</div>
						</div>

						<div class="col-md-12 p-lr-o">
							<div class="form-group">
								<label for="">Google Maps API Key</label>
								<input type="text" class="form-control" name="google_maps_api_key" placeholder="Google Maps API Key" value="{{ (isset($empresa)?$empresa->google_maps_api_key:'') }}">
							</div>
						</div>


						<div class="col-md-12 p-lr-o">
							<div class="form-group">
								<hr>
							</div>
						</div>

						<div class="col-md-12 p-lr-o">
							<div class="form-group">
								<label for="">FCM Server Key</label>
								<input type="text" class="form-control" name="fcm_server_key" placeholder="FCM Server Key" value="{{ (isset($empresa)?$empresa->fcm_server_key:'') }}">
							</div>
						</div>
					</div>
					<div class="row">
							
						<div class="col-md-12 p-lr-o">
							<div class="form-group">
								<hr>
								<label for="">Email</label>
							</div>
						</div>

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Mail Driver</label>
								<input type="text" class="form-control" name="mail_driver" placeholder="Mail Driver" value="{{ (isset($empresa)?$empresa->mail_driver:'') }}">
							</div>
						</div>

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Mail Host</label>
								<input type="text" class="form-control" name="mail_host" placeholder="Mail Host" value="{{ (isset($empresa)?$empresa->mail_host:'') }}">
							</div>
						</div>

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Mail Port</label>
								<input type="text" class="form-control" name="mail_port" placeholder="Mail Port" value="{{ (isset($empresa)?$empresa->mail_port:'') }}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Mail Username</label>
								<input type="text" class="form-control" name="mail_username" placeholder="Mail Username" value="{{ (isset($empresa)?$empresa->mail_username:'') }}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Mail Password</label>
								<input type="text" class="form-control" name="mail_password" placeholder="Mail Password" value="{{ (isset($empresa)?$empresa->mail_password:'') }}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Mail Encription</label>
								<input type="text" class="form-control" name="mail_encryption" placeholder="Mail Encription" value="{{ (isset($empresa)?$empresa->mail_encryption:'') }}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Mail From Name</label>
								<input type="text" class="form-control" name="mail_from_name" placeholder="Mail From Name" value="{{ (isset($empresa)?$empresa->mail_from_name:'') }}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Mail From Address</label>
								<input type="text" class="form-control" name="mail_from_address" placeholder="Mail From Address" value="{{ (isset($empresa)?$empresa->mail_from_address:'') }}">
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

<style>
	form img{
		background: rgb(250, 250, 250);
	}

	.pl-0 {
		padding-left: 0;
	}
	.pr-0 {
		padding-right: 0;
	}
</style>

<script>
	$(document).ready(function(){

		$('.desc').mask('000,00',{reverse: true});

	});
</script>
@endsection