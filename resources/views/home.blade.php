<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <select name="etapa" id="etapa" class="form-control select2">
                @foreach( $etapas as $etapa )
                <option value="{{ $etapa->id }}" @if( isset( $_GET['etapa_id'] ) and $_GET['etapa_id'] == $etapa->id ) selected="selected" @endif >{{ $etapa->descricao }}</option>
                @endforeach
            </select>
            <br><br>
        </div>
        @if( session('status') )
        <div class="col-md-12">
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        </div>
        @endif
    </div>
    @if( Helper::temPermissao('empresas-excluir') )
    <div class="row">
        <div class="col-md-4">
            <div class="panel short-states bg-light" style="margin: 0;">
                <img src="{{ url('/public/images/'. \Auth::user()->empresa()->main_logo ) }}" style="max-width: 100%; max-height: 110px; display: block; margin: auto;">
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel short-states bg-1">
                <div class="pull-right state-icon"><i class="fa fa-line-chart"></i></div>
                <div class="panel-body">
                    <h1 id="vendasCount">{{ $vendasCount }}</h1>
                    <strong class="text-uppercase">Vendas</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel short-states bg-2">
                <div class="pull-right state-icon"><i class="fa fa-dollar"></i></div>
                <div class="panel-body">
                    <h1 id="vendasTotal">{{ $vendasTotal }}</h1>
                    <strong class="text-uppercase">Faturamento</strong>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel short-states bg-3">
                <div class="pull-right state-icon"><i class="fa fa-globe"></i></div>
                <div class="panel-body">
                    <h1 id="online">{{ $online }}</h1>
                    <strong class="text-uppercase">Online Agora</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel short-states bg-4">
                <div class="pull-right state-icon"><i class="fa fa-chrome"></i></div>
                <div class="panel-body">
                    <h1 id="acessos">{{ $acessos }}</h1>
                    <strong class="text-uppercase">Acessos Hoje</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel short-states bg-5">
                <div class="pull-right state-icon"><i class="fa fa-exclamation-circle"></i></div>
                <div class="panel-body">
                    <h1 id="leads">{{ $leads }}</h1>
                    <strong class="text-uppercase">Leads</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel charts">
                <h3 class="text-center">Distribuição de Vendas na Semana</h3>
                <canvas id="vendas_por_dia"></canvas>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel charts">
                <h3 class="text-center">Distribuição de Vendas por Hora</h3>
                <canvas id="vendas_por_hora"></canvas>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel charts">
                <h3 class="text-center">Faturamento por Etapa</h3>
                <canvas id="vendas_por_etapa"></canvas>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-md-12" style="background: url('{{ url("/public/images/". \Auth::user()->empresa()->main_logo ) }}') no-repeat center /contain; height: 190px;" >
        </div>
    </div>
    @endif
    <br><br>
<style>

    .short-states h1 {
        color: inherit !important;
        margin-top: 0px;
    }

    .short-states {
        text-align: center;
    }

    .state-icon {
        position: absolute;
        right: 0;
    }
    
    small {
        font-size: 70%;
    }
    
    .ui-content-body {
        margin-bottom: 0px !important;
    }

    #content {
        /*background: url('{{ url('/public/images/'. \Auth::user()->empresa()->main_logo ) }}') no-repeat 50% 40%;*/
    }
    @media (max-width: 812px) {
        #content {
            background-size: 60%;
            background-position: 50%;
        }
    }
    @media (max-width: 400px) {
        #content {
            /*background: url('{{ url('/public/images/'. \Auth::user()->empresa()->main_logo ) }}') no-repeat 50% 39%;*/
            background-size: 90%;
        }
    }

    .charts {
        padding: 20px;
        padding-top: 0px;
    }
    .charts h3{
        color: {{ \Auth::user()->empresa()->menu_background }};
    }

    @foreach( $colors as $key => $color )
    .bg-{{ $key + 1 }} { background: {{ $color }}; color: {{ \Auth::user()->empresa()->menu_color }}; }
    @endforeach

</style>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){

        var optionsGraficoLegendaNoTopo = {
            hover: {
                animationDuration: 0
            },
            animation: {
                duration: 1,
                onComplete: function() {
                    var chartInstance = this.chart,
                    ctx = chartInstance.ctx;

                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';

                    this.data.datasets.forEach(function(dataset, i) {
                        var meta = chartInstance.controller.getDatasetMeta(i);
                        meta.data.forEach(function(bar, index) {
                            var data = dataset.data[index];
                            ctx.fillText( chartInstance.config.prefix + data, bar._model.x, bar._model.y - 5);
                        });
                    });
                }
            },
            legend: {
                display: false
            },
            tooltips: {
                enabled: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        display: true,
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        };
        
        var vendas_por_dia = new Chart( document.getElementById("vendas_por_dia").getContext("2d"), {
            type: 'bar',
            prefix: '',
            data: <?php echo json_encode( $vendas_por_dia ); ?>,
            options: optionsGraficoLegendaNoTopo,
        });
        
        var vendas_por_hora = new Chart( document.getElementById("vendas_por_hora").getContext("2d"), {
            type: 'line',
            data: <?php echo json_encode( $vendas_por_hora ); ?>,
            options: { legend: { display: false } },
        });

        var vendas_por_etapa = new Chart( document.getElementById("vendas_por_etapa").getContext("2d"), {
            type: 'bar',
            prefix: 'R$ ',
            data: <?php echo json_encode( $vendas_por_etapa ); ?>,
            options: optionsGraficoLegendaNoTopo,
        });

        function atualizaDash(){
            etapa_id = $('#etapa option:selected').val();
            $.ajax({
                type: "GET",
                url: '{{ url("/info") }}',
                data: { etapa_id: etapa_id },
                success: function( data ){
                    $('#vendasCount').html(data.vendasCount);
                    $('#vendasTotal').html(data.vendasTotal);
                    $('#leads').html(data.leads);
                    $('#online').html(data.online);
                    $('#acessos').html(data.acessos);

                    vendas_por_dia.data.labels = data.vendas_por_dia.labels;
                    vendas_por_dia.data.datasets = data.vendas_por_dia.datasets;
                    vendas_por_dia.update('none');

                    vendas_por_hora.data.labels = data.vendas_por_hora.labels;
                    vendas_por_hora.data.datasets = data.vendas_por_hora.datasets;
                    vendas_por_hora.update('none');

                    vendas_por_etapa.data.labels = data.vendas_por_etapa.labels;
                    vendas_por_etapa.data.datasets = data.vendas_por_etapa.datasets;
                    vendas_por_etapa.update('none');
                }
            });
        }
        $('#etapa').change(function(){ atualizaDash(); });
        setInterval(function(){ atualizaDash(); }, 10000); // 10s

    });
</script>
@endsection
