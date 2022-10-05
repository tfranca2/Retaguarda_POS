<?php $empresa = \DB::table('empresa')->first(); ?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $empresa->nome }}</title>
        <!-- <link rel="shortcut icon" type="image/png" href="<?php echo url('/'); ?>/assets/imgs/favicon.png" /> -->
        <link rel="shortcut icon" type="image/png" href="{{ url('/public/images/'.$empresa->favicon) }}" />

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/css/app.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/css/custom.css">

        <link href="https://fonts.googleapis.com/css2?family=Exo:wght@300;600&display=swap" rel="stylesheet"> 
        
        <!-- Styles -->
        <style>

            * {
                font-family: 'Exo', sans-serif;
            }

            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
                font-size: 16px;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            input[type="checkbox"] {
                margin-left: 0px !important;
                position: initial;
            }
        </style>
    </head>
    <body>

    <center>
        <br>
        <a href="{{ url('/') }}"><img src="{{ url('/public/images/'.$empresa->main_logo ) }}" alt="{{ $empresa->nome }}" style="max-width: 250px; max-height: 105px;"></a><br>
        <br>
    </center>

    @yield('content')

    @if(Session::has('error'))
    <div class="row">
        <div class="col-md-6 col-md-offset-3 text-center">
            <p class="alert alert-danger alert-dismissible show">{{ Session::get('error') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p>
        </div>
    </div>
    @endif

    <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/switchery/dist/switchery.min.css"/>
    <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/switchery/dist/switchery.min.css"/>
    <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/css//select2.min.css"/>
    <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/css/toastr.min.css">
    <script src="<?php echo url('/'); ?>/assets/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo url('/'); ?>/assets/bower_components/switchery/dist/switchery.min.js"></script>
    <script src="<?php echo url('/'); ?>/assets/js/sweetalert.min.js"></script>
    <script src="<?php echo url('/'); ?>/assets/js/parsley.min.js"></script>
    <script src="<?php echo url('/'); ?>/assets/js/parsley.pt-br.js"></script>
    <script src="<?php echo url('/'); ?>/assets/js/jquery.mask.min.js"></script>
    <script src="<?php echo url('/'); ?>/assets/js/toastr.min.js"></script>
    <script src="<?php echo url('/'); ?>/assets/js/select2.min.js"></script>
    <script src="<?php echo url('/'); ?>/assets/js/i18n/pt-BR.js"></script>
    <script>var base_url = "{{ url('/') }}";</script>
    <script src="{{asset('assets/js/custom.js')}}"></script>
    
    @yield('scripts')
    
    </body>
</html>
