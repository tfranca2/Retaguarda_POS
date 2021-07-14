<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				dispositivos
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('dispositivos-incluir') )
						<a href="<?php echo url('/'); ?>/dispositivos/create" class="btn btn-info btn-xs"><span class="fa fa-plus"></span> Novo</a>
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
								@if( Helper::temPermissao('dispositivos-editar') )
								<th>Ativo?</th>
								@endif
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
							@forelse( $dispositivos as $dispositivo )
								<tr>
									<td>{{ $dispositivo->nome }}</td>
									@if( Helper::temPermissao('dispositivos-editar') )
									<td class="text-center">
										<label class="switch switch-success"><input type="checkbox" data-id="{{ $dispositivo->id }}" {{ ((isset($dispositivo->deleted_at))?'':'checked="checked"') }} ><span class="slider round"></span></label>
									</td>
									@endif
									<td class="text-center">
										@if( Helper::temPermissao('dispositivos-editar') )
										<a href="{{ url('/dispositivos/'.$dispositivo->id.'/edit') }}" class="btn btn-info" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										@endif
										@if( Helper::temPermissao('dispositivos-excluir') )
										<form action="{{url('/dispositivos/'.$dispositivo->id)}}" method="POST" style="display: inline-block;">
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
				{{ $dispositivos->links() }}
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
<script>
	$(document).ready(function(){

		$('.switch').click(function(){
			var obj = $(this);
			if( $(obj).find('input').is(':checked') ){
				$.ajax({
	                type: "GET",
	                url: "{{ url('/dispositivos') }}/"+$(obj).find('input').data('id')+"/toggleActive",
	                success: function(data){
						$('.switch input[data-id='+data.id+']').prop('checked',true);
					},
					complete: function(data) {
						if( data.responseJSON.error ) {
							if( $(obj).find('input').is(':checked') )
								$(obj).find('input').prop('checked',false);
							else
								$(obj).find('input').prop('checked',true);
							toastr.error(data.responseJSON.error);
						}
					}
				});
			}
		});

	});
</script>
@endsection