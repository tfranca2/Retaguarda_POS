<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')

    @if (session('status'))
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        </div>
    </div>
    @endif

    @if( Helper::temPermissao('empresas-excluir') )
    <!-- <div class="row">
        <div class="col-md-6">
            <div class=" short-states bg-light">
                <img src="{{ url('/public/images/'. \Auth::user()->empresa()->main_logo ) }}" style="max-width: 100%; max-height: 110px; display: block; margin: auto;">
            </div>
        </div> 
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <div class="panel short-states bg-1" style="background: {{ \Auth::user()->empresa()->menu_background }};">
                <div class="pull-right state-icon"><i class="fa fa-money"></i></div>
                <div class="panel-body">
                    <h1>{{ $usuarios }}</h1>
                    <strong class="text-uppercase">Usuários</strong>
                </div>
            </div>
        </div>
    </div> -->
    <div class="row">
        <div class="col-md-12 bg-0" style="padding-bottom: 15px; border-right: 5px solid darkgray;">
            <label>ETAPAS</label><br>
            <select name="" id="" class="form-control select2" ></select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 bg-0 br10tr">
            <label>VENDAS <i class="fa fa-line-chart"></i></label><br>
            <div class="col-md-12 bg-4 btn text-left">Total: 0 vendas</div>
            <div class="col-md-12 bg-3 btn text-left">Cartão: 0 vendas</div>
            <div class="col-md-12 bg-2 btn text-left">Manual: 0 vendas <span class="pull-right">0%</span></div>
        </div>
        <div class="col-md-6 bg-0 bl10tr">
            <label>TOTAL <i class="fa fa-usd"></i></label><br>
            <div class="col-md-12 bg-4 btn text-left">Total: R$ 0,00</div>
            <div class="col-md-12 bg-3 btn text-left">Cartão: R$ 0,00</div>
            <div class="col-md-12 bg-2 btn text-left">Manual: R$ 0,00 <span class="pull-right">0%</span></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 bg-0 br10tr">
            <label>APP <i class="fa fa-mobile"></i></label><br>
            <div class="col-md-12 bg-3 btn text-left">Total: R$ 0,00 <span class="pull-right">0%</span></div>
            <div class="col-md-12 bg-1 btn text-left">Quantidade: 0 <span class="pull-right">0%</span></div>
        </div>
        <div class="col-md-6 bg-0 bl10tr">
            <label>WEB <i class="fa fa-desktop"></i></label><br>
            <div class="col-md-12 bg-3 btn text-left">Total: R$ 0,00 <span class="pull-right">0%</span></div>
            <div class="col-md-12 bg-1 btn text-left">Quantidade: <span class="pull-right">0%</span></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 bg-0 br10tr">
            <label>VENDAS VENDEDOR <i class="fa fa-line-chart"></i></label>
            <div class="col-md-12 bg-4 btn text-left">0 <span class="pull-right">0%</span></div>
        </div>
        <div class="col-md-3 bg-0 bl10tr br10tr">
            <label>TOTAL VENDEDOR <i class="fa fa-usd"></i></label>
            <div class="col-md-12 bg-4 btn text-left">R$ 0,00 <span class="pull-right">0%</span></div>
        </div>
        <div class="col-md-3 bg-0 br10tr bl10tr">
            <label>VENDAS DIRETA <i class="fa fa-line-chart"></i></label>
            <div class="col-md-12 bg-4 btn text-left">0 <span class="pull-right">0%</span></div>
        </div>
        <div class="col-md-3 bg-0 bl10tr">
            <label>TOTAL DIRETA <i class="fa fa-usd"></i></label>
            <div class="col-md-12 bg-4 btn text-left">R$ 0,00 <span class="pull-right">0%</span></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 bg-0 br10tr">
            <span class="pull-left">
                <label>Vendas por dia</label><br>
                19/04/2021 - 25/04/2021
            </span>
            <span class="pull-right">19/04/2021 - 25/04/2021 <i class="fa fa-calendar"></i></span>
            <table class="table">
                <tr>
                    <th>Data</th>
                    <th>Dia da semana</th>
                    <th>Praça</th>
                    <th>Quantidade</th>
                    <th>Valor</th>
                </tr>
            </table>
        </div>
        <div class="col-md-6 bg-0 bl10tr">
            <span class="pull-left">
                <label>Certificados vendidos</label><br>
                Sorteio: 19/04/2021 - 25/04/2021
            </span>
            <span class="pull-right">19/04/2021 - 25/04/2021 <i class="fa fa-calendar"></i></span>
            <table class="table">
                <tr>
                    <th>Praça</th>
                    <th>Etapa</th>
                    <th>Tipo</th>
                    <th>Quantidade</th>
                    <th>Valor</th>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 bg-0" style="padding-bottom: 15px; border-right: 5px solid darkgray;">
            <label>Vendas por Etapa <i class="fa fa-bar-chart"></i></label><br>
            <canvas id="vendasGraf"></canvas>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-md-12" style="background: url('{{ url("/public/images/". \Auth::user()->empresa()->main_logo ) }}') no-repeat center /contain; height: 190px;" >
        </div>
    </div>
    @endif

<style>

    .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
        margin-bottom: 15px;
    }

    .text-left {
        text-align: left;
    }

    .col-md-1.btn, .col-md-2.btn, .col-md-3.btn, .col-md-4.btn, .col-md-5.btn, .col-md-6.btn, .col-md-7.btn, .col-md-8.btn, .col-md-9.btn, .col-md-10.btn, .col-md-11.btn, .col-md-12.btn { 
        color: #fff;
        font-size: 18px;
    }

    .col-md-1.btn:hover, .col-md-2.btn:hover, .col-md-3.btn:hover, .col-md-4.btn:hover, .col-md-5.btn:hover, .col-md-6.btn:hover, .col-md-7.btn:hover, .col-md-8.btn:hover, .col-md-9.btn:hover, .col-md-10.btn:hover, .col-md-11.btn:hover, .col-md-12.btn:hover { 
        opacity: 0.9;
    }

    .bg-0 {
        background: #fff;
        border-radius: 0.7em;
        padding-top: 10px;
        border-bottom: 1px solid darkgray;
    }

    .br10tr { border-right: 5px solid darkgray }
    .bl10tr { border-left: 5px solid #eaeef3; border-right: 5px solid darkgray }

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
</style>
@endsection
