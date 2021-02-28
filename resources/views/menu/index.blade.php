<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				menus
				<div class="pull-right">
					<div class="btn-group">
						<a href="<?php echo url('/'); ?>/menus/create" class="btn btn-info btn-xs"><span class="fa fa-plus"></span> Novo</a>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table id="basic-datatables" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Menu</th>
								<th>Link</th>
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
							@forelse( $menus as $menu )
								<tr>
									<td>@if( $menu->parent )&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@endif <i class="{{ $menu->icon }}"></i> {{ $menu->label }}</td>
									<td>{{ $menu->link }}</td>
									<td class="text-center">
										<a href="{{ url('/menus/'.$menu->id.'/edit') }}" class="btn btn-info" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										@if( Helper::temPermissao('menus-editar') )
										@endif
										<form action="{{url('/menus/'.$menu->id)}}" method="POST" style="display: inline-block;">
											@method('DELETE') @csrf
											<button type="submit" class="btn btn-danger form-delete" title="Apagar"><i class="fa fa-trash" aria-hidden="true"></i></button>
										</form>
										@if( Helper::temPermissao('menus-excluir') )
										@endif
									</td>
								</tr>
							@empty
								<tr><td colspan="100" class="text-center">Sem resultados para listar</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
				{{ $menus->links() }}
			</div>
		</div>
	</div>
</div>
@endsection