<?php use App\Helpers; ?>
@extends('layouts.public')
@section('content')
@if( in_array( $venda->pagamento->status, [ 'CANCELED', 'DECLINED' ] ) )
	<div class="col-md-12 text-center">
		<h2 class="text-danger" style="font-weight: bold;">( VENDA CANCELADA )</h2>
	</div>
@else
	<div class="col-md-12 text-center">
		<h2>Comprovante de venda</h2>
		@if(!$venda->confirmada)<h2 class="text-danger" style="font-weight: bold;">( AGUARDANDO CONFIRMAÇÃO )</h2>@endif
	</div>

<div class="col-md-6 offset-md-3">
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
	<div class="col-md-9">{{ Helper::formatTelefone($venda->telefone) }}</div>
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
	<div class="col-md-9">@if(!$venda->confirmada)<s>@endif R$ {{ Helper::formatDecimalToView( $valor ) }} @if(!$venda->confirmada)</s>@endif</div>
	</div>

	<div class="row table-bordered">
		<div class="col-md-3"><b>Forma de pagamento:</b></div>
		<div class="col-md-9" style="display: flex; align-items: center;">
			{{ $venda->pagamento->tipo }}@if(!$venda->confirmada) <span class="text-danger">( Aguardando confirmação )</span>@endif
		</div>
	</div>

	<div class="row table-bordered">
	<div class="col-md-3"><b>Bilhete<?php echo ((count( $venda->matrizes )>1)?'s':''); ?>:</b></div>
	<div class="col-md-9">
		@foreach( $venda->matrizes as $matriz )
		<b>{{ $matriz['matriz']['bilhete'] }}</b>:
		<div class="text-center" style="padding-bottom: 5px;">
		@php
		if(!$venda->confirmada)
			echo '<s>';
		
		$matches = array();
		preg_match( '/([0-9]+)([^0-9]+)/', $matriz['matriz']['combinacoes'], $matches );
		$chunk = explode( end( $matches ), $matriz['matriz']['combinacoes'] );

		foreach( $chunk as $k => $c ){
			echo $c.' ';
			if( in_array( $k, [ 9, 19 ] ) )
				echo '<br>';
		}
		if(!$venda->confirmada)
			echo '</s>';
		@endphp
		@if(isset($matriz['matriz']['extra']))
		<b>Chance Extra</b>: @if(!$venda->confirmada)<s>@endif{{ $matriz['matriz']['extra'] }}@if(!$venda->confirmada)</s>@endif
		@endif
		</div>
		@endforeach
	</div>
	</div>

	<div class="row table-bordered">
		<div class="col-md-12">
			<div class="text-center">
			<br>Sorteio {{ $venda->etapa->etapa }} ( {{ date('d/m/Y',strtotime($venda->etapa->data)) }} ) <br>
			<span style="font-size: 18pt;">{{ $venda->etapa->descricao }}</span><br><br>
			</div>
			<div>
			@foreach( $venda->etapa->premiacao as $premio )
				{{ $premio->seq }}&ordm; Prêmio: <b>{{ $premio->descricao }}</b> - R$ {{ Helper::formatDecimalToView($premio->liquido) }}<br>
			@endforeach
			</div>
			<div class="text-center">
			@if($venda->etapa->premiacaoEletronica->count())
			<br>e mais <b>{{ $venda->etapa->premiacaoEletronica->count() }}</b> prêmios de <b>R$ {{ Helper::formatDecimalToView($venda->etapa->premiacaoEletronica[0]->liquido) }}</b>
			@endif
			</div>
			<br><br>
		</div>
	</div>
@endif
		<br>
		<center>Acesso: {{ date("d/m/Y \à\s H:i") }}</center>
		<br>
</div>
@endsection
@section('scripts')
<script>
	$(document).ready(function(){

        var timeouts = [];
        var countdow = 0;
        function contagemRegressivaParaRedirecionamento(){
            if( countdow ){
                countdow--;
                $('#countdow').html( countdow );
                timeouts.push( setTimeout(function(){ contagemRegressivaParaRedirecionamento(); }, 1000) );
            } else {
                window.location.href = "{{ env('SITE_URL') }}";
            }
        }
        
        function mostraBannerInformandoRedireccionamento(){

            for (var i=0; i<timeouts.length; i++) {
                clearTimeout(timeouts[i]);
            }
            timeouts = [];
            countdow = 30; // segundos

            setTimeout(function(){
                const text = document.createElement('p');
                text.append('Dentro de ');
                
                const span = document.createElement('span');
                span.setAttribute("id", "countdow");
                span.style.cssText = 'font-weight: bold;';
                span.append(countdow);
                text.appendChild(span);

                const label = document.createElement('span');
                label.style.cssText = 'font-weight: bold;';
                label.append(' segundos');
                text.appendChild(label);

                text.append(' você será redirecionado para o site, caso queira cancelar temporariamente, clique no botão abaixo.');

                swal({
                    icon: 'info',
                    title: 'Você será redirecionado...',
                    content: text,
                    button: "Espere mais um pouco!",
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    closeModal: true,
                }).then((value) => {
                    mostraBannerInformandoRedireccionamento();
                });
                contagemRegressivaParaRedirecionamento();
            }, 60000 ); // 1 minuto
        }
        mostraBannerInformandoRedireccionamento();

    });
</script>
@endsection