<?php use App\Helpers; ?>
@if( ! Helper::temPermissao('faq-listar') )
<script>window.location = "{{ url('/home') }}";</script>
@endif
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				faqs
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('faq-incluir') )
						<a href="<?php echo url('/'); ?>/faq/create" class="btn btn-info btn-xs"><span class="fa fa-plus"></span> Novo</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table id="basic-datatables" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Título</th>
								<th>Ativo</th>
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
							@forelse( $faqs as $faq )
								<tr>
									<td>{{ $faq->titulo }}</td>
									<td class="text-center">
									@if( $faq->deleted_at )
										<i class="fa fa-times" aria-hidden="true"></i>
									@else
										<i class="fa fa-check" aria-hidden="true"></i>
									@endif
									</td>
									<td class="text-center">
										@if( Helper::temPermissao('faq-editar') )
										<a href="{{ url('/faq/'.$faq->id.'/edit') }}" class="btn btn-info" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										@else
										<a href="{{ url('/faq/'.$faq->id.'/edit') }}" class="btn btn-success" title="Editar"><i class="fa fa-eye" aria-hidden="true"></i></a>
										@endif
										@if( Helper::temPermissao('faq-excluir') )
										<form action="{{url('/faq/'.$faq->id)}}" method="POST" style="display: inline-block;">
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
				{{ $faqs->links() }}
			</div>
		</div>
	</div>
</div>
@endsection