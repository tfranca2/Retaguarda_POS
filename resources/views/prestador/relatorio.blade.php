<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				Relatório prestador "{{ $prestador->usuario->name }}"
				<div class="pull-right">
					<div class="btn-group">
						<a href="<?php echo url('/'); ?>/prestadores/{{ $prestador->id }}/csv?<?php
							if( isset($_GET['data']) )
								echo 'data[]='.$_GET['data'][0].'&data[]='.$_GET['data'][1];
							if( isset($_GET['servico']) )
								echo '&servico='.$_GET['servico'];
							if( isset($_GET['cliente']) )
								echo '&cliente='.$_GET['cliente'];
							if( isset($_GET['cancelado']) )
								echo '&cancelado='.$_GET['cancelado'];
						?>" class="btn btn-info btn-xs"><span class="fa fa-share-square-o"></span> CSV</a>
					</div>
				</div>
			</div>
			<div class="panel-body">

				<form action="">
					<div class="row">
						<div class="col-md-2">
							<label for="">Inicio</label>
							<input type="date" class="form-control" name='data[]' value="<?php if(isset($_GET['data'])) echo $_GET['data'][0];?>">
						</div>
						<div class="col-md-2">
							<label for="">Fim</label>
							<input type="date" class="form-control" name='data[]' value="<?php if(isset($_GET['data'])) echo $_GET['data'][1];?>">
						</div>
						<div class="col-md-2">
							<label for="">Serviço</label>
							<select class="form-control select2" name="servico">
								<option value=""></option>
								@foreach( $servicos as $servico )
								<option value="{{$servico->id}}" <?php if( isset($_GET['servico']) and $_GET['servico'] == $servico->id ) echo 'selected'; ?>>{{ $servico->nome }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-2">
							<label for="">Cliente</label>
							<select class="form-control select2" name="cliente">
								<option value=""></option>
								@foreach( $clientes as $cliente )
								<option value="{{$cliente->id}}" <?php if( isset($_GET['cliente']) and $_GET['cliente'] == $cliente->id ) echo 'selected'; ?>>{{ $cliente->nome }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-2">
							<label for="">Cancelado?</label>
							<select class="form-control select2" name="cancelado">
								<option value=""></option>
								<option value="1" <?php if( isset($_GET['cancelado']) and $_GET['cancelado'] == 1 ) echo 'selected'; ?>>Sim</option>
								<option value="0" <?php if( isset($_GET['cancelado']) and $_GET['cancelado'] != '' and $_GET['cancelado'] == 0 ) echo 'selected'; ?>>Não</option>
							</select>
						</div>
						<div class="col-md-2">
							<label for=""><br></label><br>
							<button class="btn btn-primary"><i class="fa fa-filter"></i> Filtrar</button>
						</div>
					</div>
					<br>
				</form>

				<div class="table-responsive">
					<table id="basic-datatables" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Serviço</th>
								<th>Cliente</th>
								<th>Data</th>
								<th>Valor</th>
								<th>Gorjeta</th>
								<th>Avaliação</th>
								<th>Cancelamento</th>
							</tr>
						</thead>
						<tbody>
							@forelse( $agendamentos as $agendamento )
							<tr>
								<td>{{ $agendamento->servico->nome }}</td>
								<td><?php 
									if( $agendamento->cliente) 
										echo $agendamento->cliente->nome;
								?></td>
								<td class="text-center"><?php 
									if( $agendamento->inicio ) 
										echo date( 'd/m/y H:i', strtotime( $agendamento->inicio ) );
								?></td>
								<td class="text-right">R$ {{ Helper::formatDecimalToView($agendamento->valor) }}</td>
								<td class="text-right">R$ {{ Helper::formatDecimalToView($agendamento->gorjeta) }}</td>
								<td>{{ $agendamento->rating }}@if( $agendamento->justificativa ) - {{ $agendamento->justificativa }}@endif</td>
								<td><?php
									if( $agendamento->pagamento and in_array( $agendamento->pagamento->status, [ 6, 7]) )
										echo date( 'd/m/y H:i', strtotime( $agendamento->pagamento->updated_at ) );
								?></td>
							</tr>
							@empty
							<tr><td colspan="12" class="text-center">Sem resultados para listar</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
				<div class="pull-right">
					{{ $agendamentos->appends( request()->input() )->links() }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection