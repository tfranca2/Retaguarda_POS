@component('mail::message')
Olá!<br/>
Uma solicitação de serviço de <b>"{{ $servico }}"</b> sem um prestador localizado acaba de ser feita para a data <b>{{ date( 'd/m/Y H:i', strtotime( $inicio ) ) }}</b><br>
Por favor, consulte a agenda do sistema para mais informações.

@component('mail::button', ['url' => url('/servicos/calendario') ])
Consultar agenda
@endcomponent

<br>
Atenciosamente,<br>
{{ $empresa }}
@endcomponent