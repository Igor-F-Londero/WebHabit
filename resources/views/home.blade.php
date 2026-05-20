<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">hub operacional</p>
                <h2 class="font-['Outfit'] text-3xl font-black leading-tight text-white">
                    Escolha sua próxima missão
                </h2>
            </div>
            <p class="text-sm text-slate-400">
                {{ now()->translatedFormat('d M Y') }}
            </p>
        </div>
    </x-slot>

    @php
        $cards = [
            [
                'title' => 'Cockpit',
                'label' => 'Dashboard',
                'copy' => 'Resumo diário, streaks, check-ins e mapa de atividade.',
                'href' => route('dashboard'),
                'image' => 'images/home-cards/cockpit.svg',
                'metric' => $checkinsToday . ' check-ins hoje',
            ],
            [
                'title' => 'Hábitos',
                'label' => 'Missões recorrentes',
                'copy' => 'Crie, edite e conclua hábitos diários ou semanais.',
                'href' => route('habits.index'),
                'image' => 'images/home-cards/habits.svg',
                'metric' => $activeHabits . ' ativos',
            ],
            [
                'title' => 'Metas',
                'label' => 'Alvos de progresso',
                'copy' => 'Acompanhe objetivos com prazo e contagem de execução.',
                'href' => route('goals.index'),
                'image' => 'images/home-cards/goals.svg',
                'metric' => $activeGoals . ' em andamento',
            ],
            [
                'title' => 'Relatórios',
                'label' => 'Leitura tática',
                'copy' => 'Veja padrões, taxa de consistência e sinais de queda.',
                'href' => route('reports.index'),
                'image' => 'images/home-cards/reports.svg',
                'metric' => $bestStreak . ' dias no melhor streak',
            ],
            [
                'title' => 'Perfil',
                'label' => 'Identidade',
                'copy' => 'Atualize dados, senha e preferências da sua conta.',
                'href' => route('profile.edit'),
                'image' => 'images/home-cards/profile.svg',
                'metric' => auth()->user()->name,
            ],
        ];

        if (auth()->user()?->isAdmin()) {
            $cards[] = [
                'title' => 'Operação',
                'label' => 'Admin',
                'copy' => 'Gerencie usuários, categorias e relatórios da plataforma.',
                'href' => route('admin.dashboard'),
                'image' => 'images/home-cards/admin.svg',
                'metric' => 'modo admin',
            ];
        }
    @endphp

    <div class="hf-page">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 grid gap-4 md:grid-cols-4">
                <div class="hf-panel-pad md:col-span-2">
                    <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">status do agente</p>
                    <h3 class="mt-3 break-words font-['Outfit'] text-2xl font-black text-white sm:text-3xl">
                        {{ auth()->user()->name }}, o sistema está pronto.
                    </h3>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-400">
                        Use os cards abaixo como atalhos visuais para navegar entre execução, criação de hábitos, metas e leitura de performance.
                    </p>
                </div>
                <div class="hf-panel-pad">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">check-ins hoje</p>
                    <p class="mt-4 font-['Outfit'] text-4xl font-black text-cyan-300">{{ $checkinsToday }}</p>
                </div>
                <div class="hf-panel-pad">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">melhor streak</p>
                    <p class="mt-4 font-['Outfit'] text-4xl font-black text-pink-300">{{ $bestStreak }}</p>
                </div>
            </div>

            <div class="grid auto-rows-[17.5rem] gap-4 sm:auto-rows-[19rem] md:grid-cols-2">
                @foreach($cards as $index => $card)
                    <a
                        href="{{ $card['href'] }}"
                        class="group relative overflow-hidden rounded-[1.75rem] border border-white/10 bg-black shadow-2xl shadow-black/30 outline-none transition duration-300 hover:-translate-y-1 hover:border-cyan-300/40 hover:shadow-[0_0_48px_rgba(34,211,238,0.18)] focus-visible:ring-2 focus-visible:ring-cyan-300 {{ $index === 0 ? 'md:row-span-2' : '' }}"
                    >
                        <img
                            src="{{ asset($card['image']) }}"
                            alt=""
                            class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
                        <div class="absolute inset-0 bg-black/10 transition group-hover:bg-black/0"></div>

                        <div class="relative flex h-full flex-col justify-between p-5 sm:p-6">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <span class="rounded-full border border-white/15 bg-black/40 px-3 py-1 text-xs uppercase tracking-[0.18em] text-white/80 backdrop-blur">
                                    {{ $card['label'] }}
                                </span>
                                <span class="max-w-full truncate rounded-full border border-cyan-300/30 bg-cyan-300/10 px-3 py-1 text-xs font-semibold text-cyan-100 backdrop-blur sm:max-w-[12rem]">
                                    {{ $card['metric'] }}
                                </span>
                            </div>

                            <div>
                                <h3 class="font-['Outfit'] text-4xl font-medium leading-none text-white drop-shadow-[0_0_18px_rgba(0,0,0,0.55)] sm:text-5xl">
                                    {{ $card['title'] }}
                                </h3>
                                <p class="mt-3 max-w-md text-sm leading-6 text-zinc-200 opacity-90">
                                    {{ $card['copy'] }}
                                </p>
                                <div class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-cyan-100">
                                    Acessar
                                    <span class="transition group-hover:translate-x-1">→</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
