<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				<div class="row">
					<div class="col-sm-3">
						vendas
					</div>
					<div class="col-sm-3 text-center" style="color: #808080;">
						Total: R$ {{ $totalVendas }}
					</div>
					<div class="col-sm-4 text-center" style="color: #808080;">
						Total Comissão: R$ {{ $totalComissao }}
					</div>
					<div class="col-sm-2 text-right">
						<a href="#" id="toggleFilter" class="btn btn-link btn-xs" title="Mostrar Filtros">Filtros <i class="fa fa-chevron-up"></i></a>
					</div>
				</div>
			</div>
			<div class="panel-body">

				<div id="filter" style="/*display: none;*/">
					<form action="{{ url('/vendas') }}" method="GET">
						<div class="row">
							
							<div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label for="">Etapa</label>
									<select name="etapa_id" class="form-control select2">
										<option value="">Selecione</option>
										@forelse( $etapas as $etapa )
										<option value="{{ $etapa->id }}"
											<?php if( ( isset( $_GET['etapa_id'] ) and $_GET['etapa_id'] == $etapa->id ) or request()->etapa_id == $etapa->id ) echo 'selected'; ?>
											>{{ $etapa->descricao }}</option>
										@empty
										@endforelse
									</select>
								</div>
							</div>
							<!-- <div class="col-md-3 p-lr-o">
								<div class="form-group">
									<label for="">Distribuidor</label>
									<select name="distribuidor_id" id="distribuidor_id" class="form-control select2">
										<option value="">Selecione</option>
										@forelse( $distribuidores as $distribuidor )
										<option value="{{ $distribuidor->id }}"
											<?php if( ( isset( $_GET['distribuidor_id'] ) and $_GET['distribuidor_id'] == $distribuidor->id ) or request()->distribuidor_id == $distribuidor->id ) echo 'selected'; ?>
											>{{ $distribuidor->name }}</option>
										@empty
										@endforelse
									</select>
								</div>
							</div> -->
							<!-- <div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label for="">Dispositivo</label>
									<select name="dispositivo_id" id="dispositivo_id" class="form-control select2">
										<option value="">Selecione</option>
										<?php if( isset( $dispositivo ) and $dispositivo and isset( $_GET['dispositivo_id'] ) and $_GET['dispositivo_id'] ) { ?>
											<option value="{{ $dispositivo->id }}" selected="" >{{ $dispositivo->nome }}</option>
										<?php } ?>
									</select>
								</div>
							</div> -->

							<div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label>CPF: </label>
									<input type="text" class="form-control" name="cpf" value="{{ ((isset( $_GET['cpf'] ))?$_GET['cpf']:'') }}" >
								</div>
							</div>
							<div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label>Bilhete: </label>
									<input type="text" class="form-control" name="bilhete" value="{{ ((isset( $_GET['bilhete'] ))?$_GET['bilhete']:'') }}" >
								</div>
							</div>
							<div class="col-md-1 p-lr-o">
								<div class="form-group">
									<label>Tipo: </label>
									<select name="tipo" id="tipo" class="form-control select2">
										<option value="">Selecione</option>
										<option value="API" <?php if( isset( $_GET['tipo'] ) and $_GET['tipo'] == 'API' ) echo 'selected="selected"'; ?>>API</option>
										<option value="CREDITO" <?php if( isset( $_GET['tipo'] ) and $_GET['tipo'] == 'CREDITO' ) echo 'selected="selected"'; ?>>CREDITO</option>
										<option value="PIX" <?php if( isset( $_GET['tipo'] ) and $_GET['tipo'] == 'PIX' ) echo 'selected="selected"'; ?>>PIX</option>
										<option value="POS" <?php if( isset( $_GET['tipo'] ) and $_GET['tipo'] == 'POS' ) echo 'selected="selected"'; ?>>POS</option>
									</select>
								</div>
							</div>

							<div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label>De: </label>
									<input type="date" class="form-control" name="inicio" value="{{ ((isset( $_GET['inicio'] ))?$_GET['inicio']:'') }}" >
								</div>
							</div>
							<div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label>Até: </label>
									<input type="date" class="form-control" name="fim" value="{{ ((isset( $_GET['fim'] ))?$_GET['fim']:'') }}" >
								</div>
							</div>
							<div class="col-md-1 p-lr-o ">
								<div class="form-group">
									<a href="{{ url('/vendas') }}" class="btn btn-danger btn-block" title="Limpar Filtros"><i class="fa fa-remove"></i></a>
									<button class="btn btn-info btn-block" title="Filtrar"><i class="fa fa-search"></i></button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<br>

				<div class="table-responsive">
					<table id="basic-datatables" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>CPF</th>
								<th>Nome</th>
								<th>Telefone</th>
								<th>Valor</th>
								<th>Bilhete</th>
								<th>Data</th>
								<th>Tipo</th>
								@if( Helper::temPermissao('vendas-editar') or Helper::temPermissao('vendas-excluir') )
								<th>Ações</th>
								@endif
							</tr>
						</thead>
						<tbody>
							@forelse( $vendas as $venda )
								<tr>
									<td>{{ Helper::formatCpfCnpj( $venda->cpf ) }}</td>
									<td>{{ mb_strimwidth( $venda->nome, 0, 20, "...") }}</td>
									<td>{{ Helper::formatTelefone( $venda->telefone ) }}</td>
									<td>R$ 
										@if( $venda->pagamento )
											{{ Helper::formatDecimalToView(  $venda->etapa->valor ) }}
										@else
											0,00
										@endif
									</td>
									<td>{{ $venda->matrizes()[0]['matriz']['bilhete'] }}</td>
									<td>{{ date( "d/m H:i", strtotime( $venda->created_at ) ) }}</td>
									<td>@php 
										$tipo = '';
										if( $venda->pagamento )
											$tipo = $venda->pagamento->tipo;
										echo $tipo; 
									@endphp</td>
									@if( Helper::temPermissao('vendas-editar') or Helper::temPermissao('vendas-excluir') )
									<td class="text-center">
										@if( $venda->pagamento and $venda->pagamento->tipo == 'PIX' and $venda->pagamento->status == 'WAITING' )
										@if( Helper::temPermissao('vendas-editar') )
										<!-- <a href="{{ url('/vendas/'.$venda->id.'/confirmar') }}" class="btn btn-success" title="Confirmar"><i class="fa fa-check" aria-hidden="true"></i></a> -->
										<a href="#" class="btn btn-info shareqrcode" data-key="{{ $venda->key }}" data-telefone="{{ $venda->telefone }}" title="Enviar QR code"><i class="fa fa-qrcode" aria-hidden="true"></i></a>
										<form action="{{url('/vendas/'. $venda->id .'/confirmar')}}" method="POST" class="form-edit" style="display: inline-block;">@csrf
											<button type="submit" class="btn btn-success" title="Confirmar Pix"><i class="fa fa-check" aria-hidden="true"></i></button>
										</form>
										@else
										<button class="btn btn-warning" title="Venda ainda não confirmada"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></button>
										@endif
										@endif
										@if( Helper::temPermissao('vendas-editar') )
										<!-- <a href="{{ url('/vendas/'.$venda->id.'/edit') }}" class="btn btn-info" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a> -->
										@endif
										@if( Helper::temPermissao('vendas-excluir') )
										<!-- <form action="{{url('/vendas/'.$venda->id)}}" method="POST" style="display: inline-block;">
											@method('DELETE') @csrf
											<button type="submit" class="btn btn-danger form-delete" title="Apagar"><i class="fa fa-trash" aria-hidden="true"></i></button>
										</form> -->
										@if( $venda->pagamento and $venda->pagamento->tipo == 'CREDITO' and $venda->pagamento->status == 'PAID' )
										<form action="{{url('/vendas/'.$venda->id.'/cancel')}}" method="POST" style="display: inline-block;">
											@method('PUT') @csrf
											<button type="submit" class="btn btn-danger form-cancel" title="Cancelar"><i class="fa fa-close" aria-hidden="true"></i></button>
										</form>
										@endif
										@endif
									</td>
									@endif
								</tr>
							@empty
								<tr><td colspan="100" class="text-center">Sem resultados para listar</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
				{{ $vendas->appends( request()->input() )->links() }}
				<div class="pull-right">
					<a href="{{ url('/vendas/csv') .'?'. http_build_query( request()->input() ) }}" id="exportarCsv" class="btn btn-success pull-right" title="Exportar .csv"><i class="fa fa-file-excel-o"></i> Exportar .csv</a>
					<span class="pull-right" style="display: block; width: 30px; height: 10px;"></span>
					<a href="{{ url('/vendas/txt') .'?'. http_build_query( request()->input() ) }}" id="exportarTxtCorreios" class="btn btn-warning pull-right" title="Exportar .txt"><i class="fa fa-file-text-o"></i> Exportar .txt</a>
				</div>
			</div>
		</div>
	</div>
