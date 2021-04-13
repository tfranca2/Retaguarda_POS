<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				vendas
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('vendas-incluir') )
						<a href="<?php echo url('/'); ?>/vendas/create" class="btn btn-info btn-xs"><span class="fa fa-plus"></span> Novo</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table id="basic-datatables" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Dispositivo</th>
								<th>Etapa</th>
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
							@forelse( $vendas as $venda )
								<tr>
									<td>{{ $venda->dispositivo()->first()->nome }}</td>
									<td>Descrição</td>
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
				{{ $vendas->links() }}
			</div>
		</div>
	</div>
</div>
@endsection