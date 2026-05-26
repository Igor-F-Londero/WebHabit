@php
    $error = [
        'status' => '404',
        'eyebrow' => 'Rota perdida',
        'title' => 'Não encontramos essa página',
        'message' => 'O endereço solicitado não existe, foi movido ou foi digitado de forma incorreta.',
        'icon' => 'compass',
        'primary' => [
            'label' => 'Ir para a home',
            'url' => url('/'),
        ],
        'secondary' => [
            'label' => 'Voltar',
            'url' => url()->previous(),
        ],
        'support' => 'Confira o endereço e tente de novo.',
    ];
@endphp

@include('errors.layout')
