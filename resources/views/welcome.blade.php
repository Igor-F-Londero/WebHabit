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
    <body class="bg-slate-950 text-slate-100 antialiased">
        <div class="min-h-screen overflow-hidden bg-[radial-gradient(circle_at_18%_0%,_rgba(34,211,238,0.18),_transparent_28%),radial-gradient(circle_at_90%_16%,_rgba(217,70,239,0.12),_transparent_22%),linear-gradient(180deg,_#020617_0%,_#020617_58%,_#000_100%)]">
            <header class="relative z-20 border-b border-cyan-300/10 bg-slate-950/70 backdrop-blur">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg border border-cyan-300/25 bg-cyan-300/10 font-['Outfit'] text-sm font-black text-cyan-100 shadow-[0_0_24px_rgba(34,211,238,0.18)]">
                            HF
                        </span>
                        <span>
                            <span class="block font-['Outfit'] text-lg font-semibold tracking-tight text-white">HabitFlow</span>
                            <span class="block text-xs uppercase tracking-[0.22em] text-cyan-200/60">rotina em aventura</span>
                        </span>
                    </a>

                    <nav class="hidden items-center gap-7 text-sm text-slate-300 md:flex">
                        <a href="#plataforma" class="transition hover:text-cyan-100">Plataforma</a>
                        <a href="#recursos" class="transition hover:text-cyan-100">Recursos</a>
                        <a href="#evolucao" class="transition hover:text-cyan-100">Evolução</a>
                    </nav>

                    @if (Route::has('login'))
                        <div class="flex items-center gap-3">
                            @auth
                                <a href="{{ route('home') }}" class="rounded-lg border border-cyan-300/30 bg-cyan-300/10 px-4 py-2 text-sm font-semibold text-cyan-100 transition hover:border-cyan-200/60 hover:bg-cyan-300/15">
                                    Abrir painel
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="hidden rounded-lg border border-white/10 px-4 py-2 text-sm font-medium text-slate-200 transition hover:border-cyan-300/40 hover:bg-cyan-300/10 sm:inline-flex">
                                    Entrar
                                </a>
                                <a href="{{ route('register') }}" class="rounded-lg bg-cyan-300 px-4 py-2 text-sm font-bold text-slate-950 transition hover:bg-cyan-200">
                                    Criar conta
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </header>

            <main>
                <section class="relative">
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-cyan-300/60 to-transparent"></div>

                    <div class="mx-auto grid min-h-[calc(100vh-73px)] max-w-7xl items-center gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[0.94fr_1.06fr] lg:px-8 lg:py-16">
                        <div class="max-w-2xl">
                            <p class="mb-5 inline-flex rounded-lg border border-cyan-300/15 bg-cyan-300/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-cyan-100">
                                sistema gamificado de Missões
                            </p>

                            <h1 class="font-['Outfit'] text-5xl font-black leading-[0.94] tracking-tight text-white sm:text-6xl lg:text-7xl">
                                HabitFlow
                            </h1>

                            <p class="mt-6 max-w-xl text-base leading-8 text-slate-300 sm:text-lg">
                                Uma plataforma para criar Missões, enfrentar Chefes, ganhar XP e transformar a rotina em Campanha.
                            </p>

                            <div class="mt-8 flex flex-wrap gap-3">
                                @auth
                                    <a href="{{ route('home') }}" class="rounded-lg bg-cyan-300 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-cyan-200">
                                    Ir para minha jornada
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="rounded-lg bg-cyan-300 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-cyan-200">
                                    Começar jornada
                                    </a>
                                    <a href="{{ route('login') }}" class="rounded-lg border border-white/10 bg-white/[0.03] px-5 py-3 text-sm font-semibold text-slate-100 transition hover:border-cyan-300/40 hover:bg-cyan-300/10">
                                        Acessar conta
                                    </a>
                                @endauth
                            </div>

                            <div class="mt-10 grid max-w-xl grid-cols-3 gap-3">
                                <div class="border-l border-cyan-300/30 pl-4">
                                    <p class="font-['Outfit'] text-2xl font-bold text-white">XP</p>
                                    <p class="mt-1 text-xs text-slate-400">níveis e moedas</p>
                                </div>
                                <div class="border-l border-cyan-300/30 pl-4">
                                    <p class="font-['Outfit'] text-2xl font-bold text-cyan-200">API</p>
                                    <p class="mt-1 text-xs text-slate-400">JSON com Sanctum</p>
                                </div>
                                <div class="border-l border-cyan-300/30 pl-4">
                                    <p class="font-['Outfit'] text-2xl font-bold text-white">Herói</p>
                                    <p class="mt-1 text-xs text-slate-400">Missões e Chefes</p>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="relative overflow-hidden rounded-xl border border-cyan-300/15 bg-slate-950/80 shadow-[0_0_70px_rgba(34,211,238,0.12)] backdrop-blur">
                                <div
                                    class="absolute inset-0 bg-cover bg-center"
                                    style="background-image: url('{{ asset('images/08-11-maneiras-de-ser-um-jogador-de-RPG-melhor.png') }}');"
                                    aria-hidden="true"
                                ></div>
                                <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(2,6,23,0.18),rgba(2,6,23,0.76)),linear-gradient(90deg,rgba(2,6,23,0.75),rgba(2,6,23,0.18))]"></div>

                                <div class="relative aspect-[4/5] w-full sm:aspect-[16/9]">
                                    <div class="absolute left-4 right-4 top-4 flex items-center justify-between gap-3">
                                        <div class="rounded-lg border border-cyan-300/20 bg-slate-950/75 px-4 py-3 shadow-[0_0_24px_rgba(34,211,238,0.12)] backdrop-blur">
                                            <p class="text-xs uppercase tracking-[0.2em] text-cyan-200/70">campanha</p>
                                            <h2 class="mt-1 font-['Outfit'] text-2xl font-bold text-white">Nível 8</h2>
                                        </div>
                                        <span class="rounded-lg border border-emerald-300/20 bg-emerald-300/10 px-3 py-2 text-xs font-semibold text-emerald-200 backdrop-blur">
                                            4 de 5 missões
                                        </span>
                                    </div>

                                    <div class="absolute bottom-4 left-4 right-4 grid grid-cols-3 gap-2 sm:gap-3">
                                        <div class="rounded-lg border border-white/10 bg-slate-950/78 p-3 backdrop-blur sm:p-4">
                                            <p class="text-xs text-slate-400">XP</p>
                                            <p class="mt-1 font-['Outfit'] text-2xl font-bold text-cyan-200 sm:text-3xl">1240</p>
                                        </div>
                                        <div class="rounded-lg border border-white/10 bg-slate-950/78 p-3 backdrop-blur sm:p-4">
                                            <p class="text-xs text-slate-400">Combo</p>
                                            <p class="mt-1 font-['Outfit'] text-2xl font-bold text-white sm:text-3xl">21</p>
                                        </div>
                                        <div class="rounded-lg border border-white/10 bg-slate-950/78 p-3 backdrop-blur sm:p-4">
                                            <p class="text-xs text-slate-400">Moedas</p>
                                            <p class="mt-1 font-['Outfit'] text-2xl font-bold text-fuchsia-200 sm:text-3xl">148</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="plataforma" class="border-y border-cyan-300/10 bg-slate-950/65 px-4 py-12 sm:px-6 lg:px-8">
                    <div class="mx-auto grid max-w-7xl gap-4 md:grid-cols-3">
                        @foreach ([
                            ['Área pública', 'Apresenta o produto e leva visitantes para cadastro ou login.'],
                            ['Área do usuário', 'Centraliza Herói, Missões, Chefes, Recompensas, XP e Relatório pessoal.'],
                            ['Área administrativa', 'Permite acompanhar a Guilda, categorias, métricas e Relatórios gerais.'],
                        ] as [$title, $copy])
                            <article class="rounded-lg border border-cyan-300/10 bg-slate-950/80 p-6">
                                <h2 class="font-['Outfit'] text-xl font-bold text-white">{{ $title }}</h2>
                                <p class="mt-3 text-sm leading-7 text-slate-400">{{ $copy }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section id="recursos" class="px-4 py-16 sm:px-6 lg:px-8">
                    <div class="mx-auto max-w-7xl">
                        <div class="mb-8 max-w-2xl">
                            <p class="text-sm uppercase tracking-[0.22em] text-cyan-200/70">recursos principais</p>
                            <h2 class="mt-3 font-['Outfit'] text-4xl font-black text-white">Pronto para transformar rotina em progresso visível.</h2>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                            @foreach ([
                                ['Missões', 'Cadastro, edição, ativação, categoria, frequência e cor.'],
                                ['Chefes', 'Objetivos com prazo, progresso, status e vínculo com Missões.'],
                                ['Conquistas', 'Nível, XP, Combo e Recompensas calculadas.'],
                                ['Relatório', 'Taxas, períodos customizados e leitura de desempenho.'],
                            ] as [$title, $copy])
                                <article class="rounded-lg border border-white/10 bg-white/[0.03] p-5">
                                    <h3 class="font-['Outfit'] text-lg font-bold text-white">{{ $title }}</h3>
                                    <p class="mt-3 text-sm leading-6 text-slate-400">{{ $copy }}</p>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>

                <section id="evolucao" class="px-4 pb-16 sm:px-6 lg:px-8">
                    <div class="mx-auto grid max-w-7xl gap-6 rounded-xl border border-cyan-300/10 bg-cyan-300/[0.035] p-6 md:grid-cols-[1fr_0.85fr] md:p-8">
                        <div>
                            <p class="text-sm uppercase tracking-[0.22em] text-cyan-200/70">base para evolução</p>
                            <h2 class="mt-3 font-['Outfit'] text-3xl font-black text-white">Banco relacional, autenticação e API JSON já trabalham juntos.</h2>
                            <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300">
                                A estrutura usa Laravel, migrations com relacionamentos, roles de usuário, middleware de acesso, dashboards e endpoints autenticados para futuras integrações.
                            </p>
                        </div>

                        <div class="space-y-3 font-mono text-sm">
                            <div class="rounded-lg border border-cyan-300/15 bg-slate-950 px-4 py-3 text-slate-200">GET <span class="text-cyan-200">/api/habits</span></div>
                            <div class="rounded-lg border border-cyan-300/15 bg-slate-950 px-4 py-3 text-slate-200">POST <span class="text-emerald-200">/api/checkins</span></div>
                            <div class="rounded-lg border border-cyan-300/15 bg-slate-950 px-4 py-3 text-slate-200">GET <span class="text-fuchsia-200">/api/stats</span></div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
