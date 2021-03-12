@guest
    @php
        $empresa = \DB::table('empresa')->first();
    @endphp
@else
    @php
        $empresa = \Auth::user()->empresa();
    @endphp
@endguest
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $empresa->nome }}</title>

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- <link rel="shortcut icon" type="image/png" href="<?php echo url('/'); ?>/assets/imgs/favicon.png" /> -->
        <link rel="shortcut icon" type="image/png" href="{{ url('/public/images/'. $empresa->favicon ) }}" />

        <!-- inject:css -->
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/weather-icons/css/weather-icons.min.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/themify-icons/css/themify-icons.css">
        <!-- endinject -->

        <!--Data Table-->
        <link href="<?php echo url('/'); ?>/assets/bower_components/datatables/media/css/jquery.dataTables.css" rel="stylesheet">
        <link href="<?php echo url('/'); ?>/assets/bower_components/datatables-tabletools/css/dataTables.tableTools.css" rel="stylesheet">
        <link href="<?php echo url('/'); ?>/assets/bower_components/datatables-colvis/css/dataTables.colVis.css" rel="stylesheet">
        <link href="<?php echo url('/'); ?>/assets/bower_components/datatables-responsive/css/responsive.dataTables.scss" rel="stylesheet">
        <link href="<?php echo url('/'); ?>/assets/bower_components/datatables-scroller/css/scroller.dataTables.scss" rel="stylesheet">
        <!-- <link href="<?php echo url('/'); ?>/assets/bower_components/jqBootstrapValidation.js" rel="stylesheet"> -->

        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/switchery/dist/switchery.min.css">

        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/dist/css/main.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/dist/css/lightbox.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/dist/css/jquery-ui.css">
        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/bower_components/switchery/dist/switchery.min.css">

        <link rel="stylesheet" href="<?php echo url('/'); ?>/assets/css/toastr.min.css">
        
        <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

        <script src="<?php echo url('/'); ?>/assets/js/modernizr-custom.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/jquery-3.3.1.min.js"></script>
        <!-- <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script> -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>

        <link href="https://fonts.googleapis.com/css2?family=Exo:wght@300;600&display=swap" rel="stylesheet"> 
        
        <style>

            * {
                font-family: 'Exo', sans-serif;
            }

            .lb-data .lb-close {
              background: url('{{ url('/').'/assets/imgs/close.png' }}') no-repeat center;
            }
            #aside {
                background: #e2262e;
                color: #fff;
            }
        </style>
    </head>
    <body>

        <div id="ui" class="ui">

            <!--header start-->
            <header id="header" class="ui-header">

                <div class="navbar-header navbar-header--dark">
                    <!--logo start-->
                    <a href="<?php echo url('/'); ?>/home" class="navbar-brand text-center">
                        <span class="logo">
                            <!-- <img src="<?php echo url('/'); ?>/assets/imgs/logo-light.png" alt="" height="100%" /> -->
                            {{ $empresa->nome }}
                            <img src="{{ url('/public/images/'. $empresa->menu_logo ) }}" height="100%" />
                        </span>
                        <span class="logo-compact">
                            <!-- <img src="<?php echo url('/'); ?>/assets/imgs/logo-icon-light.png" alt=""/> -->
                            <img src="{{ url('/public/images/'. $empresa->contracted_menu_logo ) }}"/>
                        </span>
                    </a>
                    <!--logo end-->
                </div>

                <div class="navbar-collapse nav-responsive-disabled">

                    @guest
                    @else
                    <!--toggle buttons start-->
                    <ul class="nav navbar-nav">
                        <li>
                            <a class="toggle-btn" data-toggle="ui-nav" href="">
                                <i class="fa fa-bars"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- toggle buttons end -->

                    <!--notification start-->
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="{{ url('/perfil') }}" title="Alterar perfil">
                                <div style="width: 23px; height: 23px; margin: auto; border-radius: 5em; overflow: hidden; display: inline-flex; align-items: center; float: left; margin-right: 5px;background: url('@if( Auth::user()->imagem ){{ url('/public/images/'. Auth::user()->imagem ) }}@else{{ url('/public/images/avatar.png' ) }}@endif') no-repeat 100% / cover; margin-top: -3px;">
                                </div>
                                <span class="hidd en-xs">Olá {{ Auth::user()->name }}!</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo url('/'); ?>/logout" title="Sair"><i class="icon-logout"></i></a>
                        </li>
                    </ul>
                    <!--notification end-->
                    @endguest

                </div>

            </header>
            <!--header end-->

            @guest
            @else
            @include('layouts.menu')
            @endguest

            <!--main content start-->
            <div id="content" class="ui-content">
                <div class="ui-content-body">

                    <div class="ui-container">
                        <!--page title and breadcrumb start -->
                        <div class="row">
                            <div class="col-md-8">

                                <input name="url" id="base_url" value="<?php echo url('/');?>" type="hidden" />
                            </div>
                            <div class="col-md-4">
                                <ul class="breadcrumb pull-right">
                                    <li><a href="<?php echo url('/'); ?>/home"><i class="fa fa-home"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <!--page title and breadcrumb end -->

                        <!-- Exibe caixa de alerta -->
                        @if(Session::has('message'))
                            <p class="alert alert-success alert-dismissible show">{{ Session::get('message') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p>
                        @endif
                        @if(Session::has('alert'))
                            <p class="alert alert-warning alert-dismissible show">{{ Session::get('alert') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p>
                        @endif
                        @if(Session::has('error'))
                            <p class="alert alert-danger alert-dismissible show">{{ Session::get('error') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p>
                        @endif
                        <!-- fim caixa de alerta-->
                        
                        <!-- Exibe conteudo da view -->
                        @yield('content')

                        <!-- fim conteudo da view -->
                        <script>
                            $.validate({
                                lang : 'pt'
                            });
                        </script>
                    </div>

                </div>
            </div>
            <!--main content end-->

            <!--footer start-->

            <!--footer end-->

        </div>

        <script src="{{asset('assets/bower_components/jquery/dist/jquery.min.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>
        <!-- inject:js -->
        <script src="<?php echo url('/'); ?>/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/jquery.nicescroll/dist/jquery.nicescroll.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/autosize/dist/autosize.min.js"></script>
        <!-- endinject -->

        <!--Data Table-->
        <script src="<?php echo url('/'); ?>/assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/datatables-tabletools/js/dataTables.tableTools.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/datatables-colvis/js/dataTables.colVis.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/datatables-responsive/js/dataTables.responsive.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/datatables-scroller/js/dataTables.scroller.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/init-datatables.js"></script>
        <script src="<?php echo url('/'); ?>/assets/dist/js/main.js"></script>
        <script src="<?php echo url('/'); ?>/assets/dist/js/lightbox.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/validator.js"></script>
        <!-- <script src="<?php echo url('/'); ?>/assets/bower_components/switchery/dist/switchery.min.js"></script> -->
        <!-- <script src="<?php echo url('/'); ?>/assets/js/init-switchery.js"></script> -->
        <script src="<?php echo url('/'); ?>/assets/bower_components/input-mask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/bower_components/bootstrap-filestyle/bootstrap-filestyle.js"></script>
        <link rel="stylesheet" href="{{asset('assets/bower_components/fullcalendar/dist/fullcalendar.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/bower_components/fullcalendar/dist/fullcalendar.print.min.css')}}" media="print">

        <script src="<?php echo url('/'); ?>/assets/dist/js/custom.js"></script>
        <script src="<?php echo url('/'); ?>/assets/dist/js/ajax.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/Chart.js"></script>
        <!-- <script src="<?php echo url('/'); ?>/assets/js/init-chartjs.js"></script> -->


        <script src="{{asset('assets/bower_components/jquery/dist/jquery.min.js')}}"></script>
        <script src="{{asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
        <!-- endinject -->
        <script src="{{asset('assets/js/validator.js')}}"></script>
        <script src="{{asset('assets/dist/js/site.js')}}"></script>


        <script type="text/javascript" src="{{asset('assets/bower_components/datetimepicker/bootstrap-datetimepicker.js')}}"></script>
        <script type="text/javascript" src="{{asset('assets/bower_components/datetimepicker/bootstrap-datetimepicker.pt-BR.js')}}"></script>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>
        <script src="{{asset('assets/bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
        <script src="{{asset('assets/bower_components/autosize/dist/autosize.min.js')}}"></script>
        <script src="{{asset('assets/bower_components/fullcalendar/dist/fullcalendar.js')}}"></script>
        <script type="text/javascript" src="{{asset('assets/bower_components/fullcalendar/dist/locale/pt-br.js')}}"></script>
         <!-- <script src="<?php echo url('/'); ?>/assets/bower_components/switchery/dist/switchery.min.js"></script> -->
        <!-- <script src="{{asset('assets/js/init-calendar.js')}}"></script> -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.6/angular.min.js"></script> -->
         <!-- <script type="text/javascript" src="{{asset('assets/js/custom-advanced-form.js')}}"></script> -->

         <!-- <script src="<?php echo url('/'); ?>/assets/dist/js/custom.js"></script> -->
         <script src="<?php echo url('/'); ?>/assets/dist/js/ajax.js"></script>

        <script src="{{asset('assets/js/init-calendar.js')}}"></script>

        <!-- <script src="{{asset('assets/js/init-datepicker.js')}}"></script> -->

        <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.7.0/dist/sweetalert2.all.min.js"></script> -->
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/parsley.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/parsley.pt-br.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/jquery.mask.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/toastr.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/tagsinput.js"></script>
        <link href="<?php echo url('/'); ?>/assets/css/tagsinput.css" rel="stylesheet" />

        <link href="<?php echo url('/'); ?>/assets/css//select2.min.css" rel="stylesheet" />
        <script src="<?php echo url('/'); ?>/assets/js/select2.min.js"></script>
        <script src="<?php echo url('/'); ?>/assets/js/i18n/pt-BR.js"></script>

        <script>
            var base_url = "{{ url('/') }}";
            var google_maps_api_key = "{{ $empresa->google_maps_api_key }}";
        </script>

        <script src="{{asset('assets/js/custom.js')}}"></script>

        @yield('scripts')

    </body>
</html>

