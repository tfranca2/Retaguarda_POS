<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				matrizes
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('matrizes-incluir') )
						<a href="<?php echo url('/'); ?>/matrizes/create" class="btn btn-info btn-xs"><span class="fa fa-plus"></span> Novo</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table id="basic-datatables" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Bilhete</th>
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
							@forelse( $matrizes as $matriz )
								<tr>
									<td>{{ $matriz->bilhete }}</td>
									<td class="text-center">
										@if( Helper::temPermissao('matrizes-editar') )
										<a href="{{ url('/matrizes/'.$matriz->id.'/edit') }}" class="btn btn-info" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										@endif
										@if( Helper::temPermissao('matrizes-excluir') )
										<form action="{{url('/matrizes/'.$matriz->id)}}" method="POST" style="display: inline-block;">
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
				{{ $matrizes->links() }}
			</div>
		</div>
	</div>
</div>
@endsection