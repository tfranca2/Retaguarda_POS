<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body"><br></div>
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

        </div>
    </div>
    @if( Helper::temPermissao('empresas-excluir') )
    <div class="row">
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
    </div>
    @else
    <div class="row">
        <div class="col-md-12" style="background: url('{{ url("/public/images/". \Auth::user()->empresa()->main_logo ) }}') no-repeat center /contain; height: 190px;" >
        </div>
    </div>
    @endif

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
</style>
@endsection
