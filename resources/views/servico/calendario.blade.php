<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				Agenda de serviços
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('servicos-gerenciar') )
						<a href="{{ url('/servicos/contratacao') }}" class="btn btn-info btn-xs"><span class="fa fa-handshake-o"></span> Contratar</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div id='agenda'></div>
				<div class="text-center"> 
					Legenda: 
					<div class="badge nao-selecionado">Prestador indef.</div>
					<div class="badge pendente">Pendente</div>
					<div class="badge atrasado">Atrasado</div>
					<div class="badge iniciado">Iniciado</div>
					<div class="badge concluido">Concluído</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal" role="dialog">
	<div class="modal-dialog" style="width: 65%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Detalhes da contratação
					<span style="font-weight: bold;">
						&nbsp;&nbsp;&nbsp;&nbsp;
						<span id="condominio" class="text-danger"></span>
						<span id="unidade" class="text-danger"></span>
						<span id="bloco" class="text-danger"></span>
					</span>
				</h4>
			</div>
			<!-- <form action="" method="post" class="" data-parsley-validate="" > -->
				<div class="modal-body">
					
					<div class="row">
						
						<div class="col-md-6">
							<div class="form-group">
								<label for="">Cliente</label>
								<input type="text" class="form-control" name="cliente" id="cliente" readonly="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">Tipo de Serviço</label>
								<input type="text" class="form-control" name="servico" id="servico" readonly="">
							</div>
						</div>
					
					</div>
					<div class="row">

						<input type="hidden" name="id" id="id">
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Início do Serviço</label>
								<input type="text" class="form-control" name="inicio" id="inicio" readonly="">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Fim do Serviço</label>
								<input type="text" class="form-control" name="fim" id="fim" readonly="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">Prestador de serviço</label>
								<input type="text" class="form-control" name="prestador" id="prestador" readonly="">
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<label for="">Endereço do Cliente</label>
								<input type="text" class="form-control" name="endereco" id="endereco" readonly="">
							</div>
						</div>


					</div>
					<div class="row">

						<div class="col-md-3" style="display: none;">
							<div class="form-group">
								<label for="">Tipo do Imóvel</label>
								<input type="text" class="form-control" name="tipo_imovel" id="tipo_imovel" readonly="">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Valor</label>
								<input type="text" class="form-control" name="valor" id="valor" readonly="">
							</div>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<label for="">Observações</label>
								<textarea name="observacoes" id="observacoes" class="form-control" rows="1" readonly=""></textarea>
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-12">
							<hr>

							<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" id="detalhesservicobutton">Detalhes do Serviço ...</button>
							<div class="collapse" id="collapseExample">
								<div class="well">
									<div id="campos"></div>
								</div>
							</div>

						</div>

					</div>
					<div class="row">
						<hr>
						<div class="col-md-4">
							@if( Helper::temPermissao('servicos-gerenciar') )
							<center><br><a href="#" id="editar" class="btn btn-warning">Editar Contratação</a></center>
							@endif
						</div>
						<div class="col-md-4">
							<div class="form-group iniciado_block">
								<label for="">Iniciado em</label>
								<input type="text" class="form-control" name="iniciado" id="iniciado" readonly="">
							</div>
							<center><br><a href="#" id="iniciar" class="btn btn-info">Iniciar Serviço</a></center>
						</div>
						<div class="col-md-4">
							<div class="form-group concluido_block">
								<label for="">Concluído em</label>
								<input type="text" class="form-control" name="concluido" id="concluido" readonly="">
							</div>
							<center><br><a href="javascript:void(0)" id="concluir" data-toggle="modal" data-target="#modalconcluirservico" data-dismiss="modal" class="btn btn-danger">Concluir Serviço</a></center>
						</div>

					</div>

				</div>
				<div class="modal-footer">
					<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> -->
					<!-- <button class="btn btn-info" type="submit">Salvar</button> -->
				</div>
			<!-- </form> -->
		</div>
	</div>
</div>


<!-- Modal concluir serviço -->
<div class="modal fade" id="modalconcluirservico" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ url('servicos/calendario/agendamentos/concluir') }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate="" >
				@csrf
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Conclusão do serviço</h4>
			</div>
			<div class="modal-body" style="max-height: 450px; overflow-y: auto;">
				<div id="posservico"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				<button class="btn btn-info" type="submit">Concluir serviço</button>
			</div>
			</form>
		</div>
	</div>
</div>

<style>
	
	#agenda {
	  max-width: 900px;
	  margin: 40px auto;
	}

	#agenda .fc-event {
		cursor: pointer;
	}

	.nao-selecionado, 
	#agenda .fc-event.nao-selecionado {
		background: #292b2c;
		border: 1px solid #292b2c;
	}

	.pendente, 
	#agenda .fc-event.pendente {
		background: #3a87ad;
		border: 1px solid #3a87ad;
	}

	.atrasado, 
	#agenda .fc-event.atrasado {
		background: #d9534f;
		border: 1px solid #d9534f;
	}

	.iniciado, 
	#agenda .fc-event.iniciado {
		background: #f0ad4e;
		border: 1px solid #f0ad4e;
	}

	.concluido, 
	#agenda .fc-event.concluido {
		background: #5cb85c;
		border: 1px solid #5cb85c;
	}

	.badge {
		border-radius: 5px;
		font-weight: normal;
	}

	.tooltipevent {
		display: block;
		padding: 10px;
		color: #fff;
		text-align: center;
		position: absolute;
		z-index: 10001;
	}

</style>
<script>
	$(document).ready(function(){
		$('#detalhesservicobutton').click(function(){
			if( $('.collapse').is(':visible') ){
				setTimeout(function(){
					$('.collapse.in').removeClass('in');
				},500);
			}
		});
	});
</script>
@endsection
