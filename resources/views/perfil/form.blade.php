<?php 

use App\Helpers; 
use App\Menu; 

$grupos = Helper::grupos();
$menus = Menu::orderBy('label')->get()->pluck('label')->toArray();
foreach ( $menus as $menu ){
	$grupos[strtolower(Helper::sanitizeString($menu))] = ['listar','incluir','editar','excluir','gerenciar'];
}

?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($perfil))?'Editar':'Novo') }} perfil
				<div class="pull-right">
					<div class="btn-group">
						<a href="<?php echo url('/perfis'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($perfil) ) 
						<form action="{{ url('/perfis/'.$perfil->id) }}" method="post" class="form-edit" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/perfis') }}" method="post" class="form-edit" data-parsley-validate> 
					@endif
					@csrf
					<div class="form-group">
						<div class="col-md-10 p-lr-o">
							<label for="">Nome</label>
							<input type="text" class="form-control" name="nome" placeholder="Nome" required="" value="{{ (isset($perfil)?$perfil->nome:'') }}" >
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2 p-lr-o">
							<label for="">Ativo?</label><br>
							<input type="checkbox" name="ativo" 
							@if( isset( $perfil ) )
								{{ (($perfil->deleted_at)?'':'checked') }}
							@else
								checked
							@endif
							>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12 p-lr-o">
							<br><h4>Permissões</h4><br>
							<table class="table table-striped table-bordered">
								<thead>
									<tr>
										<th></th>
										<th>Módulo</th>
										@foreach( array_values( $grupos[array_key_first( Helper::grupos() )] ) as $permissao )
										<th><label class="checkbox-inline i-checks" title="Marcar todos"><input type="checkbox" class="all" onclick="checkAll(this)" data-permission="{{$permissao}}"><i></i></label> {{$permissao}}</th>
										@endforeach
									</tr>
								</thead>
								<tbody>
									<?php foreach( $grupos as $key => $permissao ): ?>
								    <tr>
								        <td>
								        	<label class="checkbox-inline i-checks" title="Marcar todos"><input type="checkbox" class="all" onclick="checkAll(this)" data-module="{{ $key }}"><i></i></label>
								        </td>
								        <td><?=$key?></td>
								        <?php foreach( $permissao as $k => $atributo ): ?>
								        <td class="tc">
								            <label class='checkbox-inline i-checks'>
								                <?php if( isset($perfil) ){ ?>
								                     <input type='checkbox' name='permissao[<?=$key?>-<?=$atributo?>]' class="<?=$key?> <?=$atributo?>" value='true' onclick="uncheck(this)" <?php echo (( Helper::perfilTemPermissao($perfil->id, $key.'-'.$atributo))?'checked':'') ?>><i></i>
								                 <?php }else{ ?>
								                    <input type='checkbox' name='permissao[<?=$key?>-<?=$atributo?>]' class="<?=$key?> <?=$atributo?>" value='true' onclick="uncheck(this)"><i></i>
								                 <?php } ?>
								            </label>
								        </td>
								        <?php endforeach?>
								    </tr>
								    <?php endforeach?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12 p-lr-o">
							<br><input type="submit" value="Salvar" class="btn btn-info pull-right">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
    function checkAll( obj ){ 
        if( $(obj).is(':checked') ){
            $('.'+$(obj).data('permission')).prop('checked',true);
            $('.'+$(obj).data('module')).prop('checked',true);
        } else {
            $('.'+$(obj).data('permission')).prop('checked',false);
            $('.'+$(obj).data('module')).prop('checked',false);
        }
    }

    function uncheck( obj ){ 
    	classList = $(obj).attr('class').split(/\s+/);
        $(".all[data-module='"+ classList[0] +"']").prop('checked',false);
        $(".all[data-permission='"+ classList[1] +"']").prop('checked',false);
    } 
</script>
@endsection