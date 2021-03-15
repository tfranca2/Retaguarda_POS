<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($etapa))?'Editar':'Novo') }} etapa
				<div class="pull-right">
					<div class="btn-group">
						@if( Helper::temPermissao('etapas-listar') )
						<a href="<?php echo url('/etapas'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($etapa) ) 
						<form action="{{ url('/etapas/'.$etapa->id) }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/etapas') }}" method="post" enctype="multipart/form-data" class="form-edit" data-parsley-validate> 
					@endif
					@csrf					
					<div class="row">
						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">Nº Etapa</label>
								<input type="text" class="form-control" name="etapa" value="{{(isset($etapa) and $etapa->etapa)?$etapa->etapa:''}}">
							</div>
						</div>
						<div class="col-md-6 p-lr-o">
							<div class="form-group">
								<label for="">Descrição</label>
								<input type="text" class="form-control" name="descricao" value="{{(isset($etapa) and $etapa->descricao)?$etapa->descricao:''}}">
							</div>
						</div>
						<div class="col-md-4 p-lr-o">
							<div class="form-group">
								<label for="">Data</label>
								<input type="date" class="form-control" name="data" value="{{(isset($etapa) and $etapa->data)?$etapa->data:''}}">
							</div>
						</div>
					</div><!-- /row -->
					<div class="row">
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">Range Inicial</label>
								<input type="text" class="form-control" name="range_inicial" value="{{(isset($etapa) and $etapa->range_inicial)?$etapa->range_inicial:''}}">
							</div>
						</div>
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">Range Final</label>
								<input type="text" class="form-control" name="range_final" value="{{(isset($etapa) and $etapa->range_final)?$etapa->range_final:''}}">
							</div>
						</div>
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">Tipo</label>
								<select name="tipo" id="tipo" class="form-control">
									<option value="-1">Selecione</option>
									<option value="1" @if( isset( $etapa ) and $etapa->tipo == 1 ) echo selected="selected"   @endif >Simples</option>
									<option value="2" @if( isset( $etapa ) and $etapa->tipo == 2 ) echo selected="selected"   @endif >Dupla</option>
									<option value="3" @if( isset( $etapa ) and $etapa->tipo == 3 ) echo selected="selected"   @endif >Tripla</option>
									<option value="4" @if( isset( $etapa ) and $etapa->tipo == 4 ) echo selected="selected"   @endif >Simples e Dupla</option>
									<option value="5" @if( isset( $etapa ) and $etapa->tipo == 5 ) echo selected="selected"   @endif >Simples e Tripla</option>
								</select>
							</div>
						</div>
						<div class="col-md-3 p-lr-o">
							<div class="form-group">
								<label for="">Intervalo</label>
								<input type="text" class="form-control" id="intervalo" name="intervalo" value="{{(isset($etapa) and $etapa->intervalo)?$etapa->intervalo:''}}">
							</div>
						</div>
					</div><!-- /row -->
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label for="">valor Simples</label>
								<input type="text" class="form-control" id="valor_simples" name="valor_simples" value="{{(isset($etapa) and $etapa->valor_simples)?$etapa->valor_simples:''}}">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="">valor Duplo</label>
								<input type="text" class="form-control" id="valor_duplo" name="valor_duplo" value="{{(isset($etapa) and $etapa->valor_duplo)?$etapa->valor_duplo:''}}">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="">valor Triplo</label>
								<input type="text" class="form-control" id="valor_triplo" name="valor_triplo" value="{{(isset($etapa) and $etapa->valor_triplo)?$etapa->valor_triplo:''}}">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="">valor comissão Simpl.</label>
								<input type="text" class="form-control" id="v_comissao_simples" name="v_comissao_simples" value="{{(isset($etapa) and $etapa->v_comissao_simples)?$etapa->v_comissao_simples:''}}">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="">valor comissão Dupla</label>
								<input type="text" class="form-control" id="v_comissao_duplo" name="v_comissao_duplo" value="{{(isset($etapa) and $etapa->v_comissao_duplo)?$etapa->v_comissao_duplo:''}}">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="">valor comissão Tripla</label>
								<input type="text" class="form-control" id="v_comissao_tripla" name="v_comissao_tripla" value="{{(isset($etapa) and $etapa->v_comissao_tripla)?$etapa->v_comissao_tripla:''}}">
							</div>
						</div>
					</div><!-- /row -->
					<div class="row">
						<div class="col-md-12 p-lr-o">
							<div class="form-group">
								<br><input type="submit" value="Salvar" class="btn btn-info pull-right">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	window.onload = function(){
		var tipo = document.getElementById("tipo");
		selecionaStatos();
		tipo.addEventListener('change',selecionaStatos);
		function selecionaStatos(){
			if(tipo.value =="1" ){
				//desabilita também o input intervalo
				document.getElementById("intervalo").disabled = true;
				document.getElementById("valor_simples").disabled = false;
				document.getElementById("v_comissao_simples").disabled = false;

				document.getElementById("valor_duplo").disabled = true;
				document.getElementById("v_comissao_duplo").disabled = true;

				document.getElementById("valor_duplo").value = "";
				document.getElementById("v_comissao_duplo").value = "";
				document.getElementById("intervalo").value = "";


				document.getElementById("valor_triplo").disabled = true;
				document.getElementById("v_comissao_tripla").disabled = true;
			}
			//dupla
			if(tipo.value =="2" ){
				document.getElementById("intervalo").disabled = false; 
				document.getElementById("valor_duplo").disabled = false;
				document.getElementById("v_comissao_duplo").disabled = false;

				document.getElementById("valor_simples").disabled = true;
				document.getElementById("v_comissao_simples").disabled = true;

				document.getElementById("valor_triplo").disabled = true;
				document.getElementById("v_comissao_tripla").disabled = true;
			}
			//tripla
			if(tipo.value =="3" ){
				document.getElementById("intervalo").disabled = false; 
				document.getElementById("valor_triplo").disabled = false;
				document.getElementById("v_comissao_tripla").disabled = false;

				document.getElementById("valor_simples").disabled = true;
				document.getElementById("v_comissao_simples").disabled = true;

				document.getElementById("valor_duplo").disabled = true;
				document.getElementById("v_comissao_duplo").disabled = true;

				document.getElementById("valor_duplo").value = "";
				document.getElementById("v_comissao_duplo").value = "";
			}
			if(tipo.value == 4){
				document.getElementById("valor_simples").disabled = false;
				document.getElementById("v_comissao_simples").disabled = false;
				document.getElementById("valor_duplo").disabled = false;
				document.getElementById("v_comissao_duplo").disabled = false;

				document.getElementById("valor_triplo").disabled = true;
				document.getElementById("v_comissao_tripla").disabled = true;

			}
			if(tipo.value == 5){
				document.getElementById("valor_simples").disabled = false;
				document.getElementById("v_comissao_simples").disabled = false;
				document.getElementById("valor_triplo").disabled = false;
				document.getElementById("v_comissao_tripla").disabled = false;

				document.getElementById("valor_duplo").disabled = true;
				document.getElementById("v_comissao_duplo").disabled = true;
				
			}
		}
	}
</script>
@endsection