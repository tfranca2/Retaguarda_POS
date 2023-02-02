<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				etapas
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('etapas-incluir') )
						<a href="<?php echo url('/'); ?>/etapas/create" class="btn btn-info btn-xs"><span class="fa fa-plus"></span> Novo</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table id="basic-datatables" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Nº Etapa</th>
								<th>Descrição</th>
								<th>Data</th>
								<th class="text-center">Frequência</th>
								@if( Helper::temPermissao('etapas-gerenciar') )
								<th class="text-center">Ativar</th>
								@endif
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
							@forelse( $etapas as $etapa )
								<tr>
									<td>{{ $etapa->etapa}}</td>
									<td>{{ $etapa->descricao }}</td>
									<td>{{ Helper::convertDate($etapa->data) }}</td>
									<td class="text-center">{{ strtoupper($etapa->frequencia) }}</td>
									@if( Helper::temPermissao('etapas-gerenciar') )
									<td class="text-center">
										<label class="switch switch-success" data-frequencia="{{ $etapa->frequencia }}"><input type="checkbox" data-id="{{ $etapa->id }}"  {{ (($etapa->ativa)?'checked="checked"':'') }} ><span class="slider round"></span></label>
									</td>
									@endif
									<td class="text-center">
										@if( Helper::temPermissao('etapas-editar') )
										<a href="{{ url('/etapas/'.$etapa->id.'/edit') }}" class="btn btn-info" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										@endif
										@if( Helper::temPermissao('etapas-excluir') )
										<form action="{{url('/etapas/'.$etapa->id)}}" method="POST" style="display: inline-block;">
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
				{{ $etapas->links() }}
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
<script>
	$('.switch').click(function(){
		var obj = $(this);

		$('.switch[data-frequencia="'+ $(obj).data('frequencia') +'"]').not( $(obj) ).find('input').prop('checked',false);
		
		if( $(obj).find('input').is(':checked') ){
			$.ajax({
                type: "GET",
                url: "{{ url('/etapas') }}/"+$(obj).find('input').data('id')+"/ativar",
                success: function(data){
					$('.switch input[data-id='+data.id+']').prop('checked',true);
					if( data.message )
						toastr.success(data.message);
				},
				complete: function(data) {
					if( data.responseJSON.error ) {
						$(obj).find('input').prop('checked',false);
						toastr.error(data.responseJSON.error);
						$('.switch input[data-id='+ data.responseJSON.id +']').prop('checked',true);
					}
				}
			});
		}

	});
</script>
@endsection