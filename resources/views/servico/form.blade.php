<?php use App\Helpers; ?>
@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-card recent-activites">
			<div class="panel-heading">
				{{ ((isset($servico))?'Editar':'Novo') }} tipo de serviço
				<div class="pull-right">
					<div class="btn-group">
						<a href="<?php echo url('/servicos'); ?>" class="btn btn-info btn-xs"><span class="fa fa-list"></span> Lista</a>
					</div>
				</div>
			</div>
			<div class="panel-body">
					@if( isset($servico) ) 
						<form action="{{ url('/servicos/'.$servico->id) }}" method="post" class="form-edit" data-parsley-validate> 
						@method('PUT') 
					@else
						<form action="{{ url('/servicos') }}" method="post" class="form-edit" data-parsley-validate> 
					@endif
					@csrf
					
					<div class="row">
						
						<div class="col-md-10 p-lr-o">
							<div class="form-group">
								<label for="">Nome</label>
								<input type="text" class="form-control" name="nome" placeholder="Nome" required="" value="{{ (isset($servico)?$servico->nome:'') }}" >
							</div>
						</div>

						<div class="col-md-2 p-lr-o">
							<div class="form-group">
								<label for="">Ativo?</label><br>
								<input type="checkbox" name="ativo" 
								@if( isset( $servico ) )
									{{ (($servico->deleted_at)?'':'checked') }}
								@else
									checked
								@endif
								>
							</div>
						</div>

					</div>
					<div class="row">
						
						<div class="col-md-12 p-lr-o">
							<hr>
							<div class="col-sm-3 p-0">
								<div class="form-group">
									<label for="">Campos personalizados</label>
								</div>
							</div>
							<div class="col-sm-1 p-0">
								<div class="form-group">
									<a href="#" id="add-campo" title="Adicionar" class="btn btn-info"><i class="fa fa-plus"></i></a>
								</div>
							</div>
						</div>

					</div>
					<div class="row">
						
						<div class="campo" style="display: none;">
							<div class="col-md-3 p-lr-o">
								<div class="form-group">
									<label for="">Nome do campo</label>
									<input type="text" name="nome_campo[]" placeholder="Nome do campo" class="form-control" value="">
								</div>
							</div>
							<div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label for="">Tipo do campo</label>
									<select name="tipo_campo[]" class="form-control">
										<option value=""></option>
										<option value="text">text</option>
										<option value="number">number</option>
										<option value="select">select</option>
										<option value="checkbox">checkbox</option>
										<option value="radio">radio</option>
										<option value="textarea">textarea</option>
										<option value="date">date</option>
										<option value="file">file</option>
										<option value="hidden">hidden</option>
									</select>
								</div>
							</div>
							<div class="col-md-4 p-lr-o">
								<div class="form-group">
									<label for="">Valores padrão</label>
									<input type="text" name="valor_padrao[]" class="form-control tags" value="" />
								</div>
							</div>
							<div class="col-md-2 p-lr-o">
								<div class="form-group">
									<label for="">Ícone do campo</label>
									<select name="icone_campo[]" class="form-control">
										<option value=""></option>
										<option value="fa-500px"><i class="fa fa-500px"></i> &#xf26e; 500px</option>
										<option value="fa-address-book"><i class="fa fa-address-book"></i> &#xf2b9; address-book</option>
										<option value="fa-address-book-o"><i class="fa fa-address-book-o"></i> &#xf2ba; address-book-o</option>
										<option value="fa-address-card"><i class="fa fa-address-card"></i> &#xf2bb; address-card</option>
										<option value="fa-address-card-o"><i class="fa fa-address-card-o"></i> &#xf2bc; address-card-o</option>
										<option value="fa-adjust"><i class="fa fa-adjust"></i> &#xf042; adjust</option>
										<option value="fa-adn"><i class="fa fa-adn"></i> &#xf170; adn</option>
										<option value="fa-align-center"><i class="fa fa-align-center"></i> &#xf037; align-center</option>
										<option value="fa-align-justify"><i class="fa fa-align-justify"></i> &#xf039; align-justify</option>
										<option value="fa-align-left"><i class="fa fa-align-left"></i> &#xf036; align-left</option>
										<option value="fa-align-right"><i class="fa fa-align-right"></i> &#xf038; align-right</option>
										<option value="fa-amazon"><i class="fa fa-amazon"></i> &#xf270; amazon</option>
										<option value="fa-ambulance"><i class="fa fa-ambulance"></i> &#xf0f9; ambulance</option>
										<option value="fa-american-sign-language-interpreting"><i class="fa fa-american-sign-language-interpreting"></i> &#xf2a3; american-sign-language-interpreting</option>
										<option value="fa-anchor"><i class="fa fa-anchor"></i> &#xf13d; anchor</option>
										<option value="fa-android"><i class="fa fa-android"></i> &#xf17b; android</option>
										<option value="fa-angellist"><i class="fa fa-angellist"></i> &#xf209; angellist</option>
										<option value="fa-angle-double-down"><i class="fa fa-angle-double-down"></i> &#xf103; angle-double-down</option>
										<option value="fa-angle-double-left"><i class="fa fa-angle-double-left"></i> &#xf100; angle-double-left</option>
										<option value="fa-angle-double-right"><i class="fa fa-angle-double-right"></i> &#xf101; angle-double-right</option>
										<option value="fa-angle-double-up"><i class="fa fa-angle-double-up"></i> &#xf102; angle-double-up</option>
										<option value="fa-angle-down"><i class="fa fa-angle-down"></i> &#xf107; angle-down</option>
										<option value="fa-angle-left"><i class="fa fa-angle-left"></i> &#xf104; angle-left</option>
										<option value="fa-angle-right"><i class="fa fa-angle-right"></i> &#xf105; angle-right</option>
										<option value="fa-angle-up"><i class="fa fa-angle-up"></i> &#xf106; angle-up</option>
										<option value="fa-apple"><i class="fa fa-apple"></i> &#xf179; apple</option>
										<option value="fa-archive"><i class="fa fa-archive"></i> &#xf187; archive</option>
										<option value="fa-area-chart"><i class="fa fa-area-chart"></i> &#xf1fe; area-chart</option>
										<option value="fa-arrow-circle-down"><i class="fa fa-arrow-circle-down"></i> &#xf0ab; arrow-circle-down</option>
										<option value="fa-arrow-circle-left"><i class="fa fa-arrow-circle-left"></i> &#xf0a8; arrow-circle-left</option>
										<option value="fa-arrow-circle-o-down"><i class="fa fa-arrow-circle-o-down"></i> &#xf01a; arrow-circle-o-down</option>
										<option value="fa-arrow-circle-o-left"><i class="fa fa-arrow-circle-o-left"></i> &#xf190; arrow-circle-o-left</option>
										<option value="fa-arrow-circle-o-right"><i class="fa fa-arrow-circle-o-right"></i> &#xf18e; arrow-circle-o-right</option>
										<option value="fa-arrow-circle-o-up"><i class="fa fa-arrow-circle-o-up"></i> &#xf01b; arrow-circle-o-up</option>
										<option value="fa-arrow-circle-right"><i class="fa fa-arrow-circle-right"></i> &#xf0a9; arrow-circle-right</option>
										<option value="fa-arrow-circle-up"><i class="fa fa-arrow-circle-up"></i> &#xf0aa; arrow-circle-up</option>
										<option value="fa-arrow-down"><i class="fa fa-arrow-down"></i> &#xf063; arrow-down</option>
										<option value="fa-arrow-left"><i class="fa fa-arrow-left"></i> &#xf060; arrow-left</option>
										<option value="fa-arrow-right"><i class="fa fa-arrow-right"></i> &#xf061; arrow-right</option>
										<option value="fa-arrow-up"><i class="fa fa-arrow-up"></i> &#xf062; arrow-up</option>
										<option value="fa-arrows"><i class="fa fa-arrows"></i> &#xf047; arrows</option>
										<option value="fa-arrows-alt"><i class="fa fa-arrows-alt"></i> &#xf0b2; arrows-alt</option>
										<option value="fa-arrows-h"><i class="fa fa-arrows-h"></i> &#xf07e; arrows-h</option>
										<option value="fa-arrows-v"><i class="fa fa-arrows-v"></i> &#xf07d; arrows-v</option>
										<option value="fa-asl-interpreting"><i class="fa fa-asl-interpreting"></i> &#xf2a3; asl-interpreting</option>
										<option value="fa-assistive-listening-systems"><i class="fa fa-assistive-listening-systems"></i> &#xf2a2; assistive-listening-systems</option>
										<option value="fa-asterisk"><i class="fa fa-asterisk"></i> &#xf069; asterisk</option>
										<option value="fa-at"><i class="fa fa-at"></i> &#xf1fa; at</option>
										<option value="fa-audio-description"><i class="fa fa-audio-description"></i> &#xf29e; audio-description</option>
										<option value="fa-automobile"><i class="fa fa-automobile"></i> &#xf1b9; automobile</option>
										<option value="fa-backward"><i class="fa fa-backward"></i> &#xf04a; backward</option>
										<option value="fa-balance-scale"><i class="fa fa-balance-scale"></i> &#xf24e; balance-scale</option>
										<option value="fa-ban"><i class="fa fa-ban"></i> &#xf05e; ban</option>
										<option value="fa-bandcamp"><i class="fa fa-bandcamp"></i> &#xf2d5; bandcamp</option>
										<option value="fa-bank"><i class="fa fa-bank"></i> &#xf19c; bank</option>
										<option value="fa-bar-chart"><i class="fa fa-bar-chart"></i> &#xf080; bar-chart</option>
										<option value="fa-bar-chart-o"><i class="fa fa-bar-chart-o"></i> &#xf080; bar-chart-o</option>
										<option value="fa-barcode"><i class="fa fa-barcode"></i> &#xf02a; barcode</option>
										<option value="fa-bars"><i class="fa fa-bars"></i> &#xf0c9; bars</option>
										<option value="fa-bath"><i class="fa fa-bath"></i> &#xf2cd; bath</option>
										<option value="fa-bathtub"><i class="fa fa-bathtub"></i> &#xf2cd; bathtub</option>
										<option value="fa-battery"><i class="fa fa-battery"></i> &#xf240; battery</option>
										<option value="fa-battery-0"><i class="fa fa-battery-0"></i> &#xf244; battery-0</option>
										<option value="fa-battery-1"><i class="fa fa-battery-1"></i> &#xf243; battery-1</option>
										<option value="fa-battery-2"><i class="fa fa-battery-2"></i> &#xf242; battery-2</option>
										<option value="fa-battery-3"><i class="fa fa-battery-3"></i> &#xf241; battery-3</option>
										<option value="fa-battery-4"><i class="fa fa-battery-4"></i> &#xf240; battery-4</option>
										<option value="fa-battery-empty"><i class="fa fa-battery-empty"></i> &#xf244; battery-empty</option>
										<option value="fa-battery-full"><i class="fa fa-battery-full"></i> &#xf240; battery-full</option>
										<option value="fa-battery-half"><i class="fa fa-battery-half"></i> &#xf242; battery-half</option>
										<option value="fa-battery-quarter"><i class="fa fa-battery-quarter"></i> &#xf243; battery-quarter</option>
										<option value="fa-battery-three-quarters"><i class="fa fa-battery-three-quarters"></i> &#xf241; battery-three-quarters</option>
										<option value="fa-bed"><i class="fa fa-bed"></i> &#xf236; bed</option>
										<option value="fa-beer"><i class="fa fa-beer"></i> &#xf0fc; beer</option>
										<option value="fa-behance"><i class="fa fa-behance"></i> &#xf1b4; behance</option>
										<option value="fa-behance-square"><i class="fa fa-behance-square"></i> &#xf1b5; behance-square</option>
										<option value="fa-bell"><i class="fa fa-bell"></i> &#xf0f3; bell</option>
										<option value="fa-bell-o"><i class="fa fa-bell-o"></i> &#xf0a2; bell-o</option>
										<option value="fa-bell-slash"><i class="fa fa-bell-slash"></i> &#xf1f6; bell-slash</option>
										<option value="fa-bell-slash-o"><i class="fa fa-bell-slash-o"></i> &#xf1f7; bell-slash-o</option>
										<option value="fa-bicycle"><i class="fa fa-bicycle"></i> &#xf206; bicycle</option>
										<option value="fa-binoculars"><i class="fa fa-binoculars"></i> &#xf1e5; binoculars</option>
										<option value="fa-birthday-cake"><i class="fa fa-birthday-cake"></i> &#xf1fd; birthday-cake</option>
										<option value="fa-bitbucket"><i class="fa fa-bitbucket"></i> &#xf171; bitbucket</option>
										<option value="fa-bitbucket-square"><i class="fa fa-bitbucket-square"></i> &#xf172; bitbucket-square</option>
										<option value="fa-bitcoin"><i class="fa fa-bitcoin"></i> &#xf15a; bitcoin</option>
										<option value="fa-black-tie"><i class="fa fa-black-tie"></i> &#xf27e; black-tie</option>
										<option value="fa-blind"><i class="fa fa-blind"></i> &#xf29d; blind</option>
										<option value="fa-bluetooth"><i class="fa fa-bluetooth"></i> &#xf293; bluetooth</option>
										<option value="fa-bluetooth-b"><i class="fa fa-bluetooth-b"></i> &#xf294; bluetooth-b</option>
										<option value="fa-bold"><i class="fa fa-bold"></i> &#xf032; bold</option>
										<option value="fa-bolt"><i class="fa fa-bolt"></i> &#xf0e7; bolt</option>
										<option value="fa-bomb"><i class="fa fa-bomb"></i> &#xf1e2; bomb</option>
										<option value="fa-book"><i class="fa fa-book"></i> &#xf02d; book</option>
										<option value="fa-bookmark"><i class="fa fa-bookmark"></i> &#xf02e; bookmark</option>
										<option value="fa-bookmark-o"><i class="fa fa-bookmark-o"></i> &#xf097; bookmark-o</option>
										<option value="fa-braille"><i class="fa fa-braille"></i> &#xf2a1; braille</option>
										<option value="fa-briefcase"><i class="fa fa-briefcase"></i> &#xf0b1; briefcase</option>
										<option value="fa-btc"><i class="fa fa-btc"></i> &#xf15a; btc</option>
										<option value="fa-bug"><i class="fa fa-bug"></i> &#xf188; bug</option>
										<option value="fa-building"><i class="fa fa-building"></i> &#xf1ad; building</option>
										<option value="fa-building-o"><i class="fa fa-building-o"></i> &#xf0f7; building-o</option>
										<option value="fa-bullhorn"><i class="fa fa-bullhorn"></i> &#xf0a1; bullhorn</option>
										<option value="fa-bullseye"><i class="fa fa-bullseye"></i> &#xf140; bullseye</option>
										<option value="fa-bus"><i class="fa fa-bus"></i> &#xf207; bus</option>
										<option value="fa-buysellads"><i class="fa fa-buysellads"></i> &#xf20d; buysellads</option>
										<option value="fa-cab"><i class="fa fa-cab"></i> &#xf1ba; cab</option>
										<option value="fa-calculator"><i class="fa fa-calculator"></i> &#xf1ec; calculator</option>
										<option value="fa-calendar"><i class="fa fa-calendar"></i> &#xf073; calendar</option>
										<option value="fa-calendar-check-o"><i class="fa fa-calendar-check-o"></i> &#xf274; calendar-check-o</option>
										<option value="fa-calendar-minus-o"><i class="fa fa-calendar-minus-o"></i> &#xf272; calendar-minus-o</option>
										<option value="fa-calendar-o"><i class="fa fa-calendar-o"></i> &#xf133; calendar-o</option>
										<option value="fa-calendar-plus-o"><i class="fa fa-calendar-plus-o"></i> &#xf271; calendar-plus-o</option>
										<option value="fa-calendar-times-o"><i class="fa fa-calendar-times-o"></i> &#xf273; calendar-times-o</option>
										<option value="fa-camera"><i class="fa fa-camera"></i> &#xf030; camera</option>
										<option value="fa-camera-retro"><i class="fa fa-camera-retro"></i> &#xf083; camera-retro</option>
										<option value="fa-car"><i class="fa fa-car"></i> &#xf1b9; car</option>
										<option value="fa-caret-down"><i class="fa fa-caret-down"></i> &#xf0d7; caret-down</option>
										<option value="fa-caret-left"><i class="fa fa-caret-left"></i> &#xf0d9; caret-left</option>
										<option value="fa-caret-right"><i class="fa fa-caret-right"></i> &#xf0da; caret-right</option>
										<option value="fa-caret-square-o-down"><i class="fa fa-caret-square-o-down"></i> &#xf150; caret-square-o-down</option>
										<option value="fa-caret-square-o-left"><i class="fa fa-caret-square-o-left"></i> &#xf191; caret-square-o-left</option>
										<option value="fa-caret-square-o-right"><i class="fa fa-caret-square-o-right"></i> &#xf152; caret-square-o-right</option>
										<option value="fa-caret-square-o-up"><i class="fa fa-caret-square-o-up"></i> &#xf151; caret-square-o-up</option>
										<option value="fa-caret-up"><i class="fa fa-caret-up"></i> &#xf0d8; caret-up</option>
										<option value="fa-cart-arrow-down"><i class="fa fa-cart-arrow-down"></i> &#xf218; cart-arrow-down</option>
										<option value="fa-cart-plus"><i class="fa fa-cart-plus"></i> &#xf217; cart-plus</option>
										<option value="fa-cc"><i class="fa fa-cc"></i> &#xf20a; cc</option>
										<option value="fa-cc-amex"><i class="fa fa-cc-amex"></i> &#xf1f3; cc-amex</option>
										<option value="fa-cc-diners-club"><i class="fa fa-cc-diners-club"></i> &#xf24c; cc-diners-club</option>
										<option value="fa-cc-discover"><i class="fa fa-cc-discover"></i> &#xf1f2; cc-discover</option>
										<option value="fa-cc-jcb"><i class="fa fa-cc-jcb"></i> &#xf24b; cc-jcb</option>
										<option value="fa-cc-mastercard"><i class="fa fa-cc-mastercard"></i> &#xf1f1; cc-mastercard</option>
										<option value="fa-cc-paypal"><i class="fa fa-cc-paypal"></i> &#xf1f4; cc-paypal</option>
										<option value="fa-cc-stripe"><i class="fa fa-cc-stripe"></i> &#xf1f5; cc-stripe</option>
										<option value="fa-cc-visa"><i class="fa fa-cc-visa"></i> &#xf1f0; cc-visa</option>
										<option value="fa-certificate"><i class="fa fa-certificate"></i> &#xf0a3; certificate</option>
										<option value="fa-chain"><i class="fa fa-chain"></i> &#xf0c1; chain</option>
										<option value="fa-chain-broken"><i class="fa fa-chain-broken"></i> &#xf127; chain-broken</option>
										<option value="fa-check"><i class="fa fa-check"></i> &#xf00c; check</option>
										<option value="fa-check-circle"><i class="fa fa-check-circle"></i> &#xf058; check-circle</option>
										<option value="fa-check-circle-o"><i class="fa fa-check-circle-o"></i> &#xf05d; check-circle-o</option>
										<option value="fa-check-square"><i class="fa fa-check-square"></i> &#xf14a; check-square</option>
										<option value="fa-check-square-o"><i class="fa fa-check-square-o"></i> &#xf046; check-square-o</option>
										<option value="fa-chevron-circle-down"><i class="fa fa-chevron-circle-down"></i> &#xf13a; chevron-circle-down</option>
										<option value="fa-chevron-circle-left"><i class="fa fa-chevron-circle-left"></i> &#xf137; chevron-circle-left</option>
										<option value="fa-chevron-circle-right"><i class="fa fa-chevron-circle-right"></i> &#xf138; chevron-circle-right</option>
										<option value="fa-chevron-circle-up"><i class="fa fa-chevron-circle-up"></i> &#xf139; chevron-circle-up</option>
										<option value="fa-chevron-down"><i class="fa fa-chevron-down"></i> &#xf078; chevron-down</option>
										<option value="fa-chevron-left"><i class="fa fa-chevron-left"></i> &#xf053; chevron-left</option>
										<option value="fa-chevron-right"><i class="fa fa-chevron-right"></i> &#xf054; chevron-right</option>
										<option value="fa-chevron-up"><i class="fa fa-chevron-up"></i> &#xf077; chevron-up</option>
										<option value="fa-child"><i class="fa fa-child"></i> &#xf1ae; child</option>
										<option value="fa-chrome"><i class="fa fa-chrome"></i> &#xf268; chrome</option>
										<option value="fa-circle"><i class="fa fa-circle"></i> &#xf111; circle</option>
										<option value="fa-circle-o"><i class="fa fa-circle-o"></i> &#xf10c; circle-o</option>
										<option value="fa-circle-o-notch"><i class="fa fa-circle-o-notch"></i> &#xf1ce; circle-o-notch</option>
										<option value="fa-circle-thin"><i class="fa fa-circle-thin"></i> &#xf1db; circle-thin</option>
										<option value="fa-clipboard"><i class="fa fa-clipboard"></i> &#xf0ea; clipboard</option>
										<option value="fa-clock-o"><i class="fa fa-clock-o"></i> &#xf017; clock-o</option>
										<option value="fa-clone"><i class="fa fa-clone"></i> &#xf24d; clone</option>
										<option value="fa-close"><i class="fa fa-close"></i> &#xf00d; close</option>
										<option value="fa-cloud"><i class="fa fa-cloud"></i> &#xf0c2; cloud</option>
										<option value="fa-cloud-download"><i class="fa fa-cloud-download"></i> &#xf0ed; cloud-download</option>
										<option value="fa-cloud-upload"><i class="fa fa-cloud-upload"></i> &#xf0ee; cloud-upload</option>
										<option value="fa-cny"><i class="fa fa-cny"></i> &#xf157; cny</option>
										<option value="fa-code"><i class="fa fa-code"></i> &#xf121; code</option>
										<option value="fa-code-fork"><i class="fa fa-code-fork"></i> &#xf126; code-fork</option>
										<option value="fa-codepen"><i class="fa fa-codepen"></i> &#xf1cb; codepen</option>
										<option value="fa-codiepie"><i class="fa fa-codiepie"></i> &#xf284; codiepie</option>
										<option value="fa-coffee"><i class="fa fa-coffee"></i> &#xf0f4; coffee</option>
										<option value="fa-cog"><i class="fa fa-cog"></i> &#xf013; cog</option>
										<option value="fa-cogs"><i class="fa fa-cogs"></i> &#xf085; cogs</option>
										<option value="fa-columns"><i class="fa fa-columns"></i> &#xf0db; columns</option>
										<option value="fa-comment"><i class="fa fa-comment"></i> &#xf075; comment</option>
										<option value="fa-comment-o"><i class="fa fa-comment-o"></i> &#xf0e5; comment-o</option>
										<option value="fa-commenting"><i class="fa fa-commenting"></i> &#xf27a; commenting</option>
										<option value="fa-commenting-o"><i class="fa fa-commenting-o"></i> &#xf27b; commenting-o</option>
										<option value="fa-comments"><i class="fa fa-comments"></i> &#xf086; comments</option>
										<option value="fa-comments-o"><i class="fa fa-comments-o"></i> &#xf0e6; comments-o</option>
										<option value="fa-compass"><i class="fa fa-compass"></i> &#xf14e; compass</option>
										<option value="fa-compress"><i class="fa fa-compress"></i> &#xf066; compress</option>
										<option value="fa-connectdevelop"><i class="fa fa-connectdevelop"></i> &#xf20e; connectdevelop</option>
										<option value="fa-contao"><i class="fa fa-contao"></i> &#xf26d; contao</option>
										<option value="fa-copy"><i class="fa fa-copy"></i> &#xf0c5; copy</option>
										<option value="fa-copyright"><i class="fa fa-copyright"></i> &#xf1f9; copyright</option>
										<option value="fa-creative-commons"><i class="fa fa-creative-commons"></i> &#xf25e; creative-commons</option>
										<option value="fa-credit-card"><i class="fa fa-credit-card"></i> &#xf09d; credit-card</option>
										<option value="fa-credit-card-alt"><i class="fa fa-credit-card-alt"></i> &#xf283; credit-card-alt</option>
										<option value="fa-crop"><i class="fa fa-crop"></i> &#xf125; crop</option>
										<option value="fa-crosshairs"><i class="fa fa-crosshairs"></i> &#xf05b; crosshairs</option>
										<option value="fa-css3"><i class="fa fa-css3"></i> &#xf13c; css3</option>
										<option value="fa-cube"><i class="fa fa-cube"></i> &#xf1b2; cube</option>
										<option value="fa-cubes"><i class="fa fa-cubes"></i> &#xf1b3; cubes</option>
										<option value="fa-cut"><i class="fa fa-cut"></i> &#xf0c4; cut</option>
										<option value="fa-cutlery"><i class="fa fa-cutlery"></i> &#xf0f5; cutlery</option>
										<option value="fa-dashboard"><i class="fa fa-dashboard"></i> &#xf0e4; dashboard</option>
										<option value="fa-dashcube"><i class="fa fa-dashcube"></i> &#xf210; dashcube</option>
										<option value="fa-database"><i class="fa fa-database"></i> &#xf1c0; database</option>
										<option value="fa-deaf"><i class="fa fa-deaf"></i> &#xf2a4; deaf</option>
										<option value="fa-deafness"><i class="fa fa-deafness"></i> &#xf2a4; deafness</option>
										<option value="fa-dedent"><i class="fa fa-dedent"></i> &#xf03b; dedent</option>
										<option value="fa-delicious"><i class="fa fa-delicious"></i> &#xf1a5; delicious</option>
										<option value="fa-desktop"><i class="fa fa-desktop"></i> &#xf108; desktop</option>
										<option value="fa-deviantart"><i class="fa fa-deviantart"></i> &#xf1bd; deviantart</option>
										<option value="fa-diamond"><i class="fa fa-diamond"></i> &#xf219; diamond</option>
										<option value="fa-digg"><i class="fa fa-digg"></i> &#xf1a6; digg</option>
										<option value="dollar"><i class="fa dollar"></i> &#xf155; lar</option>
										<option value="fa-dot-circle-o"><i class="fa fa-dot-circle-o"></i> &#xf192; dot-circle-o</option>
										<option value="fa-download"><i class="fa fa-download"></i> &#xf019; download</option>
										<option value="fa-dribbble"><i class="fa fa-dribbble"></i> &#xf17d; dribbble</option>
										<option value="fa-drivers-license"><i class="fa fa-drivers-license"></i> &#xf2c2; drivers-license</option>
										<option value="fa-drivers-license-o"><i class="fa fa-drivers-license-o"></i> &#xf2c3; drivers-license-o</option>
										<option value="fa-dropbox"><i class="fa fa-dropbox"></i> &#xf16b; dropbox</option>
										<option value="fa-drupal"><i class="fa fa-drupal"></i> &#xf1a9; drupal</option>
										<option value="fa-edge"><i class="fa fa-edge"></i> &#xf282; edge</option>
										<option value="fa-edit"><i class="fa fa-edit"></i> &#xf044; edit</option>
										<option value="fa-eercast"><i class="fa fa-eercast"></i> &#xf2da; eercast</option>
										<option value="fa-eject"><i class="fa fa-eject"></i> &#xf052; eject</option>
										<option value="fa-ellipsis-h"><i class="fa fa-ellipsis-h"></i> &#xf141; ellipsis-h</option>
										<option value="fa-ellipsis-v"><i class="fa fa-ellipsis-v"></i> &#xf142; ellipsis-v</option>
										<option value="fa-empire"><i class="fa fa-empire"></i> &#xf1d1; empire</option>
										<option value="fa-envelope"><i class="fa fa-envelope"></i> &#xf0e0; envelope</option>
										<option value="fa-envelope-o"><i class="fa fa-envelope-o"></i> &#xf003; envelope-o</option>
										<option value="fa-envelope-open"><i class="fa fa-envelope-open"></i> &#xf2b6; envelope-open</option>
										<option value="fa-envelope-open-o"><i class="fa fa-envelope-open-o"></i> &#xf2b7; envelope-open-o</option>
										<option value="fa-envelope-square"><i class="fa fa-envelope-square"></i> &#xf199; envelope-square</option>
										<option value="fa-envira"><i class="fa fa-envira"></i> &#xf299; envira</option>
										<option value="fa-eraser"><i class="fa fa-eraser"></i> &#xf12d; eraser</option>
										<option value="fa-etsy"><i class="fa fa-etsy"></i> &#xf2d7; etsy</option>
										<option value="fa-eur"><i class="fa fa-eur"></i> &#xf153; eur</option>
										<option value="fa-euro"><i class="fa fa-euro"></i> &#xf153; euro</option>
										<option value="fa-exchange"><i class="fa fa-exchange"></i> &#xf0ec; exchange</option>
										<option value="fa-exclamation"><i class="fa fa-exclamation"></i> &#xf12a; exclamation</option>
										<option value="fa-exclamation-circle"><i class="fa fa-exclamation-circle"></i> &#xf06a; exclamation-circle</option>
										<option value="fa-exclamation-triangle"><i class="fa fa-exclamation-triangle"></i> &#xf071; exclamation-triangle</option>
										<option value="fa-expand"><i class="fa fa-expand"></i> &#xf065; expand</option>
										<option value="fa-expeditedssl"><i class="fa fa-expeditedssl"></i> &#xf23e; expeditedssl</option>
										<option value="fa-external-link"><i class="fa fa-external-link"></i> &#xf08e; external-link</option>
										<option value="fa-external-link-square"><i class="fa fa-external-link-square"></i> &#xf14c; external-link-square</option>
										<option value="fa-eye"><i class="fa fa-eye"></i> &#xf06e; eye</option>
										<option value="fa-eye-slash"><i class="fa fa-eye-slash"></i> &#xf070; eye-slash</option>
										<option value="fa-eyedropper"><i class="fa fa-eyedropper"></i> &#xf1fb; eyedropper</option>
										<option value="fa-fa"><i class="fa fa-fa"></i> &#xf2b4; fa</option>
										<option value="fa-facebook"><i class="fa fa-facebook"></i> &#xf09a; facebook</option>
										<option value="fa-facebook-f"><i class="fa fa-facebook-f"></i> &#xf09a; facebook-f</option>
										<option value="fa-facebook-official"><i class="fa fa-facebook-official"></i> &#xf230; facebook-official</option>
										<option value="fa-facebook-square"><i class="fa fa-facebook-square"></i> &#xf082; facebook-square</option>
										<option value="fa-fast-backward"><i class="fa fa-fast-backward"></i> &#xf049; fast-backward</option>
										<option value="fa-fast-forward"><i class="fa fa-fast-forward"></i> &#xf050; fast-forward</option>
										<option value="fa-fax"><i class="fa fa-fax"></i> &#xf1ac; fax</option>
										<option value="fa-feed"><i class="fa fa-feed"></i> &#xf09e; feed</option>
										<option value="fa-female"><i class="fa fa-female"></i> &#xf182; female</option>
										<option value="fa-fighter-jet"><i class="fa fa-fighter-jet"></i> &#xf0fb; fighter-jet</option>
										<option value="fa-file"><i class="fa fa-file"></i> &#xf15b; file</option>
										<option value="fa-file-archive-o"><i class="fa fa-file-archive-o"></i> &#xf1c6; file-archive-o</option>
										<option value="fa-file-audio-o"><i class="fa fa-file-audio-o"></i> &#xf1c7; file-audio-o</option>
										<option value="fa-file-code-o"><i class="fa fa-file-code-o"></i> &#xf1c9; file-code-o</option>
										<option value="fa-file-excel-o"><i class="fa fa-file-excel-o"></i> &#xf1c3; file-excel-o</option>
										<option value="fa-file-image-o"><i class="fa fa-file-image-o"></i> &#xf1c5; file-image-o</option>
										<option value="fa-file-movie-o"><i class="fa fa-file-movie-o"></i> &#xf1c8; file-movie-o</option>
										<option value="fa-file-o"><i class="fa fa-file-o"></i> &#xf016; file-o</option>
										<option value="fa-file-pdf-o"><i class="fa fa-file-pdf-o"></i> &#xf1c1; file-pdf-o</option>
										<option value="fa-file-photo-o"><i class="fa fa-file-photo-o"></i> &#xf1c5; file-photo-o</option>
										<option value="fa-file-picture-o"><i class="fa fa-file-picture-o"></i> &#xf1c5; file-picture-o</option>
										<option value="fa-file-powerpoint-o"><i class="fa fa-file-powerpoint-o"></i> &#xf1c4; file-powerpoint-o</option>
										<option value="fa-file-sound-o"><i class="fa fa-file-sound-o"></i> &#xf1c7; file-sound-o</option>
										<option value="fa-file-text"><i class="fa fa-file-text"></i> &#xf15c; file-text</option>
										<option value="fa-file-text-o"><i class="fa fa-file-text-o"></i> &#xf0f6; file-text-o</option>
										<option value="fa-file-video-o"><i class="fa fa-file-video-o"></i> &#xf1c8; file-video-o</option>
										<option value="fa-file-word-o"><i class="fa fa-file-word-o"></i> &#xf1c2; file-word-o</option>
										<option value="fa-file-zip-o"><i class="fa fa-file-zip-o"></i> &#xf1c6; file-zip-o</option>
										<option value="fa-files-o"><i class="fa fa-files-o"></i> &#xf0c5; files-o</option>
										<option value="fa-film"><i class="fa fa-film"></i> &#xf008; film</option>
										<option value="fa-filter"><i class="fa fa-filter"></i> &#xf0b0; filter</option>
										<option value="fa-fire"><i class="fa fa-fire"></i> &#xf06d; fire</option>
										<option value="fa-fire-extinguisher"><i class="fa fa-fire-extinguisher"></i> &#xf134; fire-extinguisher</option>
										<option value="fa-firefox"><i class="fa fa-firefox"></i> &#xf269; firefox</option>
										<option value="fa-first-order"><i class="fa fa-first-order"></i> &#xf2b0; first-order</option>
										<option value="fa-flag"><i class="fa fa-flag"></i> &#xf024; flag</option>
										<option value="fa-flag-checkered"><i class="fa fa-flag-checkered"></i> &#xf11e; flag-checkered</option>
										<option value="fa-flag-o"><i class="fa fa-flag-o"></i> &#xf11d; flag-o</option>
										<option value="fa-flash"><i class="fa fa-flash"></i> &#xf0e7; flash</option>
										<option value="fa-flask"><i class="fa fa-flask"></i> &#xf0c3; flask</option>
										<option value="fa-flickr"><i class="fa fa-flickr"></i> &#xf16e; flickr</option>
										<option value="fa-floppy-o"><i class="fa fa-floppy-o"></i> &#xf0c7; floppy-o</option>
										<option value="fa-folder"><i class="fa fa-folder"></i> &#xf07b; folder</option>
										<option value="fa-folder-o"><i class="fa fa-folder-o"></i> &#xf114; folder-o</option>
										<option value="fa-folder-open"><i class="fa fa-folder-open"></i> &#xf07c; folder-open</option>
										<option value="fa-folder-open-o"><i class="fa fa-folder-open-o"></i> &#xf115; folder-open-o</option>
										<option value="fa-font"><i class="fa fa-font"></i> &#xf031; font</option>
										<option value="fa-font-awesome"><i class="fa fa-font-awesome"></i> &#xf2b4; font-awesome</option>
										<option value="fa-fonticons"><i class="fa fa-fonticons"></i> &#xf280; fonticons</option>
										<option value="fa-fort-awesome"><i class="fa fa-fort-awesome"></i> &#xf286; fort-awesome</option>
										<option value="fa-forumbee"><i class="fa fa-forumbee"></i> &#xf211; forumbee</option>
										<option value="fa-forward"><i class="fa fa-forward"></i> &#xf04e; forward</option>
										<option value="fa-foursquare"><i class="fa fa-foursquare"></i> &#xf180; foursquare</option>
										<option value="fa-free-code-camp"><i class="fa fa-free-code-camp"></i> &#xf2c5; free-code-camp</option>
										<option value="fa-frown-o"><i class="fa fa-frown-o"></i> &#xf119; frown-o</option>
										<option value="fa-futbol-o"><i class="fa fa-futbol-o"></i> &#xf1e3; futbol-o</option>
										<option value="fa-gamepad"><i class="fa fa-gamepad"></i> &#xf11b; gamepad</option>
										<option value="fa-gavel"><i class="fa fa-gavel"></i> &#xf0e3; gavel</option>
										<option value="fa-gbp"><i class="fa fa-gbp"></i> &#xf154; gbp</option>
										<option value="fa-ge"><i class="fa fa-ge"></i> &#xf1d1; ge</option>
										<option value="fa-gear"><i class="fa fa-gear"></i> &#xf013; gear</option>
										<option value="fa-gears"><i class="fa fa-gears"></i> &#xf085; gears</option>
										<option value="fa-genderless"><i class="fa fa-genderless"></i> &#xf22d; genderless</option>
										<option value="fa-get-pocket"><i class="fa fa-get-pocket"></i> &#xf265; get-pocket</option>
										<option value="fa-gg"><i class="fa fa-gg"></i> &#xf260; gg</option>
										<option value="fa-gg-circle"><i class="fa fa-gg-circle"></i> &#xf261; gg-circle</option>
										<option value="fa-gift"><i class="fa fa-gift"></i> &#xf06b; gift</option>
										<option value="fa-git"><i class="fa fa-git"></i> &#xf1d3; git</option>
										<option value="fa-git-square"><i class="fa fa-git-square"></i> &#xf1d2; git-square</option>
										<option value="fa-github"><i class="fa fa-github"></i> &#xf09b; github</option>
										<option value="fa-github-alt"><i class="fa fa-github-alt"></i> &#xf113; github-alt</option>
										<option value="fa-github-square"><i class="fa fa-github-square"></i> &#xf092; github-square</option>
										<option value="fa-gitlab"><i class="fa fa-gitlab"></i> &#xf296; gitlab</option>
										<option value="fa-gittip"><i class="fa fa-gittip"></i> &#xf184; gittip</option>
										<option value="fa-glass"><i class="fa fa-glass"></i> &#xf000; glass</option>
										<option value="fa-glide"><i class="fa fa-glide"></i> &#xf2a5; glide</option>
										<option value="fa-glide-g"><i class="fa fa-glide-g"></i> &#xf2a6; glide-g</option>
										<option value="fa-globe"><i class="fa fa-globe"></i> &#xf0ac; globe</option>
										<option value="fa-google"><i class="fa fa-google"></i> &#xf1a0; google</option>
										<option value="fa-google-plus"><i class="fa fa-google-plus"></i> &#xf0d5; google-plus</option>
										<option value="fa-google-plus-circle"><i class="fa fa-google-plus-circle"></i> &#xf2b3; google-plus-circle</option>
										<option value="fa-google-plus-official"><i class="fa fa-google-plus-official"></i> &#xf2b3; google-plus-official</option>
										<option value="fa-google-plus-square"><i class="fa fa-google-plus-square"></i> &#xf0d4; google-plus-square</option>
										<option value="fa-google-wallet"><i class="fa fa-google-wallet"></i> &#xf1ee; google-wallet</option>
										<option value="fa-graduation-cap"><i class="fa fa-graduation-cap"></i> &#xf19d; graduation-cap</option>
										<option value="fa-gratipay"><i class="fa fa-gratipay"></i> &#xf184; gratipay</option>
										<option value="fa-grav"><i class="fa fa-grav"></i> &#xf2d6; grav</option>
										<option value="fa-group"><i class="fa fa-group"></i> &#xf0c0; group</option>
										<option value="fa-h-square"><i class="fa fa-h-square"></i> &#xf0fd; h-square</option>
										<option value="fa-hacker-news"><i class="fa fa-hacker-news"></i> &#xf1d4; hacker-news</option>
										<option value="fa-hand-grab-o"><i class="fa fa-hand-grab-o"></i> &#xf255; hand-grab-o</option>
										<option value="fa-hand-lizard-o"><i class="fa fa-hand-lizard-o"></i> &#xf258; hand-lizard-o</option>
										<option value="fa-hand-o-down"><i class="fa fa-hand-o-down"></i> &#xf0a7; hand-o-down</option>
										<option value="fa-hand-o-left"><i class="fa fa-hand-o-left"></i> &#xf0a5; hand-o-left</option>
										<option value="fa-hand-o-right"><i class="fa fa-hand-o-right"></i> &#xf0a4; hand-o-right</option>
										<option value="fa-hand-o-up"><i class="fa fa-hand-o-up"></i> &#xf0a6; hand-o-up</option>
										<option value="fa-hand-paper-o"><i class="fa fa-hand-paper-o"></i> &#xf256; hand-paper-o</option>
										<option value="fa-hand-peace-o"><i class="fa fa-hand-peace-o"></i> &#xf25b; hand-peace-o</option>
										<option value="fa-hand-pointer-o"><i class="fa fa-hand-pointer-o"></i> &#xf25a; hand-pointer-o</option>
										<option value="fa-hand-rock-o"><i class="fa fa-hand-rock-o"></i> &#xf255; hand-rock-o</option>
										<option value="fa-hand-scissors-o"><i class="fa fa-hand-scissors-o"></i> &#xf257; hand-scissors-o</option>
										<option value="fa-hand-spock-o"><i class="fa fa-hand-spock-o"></i> &#xf259; hand-spock-o</option>
										<option value="fa-hand-stop-o"><i class="fa fa-hand-stop-o"></i> &#xf256; hand-stop-o</option>
										<option value="fa-handshake-o"><i class="fa fa-handshake-o"></i> &#xf2b5; handshake-o</option>
										<option value="fa-hard-of-hearing"><i class="fa fa-hard-of-hearing"></i> &#xf2a4; hard-of-hearing</option>
										<option value="fa-hashtag"><i class="fa fa-hashtag"></i> &#xf292; hashtag</option>
										<option value="fa-hdd-o"><i class="fa fa-hdd-o"></i> &#xf0a0; hdd-o</option>
										<option value="fa-header"><i class="fa fa-header"></i> &#xf1dc; header</option>
										<option value="fa-headphones"><i class="fa fa-headphones"></i> &#xf025; headphones</option>
										<option value="fa-heart"><i class="fa fa-heart"></i> &#xf004; heart</option>
										<option value="fa-heart-o"><i class="fa fa-heart-o"></i> &#xf08a; heart-o</option>
										<option value="fa-heartbeat"><i class="fa fa-heartbeat"></i> &#xf21e; heartbeat</option>
										<option value="fa-history"><i class="fa fa-history"></i> &#xf1da; history</option>
										<option value="fa-home"><i class="fa fa-home"></i> &#xf015; home</option>
										<option value="fa-hospital-o"><i class="fa fa-hospital-o"></i> &#xf0f8; hospital-o</option>
										<option value="fa-hotel"><i class="fa fa-hotel"></i> &#xf236; hotel</option>
										<option value="fa-hourglass"><i class="fa fa-hourglass"></i> &#xf254; hourglass</option>
										<option value="fa-hourglass-1"><i class="fa fa-hourglass-1"></i> &#xf251; hourglass-1</option>
										<option value="fa-hourglass-2"><i class="fa fa-hourglass-2"></i> &#xf252; hourglass-2</option>
										<option value="fa-hourglass-3"><i class="fa fa-hourglass-3"></i> &#xf253; hourglass-3</option>
										<option value="fa-hourglass-end"><i class="fa fa-hourglass-end"></i> &#xf253; hourglass-end</option>
										<option value="fa-hourglass-half"><i class="fa fa-hourglass-half"></i> &#xf252; hourglass-half</option>
										<option value="fa-hourglass-o"><i class="fa fa-hourglass-o"></i> &#xf250; hourglass-o</option>
										<option value="fa-hourglass-start"><i class="fa fa-hourglass-start"></i> &#xf251; hourglass-start</option>
										<option value="fa-houzz"><i class="fa fa-houzz"></i> &#xf27c; houzz</option>
										<option value="fa-html5"><i class="fa fa-html5"></i> &#xf13b; html5</option>
										<option value="fa-i-cursor"><i class="fa fa-i-cursor"></i> &#xf246; i-cursor</option>
										<option value="fa-id-badge"><i class="fa fa-id-badge"></i> &#xf2c1; id-badge</option>
										<option value="fa-id-card"><i class="fa fa-id-card"></i> &#xf2c2; id-card</option>
										<option value="fa-id-card-o"><i class="fa fa-id-card-o"></i> &#xf2c3; id-card-o</option>
										<option value="fa-ils"><i class="fa fa-ils"></i> &#xf20b; ils</option>
										<option value="fa-image"><i class="fa fa-image"></i> &#xf03e; image</option>
										<option value="fa-imdb"><i class="fa fa-imdb"></i> &#xf2d8; imdb</option>
										<option value="fa-inbox"><i class="fa fa-inbox"></i> &#xf01c; inbox</option>
										<option value="fa-indent"><i class="fa fa-indent"></i> &#xf03c; indent</option>
										<option value="fa-industry"><i class="fa fa-industry"></i> &#xf275; industry</option>
										<option value="fa-info"><i class="fa fa-info"></i> &#xf129; info</option>
										<option value="fa-info-circle"><i class="fa fa-info-circle"></i> &#xf05a; info-circle</option>
										<option value="fa-inr"><i class="fa fa-inr"></i> &#xf156; inr</option>
										<option value="fa-instagram"><i class="fa fa-instagram"></i> &#xf16d; instagram</option>
										<option value="fa-institution"><i class="fa fa-institution"></i> &#xf19c; institution</option>
										<option value="fa-internet-explorer"><i class="fa fa-internet-explorer"></i> &#xf26b; internet-explorer</option>
										<option value="fa-intersex"><i class="fa fa-intersex"></i> &#xf224; intersex</option>
										<option value="fa-ioxhost"><i class="fa fa-ioxhost"></i> &#xf208; ioxhost</option>
										<option value="fa-italic"><i class="fa fa-italic"></i> &#xf033; italic</option>
										<option value="fa-joomla"><i class="fa fa-joomla"></i> &#xf1aa; joomla</option>
										<option value="fa-jpy"><i class="fa fa-jpy"></i> &#xf157; jpy</option>
										<option value="fa-jsfiddle"><i class="fa fa-jsfiddle"></i> &#xf1cc; jsfiddle</option>
										<option value="fa-key"><i class="fa fa-key"></i> &#xf084; key</option>
										<option value="fa-keyboard-o"><i class="fa fa-keyboard-o"></i> &#xf11c; keyboard-o</option>
										<option value="fa-krw"><i class="fa fa-krw"></i> &#xf159; krw</option>
										<option value="fa-language"><i class="fa fa-language"></i> &#xf1ab; language</option>
										<option value="fa-laptop"><i class="fa fa-laptop"></i> &#xf109; laptop</option>
										<option value="fa-lastfm"><i class="fa fa-lastfm"></i> &#xf202; lastfm</option>
										<option value="fa-lastfm-square"><i class="fa fa-lastfm-square"></i> &#xf203; lastfm-square</option>
										<option value="fa-leaf"><i class="fa fa-leaf"></i> &#xf06c; leaf</option>
										<option value="fa-leanpub"><i class="fa fa-leanpub"></i> &#xf212; leanpub</option>
										<option value="fa-legal"><i class="fa fa-legal"></i> &#xf0e3; legal</option>
										<option value="fa-lemon-o"><i class="fa fa-lemon-o"></i> &#xf094; lemon-o</option>
										<option value="fa-level-down"><i class="fa fa-level-down"></i> &#xf149; level-down</option>
										<option value="fa-level-up"><i class="fa fa-level-up"></i> &#xf148; level-up</option>
										<option value="fa-life-bouy"><i class="fa fa-life-bouy"></i> &#xf1cd; life-bouy</option>
										<option value="fa-life-buoy"><i class="fa fa-life-buoy"></i> &#xf1cd; life-buoy</option>
										<option value="fa-life-ring"><i class="fa fa-life-ring"></i> &#xf1cd; life-ring</option>
										<option value="fa-life-saver"><i class="fa fa-life-saver"></i> &#xf1cd; life-saver</option>
										<option value="fa-lightbulb-o"><i class="fa fa-lightbulb-o"></i> &#xf0eb; lightbulb-o</option>
										<option value="fa-line-chart"><i class="fa fa-line-chart"></i> &#xf201; line-chart</option>
										<option value="fa-link"><i class="fa fa-link"></i> &#xf0c1; link</option>
										<option value="fa-linkedin"><i class="fa fa-linkedin"></i> &#xf0e1; linkedin</option>
										<option value="fa-linkedin-square"><i class="fa fa-linkedin-square"></i> &#xf08c; linkedin-square</option>
										<option value="fa-linode"><i class="fa fa-linode"></i> &#xf2b8; linode</option>
										<option value="fa-linux"><i class="fa fa-linux"></i> &#xf17c; linux</option>
										<option value="fa-list"><i class="fa fa-list"></i> &#xf03a; list</option>
										<option value="fa-list-alt"><i class="fa fa-list-alt"></i> &#xf022; list-alt</option>
										<option value="fa-list-ol"><i class="fa fa-list-ol"></i> &#xf0cb; list-ol</option>
										<option value="fa-list-ul"><i class="fa fa-list-ul"></i> &#xf0ca; list-ul</option>
										<option value="fa-location-arrow"><i class="fa fa-location-arrow"></i> &#xf124; location-arrow</option>
										<option value="fa-lock"><i class="fa fa-lock"></i> &#xf023; lock</option>
										<option value="fa-long-arrow-down"><i class="fa fa-long-arrow-down"></i> &#xf175; long-arrow-down</option>
										<option value="fa-long-arrow-left"><i class="fa fa-long-arrow-left"></i> &#xf177; long-arrow-left</option>
										<option value="fa-long-arrow-right"><i class="fa fa-long-arrow-right"></i> &#xf178; long-arrow-right</option>
										<option value="fa-long-arrow-up"><i class="fa fa-long-arrow-up"></i> &#xf176; long-arrow-up</option>
										<option value="fa-low-vision"><i class="fa fa-low-vision"></i> &#xf2a8; low-vision</option>
										<option value="fa-magic"><i class="fa fa-magic"></i> &#xf0d0; magic</option>
										<option value="fa-magnet"><i class="fa fa-magnet"></i> &#xf076; magnet</option>
										<option value="fa-mail-forward"><i class="fa fa-mail-forward"></i> &#xf064; mail-forward</option>
										<option value="fa-mail-reply"><i class="fa fa-mail-reply"></i> &#xf112; mail-reply</option>
										<option value="fa-mail-reply-all"><i class="fa fa-mail-reply-all"></i> &#xf122; mail-reply-all</option>
										<option value="fa-male"><i class="fa fa-male"></i> &#xf183; male</option>
										<option value="fa-map"><i class="fa fa-map"></i> &#xf279; map</option>
										<option value="fa-map-marker"><i class="fa fa-map-marker"></i> &#xf041; map-marker</option>
										<option value="fa-map-o"><i class="fa fa-map-o"></i> &#xf278; map-o</option>
										<option value="fa-map-pin"><i class="fa fa-map-pin"></i> &#xf276; map-pin</option>
										<option value="fa-map-signs"><i class="fa fa-map-signs"></i> &#xf277; map-signs</option>
										<option value="fa-mars"><i class="fa fa-mars"></i> &#xf222; mars</option>
										<option value="fa-mars-double"><i class="fa fa-mars-double"></i> &#xf227; mars-double</option>
										<option value="fa-mars-stroke"><i class="fa fa-mars-stroke"></i> &#xf229; mars-stroke</option>
										<option value="fa-mars-stroke-h"><i class="fa fa-mars-stroke-h"></i> &#xf22b; mars-stroke-h</option>
										<option value="fa-mars-stroke-v"><i class="fa fa-mars-stroke-v"></i> &#xf22a; mars-stroke-v</option>
										<option value="fa-maxcdn"><i class="fa fa-maxcdn"></i> &#xf136; maxcdn</option>
										<option value="fa-meanpath"><i class="fa fa-meanpath"></i> &#xf20c; meanpath</option>
										<option value="fa-medium"><i class="fa fa-medium"></i> &#xf23a; medium</option>
										<option value="fa-medkit"><i class="fa fa-medkit"></i> &#xf0fa; medkit</option>
										<option value="fa-meetup"><i class="fa fa-meetup"></i> &#xf2e0; meetup</option>
										<option value="fa-meh-o"><i class="fa fa-meh-o"></i> &#xf11a; meh-o</option>
										<option value="fa-mercury"><i class="fa fa-mercury"></i> &#xf223; mercury</option>
										<option value="fa-microchip"><i class="fa fa-microchip"></i> &#xf2db; microchip</option>
										<option value="fa-microphone"><i class="fa fa-microphone"></i> &#xf130; microphone</option>
										<option value="fa-microphone-slash"><i class="fa fa-microphone-slash"></i> &#xf131; microphone-slash</option>
										<option value="fa-minus"><i class="fa fa-minus"></i> &#xf068; minus</option>
										<option value="fa-minus-circle"><i class="fa fa-minus-circle"></i> &#xf056; minus-circle</option>
										<option value="fa-minus-square"><i class="fa fa-minus-square"></i> &#xf146; minus-square</option>
										<option value="fa-minus-square-o"><i class="fa fa-minus-square-o"></i> &#xf147; minus-square-o</option>
										<option value="fa-mixcloud"><i class="fa fa-mixcloud"></i> &#xf289; mixcloud</option>
										<option value="fa-mobile"><i class="fa fa-mobile"></i> &#xf10b; mobile</option>
										<option value="fa-mobile-phone"><i class="fa fa-mobile-phone"></i> &#xf10b; mobile-phone</option>
										<option value="fa-modx"><i class="fa fa-modx"></i> &#xf285; modx</option>
										<option value="fa-money"><i class="fa fa-money"></i> &#xf0d6; money</option>
										<option value="fa-moon-o"><i class="fa fa-moon-o"></i> &#xf186; moon-o</option>
										<option value="fa-mortar-board"><i class="fa fa-mortar-board"></i> &#xf19d; mortar-board</option>
										<option value="fa-motorcycle"><i class="fa fa-motorcycle"></i> &#xf21c; motorcycle</option>
										<option value="fa-mouse-pointer"><i class="fa fa-mouse-pointer"></i> &#xf245; mouse-pointer</option>
										<option value="fa-music"><i class="fa fa-music"></i> &#xf001; music</option>
										<option value="fa-navicon"><i class="fa fa-navicon"></i> &#xf0c9; navicon</option>
										<option value="fa-neuter"><i class="fa fa-neuter"></i> &#xf22c; neuter</option>
										<option value="fa-newspaper-o"><i class="fa fa-newspaper-o"></i> &#xf1ea; newspaper-o</option>
										<option value="fa-object-group"><i class="fa fa-object-group"></i> &#xf247; object-group</option>
										<option value="fa-object-ungroup"><i class="fa fa-object-ungroup"></i> &#xf248; object-ungroup</option>
										<option value="fa-odnoklassniki"><i class="fa fa-odnoklassniki"></i> &#xf263; odnoklassniki</option>
										<option value="fa-odnoklassniki-square"><i class="fa fa-odnoklassniki-square"></i> &#xf264; odnoklassniki-square</option>
										<option value="fa-opencart"><i class="fa fa-opencart"></i> &#xf23d; opencart</option>
										<option value="fa-openid"><i class="fa fa-openid"></i> &#xf19b; openid</option>
										<option value="fa-opera"><i class="fa fa-opera"></i> &#xf26a; opera</option>
										<option value="fa-optin-monster"><i class="fa fa-optin-monster"></i> &#xf23c; optin-monster</option>
										<option value="fa-outdent"><i class="fa fa-outdent"></i> &#xf03b; outdent</option>
										<option value="fa-pagelines"><i class="fa fa-pagelines"></i> &#xf18c; pagelines</option>
										<option value="fa-paint-brush"><i class="fa fa-paint-brush"></i> &#xf1fc; paint-brush</option>
										<option value="fa-paper-plane"><i class="fa fa-paper-plane"></i> &#xf1d8; paper-plane</option>
										<option value="fa-paper-plane-o"><i class="fa fa-paper-plane-o"></i> &#xf1d9; paper-plane-o</option>
										<option value="fa-paperclip"><i class="fa fa-paperclip"></i> &#xf0c6; paperclip</option>
										<option value="fa-paragraph"><i class="fa fa-paragraph"></i> &#xf1dd; paragraph</option>
										<option value="fa-paste"><i class="fa fa-paste"></i> &#xf0ea; paste</option>
										<option value="fa-pause"><i class="fa fa-pause"></i> &#xf04c; pause</option>
										<option value="fa-pause-circle"><i class="fa fa-pause-circle"></i> &#xf28b; pause-circle</option>
										<option value="fa-pause-circle-o"><i class="fa fa-pause-circle-o"></i> &#xf28c; pause-circle-o</option>
										<option value="fa-paw"><i class="fa fa-paw"></i> &#xf1b0; paw</option>
										<option value="fa-paypal"><i class="fa fa-paypal"></i> &#xf1ed; paypal</option>
										<option value="fa-pencil"><i class="fa fa-pencil"></i> &#xf040; pencil</option>
										<option value="fa-pencil-square"><i class="fa fa-pencil-square"></i> &#xf14b; pencil-square</option>
										<option value="fa-pencil-square-o"><i class="fa fa-pencil-square-o"></i> &#xf044; pencil-square-o</option>
										<option value="fa-percent"><i class="fa fa-percent"></i> &#xf295; percent</option>
										<option value="fa-phone"><i class="fa fa-phone"></i> &#xf095; phone</option>
										<option value="fa-phone-square"><i class="fa fa-phone-square"></i> &#xf098; phone-square</option>
										<option value="fa-photo"><i class="fa fa-photo"></i> &#xf03e; photo</option>
										<option value="fa-picture-o"><i class="fa fa-picture-o"></i> &#xf03e; picture-o</option>
										<option value="fa-pie-chart"><i class="fa fa-pie-chart"></i> &#xf200; pie-chart</option>
										<option value="fa-pied-piper"><i class="fa fa-pied-piper"></i> &#xf2ae; pied-piper</option>
										<option value="fa-pied-piper-alt"><i class="fa fa-pied-piper-alt"></i> &#xf1a8; pied-piper-alt</option>
										<option value="fa-pied-piper-pp"><i class="fa fa-pied-piper-pp"></i> &#xf1a7; pied-piper-pp</option>
										<option value="fa-pinterest"><i class="fa fa-pinterest"></i> &#xf0d2; pinterest</option>
										<option value="fa-pinterest-p"><i class="fa fa-pinterest-p"></i> &#xf231; pinterest-p</option>
										<option value="fa-pinterest-square"><i class="fa fa-pinterest-square"></i> &#xf0d3; pinterest-square</option>
										<option value="fa-plane"><i class="fa fa-plane"></i> &#xf072; plane</option>
										<option value="fa-play"><i class="fa fa-play"></i> &#xf04b; play</option>
										<option value="fa-play-circle"><i class="fa fa-play-circle"></i> &#xf144; play-circle</option>
										<option value="fa-play-circle-o"><i class="fa fa-play-circle-o"></i> &#xf01d; play-circle-o</option>
										<option value="fa-plug"><i class="fa fa-plug"></i> &#xf1e6; plug</option>
										<option value="fa-plus"><i class="fa fa-plus"></i> &#xf067; plus</option>
										<option value="fa-plus-circle"><i class="fa fa-plus-circle"></i> &#xf055; plus-circle</option>
										<option value="fa-plus-square"><i class="fa fa-plus-square"></i> &#xf0fe; plus-square</option>
										<option value="fa-plus-square-o"><i class="fa fa-plus-square-o"></i> &#xf196; plus-square-o</option>
										<option value="fa-podcast"><i class="fa fa-podcast"></i> &#xf2ce; podcast</option>
										<option value="fa-power-off"><i class="fa fa-power-off"></i> &#xf011; power-off</option>
										<option value="fa-print"><i class="fa fa-print"></i> &#xf02f; print</option>
										<option value="fa-product-hunt"><i class="fa fa-product-hunt"></i> &#xf288; product-hunt</option>
										<option value="fa-puzzle-piece"><i class="fa fa-puzzle-piece"></i> &#xf12e; puzzle-piece</option>
										<option value="fa-qq"><i class="fa fa-qq"></i> &#xf1d6; qq</option>
										<option value="fa-qrcode"><i class="fa fa-qrcode"></i> &#xf029; qrcode</option>
										<option value="fa-question"><i class="fa fa-question"></i> &#xf128; question</option>
										<option value="fa-question-circle"><i class="fa fa-question-circle"></i> &#xf059; question-circle</option>
										<option value="fa-question-circle-o"><i class="fa fa-question-circle-o"></i> &#xf29c; question-circle-o</option>
										<option value="fa-quora"><i class="fa fa-quora"></i> &#xf2c4; quora</option>
										<option value="fa-quote-left"><i class="fa fa-quote-left"></i> &#xf10d; quote-left</option>
										<option value="fa-quote-right"><i class="fa fa-quote-right"></i> &#xf10e; quote-right</option>
										<option value="fa-ra"><i class="fa fa-ra"></i> &#xf1d0; ra</option>
										<option value="fa-random"><i class="fa fa-random"></i> &#xf074; random</option>
										<option value="fa-ravelry"><i class="fa fa-ravelry"></i> &#xf2d9; ravelry</option>
										<option value="fa-rebel"><i class="fa fa-rebel"></i> &#xf1d0; rebel</option>
										<option value="fa-recycle"><i class="fa fa-recycle"></i> &#xf1b8; recycle</option>
										<option value="fa-reddit"><i class="fa fa-reddit"></i> &#xf1a1; reddit</option>
										<option value="fa-reddit-alien"><i class="fa fa-reddit-alien"></i> &#xf281; reddit-alien</option>
										<option value="fa-reddit-square"><i class="fa fa-reddit-square"></i> &#xf1a2; reddit-square</option>
										<option value="fa-refresh"><i class="fa fa-refresh"></i> &#xf021; refresh</option>
										<option value="fa-registered"><i class="fa fa-registered"></i> &#xf25d; registered</option>
										<option value="fa-remove"><i class="fa fa-remove"></i> &#xf00d; remove</option>
										<option value="fa-renren"><i class="fa fa-renren"></i> &#xf18b; renren</option>
										<option value="fa-reorder"><i class="fa fa-reorder"></i> &#xf0c9; reorder</option>
										<option value="fa-repeat"><i class="fa fa-repeat"></i> &#xf01e; repeat</option>
										<option value="fa-reply"><i class="fa fa-reply"></i> &#xf112; reply</option>
										<option value="fa-reply-all"><i class="fa fa-reply-all"></i> &#xf122; reply-all</option>
										<option value="fa-resistance"><i class="fa fa-resistance"></i> &#xf1d0; resistance</option>
										<option value="fa-retweet"><i class="fa fa-retweet"></i> &#xf079; retweet</option>
										<option value="fa-rmb"><i class="fa fa-rmb"></i> &#xf157; rmb</option>
										<option value="fa-road"><i class="fa fa-road"></i> &#xf018; road</option>
										<option value="fa-rocket"><i class="fa fa-rocket"></i> &#xf135; rocket</option>
										<option value="fa-rotate-left"><i class="fa fa-rotate-left"></i> &#xf0e2; rotate-left</option>
										<option value="fa-rotate-right"><i class="fa fa-rotate-right"></i> &#xf01e; rotate-right</option>
										<option value="fa-rouble"><i class="fa fa-rouble"></i> &#xf158; rouble</option>
										<option value="fa-rss"><i class="fa fa-rss"></i> &#xf09e; rss</option>
										<option value="fa-rss-square"><i class="fa fa-rss-square"></i> &#xf143; rss-square</option>
										<option value="fa-rub"><i class="fa fa-rub"></i> &#xf158; rub</option>
										<option value="fa-ruble"><i class="fa fa-ruble"></i> &#xf158; ruble</option>
										<option value="fa-rupee"><i class="fa fa-rupee"></i> &#xf156; rupee</option>
										<option value="fa-s15"><i class="fa fa-s15"></i> &#xf2cd; s15</option>
										<option value="fa-safari"><i class="fa fa-safari"></i> &#xf267; safari</option>
										<option value="fa-save"><i class="fa fa-save"></i> &#xf0c7; save</option>
										<option value="fa-scissors"><i class="fa fa-scissors"></i> &#xf0c4; scissors</option>
										<option value="fa-scribd"><i class="fa fa-scribd"></i> &#xf28a; scribd</option>
										<option value="fa-search"><i class="fa fa-search"></i> &#xf002; search</option>
										<option value="fa-search-minus"><i class="fa fa-search-minus"></i> &#xf010; search-minus</option>
										<option value="fa-search-plus"><i class="fa fa-search-plus"></i> &#xf00e; search-plus</option>
										<option value="fa-sellsy"><i class="fa fa-sellsy"></i> &#xf213; sellsy</option>
										<option value="fa-send"><i class="fa fa-send"></i> &#xf1d8; send</option>
										<option value="fa-send-o"><i class="fa fa-send-o"></i> &#xf1d9; send-o</option>
										<option value="fa-server"><i class="fa fa-server"></i> &#xf233; server</option>
										<option value="fa-share"><i class="fa fa-share"></i> &#xf064; share</option>
										<option value="fa-share-alt"><i class="fa fa-share-alt"></i> &#xf1e0; share-alt</option>
										<option value="fa-share-alt-square"><i class="fa fa-share-alt-square"></i> &#xf1e1; share-alt-square</option>
										<option value="fa-share-square"><i class="fa fa-share-square"></i> &#xf14d; share-square</option>
										<option value="fa-share-square-o"><i class="fa fa-share-square-o"></i> &#xf045; share-square-o</option>
										<option value="fa-shekel"><i class="fa fa-shekel"></i> &#xf20b; shekel</option>
										<option value="fa-sheqel"><i class="fa fa-sheqel"></i> &#xf20b; sheqel</option>
										<option value="fa-shield"><i class="fa fa-shield"></i> &#xf132; shield</option>
										<option value="fa-ship"><i class="fa fa-ship"></i> &#xf21a; ship</option>
										<option value="fa-shirtsinbulk"><i class="fa fa-shirtsinbulk"></i> &#xf214; shirtsinbulk</option>
										<option value="fa-shopping-bag"><i class="fa fa-shopping-bag"></i> &#xf290; shopping-bag</option>
										<option value="fa-shopping-basket"><i class="fa fa-shopping-basket"></i> &#xf291; shopping-basket</option>
										<option value="fa-shopping-cart"><i class="fa fa-shopping-cart"></i> &#xf07a; shopping-cart</option>
										<option value="fa-shower"><i class="fa fa-shower"></i> &#xf2cc; shower</option>
										<option value="fa-sign-in"><i class="fa fa-sign-in"></i> &#xf090; sign-in</option>
										<option value="fa-sign-language"><i class="fa fa-sign-language"></i> &#xf2a7; sign-language</option>
										<option value="fa-sign-out"><i class="fa fa-sign-out"></i> &#xf08b; sign-out</option>
										<option value="fa-signal"><i class="fa fa-signal"></i> &#xf012; signal</option>
										<option value="fa-signing"><i class="fa fa-signing"></i> &#xf2a7; signing</option>
										<option value="fa-simplybuilt"><i class="fa fa-simplybuilt"></i> &#xf215; simplybuilt</option>
										<option value="fa-sitemap"><i class="fa fa-sitemap"></i> &#xf0e8; sitemap</option>
										<option value="fa-skyatlas"><i class="fa fa-skyatlas"></i> &#xf216; skyatlas</option>
										<option value="fa-skype"><i class="fa fa-skype"></i> &#xf17e; skype</option>
										<option value="fa-slack"><i class="fa fa-slack"></i> &#xf198; slack</option>
										<option value="fa-sliders"><i class="fa fa-sliders"></i> &#xf1de; sliders</option>
										<option value="fa-slideshare"><i class="fa fa-slideshare"></i> &#xf1e7; slideshare</option>
										<option value="fa-smile-o"><i class="fa fa-smile-o"></i> &#xf118; smile-o</option>
										<option value="fa-snapchat"><i class="fa fa-snapchat"></i> &#xf2ab; snapchat</option>
										<option value="fa-snapchat-ghost"><i class="fa fa-snapchat-ghost"></i> &#xf2ac; snapchat-ghost</option>
										<option value="fa-snapchat-square"><i class="fa fa-snapchat-square"></i> &#xf2ad; snapchat-square</option>
										<option value="fa-snowflake-o"><i class="fa fa-snowflake-o"></i> &#xf2dc; snowflake-o</option>
										<option value="fa-soccer-ball-o"><i class="fa fa-soccer-ball-o"></i> &#xf1e3; soccer-ball-o</option>
										<option value="fa-sort"><i class="fa fa-sort"></i> &#xf0dc; sort</option>
										<option value="fa-sort-alpha-asc"><i class="fa fa-sort-alpha-asc"></i> &#xf15d; sort-alpha-asc</option>
										<option value="fa-sort-alpha-desc"><i class="fa fa-sort-alpha-desc"></i> &#xf15e; sort-alpha-desc</option>
										<option value="fa-sort-amount-asc"><i class="fa fa-sort-amount-asc"></i> &#xf160; sort-amount-asc</option>
										<option value="fa-sort-amount-desc"><i class="fa fa-sort-amount-desc"></i> &#xf161; sort-amount-desc</option>
										<option value="fa-sort-asc"><i class="fa fa-sort-asc"></i> &#xf0de; sort-asc</option>
										<option value="fa-sort-desc"><i class="fa fa-sort-desc"></i> &#xf0dd; sort-desc</option>
										<option value="fa-sort-down"><i class="fa fa-sort-down"></i> &#xf0dd; sort-down</option>
										<option value="fa-sort-numeric-asc"><i class="fa fa-sort-numeric-asc"></i> &#xf162; sort-numeric-asc</option>
										<option value="fa-sort-numeric-desc"><i class="fa fa-sort-numeric-desc"></i> &#xf163; sort-numeric-desc</option>
										<option value="fa-sort-up"><i class="fa fa-sort-up"></i> &#xf0de; sort-up</option>
										<option value="fa-soundcloud"><i class="fa fa-soundcloud"></i> &#xf1be; soundcloud</option>
										<option value="fa-space-shuttle"><i class="fa fa-space-shuttle"></i> &#xf197; space-shuttle</option>
										<option value="fa-spinner"><i class="fa fa-spinner"></i> &#xf110; spinner</option>
										<option value="fa-spoon"><i class="fa fa-spoon"></i> &#xf1b1; spoon</option>
										<option value="fa-spotify"><i class="fa fa-spotify"></i> &#xf1bc; spotify</option>
										<option value="fa-square"><i class="fa fa-square"></i> &#xf0c8; square</option>
										<option value="fa-square-o"><i class="fa fa-square-o"></i> &#xf096; square-o</option>
										<option value="fa-stack-exchange"><i class="fa fa-stack-exchange"></i> &#xf18d; stack-exchange</option>
										<option value="fa-stack-overflow"><i class="fa fa-stack-overflow"></i> &#xf16c; stack-overflow</option>
										<option value="fa-star"><i class="fa fa-star"></i> &#xf005; star</option>
										<option value="fa-star-half"><i class="fa fa-star-half"></i> &#xf089; star-half</option>
										<option value="fa-star-half-empty"><i class="fa fa-star-half-empty"></i> &#xf123; star-half-empty</option>
										<option value="fa-star-half-full"><i class="fa fa-star-half-full"></i> &#xf123; star-half-full</option>
										<option value="fa-star-half-o"><i class="fa fa-star-half-o"></i> &#xf123; star-half-o</option>
										<option value="fa-star-o"><i class="fa fa-star-o"></i> &#xf006; star-o</option>
										<option value="fa-steam"><i class="fa fa-steam"></i> &#xf1b6; steam</option>
										<option value="fa-steam-square"><i class="fa fa-steam-square"></i> &#xf1b7; steam-square</option>
										<option value="fa-step-backward"><i class="fa fa-step-backward"></i> &#xf048; step-backward</option>
										<option value="fa-step-forward"><i class="fa fa-step-forward"></i> &#xf051; step-forward</option>
										<option value="fa-stethoscope"><i class="fa fa-stethoscope"></i> &#xf0f1; stethoscope</option>
										<option value="fa-sticky-note"><i class="fa fa-sticky-note"></i> &#xf249; sticky-note</option>
										<option value="fa-sticky-note-o"><i class="fa fa-sticky-note-o"></i> &#xf24a; sticky-note-o</option>
										<option value="fa-stop"><i class="fa fa-stop"></i> &#xf04d; stop</option>
										<option value="fa-stop-circle"><i class="fa fa-stop-circle"></i> &#xf28d; stop-circle</option>
										<option value="fa-stop-circle-o"><i class="fa fa-stop-circle-o"></i> &#xf28e; stop-circle-o</option>
										<option value="fa-street-view"><i class="fa fa-street-view"></i> &#xf21d; street-view</option>
										<option value="fa-strikethrough"><i class="fa fa-strikethrough"></i> &#xf0cc; strikethrough</option>
										<option value="fa-stumbleupon"><i class="fa fa-stumbleupon"></i> &#xf1a4; stumbleupon</option>
										<option value="fa-stumbleupon-circle"><i class="fa fa-stumbleupon-circle"></i> &#xf1a3; stumbleupon-circle</option>
										<option value="fa-subscript"><i class="fa fa-subscript"></i> &#xf12c; subscript</option>
										<option value="fa-subway"><i class="fa fa-subway"></i> &#xf239; subway</option>
										<option value="fa-suitcase"><i class="fa fa-suitcase"></i> &#xf0f2; suitcase</option>
										<option value="fa-sun-o"><i class="fa fa-sun-o"></i> &#xf185; sun-o</option>
										<option value="fa-superpowers"><i class="fa fa-superpowers"></i> &#xf2dd; superpowers</option>
										<option value="fa-superscript"><i class="fa fa-superscript"></i> &#xf12b; superscript</option>
										<option value="fa-support"><i class="fa fa-support"></i> &#xf1cd; support</option>
										<option value="fa-table"><i class="fa fa-table"></i> &#xf0ce; table</option>
										<option value="fa-tablet"><i class="fa fa-tablet"></i> &#xf10a; tablet</option>
										<option value="fa-tachometer"><i class="fa fa-tachometer"></i> &#xf0e4; tachometer</option>
										<option value="fa-tag"><i class="fa fa-tag"></i> &#xf02b; tag</option>
										<option value="fa-tags"><i class="fa fa-tags"></i> &#xf02c; tags</option>
										<option value="fa-tasks"><i class="fa fa-tasks"></i> &#xf0ae; tasks</option>
										<option value="fa-taxi"><i class="fa fa-taxi"></i> &#xf1ba; taxi</option>
										<option value="fa-telegram"><i class="fa fa-telegram"></i> &#xf2c6; telegram</option>
										<option value="fa-television"><i class="fa fa-television"></i> &#xf26c; television</option>
										<option value="fa-tencent-weibo"><i class="fa fa-tencent-weibo"></i> &#xf1d5; tencent-weibo</option>
										<option value="fa-terminal"><i class="fa fa-terminal"></i> &#xf120; terminal</option>
										<option value="fa-text-height"><i class="fa fa-text-height"></i> &#xf034; text-height</option>
										<option value="fa-text-width"><i class="fa fa-text-width"></i> &#xf035; text-width</option>
										<option value="fa-th"><i class="fa fa-th"></i> &#xf00a; th</option>
										<option value="fa-th-large"><i class="fa fa-th-large"></i> &#xf009; th-large</option>
										<option value="fa-th-list"><i class="fa fa-th-list"></i> &#xf00b; th-list</option>
										<option value="fa-themeisle"><i class="fa fa-themeisle"></i> &#xf2b2; themeisle</option>
										<option value="fa-thermometer"><i class="fa fa-thermometer"></i> &#xf2c7; thermometer</option>
										<option value="fa-thermometer-0"><i class="fa fa-thermometer-0"></i> &#xf2cb; thermometer-0</option>
										<option value="fa-thermometer-1"><i class="fa fa-thermometer-1"></i> &#xf2ca; thermometer-1</option>
										<option value="fa-thermometer-2"><i class="fa fa-thermometer-2"></i> &#xf2c9; thermometer-2</option>
										<option value="fa-thermometer-3"><i class="fa fa-thermometer-3"></i> &#xf2c8; thermometer-3</option>
										<option value="fa-thermometer-4"><i class="fa fa-thermometer-4"></i> &#xf2c7; thermometer-4</option>
										<option value="fa-thermometer-empty"><i class="fa fa-thermometer-empty"></i> &#xf2cb; thermometer-empty</option>
										<option value="fa-thermometer-full"><i class="fa fa-thermometer-full"></i> &#xf2c7; thermometer-full</option>
										<option value="fa-thermometer-half"><i class="fa fa-thermometer-half"></i> &#xf2c9; thermometer-half</option>
										<option value="fa-thermometer-quarter"><i class="fa fa-thermometer-quarter"></i> &#xf2ca; thermometer-quarter</option>
										<option value="fa-thermometer-three-quarters"><i class="fa fa-thermometer-three-quarters"></i> &#xf2c8; thermometer-three-quarters</option>
										<option value="fa-thumb-tack"><i class="fa fa-thumb-tack"></i> &#xf08d; thumb-tack</option>
										<option value="fa-thumbs-down"><i class="fa fa-thumbs-down"></i> &#xf165; thumbs-down</option>
										<option value="fa-thumbs-o-down"><i class="fa fa-thumbs-o-down"></i> &#xf088; thumbs-o-down</option>
										<option value="fa-thumbs-o-up"><i class="fa fa-thumbs-o-up"></i> &#xf087; thumbs-o-up</option>
										<option value="fa-thumbs-up"><i class="fa fa-thumbs-up"></i> &#xf164; thumbs-up</option>
										<option value="fa-ticket"><i class="fa fa-ticket"></i> &#xf145; ticket</option>
										<option value="fa-times"><i class="fa fa-times"></i> &#xf00d; times</option>
										<option value="fa-times-circle"><i class="fa fa-times-circle"></i> &#xf057; times-circle</option>
										<option value="fa-times-circle-o"><i class="fa fa-times-circle-o"></i> &#xf05c; times-circle-o</option>
										<option value="fa-times-rectangle"><i class="fa fa-times-rectangle"></i> &#xf2d3; times-rectangle</option>
										<option value="fa-times-rectangle-o"><i class="fa fa-times-rectangle-o"></i> &#xf2d4; times-rectangle-o</option>
										<option value="fa-tint"><i class="fa fa-tint"></i> &#xf043; tint</option>
										<option value="fa-toggle-down"><i class="fa fa-toggle-down"></i> &#xf150; toggle-down</option>
										<option value="fa-toggle-left"><i class="fa fa-toggle-left"></i> &#xf191; toggle-left</option>
										<option value="fa-toggle-off"><i class="fa fa-toggle-off"></i> &#xf204; toggle-off</option>
										<option value="fa-toggle-on"><i class="fa fa-toggle-on"></i> &#xf205; toggle-on</option>
										<option value="fa-toggle-right"><i class="fa fa-toggle-right"></i> &#xf152; toggle-right</option>
										<option value="fa-toggle-up"><i class="fa fa-toggle-up"></i> &#xf151; toggle-up</option>
										<option value="fa-trademark"><i class="fa fa-trademark"></i> &#xf25c; trademark</option>
										<option value="fa-train"><i class="fa fa-train"></i> &#xf238; train</option>
										<option value="fa-transgender"><i class="fa fa-transgender"></i> &#xf224; transgender</option>
										<option value="fa-transgender-alt"><i class="fa fa-transgender-alt"></i> &#xf225; transgender-alt</option>
										<option value="fa-trash"><i class="fa fa-trash"></i> &#xf1f8; trash</option>
										<option value="fa-trash-o"><i class="fa fa-trash-o"></i> &#xf014; trash-o</option>
										<option value="fa-tree"><i class="fa fa-tree"></i> &#xf1bb; tree</option>
										<option value="fa-trello"><i class="fa fa-trello"></i> &#xf181; trello</option>
										<option value="fa-tripadvisor"><i class="fa fa-tripadvisor"></i> &#xf262; tripadvisor</option>
										<option value="fa-trophy"><i class="fa fa-trophy"></i> &#xf091; trophy</option>
										<option value="fa-truck"><i class="fa fa-truck"></i> &#xf0d1; truck</option>
										<option value="fa-try"><i class="fa fa-try"></i> &#xf195; try</option>
										<option value="fa-tty"><i class="fa fa-tty"></i> &#xf1e4; tty</option>
										<option value="fa-tumblr"><i class="fa fa-tumblr"></i> &#xf173; tumblr</option>
										<option value="fa-tumblr-square"><i class="fa fa-tumblr-square"></i> &#xf174; tumblr-square</option>
										<option value="fa-turkish-lira"><i class="fa fa-turkish-lira"></i> &#xf195; turkish-lira</option>
										<option value="fa-tv"><i class="fa fa-tv"></i> &#xf26c; tv</option>
										<option value="fa-twitch"><i class="fa fa-twitch"></i> &#xf1e8; twitch</option>
										<option value="fa-twitter"><i class="fa fa-twitter"></i> &#xf099; twitter</option>
										<option value="fa-twitter-square"><i class="fa fa-twitter-square"></i> &#xf081; twitter-square</option>
										<option value="fa-umbrella"><i class="fa fa-umbrella"></i> &#xf0e9; umbrella</option>
										<option value="fa-underline"><i class="fa fa-underline"></i> &#xf0cd; underline</option>
										<option value="fa-undo"><i class="fa fa-undo"></i> &#xf0e2; undo</option>
										<option value="fa-universal-access"><i class="fa fa-universal-access"></i> &#xf29a; universal-access</option>
										<option value="fa-university"><i class="fa fa-university"></i> &#xf19c; university</option>
										<option value="fa-unlink"><i class="fa fa-unlink"></i> &#xf127; unlink</option>
										<option value="fa-unlock"><i class="fa fa-unlock"></i> &#xf09c; unlock</option>
										<option value="fa-unlock-alt"><i class="fa fa-unlock-alt"></i> &#xf13e; unlock-alt</option>
										<option value="fa-unsorted"><i class="fa fa-unsorted"></i> &#xf0dc; unsorted</option>
										<option value="fa-upload"><i class="fa fa-upload"></i> &#xf093; upload</option>
										<option value="fa-usb"><i class="fa fa-usb"></i> &#xf287; usb</option>
										<option value="usd"><i class="fa usd"></i> &#xf155; </option>
										<option value="fa-user"><i class="fa fa-user"></i> &#xf007; user</option>
										<option value="fa-user-circle"><i class="fa fa-user-circle"></i> &#xf2bd; user-circle</option>
										<option value="fa-user-circle-o"><i class="fa fa-user-circle-o"></i> &#xf2be; user-circle-o</option>
										<option value="fa-user-md"><i class="fa fa-user-md"></i> &#xf0f0; user-md</option>
										<option value="fa-user-o"><i class="fa fa-user-o"></i> &#xf2c0; user-o</option>
										<option value="fa-user-plus"><i class="fa fa-user-plus"></i> &#xf234; user-plus</option>
										<option value="fa-user-secret"><i class="fa fa-user-secret"></i> &#xf21b; user-secret</option>
										<option value="fa-user-times"><i class="fa fa-user-times"></i> &#xf235; user-times</option>
										<option value="fa-users"><i class="fa fa-users"></i> &#xf0c0; users</option>
										<option value="fa-vcard"><i class="fa fa-vcard"></i> &#xf2bb; vcard</option>
										<option value="fa-vcard-o"><i class="fa fa-vcard-o"></i> &#xf2bc; vcard-o</option>
										<option value="fa-venus"><i class="fa fa-venus"></i> &#xf221; venus</option>
										<option value="fa-venus-double"><i class="fa fa-venus-double"></i> &#xf226; venus-double</option>
										<option value="fa-venus-mars"><i class="fa fa-venus-mars"></i> &#xf228; venus-mars</option>
										<option value="fa-viacoin"><i class="fa fa-viacoin"></i> &#xf237; viacoin</option>
										<option value="fa-viadeo"><i class="fa fa-viadeo"></i> &#xf2a9; viadeo</option>
										<option value="fa-viadeo-square"><i class="fa fa-viadeo-square"></i> &#xf2aa; viadeo-square</option>
										<option value="fa-video-camera"><i class="fa fa-video-camera"></i> &#xf03d; video-camera</option>
										<option value="fa-vimeo"><i class="fa fa-vimeo"></i> &#xf27d; vimeo</option>
										<option value="fa-vimeo-square"><i class="fa fa-vimeo-square"></i> &#xf194; vimeo-square</option>
										<option value="fa-vine"><i class="fa fa-vine"></i> &#xf1ca; vine</option>
										<option value="fa-vk"><i class="fa fa-vk"></i> &#xf189; vk</option>
										<option value="fa-volume-control-phone"><i class="fa fa-volume-control-phone"></i> &#xf2a0; volume-control-phone</option>
										<option value="fa-volume-down"><i class="fa fa-volume-down"></i> &#xf027; volume-down</option>
										<option value="fa-volume-off"><i class="fa fa-volume-off"></i> &#xf026; volume-off</option>
										<option value="fa-volume-up"><i class="fa fa-volume-up"></i> &#xf028; volume-up</option>
										<option value="fa-warning"><i class="fa fa-warning"></i> &#xf071; warning</option>
										<option value="fa-wechat"><i class="fa fa-wechat"></i> &#xf1d7; wechat</option>
										<option value="fa-weibo"><i class="fa fa-weibo"></i> &#xf18a; weibo</option>
										<option value="fa-weixin"><i class="fa fa-weixin"></i> &#xf1d7; weixin</option>
										<option value="fa-whatsapp"><i class="fa fa-whatsapp"></i> &#xf232; whatsapp</option>
										<option value="fa-wheelchair"><i class="fa fa-wheelchair"></i> &#xf193; wheelchair</option>
										<option value="fa-wheelchair-alt"><i class="fa fa-wheelchair-alt"></i> &#xf29b; wheelchair-alt</option>
										<option value="fa-wifi"><i class="fa fa-wifi"></i> &#xf1eb; wifi</option>
										<option value="fa-wikipedia-w"><i class="fa fa-wikipedia-w"></i> &#xf266; wikipedia-w</option>
										<option value="fa-window-close"><i class="fa fa-window-close"></i> &#xf2d3; window-close</option>
										<option value="fa-window-close-o"><i class="fa fa-window-close-o"></i> &#xf2d4; window-close-o</option>
										<option value="fa-window-maximize"><i class="fa fa-window-maximize"></i> &#xf2d0; window-maximize</option>
										<option value="fa-window-minimize"><i class="fa fa-window-minimize"></i> &#xf2d1; window-minimize</option>
										<option value="fa-window-restore"><i class="fa fa-window-restore"></i> &#xf2d2; window-restore</option>
										<option value="fa-windows"><i class="fa fa-windows"></i> &#xf17a; windows</option>
										<option value="fa-won"><i class="fa fa-won"></i> &#xf159; won</option>
										<option value="fa-wordpress"><i class="fa fa-wordpress"></i> &#xf19a; wordpress</option>
										<option value="fa-wpbeginner"><i class="fa fa-wpbeginner"></i> &#xf297; wpbeginner</option>
										<option value="fa-wpexplorer"><i class="fa fa-wpexplorer"></i> &#xf2de; wpexplorer</option>
										<option value="fa-wpforms"><i class="fa fa-wpforms"></i> &#xf298; wpforms</option>
										<option value="fa-wrench"><i class="fa fa-wrench"></i> &#xf0ad; wrench</option>
										<option value="fa-xing"><i class="fa fa-xing"></i> &#xf168; xing</option>
										<option value="fa-xing-square"><i class="fa fa-xing-square"></i> &#xf169; xing-square</option>
										<option value="fa-y-combinator"><i class="fa fa-y-combinator"></i> &#xf23b; y-combinator</option>
										<option value="fa-y-combinator-square"><i class="fa fa-y-combinator-square"></i> &#xf1d4; y-combinator-square</option>
										<option value="fa-yahoo"><i class="fa fa-yahoo"></i> &#xf19e; yahoo</option>
										<option value="fa-yc"><i class="fa fa-yc"></i> &#xf23b; yc</option>
										<option value="fa-yc-square"><i class="fa fa-yc-square"></i> &#xf1d4; yc-square</option>
										<option value="fa-yelp"><i class="fa fa-yelp"></i> &#xf1e9; yelp</option>
										<option value="fa-yen"><i class="fa fa-yen"></i> &#xf157; yen</option>
										<option value="fa-yoast"><i class="fa fa-yoast"></i> &#xf2b1; yoast</option>
										<option value="fa-youtube"><i class="fa fa-youtube"></i> &#xf167; youtube</option>
										<option value="fa-youtube-play"><i class="fa fa-youtube-play"></i> &#xf16a; youtube-play</option>
										<option value="fa-youtube-square"><i class="fa fa-youtube-square"></i> &#xf166; youtube-square</option>
									</select>
								</div>
							</div>
							<div class="col-md-1 p-lr-o">
								<br><label for=""><br></label>
								<a href="#" class="btn btn-danger remove-campo" title="Remover"><i class="fa fa-minus"></i></a>
							</div>
						</div>
						<div id="campos">
							@if( isset( $servico ) and isset( $servico->campos ) )
							@foreach( json_decode( $servico->campos ) as $campo )
							<div class="campo">
								<div class="col-md-3 p-lr-o">
									<div class="form-group">
										<label for="">Nome do campo</label>
										<input type="text" name="nome_campo[]" placeholder="Nome do campo" class="form-control" value="{{ $campo->nome_campo }}">
									</div>
								</div>
								<div class="col-md-2 p-lr-o">
									<div class="form-group">
										<label for="">Tipo do campo</label>
										<select name="tipo_campo[]" class="form-control">
											<option value=""></option>
											<option value="text" {{ (($campo->tipo_campo == 'text')?'selected':'') }} >text</option>
											<option value="number" {{ (($campo->tipo_campo == 'number')?'selected':'') }}>number</option>
											<option value="select" {{ (($campo->tipo_campo == 'select')?'selected':'') }}>select</option>
											<option value="checkbox" {{ (($campo->tipo_campo == 'checkbox')?'selected':'') }}>checkbox</option>
											<option value="radio" {{ (($campo->tipo_campo == 'radio')?'selected':'') }}>radio</option>
											<option value="textarea" {{ (($campo->tipo_campo == 'textarea')?'selected':'') }}>textarea</option>
											<option value="date" {{ (($campo->tipo_campo == 'date')?'selected':'') }}>date</option>
											<option value="file" {{ (($campo->tipo_campo == 'file')?'selected':'') }}>file</option>
											<option value="hidden" {{ (($campo->tipo_campo == 'hidden')?'selected':'') }}>hidden</option>
										</select>
									</div>
								</div>
								<div class="col-md-4 p-lr-o">
									<div class="form-group">
										<label for="">Valores padrão</label>
										<input type="text" name="valor_padrao[]" class="form-control tags" value="{{ $campo->valor_padrao }}" />
									</div>
								</div>
								<div class="col-md-2 p-lr-o">
									<div class="form-group">
										<label for="">Ícone do campo</label>
										<select name="icone_campo[]" class="form-control">
											<option value=""></option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-500px")?'selected':'') }} value="fa-500px"><i class="fa fa-500px"></i> &#xf26e; 500px</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-address-book")?'selected':'') }} value="fa-address-book"><i class="fa fa-address-book"></i> &#xf2b9; address-book</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-address-book-o")?'selected':'') }} value="fa-address-book-o"><i class="fa fa-address-book-o"></i> &#xf2ba; address-book-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-address-card")?'selected':'') }} value="fa-address-card"><i class="fa fa-address-card"></i> &#xf2bb; address-card</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-address-card-o")?'selected':'') }} value="fa-address-card-o"><i class="fa fa-address-card-o"></i> &#xf2bc; address-card-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-adjust")?'selected':'') }} value="fa-adjust"><i class="fa fa-adjust"></i> &#xf042; adjust</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-adn")?'selected':'') }} value="fa-adn"><i class="fa fa-adn"></i> &#xf170; adn</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-align-center")?'selected':'') }} value="fa-align-center"><i class="fa fa-align-center"></i> &#xf037; align-center</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-align-justify")?'selected':'') }} value="fa-align-justify"><i class="fa fa-align-justify"></i> &#xf039; align-justify</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-align-left")?'selected':'') }} value="fa-align-left"><i class="fa fa-align-left"></i> &#xf036; align-left</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-align-right")?'selected':'') }} value="fa-align-right"><i class="fa fa-align-right"></i> &#xf038; align-right</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-amazon")?'selected':'') }} value="fa-amazon"><i class="fa fa-amazon"></i> &#xf270; amazon</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-ambulance")?'selected':'') }} value="fa-ambulance"><i class="fa fa-ambulance"></i> &#xf0f9; ambulance</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-american-sign-language-interpreting")?'selected':'') }} value="fa-american-sign-language-interpreting"><i class="fa fa-american-sign-language-interpreting"></i> &#xf2a3; american-sign-language-interpreting</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-anchor")?'selected':'') }} value="fa-anchor"><i class="fa fa-anchor"></i> &#xf13d; anchor</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-android")?'selected':'') }} value="fa-android"><i class="fa fa-android"></i> &#xf17b; android</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-angellist")?'selected':'') }} value="fa-angellist"><i class="fa fa-angellist"></i> &#xf209; angellist</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-angle-double-down")?'selected':'') }} value="fa-angle-double-down"><i class="fa fa-angle-double-down"></i> &#xf103; angle-double-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-angle-double-left")?'selected':'') }} value="fa-angle-double-left"><i class="fa fa-angle-double-left"></i> &#xf100; angle-double-left</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-angle-double-right")?'selected':'') }} value="fa-angle-double-right"><i class="fa fa-angle-double-right"></i> &#xf101; angle-double-right</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-angle-double-up")?'selected':'') }} value="fa-angle-double-up"><i class="fa fa-angle-double-up"></i> &#xf102; angle-double-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-angle-down")?'selected':'') }} value="fa-angle-down"><i class="fa fa-angle-down"></i> &#xf107; angle-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-angle-left")?'selected':'') }} value="fa-angle-left"><i class="fa fa-angle-left"></i> &#xf104; angle-left</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-angle-right")?'selected':'') }} value="fa-angle-right"><i class="fa fa-angle-right"></i> &#xf105; angle-right</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-angle-up")?'selected':'') }} value="fa-angle-up"><i class="fa fa-angle-up"></i> &#xf106; angle-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-apple")?'selected':'') }} value="fa-apple"><i class="fa fa-apple"></i> &#xf179; apple</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-archive")?'selected':'') }} value="fa-archive"><i class="fa fa-archive"></i> &#xf187; archive</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-area-chart")?'selected':'') }} value="fa-area-chart"><i class="fa fa-area-chart"></i> &#xf1fe; area-chart</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrow-circle-down")?'selected':'') }} value="fa-arrow-circle-down"><i class="fa fa-arrow-circle-down"></i> &#xf0ab; arrow-circle-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrow-circle-left")?'selected':'') }} value="fa-arrow-circle-left"><i class="fa fa-arrow-circle-left"></i> &#xf0a8; arrow-circle-left</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrow-circle-o-down")?'selected':'') }} value="fa-arrow-circle-o-down"><i class="fa fa-arrow-circle-o-down"></i> &#xf01a; arrow-circle-o-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrow-circle-o-left")?'selected':'') }} value="fa-arrow-circle-o-left"><i class="fa fa-arrow-circle-o-left"></i> &#xf190; arrow-circle-o-left</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrow-circle-o-right")?'selected':'') }} value="fa-arrow-circle-o-right"><i class="fa fa-arrow-circle-o-right"></i> &#xf18e; arrow-circle-o-right</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrow-circle-o-up")?'selected':'') }} value="fa-arrow-circle-o-up"><i class="fa fa-arrow-circle-o-up"></i> &#xf01b; arrow-circle-o-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrow-circle-right")?'selected':'') }} value="fa-arrow-circle-right"><i class="fa fa-arrow-circle-right"></i> &#xf0a9; arrow-circle-right</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrow-circle-up")?'selected':'') }} value="fa-arrow-circle-up"><i class="fa fa-arrow-circle-up"></i> &#xf0aa; arrow-circle-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrow-down")?'selected':'') }} value="fa-arrow-down"><i class="fa fa-arrow-down"></i> &#xf063; arrow-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrow-left")?'selected':'') }} value="fa-arrow-left"><i class="fa fa-arrow-left"></i> &#xf060; arrow-left</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrow-right")?'selected':'') }} value="fa-arrow-right"><i class="fa fa-arrow-right"></i> &#xf061; arrow-right</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrow-up")?'selected':'') }} value="fa-arrow-up"><i class="fa fa-arrow-up"></i> &#xf062; arrow-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrows")?'selected':'') }} value="fa-arrows"><i class="fa fa-arrows"></i> &#xf047; arrows</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrows-alt")?'selected':'') }} value="fa-arrows-alt"><i class="fa fa-arrows-alt"></i> &#xf0b2; arrows-alt</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrows-h")?'selected':'') }} value="fa-arrows-h"><i class="fa fa-arrows-h"></i> &#xf07e; arrows-h</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-arrows-v")?'selected':'') }} value="fa-arrows-v"><i class="fa fa-arrows-v"></i> &#xf07d; arrows-v</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-asl-interpreting")?'selected':'') }} value="fa-asl-interpreting"><i class="fa fa-asl-interpreting"></i> &#xf2a3; asl-interpreting</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-assistive-listening-systems")?'selected':'') }} value="fa-assistive-listening-systems"><i class="fa fa-assistive-listening-systems"></i> &#xf2a2; assistive-listening-systems</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-asterisk")?'selected':'') }} value="fa-asterisk"><i class="fa fa-asterisk"></i> &#xf069; asterisk</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-at")?'selected':'') }} value="fa-at"><i class="fa fa-at"></i> &#xf1fa; at</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-audio-description")?'selected':'') }} value="fa-audio-description"><i class="fa fa-audio-description"></i> &#xf29e; audio-description</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-automobile")?'selected':'') }} value="fa-automobile"><i class="fa fa-automobile"></i> &#xf1b9; automobile</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-backward")?'selected':'') }} value="fa-backward"><i class="fa fa-backward"></i> &#xf04a; backward</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-balance-scale")?'selected':'') }} value="fa-balance-scale"><i class="fa fa-balance-scale"></i> &#xf24e; balance-scale</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-ban")?'selected':'') }} value="fa-ban"><i class="fa fa-ban"></i> &#xf05e; ban</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bandcamp")?'selected':'') }} value="fa-bandcamp"><i class="fa fa-bandcamp"></i> &#xf2d5; bandcamp</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bank")?'selected':'') }} value="fa-bank"><i class="fa fa-bank"></i> &#xf19c; bank</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bar-chart")?'selected':'') }} value="fa-bar-chart"><i class="fa fa-bar-chart"></i> &#xf080; bar-chart</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bar-chart-o")?'selected':'') }} value="fa-bar-chart-o"><i class="fa fa-bar-chart-o"></i> &#xf080; bar-chart-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-barcode")?'selected':'') }} value="fa-barcode"><i class="fa fa-barcode"></i> &#xf02a; barcode</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bars")?'selected':'') }} value="fa-bars"><i class="fa fa-bars"></i> &#xf0c9; bars</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bath")?'selected':'') }} value="fa-bath"><i class="fa fa-bath"></i> &#xf2cd; bath</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bathtub")?'selected':'') }} value="fa-bathtub"><i class="fa fa-bathtub"></i> &#xf2cd; bathtub</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-battery")?'selected':'') }} value="fa-battery"><i class="fa fa-battery"></i> &#xf240; battery</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-battery-0")?'selected':'') }} value="fa-battery-0"><i class="fa fa-battery-0"></i> &#xf244; battery-0</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-battery-1")?'selected':'') }} value="fa-battery-1"><i class="fa fa-battery-1"></i> &#xf243; battery-1</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-battery-2")?'selected':'') }} value="fa-battery-2"><i class="fa fa-battery-2"></i> &#xf242; battery-2</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-battery-3")?'selected':'') }} value="fa-battery-3"><i class="fa fa-battery-3"></i> &#xf241; battery-3</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-battery-4")?'selected':'') }} value="fa-battery-4"><i class="fa fa-battery-4"></i> &#xf240; battery-4</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-battery-empty")?'selected':'') }} value="fa-battery-empty"><i class="fa fa-battery-empty"></i> &#xf244; battery-empty</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-battery-full")?'selected':'') }} value="fa-battery-full"><i class="fa fa-battery-full"></i> &#xf240; battery-full</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-battery-half")?'selected':'') }} value="fa-battery-half"><i class="fa fa-battery-half"></i> &#xf242; battery-half</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-battery-quarter")?'selected':'') }} value="fa-battery-quarter"><i class="fa fa-battery-quarter"></i> &#xf243; battery-quarter</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-battery-three-quarters")?'selected':'') }} value="fa-battery-three-quarters"><i class="fa fa-battery-three-quarters"></i> &#xf241; battery-three-quarters</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bed")?'selected':'') }} value="fa-bed"><i class="fa fa-bed"></i> &#xf236; bed</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-beer")?'selected':'') }} value="fa-beer"><i class="fa fa-beer"></i> &#xf0fc; beer</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-behance")?'selected':'') }} value="fa-behance"><i class="fa fa-behance"></i> &#xf1b4; behance</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-behance-square")?'selected':'') }} value="fa-behance-square"><i class="fa fa-behance-square"></i> &#xf1b5; behance-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bell")?'selected':'') }} value="fa-bell"><i class="fa fa-bell"></i> &#xf0f3; bell</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bell-o")?'selected':'') }} value="fa-bell-o"><i class="fa fa-bell-o"></i> &#xf0a2; bell-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bell-slash")?'selected':'') }} value="fa-bell-slash"><i class="fa fa-bell-slash"></i> &#xf1f6; bell-slash</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bell-slash-o")?'selected':'') }} value="fa-bell-slash-o"><i class="fa fa-bell-slash-o"></i> &#xf1f7; bell-slash-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bicycle")?'selected':'') }} value="fa-bicycle"><i class="fa fa-bicycle"></i> &#xf206; bicycle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-binoculars")?'selected':'') }} value="fa-binoculars"><i class="fa fa-binoculars"></i> &#xf1e5; binoculars</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-birthday-cake")?'selected':'') }} value="fa-birthday-cake"><i class="fa fa-birthday-cake"></i> &#xf1fd; birthday-cake</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bitbucket")?'selected':'') }} value="fa-bitbucket"><i class="fa fa-bitbucket"></i> &#xf171; bitbucket</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bitbucket-square")?'selected':'') }} value="fa-bitbucket-square"><i class="fa fa-bitbucket-square"></i> &#xf172; bitbucket-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bitcoin")?'selected':'') }} value="fa-bitcoin"><i class="fa fa-bitcoin"></i> &#xf15a; bitcoin</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-black-tie")?'selected':'') }} value="fa-black-tie"><i class="fa fa-black-tie"></i> &#xf27e; black-tie</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-blind")?'selected':'') }} value="fa-blind"><i class="fa fa-blind"></i> &#xf29d; blind</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bluetooth")?'selected':'') }} value="fa-bluetooth"><i class="fa fa-bluetooth"></i> &#xf293; bluetooth</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bluetooth-b")?'selected':'') }} value="fa-bluetooth-b"><i class="fa fa-bluetooth-b"></i> &#xf294; bluetooth-b</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bold")?'selected':'') }} value="fa-bold"><i class="fa fa-bold"></i> &#xf032; bold</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bolt")?'selected':'') }} value="fa-bolt"><i class="fa fa-bolt"></i> &#xf0e7; bolt</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bomb")?'selected':'') }} value="fa-bomb"><i class="fa fa-bomb"></i> &#xf1e2; bomb</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-book")?'selected':'') }} value="fa-book"><i class="fa fa-book"></i> &#xf02d; book</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bookmark")?'selected':'') }} value="fa-bookmark"><i class="fa fa-bookmark"></i> &#xf02e; bookmark</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bookmark-o")?'selected':'') }} value="fa-bookmark-o"><i class="fa fa-bookmark-o"></i> &#xf097; bookmark-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-braille")?'selected':'') }} value="fa-braille"><i class="fa fa-braille"></i> &#xf2a1; braille</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-briefcase")?'selected':'') }} value="fa-briefcase"><i class="fa fa-briefcase"></i> &#xf0b1; briefcase</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-btc")?'selected':'') }} value="fa-btc"><i class="fa fa-btc"></i> &#xf15a; btc</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bug")?'selected':'') }} value="fa-bug"><i class="fa fa-bug"></i> &#xf188; bug</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-building")?'selected':'') }} value="fa-building"><i class="fa fa-building"></i> &#xf1ad; building</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-building-o")?'selected':'') }} value="fa-building-o"><i class="fa fa-building-o"></i> &#xf0f7; building-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bullhorn")?'selected':'') }} value="fa-bullhorn"><i class="fa fa-bullhorn"></i> &#xf0a1; bullhorn</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bullseye")?'selected':'') }} value="fa-bullseye"><i class="fa fa-bullseye"></i> &#xf140; bullseye</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-bus")?'selected':'') }} value="fa-bus"><i class="fa fa-bus"></i> &#xf207; bus</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-buysellads")?'selected':'') }} value="fa-buysellads"><i class="fa fa-buysellads"></i> &#xf20d; buysellads</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cab")?'selected':'') }} value="fa-cab"><i class="fa fa-cab"></i> &#xf1ba; cab</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-calculator")?'selected':'') }} value="fa-calculator"><i class="fa fa-calculator"></i> &#xf1ec; calculator</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-calendar")?'selected':'') }} value="fa-calendar"><i class="fa fa-calendar"></i> &#xf073; calendar</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-calendar-check-o")?'selected':'') }} value="fa-calendar-check-o"><i class="fa fa-calendar-check-o"></i> &#xf274; calendar-check-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-calendar-minus-o")?'selected':'') }} value="fa-calendar-minus-o"><i class="fa fa-calendar-minus-o"></i> &#xf272; calendar-minus-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-calendar-o")?'selected':'') }} value="fa-calendar-o"><i class="fa fa-calendar-o"></i> &#xf133; calendar-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-calendar-plus-o")?'selected':'') }} value="fa-calendar-plus-o"><i class="fa fa-calendar-plus-o"></i> &#xf271; calendar-plus-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-calendar-times-o")?'selected':'') }} value="fa-calendar-times-o"><i class="fa fa-calendar-times-o"></i> &#xf273; calendar-times-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-camera")?'selected':'') }} value="fa-camera"><i class="fa fa-camera"></i> &#xf030; camera</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-camera-retro")?'selected':'') }} value="fa-camera-retro"><i class="fa fa-camera-retro"></i> &#xf083; camera-retro</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-car")?'selected':'') }} value="fa-car"><i class="fa fa-car"></i> &#xf1b9; car</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-caret-down")?'selected':'') }} value="fa-caret-down"><i class="fa fa-caret-down"></i> &#xf0d7; caret-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-caret-left")?'selected':'') }} value="fa-caret-left"><i class="fa fa-caret-left"></i> &#xf0d9; caret-left</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-caret-right")?'selected':'') }} value="fa-caret-right"><i class="fa fa-caret-right"></i> &#xf0da; caret-right</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-caret-square-o-down")?'selected':'') }} value="fa-caret-square-o-down"><i class="fa fa-caret-square-o-down"></i> &#xf150; caret-square-o-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-caret-square-o-left")?'selected':'') }} value="fa-caret-square-o-left"><i class="fa fa-caret-square-o-left"></i> &#xf191; caret-square-o-left</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-caret-square-o-right")?'selected':'') }} value="fa-caret-square-o-right"><i class="fa fa-caret-square-o-right"></i> &#xf152; caret-square-o-right</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-caret-square-o-up")?'selected':'') }} value="fa-caret-square-o-up"><i class="fa fa-caret-square-o-up"></i> &#xf151; caret-square-o-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-caret-up")?'selected':'') }} value="fa-caret-up"><i class="fa fa-caret-up"></i> &#xf0d8; caret-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cart-arrow-down")?'selected':'') }} value="fa-cart-arrow-down"><i class="fa fa-cart-arrow-down"></i> &#xf218; cart-arrow-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cart-plus")?'selected':'') }} value="fa-cart-plus"><i class="fa fa-cart-plus"></i> &#xf217; cart-plus</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cc")?'selected':'') }} value="fa-cc"><i class="fa fa-cc"></i> &#xf20a; cc</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cc-amex")?'selected':'') }} value="fa-cc-amex"><i class="fa fa-cc-amex"></i> &#xf1f3; cc-amex</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cc-diners-club")?'selected':'') }} value="fa-cc-diners-club"><i class="fa fa-cc-diners-club"></i> &#xf24c; cc-diners-club</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cc-discover")?'selected':'') }} value="fa-cc-discover"><i class="fa fa-cc-discover"></i> &#xf1f2; cc-discover</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cc-jcb")?'selected':'') }} value="fa-cc-jcb"><i class="fa fa-cc-jcb"></i> &#xf24b; cc-jcb</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cc-mastercard")?'selected':'') }} value="fa-cc-mastercard"><i class="fa fa-cc-mastercard"></i> &#xf1f1; cc-mastercard</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cc-paypal")?'selected':'') }} value="fa-cc-paypal"><i class="fa fa-cc-paypal"></i> &#xf1f4; cc-paypal</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cc-stripe")?'selected':'') }} value="fa-cc-stripe"><i class="fa fa-cc-stripe"></i> &#xf1f5; cc-stripe</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cc-visa")?'selected':'') }} value="fa-cc-visa"><i class="fa fa-cc-visa"></i> &#xf1f0; cc-visa</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-certificate")?'selected':'') }} value="fa-certificate"><i class="fa fa-certificate"></i> &#xf0a3; certificate</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-chain")?'selected':'') }} value="fa-chain"><i class="fa fa-chain"></i> &#xf0c1; chain</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-chain-broken")?'selected':'') }} value="fa-chain-broken"><i class="fa fa-chain-broken"></i> &#xf127; chain-broken</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-check")?'selected':'') }} value="fa-check"><i class="fa fa-check"></i> &#xf00c; check</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-check-circle")?'selected':'') }} value="fa-check-circle"><i class="fa fa-check-circle"></i> &#xf058; check-circle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-check-circle-o")?'selected':'') }} value="fa-check-circle-o"><i class="fa fa-check-circle-o"></i> &#xf05d; check-circle-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-check-square")?'selected':'') }} value="fa-check-square"><i class="fa fa-check-square"></i> &#xf14a; check-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-check-square-o")?'selected':'') }} value="fa-check-square-o"><i class="fa fa-check-square-o"></i> &#xf046; check-square-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-chevron-circle-down")?'selected':'') }} value="fa-chevron-circle-down"><i class="fa fa-chevron-circle-down"></i> &#xf13a; chevron-circle-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-chevron-circle-left")?'selected':'') }} value="fa-chevron-circle-left"><i class="fa fa-chevron-circle-left"></i> &#xf137; chevron-circle-left</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-chevron-circle-right")?'selected':'') }} value="fa-chevron-circle-right"><i class="fa fa-chevron-circle-right"></i> &#xf138; chevron-circle-right</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-chevron-circle-up")?'selected':'') }} value="fa-chevron-circle-up"><i class="fa fa-chevron-circle-up"></i> &#xf139; chevron-circle-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-chevron-down")?'selected':'') }} value="fa-chevron-down"><i class="fa fa-chevron-down"></i> &#xf078; chevron-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-chevron-left")?'selected':'') }} value="fa-chevron-left"><i class="fa fa-chevron-left"></i> &#xf053; chevron-left</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-chevron-right")?'selected':'') }} value="fa-chevron-right"><i class="fa fa-chevron-right"></i> &#xf054; chevron-right</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-chevron-up")?'selected':'') }} value="fa-chevron-up"><i class="fa fa-chevron-up"></i> &#xf077; chevron-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-child")?'selected':'') }} value="fa-child"><i class="fa fa-child"></i> &#xf1ae; child</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-chrome")?'selected':'') }} value="fa-chrome"><i class="fa fa-chrome"></i> &#xf268; chrome</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-circle")?'selected':'') }} value="fa-circle"><i class="fa fa-circle"></i> &#xf111; circle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-circle-o")?'selected':'') }} value="fa-circle-o"><i class="fa fa-circle-o"></i> &#xf10c; circle-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-circle-o-notch")?'selected':'') }} value="fa-circle-o-notch"><i class="fa fa-circle-o-notch"></i> &#xf1ce; circle-o-notch</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-circle-thin")?'selected':'') }} value="fa-circle-thin"><i class="fa fa-circle-thin"></i> &#xf1db; circle-thin</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-clipboard")?'selected':'') }} value="fa-clipboard"><i class="fa fa-clipboard"></i> &#xf0ea; clipboard</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-clock-o")?'selected':'') }} value="fa-clock-o"><i class="fa fa-clock-o"></i> &#xf017; clock-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-clone")?'selected':'') }} value="fa-clone"><i class="fa fa-clone"></i> &#xf24d; clone</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-close")?'selected':'') }} value="fa-close"><i class="fa fa-close"></i> &#xf00d; close</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cloud")?'selected':'') }} value="fa-cloud"><i class="fa fa-cloud"></i> &#xf0c2; cloud</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cloud-download")?'selected':'') }} value="fa-cloud-download"><i class="fa fa-cloud-download"></i> &#xf0ed; cloud-download</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cloud-upload")?'selected':'') }} value="fa-cloud-upload"><i class="fa fa-cloud-upload"></i> &#xf0ee; cloud-upload</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cny")?'selected':'') }} value="fa-cny"><i class="fa fa-cny"></i> &#xf157; cny</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-code")?'selected':'') }} value="fa-code"><i class="fa fa-code"></i> &#xf121; code</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-code-fork")?'selected':'') }} value="fa-code-fork"><i class="fa fa-code-fork"></i> &#xf126; code-fork</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-codepen")?'selected':'') }} value="fa-codepen"><i class="fa fa-codepen"></i> &#xf1cb; codepen</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-codiepie")?'selected':'') }} value="fa-codiepie"><i class="fa fa-codiepie"></i> &#xf284; codiepie</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-coffee")?'selected':'') }} value="fa-coffee"><i class="fa fa-coffee"></i> &#xf0f4; coffee</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cog")?'selected':'') }} value="fa-cog"><i class="fa fa-cog"></i> &#xf013; cog</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cogs")?'selected':'') }} value="fa-cogs"><i class="fa fa-cogs"></i> &#xf085; cogs</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-columns")?'selected':'') }} value="fa-columns"><i class="fa fa-columns"></i> &#xf0db; columns</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-comment")?'selected':'') }} value="fa-comment"><i class="fa fa-comment"></i> &#xf075; comment</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-comment-o")?'selected':'') }} value="fa-comment-o"><i class="fa fa-comment-o"></i> &#xf0e5; comment-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-commenting")?'selected':'') }} value="fa-commenting"><i class="fa fa-commenting"></i> &#xf27a; commenting</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-commenting-o")?'selected':'') }} value="fa-commenting-o"><i class="fa fa-commenting-o"></i> &#xf27b; commenting-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-comments")?'selected':'') }} value="fa-comments"><i class="fa fa-comments"></i> &#xf086; comments</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-comments-o")?'selected':'') }} value="fa-comments-o"><i class="fa fa-comments-o"></i> &#xf0e6; comments-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-compass")?'selected':'') }} value="fa-compass"><i class="fa fa-compass"></i> &#xf14e; compass</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-compress")?'selected':'') }} value="fa-compress"><i class="fa fa-compress"></i> &#xf066; compress</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-connectdevelop")?'selected':'') }} value="fa-connectdevelop"><i class="fa fa-connectdevelop"></i> &#xf20e; connectdevelop</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-contao")?'selected':'') }} value="fa-contao"><i class="fa fa-contao"></i> &#xf26d; contao</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-copy")?'selected':'') }} value="fa-copy"><i class="fa fa-copy"></i> &#xf0c5; copy</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-copyright")?'selected':'') }} value="fa-copyright"><i class="fa fa-copyright"></i> &#xf1f9; copyright</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-creative-commons")?'selected':'') }} value="fa-creative-commons"><i class="fa fa-creative-commons"></i> &#xf25e; creative-commons</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-credit-card")?'selected':'') }} value="fa-credit-card"><i class="fa fa-credit-card"></i> &#xf09d; credit-card</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-credit-card-alt")?'selected':'') }} value="fa-credit-card-alt"><i class="fa fa-credit-card-alt"></i> &#xf283; credit-card-alt</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-crop")?'selected':'') }} value="fa-crop"><i class="fa fa-crop"></i> &#xf125; crop</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-crosshairs")?'selected':'') }} value="fa-crosshairs"><i class="fa fa-crosshairs"></i> &#xf05b; crosshairs</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-css3")?'selected':'') }} value="fa-css3"><i class="fa fa-css3"></i> &#xf13c; css3</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cube")?'selected':'') }} value="fa-cube"><i class="fa fa-cube"></i> &#xf1b2; cube</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cubes")?'selected':'') }} value="fa-cubes"><i class="fa fa-cubes"></i> &#xf1b3; cubes</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cut")?'selected':'') }} value="fa-cut"><i class="fa fa-cut"></i> &#xf0c4; cut</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-cutlery")?'selected':'') }} value="fa-cutlery"><i class="fa fa-cutlery"></i> &#xf0f5; cutlery</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-dashboard")?'selected':'') }} value="fa-dashboard"><i class="fa fa-dashboard"></i> &#xf0e4; dashboard</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-dashcube")?'selected':'') }} value="fa-dashcube"><i class="fa fa-dashcube"></i> &#xf210; dashcube</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-database")?'selected':'') }} value="fa-database"><i class="fa fa-database"></i> &#xf1c0; database</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-deaf")?'selected':'') }} value="fa-deaf"><i class="fa fa-deaf"></i> &#xf2a4; deaf</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-deafness")?'selected':'') }} value="fa-deafness"><i class="fa fa-deafness"></i> &#xf2a4; deafness</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-dedent")?'selected':'') }} value="fa-dedent"><i class="fa fa-dedent"></i> &#xf03b; dedent</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-delicious")?'selected':'') }} value="fa-delicious"><i class="fa fa-delicious"></i> &#xf1a5; delicious</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-desktop")?'selected':'') }} value="fa-desktop"><i class="fa fa-desktop"></i> &#xf108; desktop</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-deviantart")?'selected':'') }} value="fa-deviantart"><i class="fa fa-deviantart"></i> &#xf1bd; deviantart</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-diamond")?'selected':'') }} value="fa-diamond"><i class="fa fa-diamond"></i> &#xf219; diamond</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-digg")?'selected':'') }} value="fa-digg"><i class="fa fa-digg"></i> &#xf1a6; digg</option>
											<option value="dollar"><i class="fa dollar"></i> &#xf155; lar</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-dot-circle-o")?'selected':'') }} value="fa-dot-circle-o"><i class="fa fa-dot-circle-o"></i> &#xf192; dot-circle-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-download")?'selected':'') }} value="fa-download"><i class="fa fa-download"></i> &#xf019; download</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-dribbble")?'selected':'') }} value="fa-dribbble"><i class="fa fa-dribbble"></i> &#xf17d; dribbble</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-drivers-license")?'selected':'') }} value="fa-drivers-license"><i class="fa fa-drivers-license"></i> &#xf2c2; drivers-license</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-drivers-license-o")?'selected':'') }} value="fa-drivers-license-o"><i class="fa fa-drivers-license-o"></i> &#xf2c3; drivers-license-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-dropbox")?'selected':'') }} value="fa-dropbox"><i class="fa fa-dropbox"></i> &#xf16b; dropbox</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-drupal")?'selected':'') }} value="fa-drupal"><i class="fa fa-drupal"></i> &#xf1a9; drupal</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-edge")?'selected':'') }} value="fa-edge"><i class="fa fa-edge"></i> &#xf282; edge</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-edit")?'selected':'') }} value="fa-edit"><i class="fa fa-edit"></i> &#xf044; edit</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-eercast")?'selected':'') }} value="fa-eercast"><i class="fa fa-eercast"></i> &#xf2da; eercast</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-eject")?'selected':'') }} value="fa-eject"><i class="fa fa-eject"></i> &#xf052; eject</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-ellipsis-h")?'selected':'') }} value="fa-ellipsis-h"><i class="fa fa-ellipsis-h"></i> &#xf141; ellipsis-h</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-ellipsis-v")?'selected':'') }} value="fa-ellipsis-v"><i class="fa fa-ellipsis-v"></i> &#xf142; ellipsis-v</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-empire")?'selected':'') }} value="fa-empire"><i class="fa fa-empire"></i> &#xf1d1; empire</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-envelope")?'selected':'') }} value="fa-envelope"><i class="fa fa-envelope"></i> &#xf0e0; envelope</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-envelope-o")?'selected':'') }} value="fa-envelope-o"><i class="fa fa-envelope-o"></i> &#xf003; envelope-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-envelope-open")?'selected':'') }} value="fa-envelope-open"><i class="fa fa-envelope-open"></i> &#xf2b6; envelope-open</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-envelope-open-o")?'selected':'') }} value="fa-envelope-open-o"><i class="fa fa-envelope-open-o"></i> &#xf2b7; envelope-open-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-envelope-square")?'selected':'') }} value="fa-envelope-square"><i class="fa fa-envelope-square"></i> &#xf199; envelope-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-envira")?'selected':'') }} value="fa-envira"><i class="fa fa-envira"></i> &#xf299; envira</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-eraser")?'selected':'') }} value="fa-eraser"><i class="fa fa-eraser"></i> &#xf12d; eraser</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-etsy")?'selected':'') }} value="fa-etsy"><i class="fa fa-etsy"></i> &#xf2d7; etsy</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-eur")?'selected':'') }} value="fa-eur"><i class="fa fa-eur"></i> &#xf153; eur</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-euro")?'selected':'') }} value="fa-euro"><i class="fa fa-euro"></i> &#xf153; euro</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-exchange")?'selected':'') }} value="fa-exchange"><i class="fa fa-exchange"></i> &#xf0ec; exchange</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-exclamation")?'selected':'') }} value="fa-exclamation"><i class="fa fa-exclamation"></i> &#xf12a; exclamation</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-exclamation-circle")?'selected':'') }} value="fa-exclamation-circle"><i class="fa fa-exclamation-circle"></i> &#xf06a; exclamation-circle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-exclamation-triangle")?'selected':'') }} value="fa-exclamation-triangle"><i class="fa fa-exclamation-triangle"></i> &#xf071; exclamation-triangle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-expand")?'selected':'') }} value="fa-expand"><i class="fa fa-expand"></i> &#xf065; expand</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-expeditedssl")?'selected':'') }} value="fa-expeditedssl"><i class="fa fa-expeditedssl"></i> &#xf23e; expeditedssl</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-external-link")?'selected':'') }} value="fa-external-link"><i class="fa fa-external-link"></i> &#xf08e; external-link</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-external-link-square")?'selected':'') }} value="fa-external-link-square"><i class="fa fa-external-link-square"></i> &#xf14c; external-link-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-eye")?'selected':'') }} value="fa-eye"><i class="fa fa-eye"></i> &#xf06e; eye</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-eye-slash")?'selected':'') }} value="fa-eye-slash"><i class="fa fa-eye-slash"></i> &#xf070; eye-slash</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-eyedropper")?'selected':'') }} value="fa-eyedropper"><i class="fa fa-eyedropper"></i> &#xf1fb; eyedropper</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-fa")?'selected':'') }} value="fa-fa"><i class="fa fa-fa"></i> &#xf2b4; fa</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-facebook")?'selected':'') }} value="fa-facebook"><i class="fa fa-facebook"></i> &#xf09a; facebook</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-facebook-f")?'selected':'') }} value="fa-facebook-f"><i class="fa fa-facebook-f"></i> &#xf09a; facebook-f</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-facebook-official")?'selected':'') }} value="fa-facebook-official"><i class="fa fa-facebook-official"></i> &#xf230; facebook-official</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-facebook-square")?'selected':'') }} value="fa-facebook-square"><i class="fa fa-facebook-square"></i> &#xf082; facebook-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-fast-backward")?'selected':'') }} value="fa-fast-backward"><i class="fa fa-fast-backward"></i> &#xf049; fast-backward</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-fast-forward")?'selected':'') }} value="fa-fast-forward"><i class="fa fa-fast-forward"></i> &#xf050; fast-forward</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-fax")?'selected':'') }} value="fa-fax"><i class="fa fa-fax"></i> &#xf1ac; fax</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-feed")?'selected':'') }} value="fa-feed"><i class="fa fa-feed"></i> &#xf09e; feed</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-female")?'selected':'') }} value="fa-female"><i class="fa fa-female"></i> &#xf182; female</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-fighter-jet")?'selected':'') }} value="fa-fighter-jet"><i class="fa fa-fighter-jet"></i> &#xf0fb; fighter-jet</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file")?'selected':'') }} value="fa-file"><i class="fa fa-file"></i> &#xf15b; file</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-archive-o")?'selected':'') }} value="fa-file-archive-o"><i class="fa fa-file-archive-o"></i> &#xf1c6; file-archive-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-audio-o")?'selected':'') }} value="fa-file-audio-o"><i class="fa fa-file-audio-o"></i> &#xf1c7; file-audio-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-code-o")?'selected':'') }} value="fa-file-code-o"><i class="fa fa-file-code-o"></i> &#xf1c9; file-code-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-excel-o")?'selected':'') }} value="fa-file-excel-o"><i class="fa fa-file-excel-o"></i> &#xf1c3; file-excel-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-image-o")?'selected':'') }} value="fa-file-image-o"><i class="fa fa-file-image-o"></i> &#xf1c5; file-image-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-movie-o")?'selected':'') }} value="fa-file-movie-o"><i class="fa fa-file-movie-o"></i> &#xf1c8; file-movie-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-o")?'selected':'') }} value="fa-file-o"><i class="fa fa-file-o"></i> &#xf016; file-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-pdf-o")?'selected':'') }} value="fa-file-pdf-o"><i class="fa fa-file-pdf-o"></i> &#xf1c1; file-pdf-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-photo-o")?'selected':'') }} value="fa-file-photo-o"><i class="fa fa-file-photo-o"></i> &#xf1c5; file-photo-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-picture-o")?'selected':'') }} value="fa-file-picture-o"><i class="fa fa-file-picture-o"></i> &#xf1c5; file-picture-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-powerpoint-o")?'selected':'') }} value="fa-file-powerpoint-o"><i class="fa fa-file-powerpoint-o"></i> &#xf1c4; file-powerpoint-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-sound-o")?'selected':'') }} value="fa-file-sound-o"><i class="fa fa-file-sound-o"></i> &#xf1c7; file-sound-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-text")?'selected':'') }} value="fa-file-text"><i class="fa fa-file-text"></i> &#xf15c; file-text</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-text-o")?'selected':'') }} value="fa-file-text-o"><i class="fa fa-file-text-o"></i> &#xf0f6; file-text-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-video-o")?'selected':'') }} value="fa-file-video-o"><i class="fa fa-file-video-o"></i> &#xf1c8; file-video-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-word-o")?'selected':'') }} value="fa-file-word-o"><i class="fa fa-file-word-o"></i> &#xf1c2; file-word-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-file-zip-o")?'selected':'') }} value="fa-file-zip-o"><i class="fa fa-file-zip-o"></i> &#xf1c6; file-zip-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-files-o")?'selected':'') }} value="fa-files-o"><i class="fa fa-files-o"></i> &#xf0c5; files-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-film")?'selected':'') }} value="fa-film"><i class="fa fa-film"></i> &#xf008; film</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-filter")?'selected':'') }} value="fa-filter"><i class="fa fa-filter"></i> &#xf0b0; filter</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-fire")?'selected':'') }} value="fa-fire"><i class="fa fa-fire"></i> &#xf06d; fire</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-fire-extinguisher")?'selected':'') }} value="fa-fire-extinguisher"><i class="fa fa-fire-extinguisher"></i> &#xf134; fire-extinguisher</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-firefox")?'selected':'') }} value="fa-firefox"><i class="fa fa-firefox"></i> &#xf269; firefox</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-first-order")?'selected':'') }} value="fa-first-order"><i class="fa fa-first-order"></i> &#xf2b0; first-order</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-flag")?'selected':'') }} value="fa-flag"><i class="fa fa-flag"></i> &#xf024; flag</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-flag-checkered")?'selected':'') }} value="fa-flag-checkered"><i class="fa fa-flag-checkered"></i> &#xf11e; flag-checkered</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-flag-o")?'selected':'') }} value="fa-flag-o"><i class="fa fa-flag-o"></i> &#xf11d; flag-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-flash")?'selected':'') }} value="fa-flash"><i class="fa fa-flash"></i> &#xf0e7; flash</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-flask")?'selected':'') }} value="fa-flask"><i class="fa fa-flask"></i> &#xf0c3; flask</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-flickr")?'selected':'') }} value="fa-flickr"><i class="fa fa-flickr"></i> &#xf16e; flickr</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-floppy-o")?'selected':'') }} value="fa-floppy-o"><i class="fa fa-floppy-o"></i> &#xf0c7; floppy-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-folder")?'selected':'') }} value="fa-folder"><i class="fa fa-folder"></i> &#xf07b; folder</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-folder-o")?'selected':'') }} value="fa-folder-o"><i class="fa fa-folder-o"></i> &#xf114; folder-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-folder-open")?'selected':'') }} value="fa-folder-open"><i class="fa fa-folder-open"></i> &#xf07c; folder-open</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-folder-open-o")?'selected':'') }} value="fa-folder-open-o"><i class="fa fa-folder-open-o"></i> &#xf115; folder-open-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-font")?'selected':'') }} value="fa-font"><i class="fa fa-font"></i> &#xf031; font</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-font-awesome")?'selected':'') }} value="fa-font-awesome"><i class="fa fa-font-awesome"></i> &#xf2b4; font-awesome</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-fonticons")?'selected':'') }} value="fa-fonticons"><i class="fa fa-fonticons"></i> &#xf280; fonticons</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-fort-awesome")?'selected':'') }} value="fa-fort-awesome"><i class="fa fa-fort-awesome"></i> &#xf286; fort-awesome</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-forumbee")?'selected':'') }} value="fa-forumbee"><i class="fa fa-forumbee"></i> &#xf211; forumbee</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-forward")?'selected':'') }} value="fa-forward"><i class="fa fa-forward"></i> &#xf04e; forward</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-foursquare")?'selected':'') }} value="fa-foursquare"><i class="fa fa-foursquare"></i> &#xf180; foursquare</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-free-code-camp")?'selected':'') }} value="fa-free-code-camp"><i class="fa fa-free-code-camp"></i> &#xf2c5; free-code-camp</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-frown-o")?'selected':'') }} value="fa-frown-o"><i class="fa fa-frown-o"></i> &#xf119; frown-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-futbol-o")?'selected':'') }} value="fa-futbol-o"><i class="fa fa-futbol-o"></i> &#xf1e3; futbol-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-gamepad")?'selected':'') }} value="fa-gamepad"><i class="fa fa-gamepad"></i> &#xf11b; gamepad</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-gavel")?'selected':'') }} value="fa-gavel"><i class="fa fa-gavel"></i> &#xf0e3; gavel</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-gbp")?'selected':'') }} value="fa-gbp"><i class="fa fa-gbp"></i> &#xf154; gbp</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-ge")?'selected':'') }} value="fa-ge"><i class="fa fa-ge"></i> &#xf1d1; ge</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-gear")?'selected':'') }} value="fa-gear"><i class="fa fa-gear"></i> &#xf013; gear</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-gears")?'selected':'') }} value="fa-gears"><i class="fa fa-gears"></i> &#xf085; gears</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-genderless")?'selected':'') }} value="fa-genderless"><i class="fa fa-genderless"></i> &#xf22d; genderless</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-get-pocket")?'selected':'') }} value="fa-get-pocket"><i class="fa fa-get-pocket"></i> &#xf265; get-pocket</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-gg")?'selected':'') }} value="fa-gg"><i class="fa fa-gg"></i> &#xf260; gg</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-gg-circle")?'selected':'') }} value="fa-gg-circle"><i class="fa fa-gg-circle"></i> &#xf261; gg-circle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-gift")?'selected':'') }} value="fa-gift"><i class="fa fa-gift"></i> &#xf06b; gift</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-git")?'selected':'') }} value="fa-git"><i class="fa fa-git"></i> &#xf1d3; git</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-git-square")?'selected':'') }} value="fa-git-square"><i class="fa fa-git-square"></i> &#xf1d2; git-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-github")?'selected':'') }} value="fa-github"><i class="fa fa-github"></i> &#xf09b; github</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-github-alt")?'selected':'') }} value="fa-github-alt"><i class="fa fa-github-alt"></i> &#xf113; github-alt</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-github-square")?'selected':'') }} value="fa-github-square"><i class="fa fa-github-square"></i> &#xf092; github-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-gitlab")?'selected':'') }} value="fa-gitlab"><i class="fa fa-gitlab"></i> &#xf296; gitlab</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-gittip")?'selected':'') }} value="fa-gittip"><i class="fa fa-gittip"></i> &#xf184; gittip</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-glass")?'selected':'') }} value="fa-glass"><i class="fa fa-glass"></i> &#xf000; glass</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-glide")?'selected':'') }} value="fa-glide"><i class="fa fa-glide"></i> &#xf2a5; glide</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-glide-g")?'selected':'') }} value="fa-glide-g"><i class="fa fa-glide-g"></i> &#xf2a6; glide-g</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-globe")?'selected':'') }} value="fa-globe"><i class="fa fa-globe"></i> &#xf0ac; globe</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-google")?'selected':'') }} value="fa-google"><i class="fa fa-google"></i> &#xf1a0; google</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-google-plus")?'selected':'') }} value="fa-google-plus"><i class="fa fa-google-plus"></i> &#xf0d5; google-plus</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-google-plus-circle")?'selected':'') }} value="fa-google-plus-circle"><i class="fa fa-google-plus-circle"></i> &#xf2b3; google-plus-circle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-google-plus-official")?'selected':'') }} value="fa-google-plus-official"><i class="fa fa-google-plus-official"></i> &#xf2b3; google-plus-official</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-google-plus-square")?'selected':'') }} value="fa-google-plus-square"><i class="fa fa-google-plus-square"></i> &#xf0d4; google-plus-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-google-wallet")?'selected':'') }} value="fa-google-wallet"><i class="fa fa-google-wallet"></i> &#xf1ee; google-wallet</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-graduation-cap")?'selected':'') }} value="fa-graduation-cap"><i class="fa fa-graduation-cap"></i> &#xf19d; graduation-cap</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-gratipay")?'selected':'') }} value="fa-gratipay"><i class="fa fa-gratipay"></i> &#xf184; gratipay</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-grav")?'selected':'') }} value="fa-grav"><i class="fa fa-grav"></i> &#xf2d6; grav</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-group")?'selected':'') }} value="fa-group"><i class="fa fa-group"></i> &#xf0c0; group</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-h-square")?'selected':'') }} value="fa-h-square"><i class="fa fa-h-square"></i> &#xf0fd; h-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hacker-news")?'selected':'') }} value="fa-hacker-news"><i class="fa fa-hacker-news"></i> &#xf1d4; hacker-news</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hand-grab-o")?'selected':'') }} value="fa-hand-grab-o"><i class="fa fa-hand-grab-o"></i> &#xf255; hand-grab-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hand-lizard-o")?'selected':'') }} value="fa-hand-lizard-o"><i class="fa fa-hand-lizard-o"></i> &#xf258; hand-lizard-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hand-o-down")?'selected':'') }} value="fa-hand-o-down"><i class="fa fa-hand-o-down"></i> &#xf0a7; hand-o-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hand-o-left")?'selected':'') }} value="fa-hand-o-left"><i class="fa fa-hand-o-left"></i> &#xf0a5; hand-o-left</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hand-o-right")?'selected':'') }} value="fa-hand-o-right"><i class="fa fa-hand-o-right"></i> &#xf0a4; hand-o-right</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hand-o-up")?'selected':'') }} value="fa-hand-o-up"><i class="fa fa-hand-o-up"></i> &#xf0a6; hand-o-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hand-paper-o")?'selected':'') }} value="fa-hand-paper-o"><i class="fa fa-hand-paper-o"></i> &#xf256; hand-paper-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hand-peace-o")?'selected':'') }} value="fa-hand-peace-o"><i class="fa fa-hand-peace-o"></i> &#xf25b; hand-peace-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hand-pointer-o")?'selected':'') }} value="fa-hand-pointer-o"><i class="fa fa-hand-pointer-o"></i> &#xf25a; hand-pointer-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hand-rock-o")?'selected':'') }} value="fa-hand-rock-o"><i class="fa fa-hand-rock-o"></i> &#xf255; hand-rock-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hand-scissors-o")?'selected':'') }} value="fa-hand-scissors-o"><i class="fa fa-hand-scissors-o"></i> &#xf257; hand-scissors-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hand-spock-o")?'selected':'') }} value="fa-hand-spock-o"><i class="fa fa-hand-spock-o"></i> &#xf259; hand-spock-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hand-stop-o")?'selected':'') }} value="fa-hand-stop-o"><i class="fa fa-hand-stop-o"></i> &#xf256; hand-stop-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-handshake-o")?'selected':'') }} value="fa-handshake-o"><i class="fa fa-handshake-o"></i> &#xf2b5; handshake-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hard-of-hearing")?'selected':'') }} value="fa-hard-of-hearing"><i class="fa fa-hard-of-hearing"></i> &#xf2a4; hard-of-hearing</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hashtag")?'selected':'') }} value="fa-hashtag"><i class="fa fa-hashtag"></i> &#xf292; hashtag</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hdd-o")?'selected':'') }} value="fa-hdd-o"><i class="fa fa-hdd-o"></i> &#xf0a0; hdd-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-header")?'selected':'') }} value="fa-header"><i class="fa fa-header"></i> &#xf1dc; header</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-headphones")?'selected':'') }} value="fa-headphones"><i class="fa fa-headphones"></i> &#xf025; headphones</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-heart")?'selected':'') }} value="fa-heart"><i class="fa fa-heart"></i> &#xf004; heart</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-heart-o")?'selected':'') }} value="fa-heart-o"><i class="fa fa-heart-o"></i> &#xf08a; heart-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-heartbeat")?'selected':'') }} value="fa-heartbeat"><i class="fa fa-heartbeat"></i> &#xf21e; heartbeat</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-history")?'selected':'') }} value="fa-history"><i class="fa fa-history"></i> &#xf1da; history</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-home")?'selected':'') }} value="fa-home"><i class="fa fa-home"></i> &#xf015; home</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hospital-o")?'selected':'') }} value="fa-hospital-o"><i class="fa fa-hospital-o"></i> &#xf0f8; hospital-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hotel")?'selected':'') }} value="fa-hotel"><i class="fa fa-hotel"></i> &#xf236; hotel</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hourglass")?'selected':'') }} value="fa-hourglass"><i class="fa fa-hourglass"></i> &#xf254; hourglass</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hourglass-1")?'selected':'') }} value="fa-hourglass-1"><i class="fa fa-hourglass-1"></i> &#xf251; hourglass-1</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hourglass-2")?'selected':'') }} value="fa-hourglass-2"><i class="fa fa-hourglass-2"></i> &#xf252; hourglass-2</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hourglass-3")?'selected':'') }} value="fa-hourglass-3"><i class="fa fa-hourglass-3"></i> &#xf253; hourglass-3</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hourglass-end")?'selected':'') }} value="fa-hourglass-end"><i class="fa fa-hourglass-end"></i> &#xf253; hourglass-end</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hourglass-half")?'selected':'') }} value="fa-hourglass-half"><i class="fa fa-hourglass-half"></i> &#xf252; hourglass-half</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hourglass-o")?'selected':'') }} value="fa-hourglass-o"><i class="fa fa-hourglass-o"></i> &#xf250; hourglass-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-hourglass-start")?'selected':'') }} value="fa-hourglass-start"><i class="fa fa-hourglass-start"></i> &#xf251; hourglass-start</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-houzz")?'selected':'') }} value="fa-houzz"><i class="fa fa-houzz"></i> &#xf27c; houzz</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-html5")?'selected':'') }} value="fa-html5"><i class="fa fa-html5"></i> &#xf13b; html5</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-i-cursor")?'selected':'') }} value="fa-i-cursor"><i class="fa fa-i-cursor"></i> &#xf246; i-cursor</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-id-badge")?'selected':'') }} value="fa-id-badge"><i class="fa fa-id-badge"></i> &#xf2c1; id-badge</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-id-card")?'selected':'') }} value="fa-id-card"><i class="fa fa-id-card"></i> &#xf2c2; id-card</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-id-card-o")?'selected':'') }} value="fa-id-card-o"><i class="fa fa-id-card-o"></i> &#xf2c3; id-card-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-ils")?'selected':'') }} value="fa-ils"><i class="fa fa-ils"></i> &#xf20b; ils</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-image")?'selected':'') }} value="fa-image"><i class="fa fa-image"></i> &#xf03e; image</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-imdb")?'selected':'') }} value="fa-imdb"><i class="fa fa-imdb"></i> &#xf2d8; imdb</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-inbox")?'selected':'') }} value="fa-inbox"><i class="fa fa-inbox"></i> &#xf01c; inbox</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-indent")?'selected':'') }} value="fa-indent"><i class="fa fa-indent"></i> &#xf03c; indent</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-industry")?'selected':'') }} value="fa-industry"><i class="fa fa-industry"></i> &#xf275; industry</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-info")?'selected':'') }} value="fa-info"><i class="fa fa-info"></i> &#xf129; info</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-info-circle")?'selected':'') }} value="fa-info-circle"><i class="fa fa-info-circle"></i> &#xf05a; info-circle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-inr")?'selected':'') }} value="fa-inr"><i class="fa fa-inr"></i> &#xf156; inr</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-instagram")?'selected':'') }} value="fa-instagram"><i class="fa fa-instagram"></i> &#xf16d; instagram</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-institution")?'selected':'') }} value="fa-institution"><i class="fa fa-institution"></i> &#xf19c; institution</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-internet-explorer")?'selected':'') }} value="fa-internet-explorer"><i class="fa fa-internet-explorer"></i> &#xf26b; internet-explorer</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-intersex")?'selected':'') }} value="fa-intersex"><i class="fa fa-intersex"></i> &#xf224; intersex</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-ioxhost")?'selected':'') }} value="fa-ioxhost"><i class="fa fa-ioxhost"></i> &#xf208; ioxhost</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-italic")?'selected':'') }} value="fa-italic"><i class="fa fa-italic"></i> &#xf033; italic</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-joomla")?'selected':'') }} value="fa-joomla"><i class="fa fa-joomla"></i> &#xf1aa; joomla</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-jpy")?'selected':'') }} value="fa-jpy"><i class="fa fa-jpy"></i> &#xf157; jpy</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-jsfiddle")?'selected':'') }} value="fa-jsfiddle"><i class="fa fa-jsfiddle"></i> &#xf1cc; jsfiddle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-key")?'selected':'') }} value="fa-key"><i class="fa fa-key"></i> &#xf084; key</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-keyboard-o")?'selected':'') }} value="fa-keyboard-o"><i class="fa fa-keyboard-o"></i> &#xf11c; keyboard-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-krw")?'selected':'') }} value="fa-krw"><i class="fa fa-krw"></i> &#xf159; krw</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-language")?'selected':'') }} value="fa-language"><i class="fa fa-language"></i> &#xf1ab; language</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-laptop")?'selected':'') }} value="fa-laptop"><i class="fa fa-laptop"></i> &#xf109; laptop</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-lastfm")?'selected':'') }} value="fa-lastfm"><i class="fa fa-lastfm"></i> &#xf202; lastfm</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-lastfm-square")?'selected':'') }} value="fa-lastfm-square"><i class="fa fa-lastfm-square"></i> &#xf203; lastfm-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-leaf")?'selected':'') }} value="fa-leaf"><i class="fa fa-leaf"></i> &#xf06c; leaf</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-leanpub")?'selected':'') }} value="fa-leanpub"><i class="fa fa-leanpub"></i> &#xf212; leanpub</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-legal")?'selected':'') }} value="fa-legal"><i class="fa fa-legal"></i> &#xf0e3; legal</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-lemon-o")?'selected':'') }} value="fa-lemon-o"><i class="fa fa-lemon-o"></i> &#xf094; lemon-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-level-down")?'selected':'') }} value="fa-level-down"><i class="fa fa-level-down"></i> &#xf149; level-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-level-up")?'selected':'') }} value="fa-level-up"><i class="fa fa-level-up"></i> &#xf148; level-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-life-bouy")?'selected':'') }} value="fa-life-bouy"><i class="fa fa-life-bouy"></i> &#xf1cd; life-bouy</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-life-buoy")?'selected':'') }} value="fa-life-buoy"><i class="fa fa-life-buoy"></i> &#xf1cd; life-buoy</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-life-ring")?'selected':'') }} value="fa-life-ring"><i class="fa fa-life-ring"></i> &#xf1cd; life-ring</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-life-saver")?'selected':'') }} value="fa-life-saver"><i class="fa fa-life-saver"></i> &#xf1cd; life-saver</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-lightbulb-o")?'selected':'') }} value="fa-lightbulb-o"><i class="fa fa-lightbulb-o"></i> &#xf0eb; lightbulb-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-line-chart")?'selected':'') }} value="fa-line-chart"><i class="fa fa-line-chart"></i> &#xf201; line-chart</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-link")?'selected':'') }} value="fa-link"><i class="fa fa-link"></i> &#xf0c1; link</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-linkedin")?'selected':'') }} value="fa-linkedin"><i class="fa fa-linkedin"></i> &#xf0e1; linkedin</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-linkedin-square")?'selected':'') }} value="fa-linkedin-square"><i class="fa fa-linkedin-square"></i> &#xf08c; linkedin-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-linode")?'selected':'') }} value="fa-linode"><i class="fa fa-linode"></i> &#xf2b8; linode</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-linux")?'selected':'') }} value="fa-linux"><i class="fa fa-linux"></i> &#xf17c; linux</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-list")?'selected':'') }} value="fa-list"><i class="fa fa-list"></i> &#xf03a; list</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-list-alt")?'selected':'') }} value="fa-list-alt"><i class="fa fa-list-alt"></i> &#xf022; list-alt</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-list-ol")?'selected':'') }} value="fa-list-ol"><i class="fa fa-list-ol"></i> &#xf0cb; list-ol</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-list-ul")?'selected':'') }} value="fa-list-ul"><i class="fa fa-list-ul"></i> &#xf0ca; list-ul</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-location-arrow")?'selected':'') }} value="fa-location-arrow"><i class="fa fa-location-arrow"></i> &#xf124; location-arrow</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-lock")?'selected':'') }} value="fa-lock"><i class="fa fa-lock"></i> &#xf023; lock</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-long-arrow-down")?'selected':'') }} value="fa-long-arrow-down"><i class="fa fa-long-arrow-down"></i> &#xf175; long-arrow-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-long-arrow-left")?'selected':'') }} value="fa-long-arrow-left"><i class="fa fa-long-arrow-left"></i> &#xf177; long-arrow-left</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-long-arrow-right")?'selected':'') }} value="fa-long-arrow-right"><i class="fa fa-long-arrow-right"></i> &#xf178; long-arrow-right</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-long-arrow-up")?'selected':'') }} value="fa-long-arrow-up"><i class="fa fa-long-arrow-up"></i> &#xf176; long-arrow-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-low-vision")?'selected':'') }} value="fa-low-vision"><i class="fa fa-low-vision"></i> &#xf2a8; low-vision</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-magic")?'selected':'') }} value="fa-magic"><i class="fa fa-magic"></i> &#xf0d0; magic</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-magnet")?'selected':'') }} value="fa-magnet"><i class="fa fa-magnet"></i> &#xf076; magnet</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-mail-forward")?'selected':'') }} value="fa-mail-forward"><i class="fa fa-mail-forward"></i> &#xf064; mail-forward</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-mail-reply")?'selected':'') }} value="fa-mail-reply"><i class="fa fa-mail-reply"></i> &#xf112; mail-reply</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-mail-reply-all")?'selected':'') }} value="fa-mail-reply-all"><i class="fa fa-mail-reply-all"></i> &#xf122; mail-reply-all</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-male")?'selected':'') }} value="fa-male"><i class="fa fa-male"></i> &#xf183; male</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-map")?'selected':'') }} value="fa-map"><i class="fa fa-map"></i> &#xf279; map</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-map-marker")?'selected':'') }} value="fa-map-marker"><i class="fa fa-map-marker"></i> &#xf041; map-marker</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-map-o")?'selected':'') }} value="fa-map-o"><i class="fa fa-map-o"></i> &#xf278; map-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-map-pin")?'selected':'') }} value="fa-map-pin"><i class="fa fa-map-pin"></i> &#xf276; map-pin</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-map-signs")?'selected':'') }} value="fa-map-signs"><i class="fa fa-map-signs"></i> &#xf277; map-signs</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-mars")?'selected':'') }} value="fa-mars"><i class="fa fa-mars"></i> &#xf222; mars</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-mars-double")?'selected':'') }} value="fa-mars-double"><i class="fa fa-mars-double"></i> &#xf227; mars-double</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-mars-stroke")?'selected':'') }} value="fa-mars-stroke"><i class="fa fa-mars-stroke"></i> &#xf229; mars-stroke</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-mars-stroke-h")?'selected':'') }} value="fa-mars-stroke-h"><i class="fa fa-mars-stroke-h"></i> &#xf22b; mars-stroke-h</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-mars-stroke-v")?'selected':'') }} value="fa-mars-stroke-v"><i class="fa fa-mars-stroke-v"></i> &#xf22a; mars-stroke-v</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-maxcdn")?'selected':'') }} value="fa-maxcdn"><i class="fa fa-maxcdn"></i> &#xf136; maxcdn</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-meanpath")?'selected':'') }} value="fa-meanpath"><i class="fa fa-meanpath"></i> &#xf20c; meanpath</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-medium")?'selected':'') }} value="fa-medium"><i class="fa fa-medium"></i> &#xf23a; medium</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-medkit")?'selected':'') }} value="fa-medkit"><i class="fa fa-medkit"></i> &#xf0fa; medkit</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-meetup")?'selected':'') }} value="fa-meetup"><i class="fa fa-meetup"></i> &#xf2e0; meetup</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-meh-o")?'selected':'') }} value="fa-meh-o"><i class="fa fa-meh-o"></i> &#xf11a; meh-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-mercury")?'selected':'') }} value="fa-mercury"><i class="fa fa-mercury"></i> &#xf223; mercury</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-microchip")?'selected':'') }} value="fa-microchip"><i class="fa fa-microchip"></i> &#xf2db; microchip</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-microphone")?'selected':'') }} value="fa-microphone"><i class="fa fa-microphone"></i> &#xf130; microphone</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-microphone-slash")?'selected':'') }} value="fa-microphone-slash"><i class="fa fa-microphone-slash"></i> &#xf131; microphone-slash</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-minus")?'selected':'') }} value="fa-minus"><i class="fa fa-minus"></i> &#xf068; minus</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-minus-circle")?'selected':'') }} value="fa-minus-circle"><i class="fa fa-minus-circle"></i> &#xf056; minus-circle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-minus-square")?'selected':'') }} value="fa-minus-square"><i class="fa fa-minus-square"></i> &#xf146; minus-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-minus-square-o")?'selected':'') }} value="fa-minus-square-o"><i class="fa fa-minus-square-o"></i> &#xf147; minus-square-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-mixcloud")?'selected':'') }} value="fa-mixcloud"><i class="fa fa-mixcloud"></i> &#xf289; mixcloud</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-mobile")?'selected':'') }} value="fa-mobile"><i class="fa fa-mobile"></i> &#xf10b; mobile</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-mobile-phone")?'selected':'') }} value="fa-mobile-phone"><i class="fa fa-mobile-phone"></i> &#xf10b; mobile-phone</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-modx")?'selected':'') }} value="fa-modx"><i class="fa fa-modx"></i> &#xf285; modx</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-money")?'selected':'') }} value="fa-money"><i class="fa fa-money"></i> &#xf0d6; money</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-moon-o")?'selected':'') }} value="fa-moon-o"><i class="fa fa-moon-o"></i> &#xf186; moon-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-mortar-board")?'selected':'') }} value="fa-mortar-board"><i class="fa fa-mortar-board"></i> &#xf19d; mortar-board</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-motorcycle")?'selected':'') }} value="fa-motorcycle"><i class="fa fa-motorcycle"></i> &#xf21c; motorcycle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-mouse-pointer")?'selected':'') }} value="fa-mouse-pointer"><i class="fa fa-mouse-pointer"></i> &#xf245; mouse-pointer</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-music")?'selected':'') }} value="fa-music"><i class="fa fa-music"></i> &#xf001; music</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-navicon")?'selected':'') }} value="fa-navicon"><i class="fa fa-navicon"></i> &#xf0c9; navicon</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-neuter")?'selected':'') }} value="fa-neuter"><i class="fa fa-neuter"></i> &#xf22c; neuter</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-newspaper-o")?'selected':'') }} value="fa-newspaper-o"><i class="fa fa-newspaper-o"></i> &#xf1ea; newspaper-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-object-group")?'selected':'') }} value="fa-object-group"><i class="fa fa-object-group"></i> &#xf247; object-group</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-object-ungroup")?'selected':'') }} value="fa-object-ungroup"><i class="fa fa-object-ungroup"></i> &#xf248; object-ungroup</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-odnoklassniki")?'selected':'') }} value="fa-odnoklassniki"><i class="fa fa-odnoklassniki"></i> &#xf263; odnoklassniki</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-odnoklassniki-square")?'selected':'') }} value="fa-odnoklassniki-square"><i class="fa fa-odnoklassniki-square"></i> &#xf264; odnoklassniki-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-opencart")?'selected':'') }} value="fa-opencart"><i class="fa fa-opencart"></i> &#xf23d; opencart</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-openid")?'selected':'') }} value="fa-openid"><i class="fa fa-openid"></i> &#xf19b; openid</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-opera")?'selected':'') }} value="fa-opera"><i class="fa fa-opera"></i> &#xf26a; opera</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-optin-monster")?'selected':'') }} value="fa-optin-monster"><i class="fa fa-optin-monster"></i> &#xf23c; optin-monster</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-outdent")?'selected':'') }} value="fa-outdent"><i class="fa fa-outdent"></i> &#xf03b; outdent</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-pagelines")?'selected':'') }} value="fa-pagelines"><i class="fa fa-pagelines"></i> &#xf18c; pagelines</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-paint-brush")?'selected':'') }} value="fa-paint-brush"><i class="fa fa-paint-brush"></i> &#xf1fc; paint-brush</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-paper-plane")?'selected':'') }} value="fa-paper-plane"><i class="fa fa-paper-plane"></i> &#xf1d8; paper-plane</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-paper-plane-o")?'selected':'') }} value="fa-paper-plane-o"><i class="fa fa-paper-plane-o"></i> &#xf1d9; paper-plane-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-paperclip")?'selected':'') }} value="fa-paperclip"><i class="fa fa-paperclip"></i> &#xf0c6; paperclip</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-paragraph")?'selected':'') }} value="fa-paragraph"><i class="fa fa-paragraph"></i> &#xf1dd; paragraph</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-paste")?'selected':'') }} value="fa-paste"><i class="fa fa-paste"></i> &#xf0ea; paste</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-pause")?'selected':'') }} value="fa-pause"><i class="fa fa-pause"></i> &#xf04c; pause</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-pause-circle")?'selected':'') }} value="fa-pause-circle"><i class="fa fa-pause-circle"></i> &#xf28b; pause-circle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-pause-circle-o")?'selected':'') }} value="fa-pause-circle-o"><i class="fa fa-pause-circle-o"></i> &#xf28c; pause-circle-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-paw")?'selected':'') }} value="fa-paw"><i class="fa fa-paw"></i> &#xf1b0; paw</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-paypal")?'selected':'') }} value="fa-paypal"><i class="fa fa-paypal"></i> &#xf1ed; paypal</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-pencil")?'selected':'') }} value="fa-pencil"><i class="fa fa-pencil"></i> &#xf040; pencil</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-pencil-square")?'selected':'') }} value="fa-pencil-square"><i class="fa fa-pencil-square"></i> &#xf14b; pencil-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-pencil-square-o")?'selected':'') }} value="fa-pencil-square-o"><i class="fa fa-pencil-square-o"></i> &#xf044; pencil-square-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-percent")?'selected':'') }} value="fa-percent"><i class="fa fa-percent"></i> &#xf295; percent</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-phone")?'selected':'') }} value="fa-phone"><i class="fa fa-phone"></i> &#xf095; phone</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-phone-square")?'selected':'') }} value="fa-phone-square"><i class="fa fa-phone-square"></i> &#xf098; phone-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-photo")?'selected':'') }} value="fa-photo"><i class="fa fa-photo"></i> &#xf03e; photo</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-picture-o")?'selected':'') }} value="fa-picture-o"><i class="fa fa-picture-o"></i> &#xf03e; picture-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-pie-chart")?'selected':'') }} value="fa-pie-chart"><i class="fa fa-pie-chart"></i> &#xf200; pie-chart</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-pied-piper")?'selected':'') }} value="fa-pied-piper"><i class="fa fa-pied-piper"></i> &#xf2ae; pied-piper</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-pied-piper-alt")?'selected':'') }} value="fa-pied-piper-alt"><i class="fa fa-pied-piper-alt"></i> &#xf1a8; pied-piper-alt</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-pied-piper-pp")?'selected':'') }} value="fa-pied-piper-pp"><i class="fa fa-pied-piper-pp"></i> &#xf1a7; pied-piper-pp</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-pinterest")?'selected':'') }} value="fa-pinterest"><i class="fa fa-pinterest"></i> &#xf0d2; pinterest</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-pinterest-p")?'selected':'') }} value="fa-pinterest-p"><i class="fa fa-pinterest-p"></i> &#xf231; pinterest-p</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-pinterest-square")?'selected':'') }} value="fa-pinterest-square"><i class="fa fa-pinterest-square"></i> &#xf0d3; pinterest-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-plane")?'selected':'') }} value="fa-plane"><i class="fa fa-plane"></i> &#xf072; plane</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-play")?'selected':'') }} value="fa-play"><i class="fa fa-play"></i> &#xf04b; play</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-play-circle")?'selected':'') }} value="fa-play-circle"><i class="fa fa-play-circle"></i> &#xf144; play-circle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-play-circle-o")?'selected':'') }} value="fa-play-circle-o"><i class="fa fa-play-circle-o"></i> &#xf01d; play-circle-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-plug")?'selected':'') }} value="fa-plug"><i class="fa fa-plug"></i> &#xf1e6; plug</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-plus")?'selected':'') }} value="fa-plus"><i class="fa fa-plus"></i> &#xf067; plus</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-plus-circle")?'selected':'') }} value="fa-plus-circle"><i class="fa fa-plus-circle"></i> &#xf055; plus-circle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-plus-square")?'selected':'') }} value="fa-plus-square"><i class="fa fa-plus-square"></i> &#xf0fe; plus-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-plus-square-o")?'selected':'') }} value="fa-plus-square-o"><i class="fa fa-plus-square-o"></i> &#xf196; plus-square-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-podcast")?'selected':'') }} value="fa-podcast"><i class="fa fa-podcast"></i> &#xf2ce; podcast</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-power-off")?'selected':'') }} value="fa-power-off"><i class="fa fa-power-off"></i> &#xf011; power-off</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-print")?'selected':'') }} value="fa-print"><i class="fa fa-print"></i> &#xf02f; print</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-product-hunt")?'selected':'') }} value="fa-product-hunt"><i class="fa fa-product-hunt"></i> &#xf288; product-hunt</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-puzzle-piece")?'selected':'') }} value="fa-puzzle-piece"><i class="fa fa-puzzle-piece"></i> &#xf12e; puzzle-piece</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-qq")?'selected':'') }} value="fa-qq"><i class="fa fa-qq"></i> &#xf1d6; qq</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-qrcode")?'selected':'') }} value="fa-qrcode"><i class="fa fa-qrcode"></i> &#xf029; qrcode</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-question")?'selected':'') }} value="fa-question"><i class="fa fa-question"></i> &#xf128; question</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-question-circle")?'selected':'') }} value="fa-question-circle"><i class="fa fa-question-circle"></i> &#xf059; question-circle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-question-circle-o")?'selected':'') }} value="fa-question-circle-o"><i class="fa fa-question-circle-o"></i> &#xf29c; question-circle-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-quora")?'selected':'') }} value="fa-quora"><i class="fa fa-quora"></i> &#xf2c4; quora</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-quote-left")?'selected':'') }} value="fa-quote-left"><i class="fa fa-quote-left"></i> &#xf10d; quote-left</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-quote-right")?'selected':'') }} value="fa-quote-right"><i class="fa fa-quote-right"></i> &#xf10e; quote-right</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-ra")?'selected':'') }} value="fa-ra"><i class="fa fa-ra"></i> &#xf1d0; ra</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-random")?'selected':'') }} value="fa-random"><i class="fa fa-random"></i> &#xf074; random</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-ravelry")?'selected':'') }} value="fa-ravelry"><i class="fa fa-ravelry"></i> &#xf2d9; ravelry</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-rebel")?'selected':'') }} value="fa-rebel"><i class="fa fa-rebel"></i> &#xf1d0; rebel</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-recycle")?'selected':'') }} value="fa-recycle"><i class="fa fa-recycle"></i> &#xf1b8; recycle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-reddit")?'selected':'') }} value="fa-reddit"><i class="fa fa-reddit"></i> &#xf1a1; reddit</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-reddit-alien")?'selected':'') }} value="fa-reddit-alien"><i class="fa fa-reddit-alien"></i> &#xf281; reddit-alien</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-reddit-square")?'selected':'') }} value="fa-reddit-square"><i class="fa fa-reddit-square"></i> &#xf1a2; reddit-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-refresh")?'selected':'') }} value="fa-refresh"><i class="fa fa-refresh"></i> &#xf021; refresh</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-registered")?'selected':'') }} value="fa-registered"><i class="fa fa-registered"></i> &#xf25d; registered</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-remove")?'selected':'') }} value="fa-remove"><i class="fa fa-remove"></i> &#xf00d; remove</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-renren")?'selected':'') }} value="fa-renren"><i class="fa fa-renren"></i> &#xf18b; renren</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-reorder")?'selected':'') }} value="fa-reorder"><i class="fa fa-reorder"></i> &#xf0c9; reorder</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-repeat")?'selected':'') }} value="fa-repeat"><i class="fa fa-repeat"></i> &#xf01e; repeat</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-reply")?'selected':'') }} value="fa-reply"><i class="fa fa-reply"></i> &#xf112; reply</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-reply-all")?'selected':'') }} value="fa-reply-all"><i class="fa fa-reply-all"></i> &#xf122; reply-all</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-resistance")?'selected':'') }} value="fa-resistance"><i class="fa fa-resistance"></i> &#xf1d0; resistance</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-retweet")?'selected':'') }} value="fa-retweet"><i class="fa fa-retweet"></i> &#xf079; retweet</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-rmb")?'selected':'') }} value="fa-rmb"><i class="fa fa-rmb"></i> &#xf157; rmb</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-road")?'selected':'') }} value="fa-road"><i class="fa fa-road"></i> &#xf018; road</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-rocket")?'selected':'') }} value="fa-rocket"><i class="fa fa-rocket"></i> &#xf135; rocket</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-rotate-left")?'selected':'') }} value="fa-rotate-left"><i class="fa fa-rotate-left"></i> &#xf0e2; rotate-left</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-rotate-right")?'selected':'') }} value="fa-rotate-right"><i class="fa fa-rotate-right"></i> &#xf01e; rotate-right</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-rouble")?'selected':'') }} value="fa-rouble"><i class="fa fa-rouble"></i> &#xf158; rouble</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-rss")?'selected':'') }} value="fa-rss"><i class="fa fa-rss"></i> &#xf09e; rss</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-rss-square")?'selected':'') }} value="fa-rss-square"><i class="fa fa-rss-square"></i> &#xf143; rss-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-rub")?'selected':'') }} value="fa-rub"><i class="fa fa-rub"></i> &#xf158; rub</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-ruble")?'selected':'') }} value="fa-ruble"><i class="fa fa-ruble"></i> &#xf158; ruble</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-rupee")?'selected':'') }} value="fa-rupee"><i class="fa fa-rupee"></i> &#xf156; rupee</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-s15")?'selected':'') }} value="fa-s15"><i class="fa fa-s15"></i> &#xf2cd; s15</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-safari")?'selected':'') }} value="fa-safari"><i class="fa fa-safari"></i> &#xf267; safari</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-save")?'selected':'') }} value="fa-save"><i class="fa fa-save"></i> &#xf0c7; save</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-scissors")?'selected':'') }} value="fa-scissors"><i class="fa fa-scissors"></i> &#xf0c4; scissors</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-scribd")?'selected':'') }} value="fa-scribd"><i class="fa fa-scribd"></i> &#xf28a; scribd</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-search")?'selected':'') }} value="fa-search"><i class="fa fa-search"></i> &#xf002; search</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-search-minus")?'selected':'') }} value="fa-search-minus"><i class="fa fa-search-minus"></i> &#xf010; search-minus</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-search-plus")?'selected':'') }} value="fa-search-plus"><i class="fa fa-search-plus"></i> &#xf00e; search-plus</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sellsy")?'selected':'') }} value="fa-sellsy"><i class="fa fa-sellsy"></i> &#xf213; sellsy</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-send")?'selected':'') }} value="fa-send"><i class="fa fa-send"></i> &#xf1d8; send</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-send-o")?'selected':'') }} value="fa-send-o"><i class="fa fa-send-o"></i> &#xf1d9; send-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-server")?'selected':'') }} value="fa-server"><i class="fa fa-server"></i> &#xf233; server</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-share")?'selected':'') }} value="fa-share"><i class="fa fa-share"></i> &#xf064; share</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-share-alt")?'selected':'') }} value="fa-share-alt"><i class="fa fa-share-alt"></i> &#xf1e0; share-alt</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-share-alt-square")?'selected':'') }} value="fa-share-alt-square"><i class="fa fa-share-alt-square"></i> &#xf1e1; share-alt-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-share-square")?'selected':'') }} value="fa-share-square"><i class="fa fa-share-square"></i> &#xf14d; share-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-share-square-o")?'selected':'') }} value="fa-share-square-o"><i class="fa fa-share-square-o"></i> &#xf045; share-square-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-shekel")?'selected':'') }} value="fa-shekel"><i class="fa fa-shekel"></i> &#xf20b; shekel</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sheqel")?'selected':'') }} value="fa-sheqel"><i class="fa fa-sheqel"></i> &#xf20b; sheqel</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-shield")?'selected':'') }} value="fa-shield"><i class="fa fa-shield"></i> &#xf132; shield</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-ship")?'selected':'') }} value="fa-ship"><i class="fa fa-ship"></i> &#xf21a; ship</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-shirtsinbulk")?'selected':'') }} value="fa-shirtsinbulk"><i class="fa fa-shirtsinbulk"></i> &#xf214; shirtsinbulk</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-shopping-bag")?'selected':'') }} value="fa-shopping-bag"><i class="fa fa-shopping-bag"></i> &#xf290; shopping-bag</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-shopping-basket")?'selected':'') }} value="fa-shopping-basket"><i class="fa fa-shopping-basket"></i> &#xf291; shopping-basket</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-shopping-cart")?'selected':'') }} value="fa-shopping-cart"><i class="fa fa-shopping-cart"></i> &#xf07a; shopping-cart</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-shower")?'selected':'') }} value="fa-shower"><i class="fa fa-shower"></i> &#xf2cc; shower</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sign-in")?'selected':'') }} value="fa-sign-in"><i class="fa fa-sign-in"></i> &#xf090; sign-in</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sign-language")?'selected':'') }} value="fa-sign-language"><i class="fa fa-sign-language"></i> &#xf2a7; sign-language</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sign-out")?'selected':'') }} value="fa-sign-out"><i class="fa fa-sign-out"></i> &#xf08b; sign-out</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-signal")?'selected':'') }} value="fa-signal"><i class="fa fa-signal"></i> &#xf012; signal</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-signing")?'selected':'') }} value="fa-signing"><i class="fa fa-signing"></i> &#xf2a7; signing</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-simplybuilt")?'selected':'') }} value="fa-simplybuilt"><i class="fa fa-simplybuilt"></i> &#xf215; simplybuilt</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sitemap")?'selected':'') }} value="fa-sitemap"><i class="fa fa-sitemap"></i> &#xf0e8; sitemap</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-skyatlas")?'selected':'') }} value="fa-skyatlas"><i class="fa fa-skyatlas"></i> &#xf216; skyatlas</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-skype")?'selected':'') }} value="fa-skype"><i class="fa fa-skype"></i> &#xf17e; skype</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-slack")?'selected':'') }} value="fa-slack"><i class="fa fa-slack"></i> &#xf198; slack</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sliders")?'selected':'') }} value="fa-sliders"><i class="fa fa-sliders"></i> &#xf1de; sliders</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-slideshare")?'selected':'') }} value="fa-slideshare"><i class="fa fa-slideshare"></i> &#xf1e7; slideshare</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-smile-o")?'selected':'') }} value="fa-smile-o"><i class="fa fa-smile-o"></i> &#xf118; smile-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-snapchat")?'selected':'') }} value="fa-snapchat"><i class="fa fa-snapchat"></i> &#xf2ab; snapchat</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-snapchat-ghost")?'selected':'') }} value="fa-snapchat-ghost"><i class="fa fa-snapchat-ghost"></i> &#xf2ac; snapchat-ghost</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-snapchat-square")?'selected':'') }} value="fa-snapchat-square"><i class="fa fa-snapchat-square"></i> &#xf2ad; snapchat-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-snowflake-o")?'selected':'') }} value="fa-snowflake-o"><i class="fa fa-snowflake-o"></i> &#xf2dc; snowflake-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-soccer-ball-o")?'selected':'') }} value="fa-soccer-ball-o"><i class="fa fa-soccer-ball-o"></i> &#xf1e3; soccer-ball-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sort")?'selected':'') }} value="fa-sort"><i class="fa fa-sort"></i> &#xf0dc; sort</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sort-alpha-asc")?'selected':'') }} value="fa-sort-alpha-asc"><i class="fa fa-sort-alpha-asc"></i> &#xf15d; sort-alpha-asc</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sort-alpha-desc")?'selected':'') }} value="fa-sort-alpha-desc"><i class="fa fa-sort-alpha-desc"></i> &#xf15e; sort-alpha-desc</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sort-amount-asc")?'selected':'') }} value="fa-sort-amount-asc"><i class="fa fa-sort-amount-asc"></i> &#xf160; sort-amount-asc</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sort-amount-desc")?'selected':'') }} value="fa-sort-amount-desc"><i class="fa fa-sort-amount-desc"></i> &#xf161; sort-amount-desc</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sort-asc")?'selected':'') }} value="fa-sort-asc"><i class="fa fa-sort-asc"></i> &#xf0de; sort-asc</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sort-desc")?'selected':'') }} value="fa-sort-desc"><i class="fa fa-sort-desc"></i> &#xf0dd; sort-desc</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sort-down")?'selected':'') }} value="fa-sort-down"><i class="fa fa-sort-down"></i> &#xf0dd; sort-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sort-numeric-asc")?'selected':'') }} value="fa-sort-numeric-asc"><i class="fa fa-sort-numeric-asc"></i> &#xf162; sort-numeric-asc</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sort-numeric-desc")?'selected':'') }} value="fa-sort-numeric-desc"><i class="fa fa-sort-numeric-desc"></i> &#xf163; sort-numeric-desc</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sort-up")?'selected':'') }} value="fa-sort-up"><i class="fa fa-sort-up"></i> &#xf0de; sort-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-soundcloud")?'selected':'') }} value="fa-soundcloud"><i class="fa fa-soundcloud"></i> &#xf1be; soundcloud</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-space-shuttle")?'selected':'') }} value="fa-space-shuttle"><i class="fa fa-space-shuttle"></i> &#xf197; space-shuttle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-spinner")?'selected':'') }} value="fa-spinner"><i class="fa fa-spinner"></i> &#xf110; spinner</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-spoon")?'selected':'') }} value="fa-spoon"><i class="fa fa-spoon"></i> &#xf1b1; spoon</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-spotify")?'selected':'') }} value="fa-spotify"><i class="fa fa-spotify"></i> &#xf1bc; spotify</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-square")?'selected':'') }} value="fa-square"><i class="fa fa-square"></i> &#xf0c8; square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-square-o")?'selected':'') }} value="fa-square-o"><i class="fa fa-square-o"></i> &#xf096; square-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-stack-exchange")?'selected':'') }} value="fa-stack-exchange"><i class="fa fa-stack-exchange"></i> &#xf18d; stack-exchange</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-stack-overflow")?'selected':'') }} value="fa-stack-overflow"><i class="fa fa-stack-overflow"></i> &#xf16c; stack-overflow</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-star")?'selected':'') }} value="fa-star"><i class="fa fa-star"></i> &#xf005; star</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-star-half")?'selected':'') }} value="fa-star-half"><i class="fa fa-star-half"></i> &#xf089; star-half</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-star-half-empty")?'selected':'') }} value="fa-star-half-empty"><i class="fa fa-star-half-empty"></i> &#xf123; star-half-empty</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-star-half-full")?'selected':'') }} value="fa-star-half-full"><i class="fa fa-star-half-full"></i> &#xf123; star-half-full</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-star-half-o")?'selected':'') }} value="fa-star-half-o"><i class="fa fa-star-half-o"></i> &#xf123; star-half-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-star-o")?'selected':'') }} value="fa-star-o"><i class="fa fa-star-o"></i> &#xf006; star-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-steam")?'selected':'') }} value="fa-steam"><i class="fa fa-steam"></i> &#xf1b6; steam</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-steam-square")?'selected':'') }} value="fa-steam-square"><i class="fa fa-steam-square"></i> &#xf1b7; steam-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-step-backward")?'selected':'') }} value="fa-step-backward"><i class="fa fa-step-backward"></i> &#xf048; step-backward</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-step-forward")?'selected':'') }} value="fa-step-forward"><i class="fa fa-step-forward"></i> &#xf051; step-forward</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-stethoscope")?'selected':'') }} value="fa-stethoscope"><i class="fa fa-stethoscope"></i> &#xf0f1; stethoscope</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sticky-note")?'selected':'') }} value="fa-sticky-note"><i class="fa fa-sticky-note"></i> &#xf249; sticky-note</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sticky-note-o")?'selected':'') }} value="fa-sticky-note-o"><i class="fa fa-sticky-note-o"></i> &#xf24a; sticky-note-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-stop")?'selected':'') }} value="fa-stop"><i class="fa fa-stop"></i> &#xf04d; stop</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-stop-circle")?'selected':'') }} value="fa-stop-circle"><i class="fa fa-stop-circle"></i> &#xf28d; stop-circle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-stop-circle-o")?'selected':'') }} value="fa-stop-circle-o"><i class="fa fa-stop-circle-o"></i> &#xf28e; stop-circle-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-street-view")?'selected':'') }} value="fa-street-view"><i class="fa fa-street-view"></i> &#xf21d; street-view</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-strikethrough")?'selected':'') }} value="fa-strikethrough"><i class="fa fa-strikethrough"></i> &#xf0cc; strikethrough</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-stumbleupon")?'selected':'') }} value="fa-stumbleupon"><i class="fa fa-stumbleupon"></i> &#xf1a4; stumbleupon</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-stumbleupon-circle")?'selected':'') }} value="fa-stumbleupon-circle"><i class="fa fa-stumbleupon-circle"></i> &#xf1a3; stumbleupon-circle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-subscript")?'selected':'') }} value="fa-subscript"><i class="fa fa-subscript"></i> &#xf12c; subscript</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-subway")?'selected':'') }} value="fa-subway"><i class="fa fa-subway"></i> &#xf239; subway</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-suitcase")?'selected':'') }} value="fa-suitcase"><i class="fa fa-suitcase"></i> &#xf0f2; suitcase</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-sun-o")?'selected':'') }} value="fa-sun-o"><i class="fa fa-sun-o"></i> &#xf185; sun-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-superpowers")?'selected':'') }} value="fa-superpowers"><i class="fa fa-superpowers"></i> &#xf2dd; superpowers</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-superscript")?'selected':'') }} value="fa-superscript"><i class="fa fa-superscript"></i> &#xf12b; superscript</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-support")?'selected':'') }} value="fa-support"><i class="fa fa-support"></i> &#xf1cd; support</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-table")?'selected':'') }} value="fa-table"><i class="fa fa-table"></i> &#xf0ce; table</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-tablet")?'selected':'') }} value="fa-tablet"><i class="fa fa-tablet"></i> &#xf10a; tablet</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-tachometer")?'selected':'') }} value="fa-tachometer"><i class="fa fa-tachometer"></i> &#xf0e4; tachometer</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-tag")?'selected':'') }} value="fa-tag"><i class="fa fa-tag"></i> &#xf02b; tag</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-tags")?'selected':'') }} value="fa-tags"><i class="fa fa-tags"></i> &#xf02c; tags</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-tasks")?'selected':'') }} value="fa-tasks"><i class="fa fa-tasks"></i> &#xf0ae; tasks</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-taxi")?'selected':'') }} value="fa-taxi"><i class="fa fa-taxi"></i> &#xf1ba; taxi</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-telegram")?'selected':'') }} value="fa-telegram"><i class="fa fa-telegram"></i> &#xf2c6; telegram</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-television")?'selected':'') }} value="fa-television"><i class="fa fa-television"></i> &#xf26c; television</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-tencent-weibo")?'selected':'') }} value="fa-tencent-weibo"><i class="fa fa-tencent-weibo"></i> &#xf1d5; tencent-weibo</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-terminal")?'selected':'') }} value="fa-terminal"><i class="fa fa-terminal"></i> &#xf120; terminal</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-text-height")?'selected':'') }} value="fa-text-height"><i class="fa fa-text-height"></i> &#xf034; text-height</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-text-width")?'selected':'') }} value="fa-text-width"><i class="fa fa-text-width"></i> &#xf035; text-width</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-th")?'selected':'') }} value="fa-th"><i class="fa fa-th"></i> &#xf00a; th</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-th-large")?'selected':'') }} value="fa-th-large"><i class="fa fa-th-large"></i> &#xf009; th-large</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-th-list")?'selected':'') }} value="fa-th-list"><i class="fa fa-th-list"></i> &#xf00b; th-list</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-themeisle")?'selected':'') }} value="fa-themeisle"><i class="fa fa-themeisle"></i> &#xf2b2; themeisle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thermometer")?'selected':'') }} value="fa-thermometer"><i class="fa fa-thermometer"></i> &#xf2c7; thermometer</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thermometer-0")?'selected':'') }} value="fa-thermometer-0"><i class="fa fa-thermometer-0"></i> &#xf2cb; thermometer-0</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thermometer-1")?'selected':'') }} value="fa-thermometer-1"><i class="fa fa-thermometer-1"></i> &#xf2ca; thermometer-1</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thermometer-2")?'selected':'') }} value="fa-thermometer-2"><i class="fa fa-thermometer-2"></i> &#xf2c9; thermometer-2</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thermometer-3")?'selected':'') }} value="fa-thermometer-3"><i class="fa fa-thermometer-3"></i> &#xf2c8; thermometer-3</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thermometer-4")?'selected':'') }} value="fa-thermometer-4"><i class="fa fa-thermometer-4"></i> &#xf2c7; thermometer-4</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thermometer-empty")?'selected':'') }} value="fa-thermometer-empty"><i class="fa fa-thermometer-empty"></i> &#xf2cb; thermometer-empty</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thermometer-full")?'selected':'') }} value="fa-thermometer-full"><i class="fa fa-thermometer-full"></i> &#xf2c7; thermometer-full</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thermometer-half")?'selected':'') }} value="fa-thermometer-half"><i class="fa fa-thermometer-half"></i> &#xf2c9; thermometer-half</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thermometer-quarter")?'selected':'') }} value="fa-thermometer-quarter"><i class="fa fa-thermometer-quarter"></i> &#xf2ca; thermometer-quarter</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thermometer-three-quarters")?'selected':'') }} value="fa-thermometer-three-quarters"><i class="fa fa-thermometer-three-quarters"></i> &#xf2c8; thermometer-three-quarters</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thumb-tack")?'selected':'') }} value="fa-thumb-tack"><i class="fa fa-thumb-tack"></i> &#xf08d; thumb-tack</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thumbs-down")?'selected':'') }} value="fa-thumbs-down"><i class="fa fa-thumbs-down"></i> &#xf165; thumbs-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thumbs-o-down")?'selected':'') }} value="fa-thumbs-o-down"><i class="fa fa-thumbs-o-down"></i> &#xf088; thumbs-o-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thumbs-o-up")?'selected':'') }} value="fa-thumbs-o-up"><i class="fa fa-thumbs-o-up"></i> &#xf087; thumbs-o-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-thumbs-up")?'selected':'') }} value="fa-thumbs-up"><i class="fa fa-thumbs-up"></i> &#xf164; thumbs-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-ticket")?'selected':'') }} value="fa-ticket"><i class="fa fa-ticket"></i> &#xf145; ticket</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-times")?'selected':'') }} value="fa-times"><i class="fa fa-times"></i> &#xf00d; times</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-times-circle")?'selected':'') }} value="fa-times-circle"><i class="fa fa-times-circle"></i> &#xf057; times-circle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-times-circle-o")?'selected':'') }} value="fa-times-circle-o"><i class="fa fa-times-circle-o"></i> &#xf05c; times-circle-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-times-rectangle")?'selected':'') }} value="fa-times-rectangle"><i class="fa fa-times-rectangle"></i> &#xf2d3; times-rectangle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-times-rectangle-o")?'selected':'') }} value="fa-times-rectangle-o"><i class="fa fa-times-rectangle-o"></i> &#xf2d4; times-rectangle-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-tint")?'selected':'') }} value="fa-tint"><i class="fa fa-tint"></i> &#xf043; tint</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-toggle-down")?'selected':'') }} value="fa-toggle-down"><i class="fa fa-toggle-down"></i> &#xf150; toggle-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-toggle-left")?'selected':'') }} value="fa-toggle-left"><i class="fa fa-toggle-left"></i> &#xf191; toggle-left</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-toggle-off")?'selected':'') }} value="fa-toggle-off"><i class="fa fa-toggle-off"></i> &#xf204; toggle-off</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-toggle-on")?'selected':'') }} value="fa-toggle-on"><i class="fa fa-toggle-on"></i> &#xf205; toggle-on</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-toggle-right")?'selected':'') }} value="fa-toggle-right"><i class="fa fa-toggle-right"></i> &#xf152; toggle-right</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-toggle-up")?'selected':'') }} value="fa-toggle-up"><i class="fa fa-toggle-up"></i> &#xf151; toggle-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-trademark")?'selected':'') }} value="fa-trademark"><i class="fa fa-trademark"></i> &#xf25c; trademark</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-train")?'selected':'') }} value="fa-train"><i class="fa fa-train"></i> &#xf238; train</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-transgender")?'selected':'') }} value="fa-transgender"><i class="fa fa-transgender"></i> &#xf224; transgender</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-transgender-alt")?'selected':'') }} value="fa-transgender-alt"><i class="fa fa-transgender-alt"></i> &#xf225; transgender-alt</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-trash")?'selected':'') }} value="fa-trash"><i class="fa fa-trash"></i> &#xf1f8; trash</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-trash-o")?'selected':'') }} value="fa-trash-o"><i class="fa fa-trash-o"></i> &#xf014; trash-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-tree")?'selected':'') }} value="fa-tree"><i class="fa fa-tree"></i> &#xf1bb; tree</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-trello")?'selected':'') }} value="fa-trello"><i class="fa fa-trello"></i> &#xf181; trello</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-tripadvisor")?'selected':'') }} value="fa-tripadvisor"><i class="fa fa-tripadvisor"></i> &#xf262; tripadvisor</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-trophy")?'selected':'') }} value="fa-trophy"><i class="fa fa-trophy"></i> &#xf091; trophy</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-truck")?'selected':'') }} value="fa-truck"><i class="fa fa-truck"></i> &#xf0d1; truck</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-try")?'selected':'') }} value="fa-try"><i class="fa fa-try"></i> &#xf195; try</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-tty")?'selected':'') }} value="fa-tty"><i class="fa fa-tty"></i> &#xf1e4; tty</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-tumblr")?'selected':'') }} value="fa-tumblr"><i class="fa fa-tumblr"></i> &#xf173; tumblr</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-tumblr-square")?'selected':'') }} value="fa-tumblr-square"><i class="fa fa-tumblr-square"></i> &#xf174; tumblr-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-turkish-lira")?'selected':'') }} value="fa-turkish-lira"><i class="fa fa-turkish-lira"></i> &#xf195; turkish-lira</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-tv")?'selected':'') }} value="fa-tv"><i class="fa fa-tv"></i> &#xf26c; tv</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-twitch")?'selected':'') }} value="fa-twitch"><i class="fa fa-twitch"></i> &#xf1e8; twitch</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-twitter")?'selected':'') }} value="fa-twitter"><i class="fa fa-twitter"></i> &#xf099; twitter</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-twitter-square")?'selected':'') }} value="fa-twitter-square"><i class="fa fa-twitter-square"></i> &#xf081; twitter-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-umbrella")?'selected':'') }} value="fa-umbrella"><i class="fa fa-umbrella"></i> &#xf0e9; umbrella</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-underline")?'selected':'') }} value="fa-underline"><i class="fa fa-underline"></i> &#xf0cd; underline</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-undo")?'selected':'') }} value="fa-undo"><i class="fa fa-undo"></i> &#xf0e2; undo</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-universal-access")?'selected':'') }} value="fa-universal-access"><i class="fa fa-universal-access"></i> &#xf29a; universal-access</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-university")?'selected':'') }} value="fa-university"><i class="fa fa-university"></i> &#xf19c; university</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-unlink")?'selected':'') }} value="fa-unlink"><i class="fa fa-unlink"></i> &#xf127; unlink</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-unlock")?'selected':'') }} value="fa-unlock"><i class="fa fa-unlock"></i> &#xf09c; unlock</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-unlock-alt")?'selected':'') }} value="fa-unlock-alt"><i class="fa fa-unlock-alt"></i> &#xf13e; unlock-alt</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-unsorted")?'selected':'') }} value="fa-unsorted"><i class="fa fa-unsorted"></i> &#xf0dc; unsorted</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-upload")?'selected':'') }} value="fa-upload"><i class="fa fa-upload"></i> &#xf093; upload</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-usb")?'selected':'') }} value="fa-usb"><i class="fa fa-usb"></i> &#xf287; usb</option>
											<option value="usd"><i class="fa usd"></i> &#xf155; </option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-user")?'selected':'') }} value="fa-user"><i class="fa fa-user"></i> &#xf007; user</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-user-circle")?'selected':'') }} value="fa-user-circle"><i class="fa fa-user-circle"></i> &#xf2bd; user-circle</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-user-circle-o")?'selected':'') }} value="fa-user-circle-o"><i class="fa fa-user-circle-o"></i> &#xf2be; user-circle-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-user-md")?'selected':'') }} value="fa-user-md"><i class="fa fa-user-md"></i> &#xf0f0; user-md</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-user-o")?'selected':'') }} value="fa-user-o"><i class="fa fa-user-o"></i> &#xf2c0; user-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-user-plus")?'selected':'') }} value="fa-user-plus"><i class="fa fa-user-plus"></i> &#xf234; user-plus</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-user-secret")?'selected':'') }} value="fa-user-secret"><i class="fa fa-user-secret"></i> &#xf21b; user-secret</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-user-times")?'selected':'') }} value="fa-user-times"><i class="fa fa-user-times"></i> &#xf235; user-times</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-users")?'selected':'') }} value="fa-users"><i class="fa fa-users"></i> &#xf0c0; users</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-vcard")?'selected':'') }} value="fa-vcard"><i class="fa fa-vcard"></i> &#xf2bb; vcard</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-vcard-o")?'selected':'') }} value="fa-vcard-o"><i class="fa fa-vcard-o"></i> &#xf2bc; vcard-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-venus")?'selected':'') }} value="fa-venus"><i class="fa fa-venus"></i> &#xf221; venus</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-venus-double")?'selected':'') }} value="fa-venus-double"><i class="fa fa-venus-double"></i> &#xf226; venus-double</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-venus-mars")?'selected':'') }} value="fa-venus-mars"><i class="fa fa-venus-mars"></i> &#xf228; venus-mars</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-viacoin")?'selected':'') }} value="fa-viacoin"><i class="fa fa-viacoin"></i> &#xf237; viacoin</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-viadeo")?'selected':'') }} value="fa-viadeo"><i class="fa fa-viadeo"></i> &#xf2a9; viadeo</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-viadeo-square")?'selected':'') }} value="fa-viadeo-square"><i class="fa fa-viadeo-square"></i> &#xf2aa; viadeo-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-video-camera")?'selected':'') }} value="fa-video-camera"><i class="fa fa-video-camera"></i> &#xf03d; video-camera</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-vimeo")?'selected':'') }} value="fa-vimeo"><i class="fa fa-vimeo"></i> &#xf27d; vimeo</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-vimeo-square")?'selected':'') }} value="fa-vimeo-square"><i class="fa fa-vimeo-square"></i> &#xf194; vimeo-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-vine")?'selected':'') }} value="fa-vine"><i class="fa fa-vine"></i> &#xf1ca; vine</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-vk")?'selected':'') }} value="fa-vk"><i class="fa fa-vk"></i> &#xf189; vk</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-volume-control-phone")?'selected':'') }} value="fa-volume-control-phone"><i class="fa fa-volume-control-phone"></i> &#xf2a0; volume-control-phone</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-volume-down")?'selected':'') }} value="fa-volume-down"><i class="fa fa-volume-down"></i> &#xf027; volume-down</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-volume-off")?'selected':'') }} value="fa-volume-off"><i class="fa fa-volume-off"></i> &#xf026; volume-off</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-volume-up")?'selected':'') }} value="fa-volume-up"><i class="fa fa-volume-up"></i> &#xf028; volume-up</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-warning")?'selected':'') }} value="fa-warning"><i class="fa fa-warning"></i> &#xf071; warning</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-wechat")?'selected':'') }} value="fa-wechat"><i class="fa fa-wechat"></i> &#xf1d7; wechat</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-weibo")?'selected':'') }} value="fa-weibo"><i class="fa fa-weibo"></i> &#xf18a; weibo</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-weixin")?'selected':'') }} value="fa-weixin"><i class="fa fa-weixin"></i> &#xf1d7; weixin</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-whatsapp")?'selected':'') }} value="fa-whatsapp"><i class="fa fa-whatsapp"></i> &#xf232; whatsapp</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-wheelchair")?'selected':'') }} value="fa-wheelchair"><i class="fa fa-wheelchair"></i> &#xf193; wheelchair</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-wheelchair-alt")?'selected':'') }} value="fa-wheelchair-alt"><i class="fa fa-wheelchair-alt"></i> &#xf29b; wheelchair-alt</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-wifi")?'selected':'') }} value="fa-wifi"><i class="fa fa-wifi"></i> &#xf1eb; wifi</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-wikipedia-w")?'selected':'') }} value="fa-wikipedia-w"><i class="fa fa-wikipedia-w"></i> &#xf266; wikipedia-w</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-window-close")?'selected':'') }} value="fa-window-close"><i class="fa fa-window-close"></i> &#xf2d3; window-close</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-window-close-o")?'selected':'') }} value="fa-window-close-o"><i class="fa fa-window-close-o"></i> &#xf2d4; window-close-o</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-window-maximize")?'selected':'') }} value="fa-window-maximize"><i class="fa fa-window-maximize"></i> &#xf2d0; window-maximize</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-window-minimize")?'selected':'') }} value="fa-window-minimize"><i class="fa fa-window-minimize"></i> &#xf2d1; window-minimize</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-window-restore")?'selected':'') }} value="fa-window-restore"><i class="fa fa-window-restore"></i> &#xf2d2; window-restore</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-windows")?'selected':'') }} value="fa-windows"><i class="fa fa-windows"></i> &#xf17a; windows</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-won")?'selected':'') }} value="fa-won"><i class="fa fa-won"></i> &#xf159; won</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-wordpress")?'selected':'') }} value="fa-wordpress"><i class="fa fa-wordpress"></i> &#xf19a; wordpress</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-wpbeginner")?'selected':'') }} value="fa-wpbeginner"><i class="fa fa-wpbeginner"></i> &#xf297; wpbeginner</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-wpexplorer")?'selected':'') }} value="fa-wpexplorer"><i class="fa fa-wpexplorer"></i> &#xf2de; wpexplorer</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-wpforms")?'selected':'') }} value="fa-wpforms"><i class="fa fa-wpforms"></i> &#xf298; wpforms</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-wrench")?'selected':'') }} value="fa-wrench"><i class="fa fa-wrench"></i> &#xf0ad; wrench</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-xing")?'selected':'') }} value="fa-xing"><i class="fa fa-xing"></i> &#xf168; xing</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-xing-square")?'selected':'') }} value="fa-xing-square"><i class="fa fa-xing-square"></i> &#xf169; xing-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-y-combinator")?'selected':'') }} value="fa-y-combinator"><i class="fa fa-y-combinator"></i> &#xf23b; y-combinator</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-y-combinator-square")?'selected':'') }} value="fa-y-combinator-square"><i class="fa fa-y-combinator-square"></i> &#xf1d4; y-combinator-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-yahoo")?'selected':'') }} value="fa-yahoo"><i class="fa fa-yahoo"></i> &#xf19e; yahoo</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-yc")?'selected':'') }} value="fa-yc"><i class="fa fa-yc"></i> &#xf23b; yc</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-yc-square")?'selected':'') }} value="fa-yc-square"><i class="fa fa-yc-square"></i> &#xf1d4; yc-square</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-yelp")?'selected':'') }} value="fa-yelp"><i class="fa fa-yelp"></i> &#xf1e9; yelp</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-yen")?'selected':'') }} value="fa-yen"><i class="fa fa-yen"></i> &#xf157; yen</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-yoast")?'selected':'') }} value="fa-yoast"><i class="fa fa-yoast"></i> &#xf2b1; yoast</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-youtube")?'selected':'') }} value="fa-youtube"><i class="fa fa-youtube"></i> &#xf167; youtube</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-youtube-play")?'selected':'') }} value="fa-youtube-play"><i class="fa fa-youtube-play"></i> &#xf16a; youtube-play</option>
											<option {{ ((isset($campo->icone_campo) and $campo->icone_campo == "fa-youtube-square")?'selected':'') }} value="fa-youtube-square"><i class="fa fa-youtube-square"></i> &#xf166; youtube-square</option>
										</select>
									</div>
								</div>
								<div class="col-md-1 p-lr-o">
									<br><label for=""><br></label>
									<a href="#" class="btn btn-danger remove-campo" title="Remover"><i class="fa fa-minus"></i></a>
								</div>
							</div>
							@endforeach
							@endif
						</div>

						<div class="col-md-12 p-lr-o">
							<div class="form-group">
								<hr>
								<label for="">Check-List - Pós Serviço</label>
								<input type="text" name="pos_servico" class="form-control tags" value="
								<?php
									if( isset( $servico ) and $servico->pos_servico ){
										echo implode( ',', json_decode( $servico->pos_servico ) );
									}
								?>
								" />

							</div>
						</div>

					</div>
					<div class="row">
						
						<div class="col-md-12 p-lr-o">
							<div class="form-group">
								<br><input type="submit" value="Salvar" class="btn btn-info pull-right">
							</div>
						</div>

					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<style>
	#campos .campo {
		display: inline-block !important;
		width: 100%;
	}

	select {
		font-family: 'FontAwesome', "Open Sans", sans-serif;
	}
</style>
<script>
	$(document).ready(function(){
	@if( ! isset( $servico ) )
		$("#add-campo").click();
	@endif
	});
</script>
@endsection