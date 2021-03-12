<?php use App\Helpers; ?>
@if( ! Helper::temPermissao('usuarios-listar') )
<script>window.location = "{{ url('/home') }}";</script>
@endif
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				usuários
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('usuarios-incluir') )
						<a href="<?php echo url('/'); ?>/usuarios/create" class="btn btn-info btn-xs"><span class="fa fa-plus"></span> Novo</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table id="basic-datatables" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Avatar</th>
								<th>Nome</th>
								<th>Email</th>
								<th>Ativo</th>
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
							@forelse( $usuarios as $usuario )
								<tr>
									<td>
										<div class="imgshell" style="background: url('@if( $usuario->imagem ){{ url('/public/images/'.$usuario->imagem ) }}@else{{ url('/public/images/avatar.png' ) }}@endif') no-repeat center / cover;">
										</div>
									</td>
									<td>{{ $usuario->name }}</td>
									<td>{{ $usuario->email }}</td>
									<td class="text-center">
									@if( $usuario->deleted_at )
										<i class="fa fa-times" aria-hidden="true"></i>
									@else
										<i class="fa fa-check" aria-hidden="true"></i>
									@endif
									</td>
									<td class="text-center">
										@if( Helper::temPermissao('usuarios-editar') )
										<a href="{{ url('/usuarios/'.$usuario->id.'/edit') }}" class="btn btn-info" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										@endif
										@if( Helper::temPermissao('usuarios-excluir') )
										<form action="{{url('/usuarios/'.$usuario->id)}}" method="POST" style="display: inline-block;">
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
				{{ $usuarios->links() }}
			</div>
		</div>
	</div>
</div>
@endsection