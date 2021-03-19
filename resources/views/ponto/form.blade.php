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
								<input type="text" class="form-control telefone" name="telefone" value="{{(isset($ponto) and $ponto->telefone)?$ponto->telefone:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Telefone 2</label>
								<input type="text" class="form-control telefone" name="telefone2" value="{{(isset($ponto) and $ponto->telefone2)?$ponto->telefone2:''}}">
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
								<input type="time" class="form-control" name="encerramento" value="{{(isset($ponto) and $ponto->encerramento)?$ponto->encerramento:''}}">
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
								<textarea name="observacao" class="form-control" cols="30" rows="8">{{(isset($ponto) and $ponto->observacao)?$ponto->observacao:''}}</textarea>
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Distribuidor</label>
								@if( \Helper::temPermissao('cidades-gerenciar') )
								<select name="distribuidor_id" class="form-control select2">
									<option value=""></option>
									@forelse( $usuarios as $usuario )
									<option value="{{$usuario->id}}" 
										@if( isset($ponto) and $ponto->usuario_id == $usuario->id ) selected="selected" @endif
									>{{$usuario->name}}</option>
									@empty
									@endforelse
								</select>
								@else
								<input type="text" value="{{\Auth::user()->name}}" class="form-control disabled" disabled="disabled">
								<input type="hidden" name="distribuidor_id" value="{{\Auth::user()->id}}">
								@endif
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Cidade</label>
								<select name="cidade_id" id="cidade_id" class="form-control select2CidadeAjax">
									<option value="">Selecione</option>
									@forelse( $cidades as $cidade )
										@if( isset($ponto) and $ponto->cidade_id == $cidade->id ) 
										@php
										$estado = $cidade->estado()->first();
										@endphp
										<option value="{{$cidade->id}}"selected="selected">{{$cidade->nome}} @if( $estado ) {{ '- '. $estado->uf }} @endif</option> 
										@endif
									@empty
									@endforelse
								</select>
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							@if( Helper::temPermissao('bairros-incluir') )
							<div class="form-group pull-left" style="width: 90%; padding-right: 10px;">
							@else
							<div class="form-group">
							@endif
								<label for="">Bairro</label>
								<input type="hidden" id="_bairro_id" value="{{(isset($ponto) and $ponto->bairro_id)?$ponto->bairro_id:''}}">
								<select name="bairro_id" id="bairro_id" class="form-control select2">
									<option value=""></option>
								</select>
							</div>
							@if( Helper::temPermissao('bairros-incluir') )
							<div class="form-group pull-right text-right" style="width: 10%;">
								<label for=""><br></label><br>
								<a href="#" class="btn btn-info disabled addBairroBtn" data-toggle="modal" data-target="#addBairroModal" title="Adicionar Bairro"><i class="fa fa-plus"></i></a>
							</div>
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

<div class="modal fade" id="addBairroModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Adicionar Bairro</h4>
        </div>
        <div class="modal-body">
        	<label for="">Nome</label>
        	<input type="text" class="form-control" name="bairronome" id="bairronome" value="" >
        </div>
        <div class="modal-footer">
        	<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        	<a href="#" class="btn btn-info addBairro">Salvar e Selecionar</a>
        </div>
      </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){

		$('#cidade_id').on('change', function(){
			$.get('{{ url("/cidades") }}/'+ $('#cidade_id option:selected').val() +'/bairros', function(objs){
				$('#bairro_id').empty();
				$('#bairro_id').append('<option value="">Selecione</option>');
				$(objs).each(function(){
					$('#bairro_id').append('<option value="'+this.id+'">'+this.nome+'</option>');
				});

				if( $('#_bairro_id').val() ){
					$('#bairro_id').val( $('#_bairro_id').val() ).trigger('change');
				}

			});

			$('.addBairroBtn').removeClass('disabled');
		});

		$('#addBairroModal').on('shown.bs.modal', function(){
		  $('#bairronome').trigger('focus');
		})

		$('.addBairro').click(function(){
			if( $('#bairronome').val().length >= 3 ){
				$.post( "{{url('/bairros')}}", { nome: $('#bairronome').val(), cidade_id: $('#cidade_id option:selected').val(), _token: $('input[name="_token"]').val() })
					.done(function( data ) {
					$('#bairro_id').append('<option value="'+data.bairro.id+'" selected="selected">'+data.bairro.nome+'</option>');
				});
				$('#bairronome').val('');
				$('#addBairroModal .close')[0].click();
			} else {
				toastr.error('Escreva algum nome para o bairro!');
			}
		});

		@if( isset($ponto) and $ponto->cidade_id ) $('#cidade_id').change(); @endif
	});
</script>
@endsection