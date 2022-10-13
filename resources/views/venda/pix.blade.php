<?php 
    use App\Helpers; 
    $empresa = \DB::table('empresa')->first();
?>
@extends('layouts.public')
@section('content')
<div class="container">
    <div class="col-md-12">
        <h3 class="text-center"><i class="fa fa-hourglass-o" aria-hidden="true"></i> Aguardando pagamento</h3>
        <p class="text-center">Pagar com Pix é rápido e seguro! É só seguir estes passos:</p>
        <br>
    </div>
    <div id="desktop" class="row" >
        <div class="col-sm-6">
            <img id="qrcode" src="data:image/png;base64, {{ base64_encode($qrcode) }}">
        </div>
        <div class="col-sm-6">
            <ol>
                <li><b>Abra o aplicativo ou internet Banking</b> para pagar</li>
                <li>Na opção Pix, escolha <b>"Ler QR Code"</b></li>
                <li>
                    <b>Leia o QR Code</b> ou, se preferir, <b>copie o código</b> para <b>Pix Copia e Cola</b>
                    <p class="text-center"><a class="btn btn-dark copiar" data-clipboard-text="{{ $pix }}"><i class="fa fa-files-o" aria-hidden="true"></i> COPIAR CÓDIGO PIX</a></p>
                    <p><small style="color: #ccc;">Você tem <b>24 horas</b> para pagar. Depois desse tempo seu pedido será cancelado.</small></p>
                </li>
                <li>Revise as informações e <b>confirme o pagamento</b></li>
                <li>Após a finalização do pagamento, <b>envie o comprovante</b> para o número: 
                    <p class="text-center"><a href="https://api.whatsapp.com/send?phone=+55{{ env('WHATSAPP') }}&text=Olá" target="_blank" class="btn btn-success"><i class="fa fa-whatsapp" aria-hidden="true"></i> {{ Helper::formatTelefone( env('WHATSAPP') ) }}</a></p>
                </li>
            </ol>
        </div>
    </div>
    <div id="mobile" style="display: none;">
        <div class="col-md-12">
            <ol>
                <li>
                    <b>Copie o código Pix</b>
                    <p class="text-center"><a class="btn btn-dark copiar" data-clipboard-text="{{ $pix }}"><i class="fa fa-files-o" aria-hidden="true"></i> COPIAR CÓDIGO PIX</a></p>
                    <p><small style="color: #ccc;">Você tem <b>24 horas</b> para pagar. Depois desse tempo seu pedido será cancelado.</small></p>
                </li>
                <li><b>Abra o aplicativo</b> do seu banco e acesse a <b>opção Pix</b></li>
                <li>Escolha <b>"Pix Copia e Cola"</b> e cole o código copiado</li>
                <li>Revise as informações e <b>confirme o pagamento</b></li>
                <li>Após a finalização do pagamento, <b>envie o comprovante</b> para o número: 
                    <p class="text-center"><a href="https://api.whatsapp.com/send?phone=+55{{ env('WHATSAPP') }}&text=Olá" target="_blank" class="btn btn-success"><i class="fa fa-whatsapp" aria-hidden="true"></i> {{ Helper::formatTelefone( env('WHATSAPP') ) }}</a></p>
                </li>
            </ol>
        </div>
    </div>
    <br><br>
</div>
@endsection
@section('css')
<style>
    .btn-dark, .btn-dark:focus {
        color: #fff !important;
    }
    #qrcode {
        box-shadow: 5px 5px 25px;
        border-radius: 15px;
        max-width: 100%;
        display: block;
        margin: auto;
    }
    ol {
        padding-left: 15px;
    }
</style>
@endsection
@section('scripts')
<script>
    function showpage(){
        if( $(document).width() <= 800 ){
            $('#desktop').hide();
            $('#mobile').show();
        } else {
            $('#desktop').show();
            $('#mobile').hide();
        }
    }
    showpage();
    $(window).on('resize', function(){
        showpage();
    });

    $(document).ready(function(){
        var clipboard = new ClipboardJS('.copiar');

        var timeouts = [];
        var countdow = 0;
        function contagemRegressivaParaRedirecionamento(){
            if( countdow ){
                countdow--;
                $('#countdow').html( countdow );
                timeouts.push( setTimeout(function(){ contagemRegressivaParaRedirecionamento(); }, 1000) );
            } else {
                window.location.href = "{{ env('SITE_URL') }}";
            }
        }
        
        function mostraBannerInformandoRedireccionamento(){

            for (var i=0; i<timeouts.length; i++) {
                clearTimeout(timeouts[i]);
            }
            timeouts = [];
            countdow = 60; // segundos

            setTimeout(function(){
                const text = document.createElement('p');
                text.append('Dentro de ');
                
                const span = document.createElement('span');
                span.setAttribute("id", "countdow");
                span.style.cssText = 'font-weight: bold;';
                span.append(countdow);
                text.appendChild(span);

                const label = document.createElement('span');
                label.style.cssText = 'font-weight: bold;';
                label.append(' segundos');
                text.appendChild(label);

                text.append(' você será redirecionado para o site, caso queira cancelar temporariamente, clique no botão abaixo.');

                swal({
                    icon: 'info',
                    title: 'Você será redirecionado...',
                    content: text,
                    button: "Espere mais um pouco!",
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    closeModal: true,
                }).then((value) => {
                    mostraBannerInformandoRedireccionamento();
                });
                contagemRegressivaParaRedirecionamento();
            }, 120000 ); // 2 minutos 2*60*1000
        }
        mostraBannerInformandoRedireccionamento();

    });
</script>
@endsection