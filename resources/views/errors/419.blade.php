@php
    $error = [
        'status' => '419',
        'eyebrow' => 'Sessão expirada',
        'title' => 'Sua sessão ficou antiga',
        'message' => 'Por segurança, a página precisa ser recarregada ou a sessão precisa ser renovada antes de continuar.',
        'icon' => 'clock',
        'primary' => [
            'label' => auth()->check() ? 'Retomar sessão' : 'Entrar novamente',
            'url' => auth()->check() ? route('home') : route('login'),
        ],
        'secondary' => [
            'label' => 'Ir para a home',
            'url' => url('/'),
        ],
        'support' => 'Essa proteção impede que formulários sejam enviados com dados vencidos.',
    ];
@endphp

@include('errors.layout')
