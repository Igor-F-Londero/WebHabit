<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WebHabit') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600;outfit:500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-[radial-gradient(circle_at_18%_0%,_rgba(34,211,238,0.16),_transparent_28%),radial-gradient(circle_at_88%_12%,_rgba(217,70,239,0.12),_transparent_22%),linear-gradient(180deg,_rgba(2,6,23,0.98),_rgba(0,0,0,1))] px-6 py-10">
            <div class="mx-auto flex min-h-[calc(100vh-5rem)] w-full max-w-6xl flex-col items-center justify-center">
                <div class="w-full max-w-md">
                    <div class="mb-8 flex items-center justify-center">
                        <a href="/" class="flex items-center gap-3">
                            <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-cyan-300/10 ring-1 ring-cyan-300/25 shadow-[0_0_28px_rgba(34,211,238,0.14)]">
                                <span class="font-['Outfit'] text-xl font-bold text-cyan-100">HF</span>
                            </div>
                            <div>
                                <p class="font-['Outfit'] text-2xl font-semibold tracking-tight text-white">WebHabit</p>
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">hub operacional</p>
                            </div>
                        </a>
                    </div>

                    <div class="hf-panel px-6 py-8 sm:px-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
