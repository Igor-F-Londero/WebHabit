@php
    $error = $error ?? [];
    $status = $error['status'] ?? 'Erro';
    $eyebrow = $error['eyebrow'] ?? 'Tratamento de erro';
    $title = $error['title'] ?? 'Algo saiu do caminho';
    $message = $error['message'] ?? 'Tente novamente em instantes.';
    $icon = $error['icon'] ?? 'spark';
    $primary = $error['primary'] ?? ['label' => 'Ir para a home', 'url' => url('/')];
    $secondary = $error['secondary'] ?? ['label' => 'Voltar', 'url' => url()->previous()];
    $support = $error['support'] ?? 'Se o problema persistir, retome o fluxo pela home.';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WebHabit') }} | {{ $status }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600;outfit:500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="hf-game-shell relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_0%,rgba(34,211,238,0.18),transparent_24%),radial-gradient(circle_at_88%_12%,rgba(217,70,239,0.16),transparent_20%),linear-gradient(180deg,rgba(2,6,23,0.92),rgba(2,6,23,1))]"></div>
            <div class="relative mx-auto flex min-h-screen w-full max-w-6xl items-center px-6 py-10 sm:px-8 lg:px-10">
                <div class="grid w-full gap-6 lg:grid-cols-[1.08fr_0.92fr]">
                    <section class="hf-panel relative overflow-hidden p-6 sm:p-8 lg:p-10">
                        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(135deg,rgba(34,211,238,0.07),transparent_35%,rgba(217,70,239,0.08)_70%,transparent_100%)] opacity-80"></div>
                        <div class="relative z-10 flex flex-col gap-6">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="rounded-full border border-cyan-300/20 bg-cyan-300/10 px-3 py-1 text-[0.65rem] font-semibold uppercase tracking-[0.35em] text-cyan-100">
                                    {{ $status }}
                                </span>
                                <span class="text-xs uppercase tracking-[0.32em] text-slate-500">
                                    {{ $eyebrow }}
                                </span>
                            </div>

                            <div>
                                <p class="font-['Outfit'] text-sm font-medium uppercase tracking-[0.32em] text-cyan-200/70">
                                    WebHabit
                                </p>
                                <h1 class="mt-3 max-w-2xl font-['Outfit'] text-3xl font-semibold text-white sm:text-4xl lg:text-5xl">
                                    {{ $title }}
                                </h1>
                            </div>

                            <p class="max-w-2xl text-sm leading-6 text-slate-300 sm:text-base">
                                {{ $message }}
                            </p>

                            <div class="flex flex-wrap gap-3">
                                <a
                                    href="{{ $primary['url'] }}"
                                    class="inline-flex items-center justify-center rounded-xl bg-cyan-300 px-4 py-2.5 text-sm font-semibold text-slate-950 transition hover:bg-cyan-200"
                                >
                                    {{ $primary['label'] }}
                                </a>
                                <a
                                    href="{{ $secondary['url'] }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/[0.03] px-4 py-2.5 text-sm font-semibold text-slate-100 transition hover:border-cyan-300/30 hover:bg-cyan-300/10"
                                >
                                    {{ $secondary['label'] }}
                                </a>
                            </div>

                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">
                                {{ $support }}
                            </p>
                        </div>
                    </section>

                    <aside class="hf-panel relative overflow-hidden p-6 sm:p-8 lg:p-10">
                        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_84%_18%,rgba(34,211,238,0.16),transparent_28%),radial-gradient(circle_at_88%_76%,rgba(217,70,239,0.14),transparent_24%)]"></div>
                        <div class="relative z-10 flex h-full min-h-[20rem] flex-col justify-between gap-6">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.34em] text-slate-500">
                                        Status do sistema
                                    </p>
                                    <p class="mt-2 font-['Outfit'] text-2xl font-semibold text-white">
                                        Protegendo sua jornada
                                    </p>
                                    <p class="mt-2 max-w-sm text-sm leading-6 text-slate-400">
                                        A interface continua no universo do WebHabit mesmo quando algo foge do padrão.
                                    </p>
                                </div>

                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-cyan-300/20 bg-cyan-300/10 text-cyan-100 shadow-[0_0_24px_rgba(34,211,238,0.12)]">
                                    <x-webhabit.icon :name="$icon" class="h-8 w-8" />
                                </div>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                                    <p class="text-xs uppercase tracking-[0.28em] text-slate-500">O que fazer agora</p>
                                    <p class="mt-2 text-sm text-slate-200">Volte para a home ou tente novamente em instantes.</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                                    <p class="text-xs uppercase tracking-[0.28em] text-slate-500">Proteção ativa</p>
                                    <p class="mt-2 text-sm text-slate-200">Erros públicos ficam amigáveis; respostas JSON seguem o padrão da API.</p>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-slate-950/55 p-4">
                                <p class="text-xs uppercase tracking-[0.28em] text-slate-500">Mensagem da guilda</p>
                                <p class="mt-2 text-sm leading-6 text-slate-300">
                                    Pequenos contratempos também fazem parte da campanha. A jornada continua.
                                </p>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </body>
</html>
