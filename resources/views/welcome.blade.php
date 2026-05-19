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
    <body class="bg-stone-950 text-stone-100 antialiased">
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(251,146,60,0.24),_transparent_32%),radial-gradient(circle_at_80%_10%,_rgba(16,185,129,0.2),_transparent_24%),linear-gradient(180deg,_rgba(12,10,9,0.9),_rgba(12,10,9,1))]"></div>
            <div class="absolute inset-x-0 top-0 h-px bg-white/10"></div>

            <header class="relative z-10">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-6 lg:px-8">
                    <a href="/" class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-500/15 ring-1 ring-orange-300/20">
                            <span class="font-['Outfit'] text-lg font-bold text-orange-200">HF</span>
                        </div>
                        <div>
                            <p class="font-['Outfit'] text-lg font-semibold tracking-tight text-white">HabitFlow</p>
                            <p class="text-xs uppercase tracking-[0.24em] text-stone-400">rotina com clareza</p>
                        </div>
                    </a>

                    @if (Route::has('login'))
                        <nav class="flex items-center gap-3">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="rounded-full border border-white/15 px-4 py-2 text-sm font-medium text-stone-200 transition hover:border-white/30 hover:bg-white/5">
                                    Abrir painel
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="rounded-full border border-white/15 px-4 py-2 text-sm font-medium text-stone-200 transition hover:border-white/30 hover:bg-white/5">
                                    Entrar
                                </a>
                                <a href="{{ route('register') }}" class="rounded-full bg-orange-500 px-4 py-2 text-sm font-semibold text-stone-950 transition hover:bg-orange-400">
                                    Criar conta
                                </a>
                            @endauth
                        </nav>
                    @endif
                </div>
            </header>

            <main class="relative z-10">
                <section class="px-6 pb-12 pt-8 lg:px-8 lg:pb-20 lg:pt-10">
                    <div class="mx-auto max-w-7xl">
                        <div class="max-w-4xl">
                            <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-stone-200 backdrop-blur">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                Hábitos, metas, relatórios e API em um único fluxo
                            </div>

                            <h1 class="max-w-4xl font-['Outfit'] text-5xl font-semibold leading-[0.95] tracking-tight text-white sm:text-6xl lg:text-7xl">
                                HabitFlow
                            </h1>
                            <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-300 sm:text-xl">
                                Organize hábitos, registre check-ins e acompanhe consistência com uma visão que ajuda você a manter ritmo sem perder contexto.
                            </p>

                            <div class="mt-8 flex flex-wrap items-center gap-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="rounded-full bg-orange-500 px-6 py-3 text-sm font-semibold text-stone-950 transition hover:bg-orange-400">
                                        Ir para o dashboard
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="rounded-full bg-orange-500 px-6 py-3 text-sm font-semibold text-stone-950 transition hover:bg-orange-400">
                                        Começar agora
                                    </a>
                                    <a href="{{ route('login') }}" class="rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-stone-100 transition hover:border-white/30 hover:bg-white/5">
                                        Já tenho conta
                                    </a>
                                @endauth
                            </div>
                        </div>

                        <div class="mt-12 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                                <p class="text-xs uppercase tracking-[0.22em] text-stone-400">foco diário</p>
                                <p class="mt-3 font-['Outfit'] text-3xl font-semibold text-white">1 painel</p>
                                <p class="mt-2 text-sm leading-6 text-stone-300">Hábitos do dia, streaks e visão rápida do que ainda falta fazer.</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                                <p class="text-xs uppercase tracking-[0.22em] text-stone-400">ritmo visível</p>
                                <p class="mt-3 font-['Outfit'] text-3xl font-semibold text-white">7/30 dias</p>
                                <p class="mt-2 text-sm leading-6 text-stone-300">Gráficos e heatmap para enxergar consistência sem planilha paralela.</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                                <p class="text-xs uppercase tracking-[0.22em] text-stone-400">escala</p>
                                <p class="mt-3 font-['Outfit'] text-3xl font-semibold text-white">admin + api</p>
                                <p class="mt-2 text-sm leading-6 text-stone-300">Área administrativa e endpoints autenticados prontos para integração.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="px-6 pb-20 lg:px-8">
                    <div class="mx-auto max-w-7xl">
                        <div class="overflow-hidden rounded-[2rem] border border-white/10 bg-stone-900/80 shadow-2xl shadow-black/30 backdrop-blur">
                            <div class="grid gap-0 lg:grid-cols-[1.35fr_0.95fr]">
                                <div class="border-b border-white/10 p-6 lg:border-b-0 lg:border-r">
                                    <div class="mb-5 flex items-center justify-between">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.22em] text-stone-500">dashboard pessoal</p>
                                            <h2 class="mt-2 font-['Outfit'] text-2xl font-semibold text-white">Visão do dia com progresso real</h2>
                                        </div>
                                        <span class="rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-medium text-emerald-300">ao vivo</span>
                                    </div>

                                    <div class="grid gap-4 sm:grid-cols-3">
                                        <div class="rounded-2xl bg-stone-800 p-4">
                                            <p class="text-xs uppercase tracking-[0.18em] text-stone-500">consistência</p>
                                            <p class="mt-3 font-['Outfit'] text-4xl font-semibold text-orange-300">82%</p>
                                            <p class="mt-2 text-sm text-stone-400">últimos 30 dias</p>
                                        </div>
                                        <div class="rounded-2xl bg-stone-800 p-4">
                                            <p class="text-xs uppercase tracking-[0.18em] text-stone-500">check-ins hoje</p>
                                            <p class="mt-3 font-['Outfit'] text-4xl font-semibold text-emerald-300">4</p>
                                            <p class="mt-2 text-sm text-stone-400">de 5 hábitos ativos</p>
                                        </div>
                                        <div class="rounded-2xl bg-stone-800 p-4">
                                            <p class="text-xs uppercase tracking-[0.18em] text-stone-500">top streak</p>
                                            <p class="mt-3 font-['Outfit'] text-4xl font-semibold text-sky-300">21</p>
                                            <p class="mt-2 text-sm text-stone-400">dias seguidos</p>
                                        </div>
                                    </div>

                                    <div class="mt-6 rounded-[1.5rem] border border-white/10 bg-stone-950 p-5">
                                        <div class="mb-4 flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-white">Hábitos de hoje</p>
                                                <p class="text-xs text-stone-500">check-ins rápidos com contexto</p>
                                            </div>
                                            <span class="text-xs text-stone-500">terça-feira</span>
                                        </div>

                                        <div class="space-y-3">
                                            <div class="flex items-center gap-4 rounded-2xl border border-white/8 bg-white/[0.03] px-4 py-3">
                                                <div class="h-10 w-1.5 rounded-full bg-orange-400"></div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="truncate text-sm font-medium text-white">Ler 20 páginas</p>
                                                    <p class="text-xs text-stone-500">Estudo</p>
                                                </div>
                                                <span class="rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-medium text-emerald-300">feito</span>
                                            </div>
                                            <div class="flex items-center gap-4 rounded-2xl border border-white/8 bg-white/[0.03] px-4 py-3">
                                                <div class="h-10 w-1.5 rounded-full bg-emerald-400"></div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="truncate text-sm font-medium text-white">Caminhar 30 min</p>
                                                    <p class="text-xs text-stone-500">Saúde</p>
                                                </div>
                                                <button class="rounded-full bg-orange-500 px-3 py-1 text-xs font-semibold text-stone-950">check-in</button>
                                            </div>
                                            <div class="flex items-center gap-4 rounded-2xl border border-white/8 bg-white/[0.03] px-4 py-3">
                                                <div class="h-10 w-1.5 rounded-full bg-sky-400"></div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="truncate text-sm font-medium text-white">Revisão semanal</p>
                                                    <p class="text-xs text-stone-500">Planejamento</p>
                                                </div>
                                                <span class="rounded-full bg-sky-400/10 px-3 py-1 text-xs font-medium text-sky-300">1x semana</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <div class="rounded-[1.5rem] border border-white/10 bg-stone-950 p-5">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-white">Últimos 7 dias</p>
                                                <p class="text-xs text-stone-500">ritmo de execução</p>
                                            </div>
                                            <span class="text-xs text-stone-500">check-ins</span>
                                        </div>

                                        <div class="mt-6 flex h-40 items-end gap-3">
                                            <div class="flex flex-1 flex-col items-center gap-3">
                                                <div class="w-full rounded-t-2xl bg-stone-800" style="height: 32%"></div>
                                                <span class="text-xs text-stone-500">S</span>
                                            </div>
                                            <div class="flex flex-1 flex-col items-center gap-3">
                                                <div class="w-full rounded-t-2xl bg-stone-700" style="height: 48%"></div>
                                                <span class="text-xs text-stone-500">T</span>
                                            </div>
                                            <div class="flex flex-1 flex-col items-center gap-3">
                                                <div class="w-full rounded-t-2xl bg-orange-500/70" style="height: 82%"></div>
                                                <span class="text-xs text-stone-500">Q</span>
                                            </div>
                                            <div class="flex flex-1 flex-col items-center gap-3">
                                                <div class="w-full rounded-t-2xl bg-orange-400" style="height: 94%"></div>
                                                <span class="text-xs text-stone-500">Q</span>
                                            </div>
                                            <div class="flex flex-1 flex-col items-center gap-3">
                                                <div class="w-full rounded-t-2xl bg-emerald-400" style="height: 68%"></div>
                                                <span class="text-xs text-stone-500">S</span>
                                            </div>
                                            <div class="flex flex-1 flex-col items-center gap-3">
                                                <div class="w-full rounded-t-2xl bg-sky-400" style="height: 38%"></div>
                                                <span class="text-xs text-stone-500">S</span>
                                            </div>
                                            <div class="flex flex-1 flex-col items-center gap-3">
                                                <div class="w-full rounded-t-2xl bg-stone-700" style="height: 58%"></div>
                                                <span class="text-xs text-stone-500">D</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 rounded-[1.5rem] border border-white/10 bg-stone-950 p-5">
                                        <p class="text-sm font-medium text-white">Cobertura do produto</p>
                                        <div class="mt-5 space-y-4">
                                            <div>
                                                <div class="mb-2 flex items-center justify-between text-xs text-stone-400">
                                                    <span>hábitos e check-ins</span>
                                                    <span>núcleo pronto</span>
                                                </div>
                                                <div class="h-2 rounded-full bg-stone-800">
                                                    <div class="h-2 w-[92%] rounded-full bg-orange-400"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="mb-2 flex items-center justify-between text-xs text-stone-400">
                                                    <span>metas e relatórios</span>
                                                    <span>pronto</span>
                                                </div>
                                                <div class="h-2 rounded-full bg-stone-800">
                                                    <div class="h-2 w-[88%] rounded-full bg-emerald-400"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="mb-2 flex items-center justify-between text-xs text-stone-400">
                                                    <span>admin e api</span>
                                                    <span>em operação</span>
                                                </div>
                                                <div class="h-2 rounded-full bg-stone-800">
                                                    <div class="h-2 w-[84%] rounded-full bg-sky-400"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 rounded-[1.5rem] border border-orange-400/20 bg-orange-500/10 p-5">
                                        <p class="font-['Outfit'] text-2xl font-semibold text-white">Feito para manter cadência</p>
                                        <p class="mt-3 text-sm leading-6 text-stone-300">
                                            O sistema combina execução diária, leitura de tendência e uma base pronta para crescer sem reinventar o fluxo.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="border-t border-white/10 bg-stone-100 text-stone-900">
                    <div class="mx-auto grid max-w-7xl gap-12 px-6 py-20 lg:grid-cols-3 lg:px-8">
                        <div>
                            <p class="text-sm uppercase tracking-[0.24em] text-stone-500">como funciona</p>
                            <h2 class="mt-4 font-['Outfit'] text-4xl font-semibold tracking-tight text-stone-950">
                                Um fluxo simples para manter rotina sem fricção.
                            </h2>
                        </div>

                        <div class="lg:col-span-2 grid gap-8 md:grid-cols-3">
                            <div>
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-100 font-['Outfit'] text-lg font-semibold text-orange-700">1</div>
                                <h3 class="mt-5 font-['Outfit'] text-2xl font-semibold text-stone-950">Configure</h3>
                                <p class="mt-3 text-sm leading-7 text-stone-600">
                                    Defina hábito, categoria, frequência, cor e metas com prazo para dar contexto ao que importa.
                                </p>
                            </div>
                            <div>
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 font-['Outfit'] text-lg font-semibold text-emerald-700">2</div>
                                <h3 class="mt-5 font-['Outfit'] text-2xl font-semibold text-stone-950">Execute</h3>
                                <p class="mt-3 text-sm leading-7 text-stone-600">
                                    Faça check-ins rápidos no painel do dia e mantenha streaks diários ou semanais sem burocracia.
                                </p>
                            </div>
                            <div>
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 font-['Outfit'] text-lg font-semibold text-sky-700">3</div>
                                <h3 class="mt-5 font-['Outfit'] text-2xl font-semibold text-stone-950">Leia o ritmo</h3>
                                <p class="mt-3 text-sm leading-7 text-stone-600">
                                    Use dashboard, heatmap e relatórios para enxergar padrão, corrigir queda e sustentar consistência.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="bg-stone-950 px-6 py-20 lg:px-8">
                    <div class="mx-auto max-w-7xl">
                        <div class="grid gap-8 lg:grid-cols-2">
                            <div class="rounded-[2rem] border border-white/10 bg-white/[0.03] p-8">
                                <p class="text-sm uppercase tracking-[0.24em] text-stone-500">área administrativa</p>
                                <h2 class="mt-4 font-['Outfit'] text-3xl font-semibold text-white">Operação com métricas e controle</h2>
                                <p class="mt-4 max-w-xl text-sm leading-7 text-stone-300">
                                    Acompanhe usuários, categorias, retenção e engajamento com uma leitura mais operacional da plataforma.
                                </p>
                                <ul class="mt-8 space-y-4 text-sm text-stone-300">
                                    <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-orange-400"></span><span>Dashboard admin com engajamento de 7 e 30 dias</span></li>
                                    <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span><span>Gestão de usuários com ativação e desativação de conta</span></li>
                                    <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-sky-400"></span><span>Relatórios por período e visão de categorias mais populares</span></li>
                                </ul>
                            </div>

                            <div class="rounded-[2rem] border border-white/10 bg-white/[0.03] p-8">
                                <p class="text-sm uppercase tracking-[0.24em] text-stone-500">api sanctum</p>
                                <h2 class="mt-4 font-['Outfit'] text-3xl font-semibold text-white">Integração pronta para mobile e serviços externos</h2>
                                <p class="mt-4 max-w-xl text-sm leading-7 text-stone-300">
                                    O projeto já expõe endpoints autenticados para listar hábitos, registrar check-ins e consultar estatísticas agregadas.
                                </p>
                                <div class="mt-8 space-y-3 font-mono text-sm text-stone-200">
                                    <div class="rounded-2xl border border-white/8 bg-stone-950 px-4 py-3">GET <span class="text-orange-300">/api/habits</span></div>
                                    <div class="rounded-2xl border border-white/8 bg-stone-950 px-4 py-3">POST <span class="text-emerald-300">/api/checkins</span></div>
                                    <div class="rounded-2xl border border-white/8 bg-stone-950 px-4 py-3">GET <span class="text-sky-300">/api/stats</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="border-t border-white/10 bg-stone-900 px-6 py-16 lg:px-8">
                    <div class="mx-auto flex max-w-7xl flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-2xl">
                            <p class="text-sm uppercase tracking-[0.24em] text-stone-500">pronto para começar</p>
                            <h2 class="mt-4 font-['Outfit'] text-4xl font-semibold tracking-tight text-white">
                                Construa uma rotina que continue fazendo sentido depois do entusiasmo inicial.
                            </h2>
                        </div>

                        <div class="flex flex-wrap gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="rounded-full bg-orange-500 px-6 py-3 text-sm font-semibold text-stone-950 transition hover:bg-orange-400">
                                    Abrir HabitFlow
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="rounded-full bg-orange-500 px-6 py-3 text-sm font-semibold text-stone-950 transition hover:bg-orange-400">
                                    Criar conta grátis
                                </a>
                                <a href="{{ route('login') }}" class="rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-stone-100 transition hover:border-white/30 hover:bg-white/5">
                                    Entrar
                                </a>
                            @endauth
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
