@php
    $error = [
        'status' => '403',
        'eyebrow' => 'Acesso restrito',
        'title' => 'Você não tem permissão para acessar esta área',
        'message' => 'Esta rota pede um nível de acesso específico. Se você acha que isso aconteceu por engano, volte ao fluxo principal ou fale com um administrador.',
        'icon' => 'guardian',
        'primary' => [
            'label' => auth()->check() ? 'Ir para o dashboard' : 'Entrar',
            'url' => auth()->check() ? route('dashboard') : route('login'),
        ],
        'secondary' => [
            'label' => 'Ir para a home',
            'url' => url('/'),
        ],
        'support' => 'Permissões e funções protegem áreas administrativas e rotas sensíveis.',
    ];
@endphp

@include('errors.layout')
