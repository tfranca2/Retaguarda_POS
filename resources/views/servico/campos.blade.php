<?php use App\Helpers; ?>
<div class="row">
	<div class="col-md-12">
				<hr>
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				Informações adicionais
			</div>
			<div class="panel-body">
				@if( $servico->campos )
				@forelse( json_decode( $servico->campos ) as $campo )
				<div class="row">
					@if( !in_array( $campo->tipo_campo, [ 'hidden' ] ) )
					<div class="col-md-4 col-md-offset-1">
					@if( $campo->icone_campo )
						<i class="fa {{ $campo->icone_campo }}"></i>
					@endif
						<label for="">{{ ucfirst( $campo->nome_campo ) }}: </label>
					@if( in_array( $campo->tipo_campo, [ 'text', 'number', 'date' ] ) )
						<input type="{{ $campo->tipo_campo }}" class="form-control" name="{{ $campo->nome_campo }}" value="{{ $campo->valor_padrao }}" min='0' required="">
					@elseif( in_array( $campo->tipo_campo, [ 'file' ] ) )
						<div class="customfile">
	                        <input type="file" name="{{ $campo->nome_campo }}" id="file" required="">
	                        <span class="file_name"></span>
	                        <label class="btn btn-info" for="file"><i class="fa fa-photo"></i> Procurar</label>
                    	</div>
					@elseif( in_array( $campo->tipo_campo, [ 'checkbox', 'radio' ] ) )
						@if( $campo->valor_padrao )
						@foreach( explode( ',', $campo->valor_padrao ) as $item )
						<br><input type="{{ $campo->tipo_campo }}" name="{{ $campo->nome_campo }}{{ (( $campo->tipo_campo == 'checkbox' )?'[]':'') }}" value="{{ $item }}" required=""> {{ ucfirst( $item ) }}
						@endforeach
						@endif
					@elseif( in_array( $campo->tipo_campo, [ 'select' ] ) )
						<select name="{{ $campo->nome_campo }}" class="form-control" required="">
							<option></option>
							@if( $campo->valor_padrao )
							@foreach( explode( ',', $campo->valor_padrao ) as $item )
							<option value="{{ $item }}">{{ ucfirst( $item ) }}</option>
							@endforeach
							@endif
						</select>
					@elseif( in_array( $campo->tipo_campo, [ 'textarea' ] ) )
						<textarea name="{{ $campo->nome_campo }}" class="form-control" cols="30" rows="5" required="">{{ $campo->valor_padrao }}</textarea>
					@endif
					</div>
					@else
					<input type="hidden" name="{{ $campo->nome_campo }}" value="{{ $campo->valor_padrao }}">
					@endif
				</div>
				@empty
					<label for="">Sem campos para exibição...</label>
				@endforelse
				@if( $servico->pos_servico and false )
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
