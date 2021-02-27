@component('mail::message')
Olá!<br/>
A solicitação de serviço de <b>"{{ $servico }}"</b> para a data <b>{{ date( 'd/m/Y H:i', strtotime( $inicio ) ) }}</b> foi cancelada pelo prestador {{ $prestador }}.<br>
Estamos procurando um prestador de serviço ideal para você. Entraremos em contato em breve.<br>
Consulte a agenda do sistema para mais informações.

@component('mail::button', ['url' => url('/servicos/calendario') ])
Consultar agenda
@endcomponent

<br>
Agradecemos a compreensão,<br>
{{ $empresa }}
@endcomponent