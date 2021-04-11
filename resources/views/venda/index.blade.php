<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				vendas
				<div class="pull-right">

					<span class="btn" style="color: #808080; cursor: default;"><label>Total: R$ {{ $totalVendas }}</label></span> |
					<span class="btn" style="color: #808080; cursor: default;"><label>Total Comissão: R$ {{ $totalComissao }}</label></span>

					<!-- <div class="btn-group">
						<a href="#" id="exportarCsv" class="btn btn-success pull-right" title="Exportar .csv"><i class="fa fa-file-excel-o"></i> Exportar</a>
					</div> -->

					<div class="btn-group">
						<a href="#" id="toggleFilter" class="btn btn-link pull-right" title="Mostrar Filtros">Filtros <i class="fa fa-chevron-up"></i></a>
					</div>
					<div class="btn-group">
						@if( Helper::temPermissao('vendas-incluir') )
						<a href="<?php echo url('/'); ?>/vendas/create" class="btn btn-info btn-xs"><span class="fa fa-plus"></span> Novo</a>
						@endif
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
							<div class="col-md-3 p-lr-o">
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
							</div>
							<div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label for="">Dispositivo</label>
									<select name="dispositivo_id" id="dispositivo_id" class="form-control select2">
										<option value="">Selecione</option>
										<?php if( isset( $dispositivo ) and $dispositivo and isset( $_GET['dispositivo_id'] ) and $_GET['dispositivo_id'] ) { ?>
											<option value="{{ $dispositivo->id }}" selected="" >{{ $dispositivo->nome }}</option>
										<?php } ?>
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
								<th>Etapa</th>
								<th>Distribuidor</th>
								<th>Dispositivo</th>
								<th>Valor</th>
								<th>Comissão</th>
								<th>Data</th>
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
							@forelse( $vendas as $venda )
								<tr>
									<td>{{ $venda->etapa->descricao }}</td>
									<td>{{ $venda->dispositivo->distribuidor->name }}</td>
									<td>{{ $venda->dispositivo->nome }}</td>
									<td>R$ {{ Helper::formatDecimalToView( $venda->etapa->valor_simples ) }}</td>
									<td>R$ {{ Helper::formatDecimalToView( $venda->etapa->v_comissao_simples ) }}</td>
									<td>{{ Helper::convertDate( $venda->created_at ) }}</td>
									<td class="text-center">
										@if( Helper::temPermissao('vendas-editar') )
										<a href="{{ url('/vendas/'.$venda->id.'/edit') }}" class="btn btn-info" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										@endif
										@if( Helper::temPermissao('vendas-excluir') )
										<form action="{{url('/vendas/'.$venda->id)}}" method="POST" style="display: inline-block;">
											@method('DELETE') @csrf
											<button type="submit" class="btn btn-danger form-delete" title="Apagar"><i class="fa fa-trash" aria-hidden="true"></i></button>
										</form>
										@endif
									</td>
								</tr>
							@empty
								<tr><td colspan="100" class="text-center">Sem resultados para listar</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
				{{ $vendas->appends( request()->input() )->links() }}
			</div>
		</div>
	</div>
</div>
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

	});
</script>
@endsection