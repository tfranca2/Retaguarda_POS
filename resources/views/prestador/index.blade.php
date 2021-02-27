<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				prestadores
				<div class="pull-right">
					<div class="btn-group">
						<a href="#" id="toggleFilter" class="btn btn-link pull-right" title="Mostrar Filtros">Filtros <i class="fa fa-chevron-down"></i></a>
					</div>
					<div class="btn-group">
						@if( Helper::temPermissao('prestadores-incluir') )
						<a href="<?php echo url('/'); ?>/prestadores/create" class="btn btn-info btn-xs"><span class="fa fa-plus"></span> Novo</a>
						@endif
					</div>
				</div>
			</div>
			<div class="panel-body">

				<div id="filter" style="display: none;">
					<form action="{{ url('/prestadores') }}" method="GET">
						<div class="row">
							
							<div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label for="">Nome</label>
									<input type="text" class="form-control" name="nome" value="{{ ((isset( $_GET['nome'] ))?$_GET['nome']:'') }}" placeholder="Nome">
								</div>
							</div>
							<div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label for="">CPF</label>
									<input type="text" class="form-control" name="cpf" value="{{ ((isset( $_GET['cpf'] ))?$_GET['cpf']:'') }}" placeholder="CPF">
								</div>
							</div>
							<div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label for="">Telefone</label>
									<input type="text" class="form-control telefone" name="telefone" value="{{ ((isset( $_GET['telefone'] ))?$_GET['telefone']:'') }}" placeholder="Telefone">
								</div>
							</div>
							<div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label for="">E-mail</label>
									<input type="text" class="form-control" name="email" value="{{ ((isset( $_GET['email'] ))?$_GET['email']:'') }}" placeholder="E-mail">
								</div>
							</div>
							<div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label for="">Serviços Prestados</label>
									<select name="servico_id" class="form-control">
										<option></option>
										@forelse( $servicos as $servico )
										<option value="{{ $servico->id }}"
											<?php if( isset( $_GET['servico_id'] ) and $_GET['servico_id'] == $servico->id ) echo 'selected'; ?>
											>{{ $servico->nome }}</option>
										@empty
										@endforelse
									</select>
								</div>
							</div>
							<div class="col-md-1 p-lr-o">
								<div class="form-group">
									<label for="">Ativo</label>
									<select name="ativo" class="form-control">
										<option></option>
										<option value="1" 
											<?php if( isset( $_GET['ativo'] ) and $_GET['ativo'] == 1 ) echo 'selected'; ?> >Sim</option>
										<option value="0" <?php if( isset( $_GET['ativo'] ) and $_GET['ativo'] != null and $_GET['ativo'] == 0 ) echo 'selected'; ?> >Não</option>
									</select>
								</div>
							</div>
							<div class="col-md-1 p-lr-o">
								<div class="form-group">
									<a href="{{ url('/prestadores') }}" class="btn btn-danger btn-block" title="Limpar Filtros"><i class="fa fa-remove"></i></a>
									<button class="btn btn-info btn-block" title="Filtrar"><i class="fa fa-search"></i></button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<br>

				<div class="table-responsive">
					<table id="basic-datatables" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Imagem</th>
								<th>Nome</th>
								<th>CPF</th>
								<th>Telefone</th>
								<th>E-mail</th>
								<th>Rating</th>
								<th>Serviços Prestados</th>
								<th>Ativo</th>
								<th style="width: 83px;">Ações</th>
							</tr>
						</thead>
						<tbody>
							@forelse( $prestadores as $prestador )
							@php
								$usuario = $prestador->usuario()->first();
							@endphp
							@if( ! $usuario )
								@continue
							@endif
								<tr>
									<td>
										<div class="imgshell" style="background: url('@if( $usuario->imagem ){{ url('/public/images/'.$usuario->imagem ) }}@else{{ url('/public/images/avatar.png' ) }}@endif') no-repeat center / cover;">
										</div>
									</td>
									<td>{{ $usuario->name }}</td>
									<td>{{ Helper::formatCpfCnpj( $usuario->cpf ) }}</td>
									<td>{{ Helper::formatTelefone( $prestador->celular ) }}</td>
									<td style="word-break: break-all;">{{ $usuario->email }}</td>
									<td style="min-width: 83px;">
										<?php
											// star rating
											$inteiro = intval($prestador->rating);
											$decimais = $prestador->rating-intval($prestador->rating);
											$ratingexibicao = number_format( $prestador->rating, 2, ',', '' );
																		
											$stars = array();
											for( $i=0; $i<$inteiro; $i++ )
												$stars[] = '<i class="fa fa-star" title="'.$ratingexibicao.'"></i>'; // cheia
											
											if( $decimais > 0 )
												if( $decimais >= 0.8 )
													$stars[] = '<i class="fa fa-star" title="'.$ratingexibicao.'"></i>'; // cheia
												else
													$stars[] = '<i class="fa fa-star-half-o" title="'.$ratingexibicao.'"></i>'; // meia
											
											$totalstars = 5-count($stars);
											if( count( $stars ) < 5 )
												for( $i=0; $i < $totalstars; $i++ )
													$stars[] = '<i class="fa fa-star-o" title="'.$ratingexibicao.'"></i>'; // vazia
																		
											foreach ( $stars as $star )
												echo $star;
									
										?>
									</td>
									<td>
										<ul>
											@foreach( $prestador->servicos()->get() as $servico )
											@php
												$servico = $servico->servico()->first();
											@endphp
											@if( $servico )
											<li>{{ $servico->nome }}</li>
											@endif
											@endforeach
										</ul>
									</td>
									<td class="text-center">
									@if( $prestador->deleted_at )
										<i class="fa fa-times" aria-hidden="true"></i>
									@else
										<i class="fa fa-check" aria-hidden="true"></i>
									@endif
									</td>
									<td class="text-center actions">
										@if( Helper::temPermissao('prestadores-editar') )
										<a href="{{ url('/prestadores/'.$prestador->id.'/relatorio') }}" class="btn btn-success" title="Relatório"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
										<a href="{{ url('/prestadores/'.$prestador->id.'/edit') }}" class="btn btn-info" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										@endif
										@if( Helper::temPermissao('prestadores-excluir') )
										<form action="{{url('/prestadores/'.$prestador->id)}}" method="POST" style="display: inline-block;">
											@method('DELETE') @csrf
											<button type="submit" class="btn btn-danger form-delete" title="Apagar"><i class="fa fa-trash" aria-hidden="true"></i></button>
										</form>
										@endif
									</td>
								</tr>
							@empty
								<tr><td colspan="7" class="text-center">Sem resultados para listar</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
				{{ $prestadores->links() }}
			</div>
		</div>
	</div>
</div>
<style>
	.actions .btn {
		margin: 5px auto;
	}
</style>
@endsection