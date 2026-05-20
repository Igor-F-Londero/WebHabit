<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>HabitFlow</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600;outfit:500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-black text-zinc-100 antialiased">
        <div class="relative min-h-screen overflow-hidden bg-black">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,_rgba(236,72,153,0.26),_transparent_30%),radial-gradient(circle_at_18%_25%,_rgba(168,85,247,0.14),_transparent_28%),linear-gradient(180deg,_rgba(12,8,12,0.35),_#000_62%)]"></div>
            <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.035)_1px,_transparent_1px),linear-gradient(90deg,_rgba(255,255,255,0.025)_1px,_transparent_1px)] bg-[size:72px_72px] opacity-20"></div>
            <div class="absolute inset-x-8 top-12 h-px bg-gradient-to-r from-transparent via-pink-400/50 to-transparent"></div>

            <div class="relative z-10 mx-auto max-w-7xl px-6 py-8 lg:px-8">
                <div class="rounded-[2rem] border border-pink-400/20 bg-zinc-950/70 shadow-[0_0_80px_rgba(236,72,153,0.14)] backdrop-blur-xl">
                    <header class="flex items-center justify-between px-6 py-5 lg:px-8">
                        <a href="/" class="flex items-center gap-3">
                            <div class="relative flex h-11 w-11 items-center justify-center rounded-2xl border border-pink-300/30 bg-pink-500/10 shadow-[0_0_28px_rgba(236,72,153,0.35)]">
                                <span class="absolute h-5 w-7 rounded-full border border-pink-200/80"></span>
                                <span class="font-['Outfit'] text-sm font-black tracking-tight text-pink-100">HF</span>
                            </div>
                            <div>
                                <p class="font-['Outfit'] text-lg font-semibold tracking-tight text-white">HabitFlow</p>
                                <p class="text-xs uppercase tracking-[0.24em] text-pink-200/60">neon discipline</p>
                            </div>
                        </a>

                        <nav class="hidden items-center gap-7 text-sm text-zinc-300 md:flex">
                            <a href="#valores" class="transition hover:text-pink-200">Valores</a>
                            <a href="#fluxo" class="transition hover:text-pink-200">Fluxo</a>
                            <a href="#recursos" class="transition hover:text-pink-200">Recursos</a>
                            <a href="#api" class="transition hover:text-pink-200">API</a>
                        </nav>

                        @if (Route::has('login'))
                            <div class="flex items-center gap-3">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="rounded-xl border border-pink-300/30 bg-pink-500/10 px-4 py-2 text-sm font-semibold text-pink-100 shadow-[0_0_24px_rgba(236,72,153,0.22)] transition hover:border-pink-200/60 hover:bg-pink-500/20">
                                        Abrir painel
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="hidden rounded-xl border border-white/10 px-4 py-2 text-sm font-medium text-zinc-200 transition hover:border-pink-300/40 hover:bg-pink-500/10 sm:inline-flex">
                                        Entrar
                                    </a>
                                    <a href="{{ route('register') }}" class="rounded-xl border border-pink-200/50 bg-pink-500/20 px-4 py-2 text-sm font-semibold text-white shadow-[0_0_28px_rgba(236,72,153,0.35)] transition hover:bg-pink-500/30">
                                        Get Started
                                    </a>
                                @endauth
                            </div>
                        @endif
                    </header>

                    <main>
                        <section class="relative px-6 pb-14 pt-12 text-center lg:px-8 lg:pb-20 lg:pt-16">
                            <div class="absolute left-1/2 top-20 h-56 w-56 -translate-x-1/2 rounded-full bg-pink-500/20 blur-3xl"></div>

                            <div class="relative mx-auto max-w-5xl">
                                <div class="mx-auto mb-7 inline-flex items-center gap-3 rounded-2xl border border-pink-300/20 bg-black/50 px-4 py-2 text-xs uppercase tracking-[0.24em] text-pink-100 shadow-[0_0_32px_rgba(236,72,153,0.16)]">
                                    <span class="h-2 w-2 rounded-full bg-pink-300 shadow-[0_0_18px_rgba(249,168,212,0.9)]"></span>
                                    Sistema de rotina gamificada
                                </div>

                                <h1 class="font-['Outfit'] text-5xl font-black uppercase leading-[0.9] tracking-tight text-white sm:text-7xl lg:text-8xl">
                                    Domine sua
                                    <span class="block bg-gradient-to-r from-pink-200 via-fuchsia-400 to-pink-600 bg-clip-text text-transparent drop-shadow-[0_0_28px_rgba(236,72,153,0.38)]">
                                        rotina futura
                                    </span>
                                </h1>

                                <p class="mx-auto mt-7 max-w-2xl text-base leading-8 text-zinc-300 sm:text-lg">
                                    Transforme hábitos, check-ins, metas e relatórios em um cockpit pessoal com cadência, streaks e leitura visual do seu progresso.
                                </p>

                                <div class="mt-9 flex flex-wrap items-center justify-center gap-4">
                                    @auth
                                        <a href="{{ url('/dashboard') }}" class="rounded-2xl border border-pink-200/50 bg-pink-500/20 px-7 py-3 text-sm font-bold text-white shadow-[0_0_34px_rgba(236,72,153,0.35)] transition hover:bg-pink-500/30">
                                            Entrar no cockpit
                                        </a>
                                    @else
                                        <a href="{{ route('register') }}" class="rounded-2xl border border-pink-200/50 bg-pink-500/20 px-7 py-3 text-sm font-bold text-white shadow-[0_0_34px_rgba(236,72,153,0.35)] transition hover:bg-pink-500/30">
                                            Começar agora
                                        </a>
                                        <a href="{{ route('login') }}" class="rounded-2xl border border-white/10 bg-white/[0.03] px-7 py-3 text-sm font-semibold text-zinc-100 transition hover:border-pink-300/40 hover:bg-pink-500/10">
                                            Já tenho conta
                                        </a>
                                    @endauth
                                </div>
                            </div>

                            <div class="mx-auto mt-14 max-w-5xl">
                                <div class="grid gap-4 rounded-[1.7rem] border border-pink-300/20 bg-black/55 p-4 shadow-[0_0_60px_rgba(236,72,153,0.13)] backdrop-blur md:grid-cols-4">
                                    <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                                        <p class="font-['Outfit'] text-3xl font-bold text-white">82%</p>
                                        <p class="mt-1 text-sm text-zinc-400">Consistência</p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                                        <p class="font-['Outfit'] text-3xl font-bold text-pink-200">21</p>
                                        <p class="mt-1 text-sm text-zinc-400">Dias de streak</p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                                        <p class="font-['Outfit'] text-3xl font-bold text-fuchsia-300">4/5</p>
                                        <p class="mt-1 text-sm text-zinc-400">Missões hoje</p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                                        <p class="font-['Outfit'] text-3xl font-bold text-white">API</p>
                                        <p class="mt-1 text-sm text-zinc-400">Pronta para escala</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="valores" class="border-y border-pink-300/10 px-6 py-12 lg:px-8">
                            <div class="mx-auto grid max-w-6xl gap-4 md:grid-cols-3">
                                <article class="rounded-[1.5rem] border border-pink-300/15 bg-zinc-950/80 p-6 shadow-[0_0_36px_rgba(236,72,153,0.08)]">
                                    <p class="text-xs uppercase tracking-[0.22em] text-pink-200/60">foco diário</p>
                                    <h2 class="mt-4 font-['Outfit'] text-2xl font-bold text-white">Painel de missões</h2>
                                    <p class="mt-3 text-sm leading-7 text-zinc-400">Veja o que precisa ser feito hoje, registre check-ins e proteja sua sequência sem fricção.</p>
                                </article>
                                <article class="rounded-[1.5rem] border border-pink-300/15 bg-zinc-950/80 p-6 shadow-[0_0_36px_rgba(236,72,153,0.08)]">
                                    <p class="text-xs uppercase tracking-[0.22em] text-pink-200/60">progressão</p>
                                    <h2 class="mt-4 font-['Outfit'] text-2xl font-bold text-white">Metas com prazo</h2>
                                    <p class="mt-3 text-sm leading-7 text-zinc-400">Transforme intenção em alvo mensurável, com contagem, status e histórico de execução.</p>
                                </article>
                                <article class="rounded-[1.5rem] border border-pink-300/15 bg-zinc-950/80 p-6 shadow-[0_0_36px_rgba(236,72,153,0.08)]">
                                    <p class="text-xs uppercase tracking-[0.22em] text-pink-200/60">leitura tática</p>
                                    <h2 class="mt-4 font-['Outfit'] text-2xl font-bold text-white">Relatórios visuais</h2>
                                    <p class="mt-3 text-sm leading-7 text-zinc-400">Acompanhe taxas, heatmap, categorias e padrões para ajustar a rotina antes da queda.</p>
                                </article>
                            </div>
                        </section>

                        <section id="fluxo" class="px-6 py-20 lg:px-8">
                            <div class="mx-auto grid max-w-6xl gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.24em] text-pink-200/60">como funciona</p>
                                    <h2 class="mt-4 font-['Outfit'] text-4xl font-black leading-tight text-white sm:text-5xl">
                                        Cinco ações. Zero ruído.
                                    </h2>
                                    <p class="mt-5 text-sm leading-7 text-zinc-400">
                                        O HabitFlow organiza o ciclo inteiro: criar hábitos, executar check-ins, manter streaks, revisar métricas e expandir para API/admin.
                                    </p>
                                </div>

                                <div class="relative">
                                    <div class="absolute left-6 top-5 h-[calc(100%-2.5rem)] w-px bg-gradient-to-b from-pink-300 via-fuchsia-500 to-transparent"></div>
                                    <div class="space-y-5">
                                        @foreach ([
                                            ['01', 'Configure hábitos', 'Categoria, frequência, cor e contexto em uma única tela.'],
                                            ['02', 'Faça check-ins', 'Registro rápido para manter a sequência viva.'],
                                            ['03', 'Persiga metas', 'Alvos por período com progresso claro.'],
                                            ['04', 'Leia os sinais', 'Dashboard e relatórios mostram ritmo e queda.'],
                                            ['05', 'Escale o fluxo', 'Admin e API prontos para integrar novas experiências.'],
                                        ] as [$step, $title, $copy])
                                            <div class="relative ml-12 rounded-2xl border border-pink-300/15 bg-zinc-950/90 p-5 shadow-[0_0_28px_rgba(236,72,153,0.08)]">
                                                <span class="absolute -left-[3.35rem] top-6 h-4 w-4 rounded-full border border-pink-200 bg-pink-500 shadow-[0_0_22px_rgba(236,72,153,0.85)]"></span>
                                                <p class="font-['Outfit'] text-sm font-bold text-pink-200">{{ $step }}</p>
                                                <h3 class="mt-1 font-['Outfit'] text-xl font-bold text-white">{{ $title }}</h3>
                                                <p class="mt-2 text-sm leading-6 text-zinc-400">{{ $copy }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="recursos" class="px-6 pb-20 lg:px-8">
                            <div class="mx-auto max-w-6xl">
                                <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                                    <div>
                                        <p class="text-sm uppercase tracking-[0.24em] text-pink-200/60">recursos</p>
                                        <h2 class="mt-3 font-['Outfit'] text-4xl font-black text-white">Receipts. Not promises.</h2>
                                    </div>
                                    <p class="max-w-md text-sm leading-7 text-zinc-400">A base já cobre a rotina real: hábitos, metas, check-ins, relatórios, administração e endpoints autenticados.</p>
                                </div>

                                <div class="grid gap-4 md:grid-cols-2">
                                    <article class="rounded-[1.5rem] border border-pink-300/15 bg-gradient-to-br from-pink-500/15 to-zinc-950 p-6">
                                        <p class="text-xs uppercase tracking-[0.22em] text-pink-200/70">dashboard</p>
                                        <h3 class="mt-10 font-['Outfit'] text-2xl font-bold text-white">Cockpit de execução diária</h3>
                                        <p class="mt-3 text-sm leading-7 text-zinc-400">Resumo de hábitos, check-ins, streaks e mapa de consistência.</p>
                                    </article>
                                    <article class="rounded-[1.5rem] border border-fuchsia-300/15 bg-gradient-to-br from-fuchsia-500/15 to-zinc-950 p-6">
                                        <p class="text-xs uppercase tracking-[0.22em] text-fuchsia-200/70">admin</p>
                                        <h3 class="mt-10 font-['Outfit'] text-2xl font-bold text-white">Operação da plataforma</h3>
                                        <p class="mt-3 text-sm leading-7 text-zinc-400">Gestão de usuários, categorias e relatórios administrativos.</p>
                                    </article>
                                </div>
                            </div>
                        </section>

                        <section id="api" class="border-t border-pink-300/10 px-6 py-16 lg:px-8">
                            <div class="mx-auto grid max-w-6xl gap-6 lg:grid-cols-[1fr_0.85fr] lg:items-center">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.24em] text-pink-200/60">api sanctum</p>
                                    <h2 class="mt-4 font-['Outfit'] text-4xl font-black text-white">Integre o cockpit em outros canais.</h2>
                                    <p class="mt-5 max-w-2xl text-sm leading-7 text-zinc-400">
                                        Endpoints autenticados permitem listar hábitos, registrar check-ins e consultar estatísticas para apps mobile, automações ou serviços externos.
                                    </p>
                                </div>
                                <div class="space-y-3 font-mono text-sm text-zinc-200">
                                    <div class="rounded-2xl border border-pink-300/15 bg-black px-4 py-3">GET <span class="text-pink-300">/api/habits</span></div>
                                    <div class="rounded-2xl border border-pink-300/15 bg-black px-4 py-3">POST <span class="text-fuchsia-300">/api/checkins</span></div>
                                    <div class="rounded-2xl border border-pink-300/15 bg-black px-4 py-3">GET <span class="text-pink-100">/api/stats</span></div>
                                </div>
                            </div>
                        </section>
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