</div>
<a id="share" href="#" target="_blank" style="display: none;"></a>
@endsection
@section('scripts')
<script>
	$(document).ready(function(){

		$('#distribuidor_id').on('change',function(){
			var dispositivo_id = <?php echo ( ( isset( $dispositivo ) and $dispositivo ) ? $dispositivo->id : 0 ); ?>;
			var distribuidor_id = $('#distribuidor_id option:selected').val();
			if( distribuidor_id ){
				$.ajax({
	                type: "GET",
	                url: "{{ url('/distribuidores') }}/"+ distribuidor_id +"/dispositivos",
	                success: function( data ){
						console.log(data);
						$('#dispositivo_id').empty();
						$('#dispositivo_id').append('<option value="">Selecione</option>');
						$(data).each(function(){
							selected = '';
							if( this.id == dispositivo_id )
								selected = 'selected="selected"';
							$('#dispositivo_id').append('<option value="'+ this.id +'" '+ selected +' >'+ this.nome +'</option>');
						});
					}
				});
			}
		});
		$('#distribuidor_id').change()

		$('.shareqrcode').click(function(e){
			e.preventDefault();
			var telefone = $(this).data('telefone');
			var key = $(this).data('key');
			$.ajax({
				type: "GET",
				url: "{{ url('/pix') }}/"+ key,
				success: function( data ){
					link = "https://wa.me/55"+ telefone +"?text="+ encodeURI("Este é o código Pix para confirmar sua compra, copie o código a seguir e cole na função \"*Pix Copia e Cola*\" no seu aplicativo de banco.\n\n"+data);

					$('#share').attr('href', link);
					$('#share')[0].click();
					$('#share').attr('href', '#');
				}
			});
		});

	});
</script>
@endsection