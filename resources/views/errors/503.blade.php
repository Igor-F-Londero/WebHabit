@php
    $error = [
        'status' => '503',
        'eyebrow' => 'Manutenção',
        'title' => 'Estamos fazendo ajustes rápidos',
        'message' => 'O WebHabit está em manutenção ou temporariamente indisponível. Voltamos em breve.',
        'icon' => 'flame',
        'primary' => [
            'label' => 'Ir para a home',
            'url' => url('/'),
        ],
        'secondary' => [
            'label' => 'Atualizar',
            'url' => request()->fullUrl(),
        ],
        'support' => 'Se você já estava em uma missão, ela estará esperando quando o sistema voltar.',
    ];
@endphp

@include('errors.layout')
