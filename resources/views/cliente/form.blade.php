<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-card recent-activites">
                <div class="panel-heading">
                    {{ ((isset($premiacao))?'Editar':'Novo') }} Cliente
                    <div class="pull-right">
                        <div class="btn-group">
                            @if( Helper::temPermissao('cidades-listar') )
                                <a href="<?php echo url('/cliente'); ?>" class="btn btn-info btn-xs"><span
                                        class="fa fa-list"></span> Lista</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    @if( isset($premiacao) )
                        <form action="{{ url('/cliente/'.$cliente->id) }}" method="post"
                              enctype="multipart/form-data" class="form-edit" data-parsley-validate>
                            @method('PUT')
                            @else
                                <form action="{{ url('/cliente') }}" method="post" enctype="multipart/form-data"
                                      class="form-edit" data-parsley-validate>
                                    @endif
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-2 p-lr-o">
                                            <div class="form-group">
                                                <label for="">CPF</label>
                                                <input type="text" class="form-control" name="CPF" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-2 p-lr-o">
                                            <div class="form-group">
                                                <label for="">Nome Cliente</label>
                                                <input type="text" class="form-control" name="nome" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-2 p-lr-o">
                                            <div class="form-group">
                                                <label for="">E-mail</label>
                                                <input type="email" class="form-control" name="email"
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="col-md-2 p-lr-o">
                                            <div class="form-group">
                                                <label for="">DDD</label>
                                                <input type="text" class="form-control" name="DDD"
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="col-md-2 p-lr-o">
                                            <div class="form-group">
                                                <label for="">Telefone</label>
                                                <input type="text" class="form-control" name="telefone"
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="col-md-2 p-lr-o">
                                            <div class="form-group">
                                                <label for="">Cidade</label>
                                                <select name="cidade_id" class="form-control select2CidadeAjax">
                                                    <option value="">Selecione</option>
                                                    @forelse( $cidades as $cidade )
                                                        @php
                                                            $estado = $cidade->estado()->first();
                                                        @endphp
                                                        <option value="{{$cidade->id}}"
                                                                selected="selected">{{$cidade->nome}} @if( $estado ) {{ '- '. $estado->uf }} @endif</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>
                                    </div>

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
@endsection
