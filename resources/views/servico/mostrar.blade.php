<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				Detalhes do serviço
				<div class="pull-right">
					<a href="<?php echo url('/servicos'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
					@if( Helper::temPermissao('servicos-editar') )
					<a href="{{ url('/servicos/'.$servico->id.'/edit') }}" class="btn btn-info btn-xs" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i> editar</a>
					@endif
				</div>
			</div>
			<div class="panel-body">
				<h4>{{ ucfirst( $servico->nome ) }}</h4>
				<hr>
				@if( $servico->campos )
				@forelse( json_decode( $servico->campos ) as $campo )
				<div class="row">
					@if( !in_array( $campo->tipo_campo, [ 'hidden' ] ) )
					<div class="col-md-4 col-md-offset-1">
					@if( $campo->icone_campo )
						<i class="fa {{ $campo->icone_campo }}"></i>
					@endif
						<label for="">{{ ucfirst( $campo->nome_campo ) }}: </label>
					@if( in_array( $campo->tipo_campo, [ 'text', 'number', 'date', 'file' ] ) )
						<input type="{{ $campo->tipo_campo }}" class="form-control" name="{{ $campo->nome_campo }}" value="{{ $campo->valor_padrao }}">
					@elseif( in_array( $campo->tipo_campo, [ 'checkbox', 'radio' ] ) )
						@if( $campo->valor_padrao )
						@foreach( explode( ',', $campo->valor_padrao ) as $item )
						<br><input type="{{ $campo->tipo_campo }}" name="{{ $campo->nome_campo }}{{ (( $campo->tipo_campo == 'checkbox' )?'[]':'') }}" value="{{ $item }}"> {{ ucfirst( $item ) }}
						@endforeach
						@endif
					@elseif( in_array( $campo->tipo_campo, [ 'select' ] ) )
						<select name="{{ $campo->nome_campo }}" class="form-control">
							<option></option>
							@if( $campo->valor_padrao )
							@foreach( explode( ',', $campo->valor_padrao ) as $item )
							<option value="{{ $item }}">{{ ucfirst( $item ) }}</option>
							@endforeach
							@endif
						</select>
					@elseif( in_array( $campo->tipo_campo, [ 'textarea' ] ) )
						<textarea name="{{ $campo->nome_campo }}" class="form-control" cols="30" rows="5">{{ $campo->valor_padrao }}</textarea>
					@endif
					</div>
					@else
					<input type="hidden" name="{{ $campo->nome_campo }}" value="{{ $campo->valor_padrao }}">
					@endif
				</div>
				@empty
					<label for="">Sem campos para exibição...</label>
				@endforelse
				@if( $servico->pos_servico )
				<div class="row">
					<div class="col-md-12">
						<br><hr>
					</div>
					<div class="col-md-4 col-md-offset-1">
						<label for="">Check-List - Pós Serviço</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						@php
						$pos_servico = (Array) explode( ',', $servico->pos_servico );
						@endphp
						@forelse( $pos_servico as $pos_servico )
						<br><input type="checkbox" name="{{ $pos_servico }}"> {{ ucfirst($pos_servico) }}
						@empty
						@endforelse
					</div>
				</div>
				@endif
				@else
					<label for="">Sem campos para exibição...</label>
				@endif
				<hr>
				<br>
			</div>
		</div>
	</div>
</div>
<style>
	.col-md-4 > label {
		margin-top: 15px;
	}
</style>
@endsection
