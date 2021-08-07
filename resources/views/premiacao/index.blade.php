<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-card recent-activites">
                <div class="panel-heading">
                    Premiação
                    <div class="pull-right">
                        <div class="btn-group">
                            @if( Helper::temPermissao('cidades-incluir') )
                                <a href="<?php echo url('/'); ?>/premiacao/create" class="btn btn-info btn-xs"><span
                                        class="fa fa-plus"></span> Novo</a>
                                <a href="<?php echo url('/'); ?>/premiacaoeletronica/create" class="btn btn-info btn-xs"><span
                                        class="fa fa-plus"></span> Novo Rodada Especial</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Nº Etapa</th>
                                <th>Sequência</th>
                                <th>Descrição prêmio</th>
                                <th>Valor</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse( $premiacoes as $premiacao )
                                <tr>
                                    @php
                                        //$estado = $cidade->estado()->first();
                                    @endphp
                                    <td>{{ $premiacao->etapa_id }}</td>
                                    <td>{{ $premiacao->seq }} º</td>
                                    <td>{{ $premiacao->descricao }}</td>
                                    <td>{{ $premiacao->bruto }}</td>
                                    <td class="text-center">
                                        @if( Helper::temPermissao('cidades-editar') )
                                            <a href="{{ url('/premiacao/'.$premiacao->id.'/edit') }}"
                                               class="btn btn-info" title="Editar"><i class="fa fa-pencil"
                                                                                      aria-hidden="true"></i></a>
                                        @endif
                                        @if( Helper::temPermissao('cidades-excluir') )
                                            <form action="{{url('/premiacao/'.$premiacao->id)}}" method="POST"
                                                  style="display: inline-block;">
                                                @method('DELETE') @csrf
                                                <button type="submit" class="btn btn-danger form-delete" title="Apagar">
                                                    <i class="fa fa-trash" aria-hidden="true"></i></button>
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
                    {{ $premiacoes->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
