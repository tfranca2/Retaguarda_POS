<?php 

    $empresa = \DB::table('empresa')->first();

?>
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
        <link rel="stylesheet" href="<?php echo url('/'); ?>/css/app.css">

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
            <img src="{{url('/assets/imgs')}}/termocancelamento.png" alt="{{ $empresa->nome }}" style="max-width: 100%;">
        </center>
    </body>
</html>
