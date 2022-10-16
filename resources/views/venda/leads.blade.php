<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				leads
				<div class="pull-right">
					<div class="btn-group">
						<div class="btn-group">
							<a href="#" id="toggleFilter" class="btn btn-link pull-right" title="Mostrar Filtros">Filtros <i class="fa fa-chevron-up"></i></a>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div id="filter" style="/*display: none;*/">
					<form action="{{ url('/leads') }}" method="GET">
						<div class="row">
							<div class="col-md-4"></div>
							<div class="col-md-4">
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
							<div class="col-md-3"></div>
							<div class="col-md-1">
								<div class="form-group">
									<a href="{{ url('/leads') }}" class="btn btn-danger btn-block" title="Limpar Filtros"><i class="fa fa-remove"></i></a>
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
								<th>E-mail</th>
								<th>Data</th>
							</tr>
						</thead>
						<tbody>
							@forelse( $leads as $lead )
							<tr>
								<td>{{ Helper::formatCpfCnpj( $lead->cpf ) }}</td>
								<td>{{ $lead->nome }}</td>
								<td>{{ Helper::formatTelefone( $lead->telefone ) }}</td>
								<td>{{ $lead->email }}</td>
								<td>{{ date( "d/m H:i", strtotime( $lead->created_at ) ) }}</td>
							</tr>
							@empty
							<tr><td colspan="100" class="text-center">Sem resultados para listar</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
				{{ $leads->appends( request()->input() )->links() }}
			</div>
		</div>
	</div>
</div>
@endsection
@section('css')
<style type="text/css">
	
</style>
@endsection
@section('scripts')
<script>
	$(document).ready(function(){

	});
</script>
@endsection