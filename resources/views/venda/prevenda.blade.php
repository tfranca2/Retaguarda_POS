<?php 
	use App\Helpers; 

	$empresa = \DB::table('empresa')->first();
?>
@extends('layouts.public')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center">
			<b><i class="fa fa-shopping-cart"></i> TOTAL: R$ <span class="preco">10,00</span></b><br>
			cartela <span class="index_cartela">1</span><br><br>
		</div>
		<div class="col-md-12 text-center">
			<div class="destaque"><b>Número do título: <span class="numero_cartela">1231231</span></b></div>
			<div class="destaque-sub">Números para participações</div>
		</div>
		<div class="col-md-12">
			<div class="prev"><i class="fa fa-chevron-left"></i></div>
			<div class="next"><i class="fa fa-chevron-right"></i></div>
			<div class="round-case">
				<div class="round">01</div>
				<div class="round">02</div>
				<div class="round">03</div>
				<div class="round">04</div>
				<div class="round">05</div>
				<div class="round">06</div>
				<div class="round">07</div>
				<div class="round">08</div>
				<div class="round">09</div>
				<div class="round">10</div>
				<div class="round">11</div>
				<div class="round">12</div>
				<div class="round">13</div>
				<div class="round">14</div>
				<div class="round">15</div>
				<div class="round">16</div>
				<div class="round">17</div>
				<div class="round">18</div>
				<div class="round">19</div>
				<div class="round">20</div>
			</div>
		</div>
		<div class="col-md-12 text-center">
			<br><a href="#" id="gerar" class="btn btn-success"><i class="fa fa-refresh"></i> Gerar Novas Cartelas</a><br><br>
		</div>
	</div>
	<div class="col-md-12 text-center">
		<h2><b>DADOS OBRIGATÓRIOS PARA <span class="text-danger">RECEBER O PRÊMIO</span></b></h2>
		<small>Cartela <b class="numero_cartela">1231231</b> - R$ <span class="preco">10,00</span></small><br>
	</div>

	<div class="col-md-12">
		<form action="">
			<div class="row">
				<div class="col-md-6">
					<label for="">CPF</label>
					<input type="text" class="form-control" name="cpf" data-parsley-cpf="true" data-parsley-required="true" required="">
				</div>
				<div class="col-md-6">
					<label for="">Nome</label>
					<input type="text" class="form-control" name="nome" data-parsley-required="true" required="">
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<label for="">E-mail</label>
					<input type="email" class="form-control" name="email" data-parsley-required="true" required="">
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<label for="">Telefone</label>
					<input type="text" class="form-control" name="telefone" data-parsley-required="true" required="">
				</div>
				<div class="col-md-4">
					<label for="">Cidade</label>
					<input type="text" class="form-control" name="cidade" data-parsley-required="true" required="">
				</div>
				<div class="col-md-4">
					<label for="">Estado</label>
					<select name="uf" class="form-control select2" data-parsley-required="true" required="">
						<option value="">Selecione o Estado</option>
						@foreach( $estados as $estado )
						<option value="{{ $estado->uf }}">{{ $estado->nome }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-center">
					<input type="hidden" name="key" id="key" value="">
					<br><button id="finalizar" class="btn btn-primary">Finalizar Pedido</button>
				</div>
			</div>
		</form>
	</div>
</div>
<br>
@endsection
@section('scripts')
<style>
	.destaque, .round, .prev, .next {
		background: {{ $empresa->menu_background }}; 
		color: {{ $empresa->menu_color }};
	}
	.destaque-sub {
		color: {{ $empresa->menu_background }}; 
	}
	.destaque, .destaque-sub {
		margin: auto;
		padding: 20px;
		border-radius: 5px 5px 0 0;
	}
	.destaque, .destaque-sub, #gerar, #finalizar {
		width: 300px;
		max-width: 100%;
	}
	.round {
		width: 35px;
		height: 35px;
		padding: 5px 5px;
		border-radius: 100%;
		margin-bottom: 10px;
		display: inline-block;
		text-align: center;
		font-weight: bold;
	}
	.round-case {
		width: 200px;
		max-width: 100%;
		margin: auto;
	}
	.prev, .next {
		display: flex;
		align-items: center;
		height: 100%;
		width: 30px;
		cursor: pointer;
		opacity: 0.3;
	}
	.prev:hover, .next:hover {
		opacity: 1;
	}
	.prev > i, .next > i {
		margin: auto;
	}

	.prev {
		float: left;
	}
	.next {
		float: right;
	}
</style>
<script>
	$(document).ready(function(){
		
	});
</script>
@endsection