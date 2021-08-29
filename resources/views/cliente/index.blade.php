<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-card recent-activites">
                <div class="panel-heading">
                    Clientes
                    <div class="pull-right">
                        <div class="btn-group">
                            @if( Helper::temPermissao('cidades-incluir') )
                                <a href="<?php echo url('/'); ?>/cliente/create" class="btn btn-info btn-xs"><span
                                        class="fa fa-plus"></span> Novo</a>
                            @endif
                        </div>
                    </div>
                </div>
                <?php dd($clientes)?>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>CPF</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Cidade</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse( $clientes as $cliente )
                                <tr>
                                    <td>{{$cliente->id}}</td>
                                    <td>{{$cliente->CPF}}</td>
                                    <td>{{$cliente->nome}}</td>
                                    <td>{{$cliente->email}}</td>
                                    <td>{{$cliente->telefone}}</td>
                                    <td>{{$cliente->cidade_id}}</td>
                                    <td class="text-center">
                                        @if( Helper::temPermissao('bairros-editar') )
                                            <a href="{{ url('/cliente/'.$cliente->id.'/edit') }}" class="btn btn-info" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        @endif
                                        @if( Helper::temPermissao('bairros-excluir') )
                                            <form action="{{url('/cliente/'.$cliente->id)}}" method="POST" style="display: inline-block;">
                                                @method('DELETE') @csrf
                                                <button type="submit" class="btn btn-danger form-delete" title="Apagar"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100" class="text-center">Sem resultados para listar</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
