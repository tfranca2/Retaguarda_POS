<?php use App\Helpers; ?>
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
    <body style="background: #fff; max-width: 100%; overflow-x: hidden;">

<div class="row" style="margin: 0px;">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites" style="margin-bottom: 0px;padding-bottom: 20px;">
			<div class="row">
				<div class="col-md-12">
					<br>
					<center>
						<a href="{{url('/faqs')}}">
							<img src="{{ url('/public/images/'.$empresa->main_logo ) }}" alt="{{ $empresa->nome }}" style="max-width: 150px; max-height: 100px;">
						</a>
						<h2>FAQ</h2>
					</center>

					<form action="">
						<div class="row">
							<div class="col-sm-12">
								<div class="input-group">
									<input type="text" name="q" id="q" placeholder="Pesquisar..." value="{{$q}}" class="form-control">
									<span class="input-group-btn">
										<button class="btn btn-primary btn-block"><i class="fa fa-search"></i> Pesquisar</button>
									</span>
								</div>
							</div>
						</div>
					</form>

					<div id="accordion">
						@forelse( $faqs as $faq )
						<div class="card">
							<div class="card-header" id="heading{{$faq->id}}">
								<h5 class="mb-0">
									<button class="btn collapsed" data-toggle="collapse" data-target="#collapse{{$faq->id}}" aria-expanded="false" aria-controls="collapse{{$faq->id}}">
										{{ $faq->titulo }}
									</button>
								</h5>
							</div>
							<div id="collapse{{$faq->id}}" class="collapse" aria-labelledby="heading{{$faq->id}}" data-parent="#accordion">
								<div class="card-body">
									{{ $faq->descricao }}
								</div>
							</div>
						</div>
						@empty
						<div class="text-center">
							<br><br>
							Nenhum tópico encontrado
						@if( $q )
							referente a pesquisa "<b>{{$q}}</b>"...
							<br><br>
							<a href="{{url('/faqs')}}" style="color: #e55e72;"><i class="fa fa-long-arrow-left"></i> Voltar</a>
						@endif
						</div>
						@endforelse
					</div>
					<div class="pull-right">
						<br>
						{{ $faqs->onEachSide(1)->appends( request()->input() )->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<style>
		.btn-primary {
			background: #e55e72;
			border-color: #e55e72;
			color: #fff;
		}
		.btn-primary:hover ,
		.btn-primary:active ,
		.btn-primary:focus {
			background: #e55e72 !important;
			border-color: #e55e72 !important;
			color: #fff;
			opacity: 0.9;
		}
		.card .card-header {
			width: 100%;
			background: #e55e72;
			border-radius: 5px 5px 5px 5px;
			margin-bottom: -7px;
			position: relative;
			z-index: 1;
		}
		.card .card-header h5 {
			margin-top: 15px;
			margin-bottom: 0;
		}
		.card .card-header button {
			width: 100%;
			text-align: left;
			padding-left: 30px;
			padding-right: 30px;
			color: #fff;
			white-space:normal !important;
    		word-wrap: break-word; 
		}
		.card .card-header button:active {
			color: #fff;
		}
		.card .card-body {
			padding: 30px;
			padding-top: 15px;
			background: #ffc6cf;
			text-align: justify;
			border-radius: 0px 0px 5px 5px;
		}
		.pagination > .active > a, .pagination > .active > a:hover, .pagination > .active > a:focus, .pagination > .active > span, .pagination > .active > span:hover, .pagination > .active > span:focus {
			background: #e55e72;
		}
		.pagination > .active > a, .pagination > .active > a:hover, .pagination > .active > a:focus, .pagination > .active > span, .pagination > .active > span:hover, .pagination > .active > span:focus {
			border-color: #e55e72;
		}
		.pagination > li > a:hover, .pagination > li > a:focus, .pagination > li > span:hover, .pagination > li > span:focus {
			background: #e55e72;
			border-color: #e55e72;
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function(){
			var $myGroup = $('#accordion');
			$myGroup.on('show.bs.collapse','.collapse', function() {
			    $myGroup.find('.collapse.in').collapse('hide');
			});
		});
	</script>
 	<script src="{{asset('assets/bower_components/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/site.js')}}"></script>
    <script src="{{asset('assets/bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="<?php echo url('/'); ?>/assets/js/toastr.min.js"></script>
    <script>
        var base_url = "{{ url('/') }}";
        var google_maps_api_key = "{{ $empresa->google_maps_api_key }}";
    </script>

    <script src="{{asset('assets/js/custom.js')}}"></script>

    </body>
</html>
