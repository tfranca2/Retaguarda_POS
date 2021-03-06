<?php use App\Helpers; ?>
@if( ! Helper::temPermissao('empresas-listar') )
<script>window.location = "{{ url('/home') }}";</script>
@endif
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				empresas
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('empresas-incluir') )
						<a href="<?php echo url('/'); ?>/empresas/create" class="btn btn-info btn-xs"><span class="fa fa-plus"></span> Novo</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table id="basic-datatables" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Nome</th>
								<th>Ativo</th>
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
							@forelse( $empresas as $empresa )
								<tr>
									<td>{{ $empresa->nome }}</td>
									<td class="text-center">
									@if( $empresa->deleted_at )
										<i class="fa fa-times" aria-hidden="true"></i>
									@else
										<i class="fa fa-check" aria-hidden="true"></i>
									@endif
									</td>
									<td class="text-center">
										@if( Helper::temPermissao('empresas-editar') )
										<a href="{{ url('/empresas/'.$empresa->id.'/edit') }}" class="btn btn-info" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										@endif
										@if( Helper::temPermissao('empresas-excluir') )
										<form action="{{url('/empresas/'.$empresa->id)}}" method="POST" style="display: inline-block;">
											@method('DELETE') @csrf
											<button type="submit" class="btn btn-danger form-delete" title="Apagar"><i class="fa fa-trash" aria-hidden="true"></i></button>
										</form>
										@endif
									</td>
								</tr>
							@empty
								<tr><td colspan="3" class="text-center">Sem resultados para listar</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
				{{ $empresas->links() }}
			</div>
		</div>
	</div>
</div>
@endsection