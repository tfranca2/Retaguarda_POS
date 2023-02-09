<?php use App\Helpers;?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-card recent-activites">
                <div class="panel-heading">
                    {{ ((isset($premiacao))?'Editar':'Nova') }} Premiação da Etapa
                    <div class="pull-right">
                        <div class="btn-group">
                            @if( Helper::temPermissao('premiacao-listar') )
                                <a href="<?php echo url('/premiacao'); ?>" class="btn btn-info btn-xs"><span
                                        class="fa fa-list"></span> Lista</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    @if( isset($premiacao) )
                        <form action="{{ url('/premiacao/'.$premiacao->id) }}" method="post"
                              enctype="multipart/form-data" class="form-edit" data-parsley-validate>
                            @method('PUT')
                            @else
                                <form action="{{ url('/premiacao') }}" method="post" enctype="multipart/form-data"
                                      class="form-edit" data-parsley-validate>
                                    @endif
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3 p-lr-o">
                                            <div class="form-group">
                                                <label for="">Nº Etapa</label>
                                                <select name="etapa_id" id="etapa_id" class="form-control" required>
                                                @forelse( $etapas_ativas as $etapa )
                                                @php
                                                 $check = '';
                                                 if( isset($premiacao) && $premiacao->etapa_id == $etapa->id )
                                                    $check = 'selected="selected"';
                                                @endphp
                                                    <option value="{{ $etapa->id }}" {{ $check }}>{{ $etapa->etapa }} - {{ $etapa->descricao }}</option>
                                                @empty
                                                    <option>Sem Etapa ativa</option>
                                                @endforelse
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 p-lr-o">
                                            <div class="form-group">
                                                <label for="">Sequencia</label>
                                                <input type="number" class="form-control" name="seq" id="seq" min="{{(isset($premiacao) and $premiacao->seq)?$premiacao->seq:$proximas_sequencias[0]}}" value="{{(isset($premiacao) and $premiacao->seq)?$premiacao->seq:$proximas_sequencias[0]}}" >
                                            </div>
                                        </div>
                                        <div class="col-md-7 p-lr-o">
                                            <div class="form-group">
                                                <label for="">Prêmiação</label>
                                                <input type="text" class="form-control" name="premiacao"
                                                       value="{{(isset($premiacao) and $premiacao->premiacao)?$premiacao->premiacao:''}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 p-lr-o">
                                            <div class="form-group">
                                                <label for="">Descrição</label>
                                                <input type="text" class="form-control" name="descricao"
                                                       value="{{(isset($premiacao) and $premiacao->descricao)?$premiacao->descricao:''}}">
                                            </div>
                                        </div>
                                        <div class="col-md-2 p-lr-o">
                                            <div class="form-group">
                                                <label for="">Bruto</label>
                                                <input type="text" class="form-control" name="bruto"
                                                       value="{{(isset($premiacao) and $premiacao->bruto)?$premiacao->bruto:''}}">
                                            </div>
                                        </div>
                                        <div class="col-md-2 p-lr-o">
                                            <div class="form-group">
                                                <label for="">Liquido</label>
                                                <input type="text" class="form-control" name="liquido"
                                                       value="{{(isset($premiacao) and $premiacao->liquido)?$premiacao->liquido:''}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 p-lr-o">
                                            <div class="form-group">
                                                @if( Helper::temPermissao('premiacao-incluir') || Helper::temPermissao('premiacao-editar') )
                                                <br><input type="submit" value="Salvar" class="btn btn-info pull-right">@endif
                                            </div>
                                        </div>
                                    </div>
                                </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        $('#etapa_id').change(function(){
            if( $('#etapa_id').prop("selectedIndex") == 0 ){
                $('#seq').val({{ $proximas_sequencias[0] }});
                $('#seq').attr('min', {{ $proximas_sequencias[0] }});
            } else {
                $('#seq').val({{ $proximas_sequencias[1] }});
                $('#seq').attr('min', {{ $proximas_sequencias[1] }});
            }
        });
    });
</script>
@endsection