<?php use App\Helpers; ?>
@extends('layouts.public')
@section('content')
	<div class="col-md-12 text-center">
		<h2>Comprovante de venda</h2>
	</div>

	<div class="col-md-3"></div>
	<div class="col-md-6">
	@if(isset($venda->nome))
	<div class="row table-bordered">
	<div class="col-md-3"><b>Nome:</b></div>
	<div class="col-md-9">{{$venda->nome}}</div>
	</div>
	@endif

	@if(isset($venda->cpf))
	<div class="row table-bordered">
	<div class="col-md-3"><b>CPF:</b></div>
	<div class="col-md-9">{{ Helper::formatCpfCnpj($venda->cpf) }}</div>
	</div>
	@endif

	@if(isset($venda->telefone))
	<div class="row table-bordered">
	<div class="col-md-3"><b>Telefone:</b></div>
	<div class="col-md-9">{{$venda->telefone}}</div>
	</div>
	@endif

	<div class="row table-bordered">
	<div class="col-md-3"><b>IP:</b></div>
	<div class="col-md-9">{{$venda->ip}}</div>
	</div>

	<div class="row table-bordered">
	<div class="col-md-3"><b>Data:</b></div>
	<div class="col-md-9">{{ date('d/m/Y H:i',strtotime($venda->created_at)) }}</div>
	</div>

	<div class="row table-bordered">
	<div class="col-md-3"><b>Valor:</b></div>
	@php
		$valor = 0;
		if( count( $venda->matrizes ) == 2 )
            $valor = $venda->etapa->valor_duplo;
        elseif( count( $venda->matrizes ) == 3 )
            $valor = $venda->etapa->valor_triplo;
        else
            $valor = $venda->etapa->valor_simples;
	@endphp
	<div class="col-md-9">R$ {{ Helper::formatDecimalToView( $valor ) }}</div>
	</div>

	<div class="row table-bordered">
	<div class="col-md-3"><b>Bilhete<?php echo ((count( $venda->matrizes )>1)?'s':''); ?>:</b></div>
	<div class="col-md-9">
		@foreach( $venda->matrizes as $matriz )
		<b>{{ $matriz->matriz->bilhete }}</b>:
		<div class="text-center" style="padding-bottom: 5px;">
		@php
		$chunk = explode( '-', $matriz->matriz->combinacoes );
		foreach( $chunk as $k => $c ){
			echo $c.' ';
			if( in_array( $k, [ 9, 19 ] ) )
				echo '<br>';
		}
		@endphp
		</div>
		@endforeach
	</div>
	</div>
		<br>
	</div>
@endsection
@section('scripts')
<script>
	$(document).ready(function(){

	});
</script>
@endsection