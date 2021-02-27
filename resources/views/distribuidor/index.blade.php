<?php use App\Helpers; ?>
@if( ! Helper::temPermissao('distribuidores-listar') )
<script>window.location = "{{ url('/home') }}";</script>
@endif
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				distribuidores
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('distribuidores-incluir') )
						<a href="<?php echo url('/'); ?>/distribuidores/create" class="btn btn-info btn-xs"><span class="fa fa-plus"></span> Novo</a>
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
							@forelse( $distribuidores as $distribuidor )
								<tr>
									<td>
										<div class="imgshell">
											@if( $distribuidor->imagem )
											<img src="{{ url('/public/images/'.$distribuidor->imagem ) }}" >
											@else
											<img src="{{ url('/public/images/avatar.png' ) }}" >
											@endif
										</div>
									</td>
									<td>{{ $distribuidor->nome }}</td>
									<td>{{ $distribuidor->condominio }}</td>
									<td class="text-center">
									@if( $distribuidor->deleted_at )
										<i class="fa fa-times" aria-hidden="true"></i>
									@else
										<i class="fa fa-check" aria-hidden="true"></i>
									@endif
									</td>
									<td class="text-center">
										@if( Helper::temPermissao('distribuidores-editar') )
										<a href="{{ url('/distribuidores/'.$distribuidor->id.'/edit') }}" class="btn btn-info" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										@endif
										@if( Helper::temPermissao('distribuidores-excluir') )
										<form action="{{url('/distribuidores/'.$distribuidor->id)}}" method="POST" style="display: inline-block;">
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
				{{ $distribuidores->links() }}
			</div>
		</div>
	</div>
</div>
@endsection