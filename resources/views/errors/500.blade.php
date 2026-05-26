@php
    $error = [
        'status' => '500',
        'eyebrow' => 'Falha interna',
        'title' => 'Tivemos um contratempo no lado do sistema',
        'message' => 'Algo não funcionou como esperado. Tente novamente em alguns instantes enquanto ajustamos a rota.',
        'icon' => 'shadow',
        'primary' => [
            'label' => 'Tentar novamente',
            'url' => request()->fullUrl(),
        ],
        'secondary' => [
            'label' => 'Ir para a home',
            'url' => url('/'),
        ],
        'support' => 'Se isso continuar acontecendo, vale registrar o horário e a ação que disparou o erro.',
    ];
@endphp

@include('errors.layout')
