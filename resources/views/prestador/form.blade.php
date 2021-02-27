<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')

<div class="experiencia" style="display: none;">
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label for="">Tipo de serviço*</label>
				<select name="servico_id[]" class="form-control" required="">
					<option></option>
					@forelse( $servicos as $servico )
					<option value="{{ $servico->id }}">{{ $servico->nome }}</option>
					@empty
					<option>Sem itens para listar</option>
					@endforelse
				</select>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="">Tempo de experiência</label>
				<input type="number" class="form-control" name="tempoexp[]" >
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for=""><br></label>
				<select name="unidadetempoexp[]" class="form-control">
					<option value="meses">Meses</option>
					<option value="anos">Anos</option>
				</select>
			</div>
		</div>
		<div class="col-md-1">
			<div class="form-group">
				<label for=""><br></label><br>
				<a href="#" class="btn btn-danger remove-experiencia" title="Remover"><i class="fa fa-minus"></i></a>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($prestador))?'Editar':'Novo') }} prestador
				<div class="pull-right">
					<div class="btn-group">
						<a href="<?php echo url('/prestadores'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($prestador) ) 
						<form action="{{ url('/prestadores/'.$prestador->id) }}" method="post" class="form-edit" enctype="multipart/form-data" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/prestadores') }}" method="post" class="form-edit" enctype="multipart/form-data" data-parsley-validate> 
					@endif
					@csrf
					
					<div class="row">
						
						<div class="col-md-6 p-lr-o">

							<div class="col-sm-12 p-0">
								<div class="form-group">
									<label for="">Usuário*</label>
									<select name="usuario_id" id="usuario_id" class="form-control select2" required="">
										<option></option>
										@forelse( $usuarios as $usuario )
										<option value="{{ $usuario->id }}" <?php if( isset($prestador) and $usuario->id == $prestador->usuario_id ) echo 'selected'; ?>>{{ $usuario->name }}</option>
										@empty
										<option>Sem itens para listar</option>
										@endforelse
									</select>
								</div>
							</div>
							<div class="col-sm-12 p-0">
								<div class="form-group">
									<label for="">Email</label>
									<input type="text" class="form-control" disabled="" id="email" name="email" placeholder="Email" value="" >
								</div>
							</div>

						</div>
						<div class="col-md-6 p-lr-o">

							<div class="col-sm-10 p-0">
								<div class="form-group">
									<label for="">Imagem</label>
									<img id="avatar" src="{{ url('/public/images/avatar.png') }}">
								</div>
							</div>

							<div class="col-sm-2 p-0 text-center">
								<div class="form-group">
									<label for="">Ativo?</label><br>
									<input type="checkbox" name="ativo" 
									@if( isset( $prestador ) )
										{{ (($prestador->deleted_at)?'':'checked') }}
									@else
										checked
									@endif
									>
								</div>
							</div>

						</div>

					</div>
					<div class="row">

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">CPF</label>
								<input type="text" class="form-control" disabled="" id="cpf" name="cpf" placeholder="CPF" value="" >
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">RG*</label>
								<input type="text" class="form-control" required="" name="rg" id="rg" value="{{ ((isset($prestador))?$prestador->rg:'') }}" placeholder="RG" >
							</div>
						</div>
					
					</div>
					<div class="row">

						<div class="col-md-12 p-lr-o text-center">
							<br>
							<div class="form-group check">
								<input type="checkbox" name="checkantecedentes"
								@if( isset( $prestador ) )
									{{ (($prestador->checkantecedentes)?'checked':'') }}
								@endif
								>
								<label for="">Autorizo a checagem de meus antecedentes criminais</label>
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-12 p-lr-o">
							<div class="form-group">
								<hr>
								<label for="">Filiação</label>
							</div>
						</div>

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Mãe</label>
								<input type="text" class="form-control" name="mae" placeholder="Mãe" value="{{ ((isset($prestador))?$prestador->mae:'') }}" >
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Pai</label>
								<input type="text" class="form-control" name="pai" placeholder="Pai" value="{{ ((isset($prestador))?$prestador->pai:'') }}" >
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-12 p-lr-o">
							<div class="form-group">
								<hr>
								<label for="">Contato</label>
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">CEP</label>
								<input type="text" class="form-control" name="cep" id="cep" value="{{ ((isset($prestador))?$prestador->cep:'') }}" placeholder="CEP" required="" >
							</div>
						</div>
						<div class="col-md-8 p-lr-o">
							<div class="form-group">
								<label for="">Endereço</label>
								<input type="text" class="form-control" name="endereco" id="endereco" value="{{ ((isset($prestador))?$prestador->endereco:'') }}" placeholder="Endereço" required="" >
							</div>
						</div>

						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">Número</label>
								<input type="text" class="form-control" name="numero" id="numero" value="{{ ((isset($prestador))?$prestador->numero:'') }}" placeholder="Número" >
							</div>
						</div>

					</div>
					<div class="row">
					
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Bairro</label>
								<input type="text" class="form-control" name="bairro" id="bairro" value="{{ ((isset($prestador))?$prestador->bairro:'') }}" placeholder="Bairro" required="" >
							</div>
						</div>

						<div class="col-md-4 p-lr-o">
							<div class="form-group">
								<label for="">Cidade</label>
								<input type="text" class="form-control" name="cidade" id="cidade" value="{{ ((isset($prestador))?$prestador->cidade:'') }}" placeholder="Cidade" required="" >
							</div>
						</div>
						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">Estado</label>
								<input type="text" class="form-control" name="estado" id="estado" value="{{ ((isset($prestador))?$prestador->estado:'') }}" placeholder="Estado" required="" >
							</div>
						</div>
					
						<input type="hidden" id="latitude" name="latitude" value="{{ ((isset($prestador))?$prestador->latitude:'') }}">
						<input type="hidden" id="longitude" name="longitude" value="{{ ((isset($prestador))?$prestador->longitude:'') }}">

					</div>
					<div class="row">

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Telefone Celular*</label>
								<input type="text" class="form-control telefone" required="" name="celular" id="celular" value="{{ ((isset($prestador))?$prestador->celular:'') }}" placeholder="Telefone Celular" >
							</div>
						</div>

						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Telefone Fixo</label>
								<input type="text" class="form-control telefone" name="fixo" id="fixo" value="{{ ((isset($prestador))?$prestador->fixo:'') }}" placeholder="Telefone Fixo" >
							</div>
						</div>
					
					</div>
					<div class="row">

						<div class="col-md-12 p-lr-o">
							<hr>
							<div class="form-group">
								<label for="">Observações</label>
								<textarea class="form-control" name="observacoes" id="observacoes" cols="30" rows="3" placeholder="Observações" maxlength="255">{{ ((isset($prestador))?$prestador->observacoes:'') }}</textarea>
							</div>
						</div>

					</div>
					<div class="row">
						
						<div class="col-md-12">
							<hr>
							<div class="col-sm-4 p-0">
								<div class="form-group">
									<label for="">Serviços prestados e tempo de experiência</label>
								</div>
							</div>
							<div class="col-sm-1 p-0">
								<div class="form-group">
									<a href="#" class="btn btn-info" id="add-experiencia" title="Adicionar"><i class="fa fa-plus"></i></a>
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div id="experiencias">
								@if( isset($prestador) )
								@forelse( $prestador->servicos()->get() as $servicoprestado )
								@php
								// dd($servicoprestado);
									if( $servicoprestado->experiencia )
										$exp = explode( ' ', $servicoprestado->experiencia );
								@endphp
								<div class="experiencia">
									<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="">Tipo de serviço*</label>
											<select id="servico_id"  name="servico_id[]" class="form-control" required="">
												<option></option>
												@forelse( $servicos as $servico )
												<option value="{{ $servico->id }}" <?php 
													if( $servico->id == $servicoprestado->servico_id ) echo 'selected'; ?>>{{ $servico->nome }}</option>
												@empty
												<option>Sem itens para listar</option>
												@endforelse
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="">Tempo de experiência</label>
											<input type="number" class="form-control" name="tempoexp[]" value="{{ ((isset($exp))?$exp[0]:'') }}" >
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for=""><br></label>
											<select name="unidadetempoexp[]" class="form-control">
												<option value="meses" {{ (( isset($exp) and $exp[1] == 'meses' )?'selected':'' )}} >Meses</option>
												<option value="anos" {{ (( isset($exp) and  $exp[1] == 'anos' )?'selected':'' )}} >Anos</option>
											</select>
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<label for=""><br></label><br>
											<a href="#" class="btn btn-danger remove-experiencia" title="Remover"><i class="fa fa-minus"></i></a>
										</div>
									</div>
									</div>
								</div>
								@empty
								@endforelse
								@endif
							</div>
						</div>

					</div>
					<div class="row">
						
						<div class="col-md-12 p-lr-o">
							<hr>
							<div class="col-sm-4 p-0">
								<div class="form-group">
									<label for="">Dados bancários</label>
								</div>
							</div>
							<div class="col-sm-1 p-0">
								<div class="form-group">
									<a href="#" class="btn btn-info" id="add-banco" title="Adicionar"><i class="fa fa-plus"></i></a>
								</div>
							</div>
						</div>

					</div>
					<div class="row">

						<div class="banco" style="display: none;">
							<div class="col-md-3 p-lr-o">
								<div class="form-group">
									<label for="">Banco</label>
									<input type="text" class="form-control" name="banco[]" placeholder="Banco" >
								</div>
							</div>
							<div class="col-md-3 p-lr-o">
								<div class="form-group">
									<label for="">Agência</label>
									<input type="text" class="form-control" name="agencia[]" placeholder="Agência" >
								</div>
							</div>
							<div class="col-md-3 p-lr-o">
								<div class="form-group">
									<label for="">Conta</label>
									<input type="text" class="form-control" name="conta[]" placeholder="Conta" >
								</div>
							</div>
							<div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label for="">Operação</label>
									<input type="text" class="form-control" name="operacao[]" placeholder="Operação" >
								</div>
							</div>
							<div class="col-md-1 p-lr-o">
								<div class="form-group">
									<label for=""><br></label><br>
									<a href="#" class="btn btn-danger remove-banco" title="Remover"><i class="fa fa-minus"></i></a>
								</div>
							</div>
						</div>

						<div id="bancos">
							@if( isset( $prestador ) )
							@foreach( $prestador->bancos()->get() as $banco )
								<div class="banco">
									<div class="col-md-3 p-lr-o">
										<div class="form-group">
											<label for="">Banco</label>
											<input type="text" class="form-control" name="banco[]" value="{{ $banco->banco }}" placeholder="Banco" >
										</div>
									</div>
									<div class="col-md-3 p-lr-o">
										<div class="form-group">
											<label for="">Agência</label>
											<input type="text" class="form-control" name="agencia[]" value="{{ $banco->agencia }}" placeholder="Agência" >
										</div>
									</div>
									<div class="col-md-3 p-lr-o">
										<div class="form-group">
											<label for="">Conta</label>
											<input type="text" class="form-control" name="conta[]" value="{{ $banco->conta }}" placeholder="Conta" >
										</div>
									</div>
									<div class="col-md-2 p-lr-o">
										<div class="form-group">
											<label for="">Operação</label>
											<input type="text" class="form-control" name="operacao[]" value="{{ $banco->operacao }}" placeholder="Operação" >
										</div>
									</div>
									<div class="col-md-1 p-lr-o">
										<div class="form-group">
											<label for=""><br></label><br>
											<a href="#" class="btn btn-danger remove-banco" title="Remover"><i class="fa fa-minus"></i></a>
										</div>
									</div>
								</div>
							@endforeach
							@endif
						</div>

					</div>
					<div class="row">
						
						<div class="col-md-12 p-lr-o">
							<hr>
							<div class="col-sm-4 p-0">
								<div class="form-group">
									<label for="">Referências comerciais</label>
								</div>
							</div>
							<div class="col-sm-1 p-0">
								<div class="form-group">
									<a href="#" class="btn btn-info" id="add-referencia" title="Adicionar"><i class="fa fa-plus"></i></a>
								</div>
							</div>
						</div>

					</div>
					<div class="row">

						<div class="referencia" style="display: none;">

							<div class="col-md-3 p-lr-o">
								<div class="form-group">
									<label for="">Empresa</label>
									<input type="text" class="form-control" name="empresa[]" placeholder="Empresa">
								</div>
							</div>
							<div class="col-md-3 p-lr-o">
								<div class="form-group">
									<label for="">Nome de Contato</label>
									<input type="text" class="form-control" name="contato[]" placeholder="Nome de Contato">
								</div>
							</div>
							<div class="col-md-3 p-lr-o">
								<div class="form-group">
									<label for="">Email</label>
									<input type="email" class="form-control" name="email[]" placeholder="Email">
								</div>
							</div>
							<div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label for="">Telefone</label>
									<input type="text" class="form-control telefone" name="telefone[]" placeholder="Telefone">
								</div>
							</div>
							<div class="col-md-1 p-lr-o">
								<div class="form-group">
									<label for=""><br></label><br>
									<a href="#" class="btn btn-danger remove-referencia" title="Remover"><i class="fa fa-minus"></i></a>
								</div>
							</div>

						</div>

						<div id="referencias">
							@if( isset( $prestador ) )
							@foreach( $prestador->referencias()->get() as $referencia )
								<div class="referencia">
									<div class="col-md-3 p-lr-o">
										<div class="form-group">
											<label for="">Empresa</label>
											<input type="text" class="form-control" name="empresa[]" value="{{ $referencia->empresa }}" placeholder="Empresa">
										</div>
									</div>
									<div class="col-md-3 p-lr-o">
										<div class="form-group">
											<label for="">Nome de Contato</label>
											<input type="text" class="form-control" name="contato[]" value="{{ $referencia->contato }}" placeholder="Nome de Contato">
										</div>
									</div>
									<div class="col-md-3 p-lr-o">
										<div class="form-group">
											<label for="">Email</label>
											<input type="email" class="form-control" name="email[]" value="{{ $referencia->email }}" placeholder="Email">
										</div>
									</div>
									<div class="col-md-2 p-lr-o">
										<div class="form-group">
											<label for="">Telefone</label>
											<input type="text" class="form-control telefone" name="telefone[]" value="{{ $referencia->telefone }}" placeholder="Telefone">
										</div>
									</div>
									<div class="col-md-1 p-lr-o">
										<div class="form-group">
											<label for=""><br></label><br>
											<a href="#" class="btn btn-danger remove-referencia" title="Remover"><i class="fa fa-minus"></i></a>
										</div>
									</div>
								</div>
							@endforeach
							@endif
						</div>

					</div>
					<div class="row">
						
						<div class="col-md-12 p-lr-o">
							<hr>
							<div class="col-sm-4 p-0">
								<div class="form-group">
									<label for="">Fotos dos documentos</label>
								</div>
							</div>
							<div class="col-sm-1 p-0">
								<div class="form-group">
									<a href="#" class="btn btn-info" id="add-documento" title="Adicionar"><i class="fa fa-plus"></i></a>
								</div>
							</div>
						</div>

					</div>
					<div class="row">
						
						<div id="documentos">
							@if( isset( $prestador ) )
							@foreach( $prestador->documentos()->get() as $documento )
								<div class="documento">
									<input type="hidden" name="documento_id[]" value="{{ $documento->id }}">
									<div class="col-md-4 p-lr-o">
										<div class="form-group">
											<label for="">Nome do documento</label>
											<input type="text" class="form-control" name="documento[]" value="{{ $documento->documento }}" placeholder="Nome do documento">
										</div>
									</div>
									<div class="col-md-4 p-lr-o">
										<div class="form-group">
											<label for="">Numeração</label>
											<input type="text" class="form-control" name="numeracao[]" value="{{ $documento->numeracao }}" placeholder="Numeração">
										</div>
									</div>
									<div class="col-md-3 p-lr-o">
										<div class="form-group">
											<label for="">Foto do Documento</label>
											@if( $documento->foto )
											<a href="{{ url( '/public/images/'.$documento->foto ) }}" data-lightbox="documentos" >
												<img src="{{ url( '/public/images/'.$documento->foto ) }}">
											</a>
											<input type="hidden" name="foto[]" value="{{ $documento->foto }}">
											@else
											<input type="file" name="foto[]" class="form-control">
											@endif
										</div>
									</div>
									<div class="col-md-1 p-lr-o">
										<div class="form-group">
											<label for=""><br></label><br>
											<a href="#" class="btn btn-danger remove-documento" title="Remover"><i class="fa fa-minus"></i></a>
										</div>
									</div>
								</div>
							@endforeach
							@endif
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
	.form-group img {
		max-width: 80px;
	}

	.check {
		display: flex;
	}
	.check input[type="checkbox"] {
		margin: 0 3px;
		margin-top: -3px;
	}
</style>
<script>
	$(document).ready(function(){
	@if( ! isset( $prestador ) )
		$("#add-experiencia").click();
		$("#add-banco").click();
		$("#add-referencia").click();
		$("#add-documento").click();
	@else
		$("#usuario_id").change();
	@endif
	});
</script>
@endsection