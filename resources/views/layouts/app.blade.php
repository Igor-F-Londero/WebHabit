<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HabitFlow') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600;outfit:500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-[radial-gradient(circle_at_18%_0%,_rgba(34,211,238,0.16),_transparent_28%),radial-gradient(circle_at_88%_12%,_rgba(217,70,239,0.12),_transparent_22%),linear-gradient(180deg,_rgba(2,6,23,0.98),_rgba(0,0,0,1))]">
            @include('layouts.navigation')

            @isset($header)
                <header class="relative z-10 border-b border-cyan-300/10 bg-slate-950/55 backdrop-blur">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="relative z-0">
                {{ $slot }}
            </main>
        </div>
        @stack('scripts')
    </body>
</html>
