@component('mail::message')
Olá!<br/>
Sua solicitação de serviço de <b>"{{ $servico }}"</b> para a data <b>{{ date( 'd/m/Y H:i', strtotime( $inicio ) ) }}</b> foi aceita pelo prestador {{ $prestador }}<br>
Por favor, consulte a agenda do sistema para mais informações.

@component('mail::button', ['url' => url('/servicos/calendario') ])
Consultar agenda
@endcomponent

<br>
Atenciosamente,<br>
{{ $empresa }}
@endcomponent