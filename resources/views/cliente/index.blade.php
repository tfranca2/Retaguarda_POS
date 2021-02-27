<?php use App\Helpers; ?>
@if( ! Helper::temPermissao('clientes-listar') )
<script>window.location = "{{ url('/home') }}";</script>
@endif
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				clientes
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('clientes-incluir') )
						<a href="<?php echo url('/'); ?>/clientes/create" class="btn btn-info btn-xs"><span class="fa fa-plus"></span> Novo</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table id="basic-datatables" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Imagem</th>
								<th>Nome</th>
								<th>Condomínio</th>
								<th>Ativo</th>
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
							@forelse( $clientes as $cliente )
								<tr>
									<td>
										<div class="imgshell">
											@if( $cliente->imagem )
											<img src="{{ url('/public/images/'.$cliente->imagem ) }}" >
											@else
											<img src="{{ url('/public/images/avatar.png' ) }}" >
											@endif
										</div>
									</td>
									<td>{{ $cliente->nome }}</td>
									<td>{{ $cliente->condominio }}</td>
									<td class="text-center">
									@if( $cliente->deleted_at )
										<i class="fa fa-times" aria-hidden="true"></i>
									@else
										<i class="fa fa-check" aria-hidden="true"></i>
									@endif
									</td>
									<td class="text-center">
										@if( Helper::temPermissao('clientes-editar') )
										<a href="{{ url('/clientes/'.$cliente->id.'/edit') }}" class="btn btn-info" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										@endif
										@if( Helper::temPermissao('clientes-excluir') )
										<form action="{{url('/clientes/'.$cliente->id)}}" method="POST" style="display: inline-block;">
											@method('DELETE') @csrf
											<button type="submit" class="btn btn-danger form-delete" title="Apagar"><i class="fa fa-trash" aria-hidden="true"></i></button>
										</form>
										@endif
									</td>
								</tr>
							@empty
								<tr><td colspan="5" class="text-center">Sem resultados para listar</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
				{{ $clientes->links() }}
			</div>
		</div>
	</div>
</div>
@endsection