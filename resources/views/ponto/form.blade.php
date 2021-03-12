<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($ponto))?'Editar':'Novo') }} ponto
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('pontos-listar') )
						<a href="<?php echo url('/pontos'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($ponto) ) 
						<form action="{{ url('/pontos/'.$ponto->id) }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/pontos') }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
					@endif
					@csrf					
					<div class="row">
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Nome</label>
								<input type="text" class="form-control" name="nome" value="{{(isset($ponto) and $ponto->nome)?$ponto->nome:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Responsável</label>
								<input type="text" class="form-control" name="responsavel" value="{{(isset($ponto) and $ponto->responsavel)?$ponto->responsavel:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Endereço</label>
								<input type="text" class="form-control" name="endereco" value="{{(isset($ponto) and $ponto->endereco)?$ponto->endereco:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Telefone</label>
								<input type="text" class="form-control" name="telefone" value="{{(isset($ponto) and $ponto->telefone)?$ponto->telefone:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Telefone 2</label>
								<input type="text" class="form-control" name="telefone2" value="{{(isset($ponto) and $ponto->telefone2)?$ponto->telefone2:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">CPF/CNPJ</label>
								<input type="text" class="form-control cpf" name="cpf_cnpj" value="{{(isset($ponto) and $ponto->cpf_cnpj)?$ponto->cpf_cnpj:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">RG</label>
								<input type="text" class="form-control" name="rg" value="{{(isset($ponto) and $ponto->rg)?$ponto->rg:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Funcionamento</label>
								<input type="text" class="form-control" name="funcionamento" value="{{(isset($ponto) and $ponto->funcionamento)?$ponto->funcionamento:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Encerramento</label>
								<input type="hour" class="form-control" name="encerramento" value="{{(isset($ponto) and $ponto->encerramento)?$ponto->encerramento:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Ponto de referência</label>
								<input type="text" class="form-control" name="ponto_referencia" value="{{(isset($ponto) and $ponto->ponto_referencia)?$ponto->ponto_referencia:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Observação</label>
								<textarea name="observacao"class="form-control" cols="30" rows="8">{{(isset($ponto) and $ponto->observacao)?$ponto->observacao:''}}</textarea>
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Distribuidor</label>
								<select name="distribuidor_id" class="form-control select2">
									<option value=""></option>
									@forelse( $distribuidores as $distribuidor )
									<option value="{{$distribuidor->id}}" 
										@if( isset($ponto) and $ponto->distribuidor_id == $distribuidor->id ) selected="selected" @endif
									>{{$distribuidor->nome}}</option>
									@empty
									@endforelse
								</select>
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Cidade</label>
								<select name="cidade_id" class="form-control select2">
									<option value=""></option>
									@forelse( $cidades as $cidade )
									<option value="{{$cidade->id}}" 
										@if( isset($ponto) and $ponto->cidade_id == $cidade->id ) selected="selected" @endif
									>{{$cidade->nome}}</option>
									@empty
									@endforelse
								</select>
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Bairro</label>
								<select name="bairro_id" class="form-control select2">
									<option value=""></option>
									@forelse( $bairros as $bairro )
									<option value="{{$bairro->id}}" 
										@if( isset($ponto) and $ponto->bairro_id == $cidade->id ) selected="selected" @endif
									>{{$bairro->nome}}</option>
									@empty
									@endforelse
								</select>
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