<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				Pagamentos
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('servicos-incluir') )
						<!-- <a href="<?php echo url('/'); ?>/servicos/create" class="btn btn-info btn-xs"><span class="fa fa-plus"></span> Novo</a> -->
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table id="basic-datatables" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Cliente</th>
								<th>Serviço</th>
								<th class="text-center">Data Contratação</th>
								<th>Prestador de Serviço</th>
								<th>Código da Transação</th>
								<th class="text-center">Status</th>
								<th class="text-center">V. Bruto</th>
								<th class="text-center">Taxa</th>
								<th class="text-center" style="min-width: 86px;">V. Líquido</th>
								<th class="text-center">Data Pagamento</th>
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
							@php
								$status = [
									0 => '',
									1 => 'Aguardando pagamento',
				                    2 => 'Em análise',
				                    3 => 'Pago',
				                    4 => 'Disponível.',
				                    5 => 'Em disputa',
				                    6 => 'Devolvido',
				                    7 => 'Cancelado',
								];
							@endphp
							@forelse( $pagamentos as $pagamento )
							<?php
								$cliente = $pagamento->cliente()->first();
								$contratacao = $pagamento->contratacao()->first();
								if( $contratacao ){
									$servico = $contratacao->servico()->first();
									$prestador = $contratacao->prestador()->first();
								}

								$usuario = null;
								if(isset($prestador))
									$usuario = $prestador->usuario()->first();
							?>
								<tr>
									<td>
										@if( isset($cliente) )
											{{ $cliente->nome }}
										@endif
									</td>
									<td>
										@if( isset($servico) )
											{{ $servico->nome }}
										@endif
									</td>
									<td class="text-center">
										@if( $contratacao )
										{{ date( 'd/m/y H:i', strtotime( $contratacao->inicio )) }}
										@endif
									</td>
									<td>
										@if( $usuario )
											{{ $usuario->name }}
										@else
											<span class="text-danger">Indeterminado</span>
										@endif
									</td>
									<td style="word-break: break-all;">{{ $pagamento->transaction_code }}</td>
									<td class="text-center">{{ $pagamento->status .' - '. $status[$pagamento->status] }}</td>
									<td class="text-right" style="min-width: 85px;">R$ {{ Helper::formatDecimalToView($pagamento->valorBruto) }}</td>
									<td class="text-right" style="min-width: 85px;">R$ {{ Helper::formatDecimalToView($pagamento->taxa) }}</td>
									<td class="text-right" style="min-width: 85px;">R$ {{ Helper::formatDecimalToView($pagamento->valorLiquido) }}</td>
									<td class="text-center">{{ date( 'd/m/y H:i', strtotime( $pagamento->date )) }}</td>
									<td class="text-center">
										@if( Helper::temPermissao('servicos-editar') and !in_array( $pagamento->status, [ 6, 7 ] ))
										@if( $contratacao )
											<a href="{{ url('/contratacao/'.$contratacao->id.'/edit') }}" class="btn btn-info" title="Reagendar" style="margin-bottom: 3px;"><i class="fa fa-calendar" aria-hidden="true"></i></a>
										@endif
										@endif
										@if( Helper::temPermissao('servicos-excluir') and !in_array( $pagamento->status, [ 6, 7 ] ))
										<form action="{{url('/pagamento')}}" method="POST" style="display: inline-block;">
											@method('PUT') @csrf
											<input type="hidden" name="code" value="{{ $pagamento->transaction_code }}">
											<button type="submit" class="btn btn-danger form-cancel" title="Cancelar e Estornar"><i class="fa fa-close" aria-hidden="true"></i></button>
										</form>
										@endif
									</td>
								</tr>
							@empty
								<tr><td colspan="11" class="text-center">Sem resultados para listar</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
				{{ $pagamentos->links() }}
			</div>
		</div>
	</div>
</div>
@endsection