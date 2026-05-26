<x-app-layout>
    <x-slot name="hideShellNav">true</x-slot>
    <x-slot name="hideShellHeader">true</x-slot>

    @php
        $nextLevelXp = $game['next_level_xp'];
        $currentLevelXp = $game['current_level_xp'];
        $levelProgress = $game['level_progress_percent'];
        $remainingXp = max(0, $nextLevelXp - $currentLevelXp);
        $boss = $game['weekly_boss'];
        $rewards = collect($game['rewards']);
        $sidebarItems = [
            ['label' => 'Cockpit', 'href' => route('dashboard'), 'active' => true, 'icon' => 'compass'],
            ['label' => 'Missões', 'href' => route('habits.index'), 'active' => false, 'icon' => 'mission'],
            ['label' => 'Chefes', 'href' => route('goals.index'), 'active' => false, 'icon' => 'boss'],
            ['label' => 'Relatório', 'href' => route('reports.index'), 'active' => false, 'icon' => 'chart'],
            ['label' => 'Conquistas', 'href' => route('reports.index').'#conquistas', 'active' => false, 'icon' => 'trophy'],
            ['label' => 'Herói', 'href' => route('profile.edit'), 'active' => false, 'icon' => 'hero'],
            ['label' => 'Guilda', 'href' => auth()->user()?->isAdmin() ? route('admin.dashboard') : route('home'), 'active' => false, 'icon' => 'guild'],
            ['label' => 'Loja', 'href' => '#recompensas', 'active' => false, 'icon' => 'shop'],
            ['label' => 'Configurações', 'href' => route('profile.edit'), 'active' => false, 'icon' => 'settings'],
        ];
        $mobileNavItems = collect($sidebarItems)->only([0, 1, 2, 3, 5]);
    @endphp

    <div class="hf-game-shell">
        <div class="grid min-h-screen lg:grid-cols-[17rem_minmax(0,1fr)]">
            <aside class="hf-game-sidebar hidden border-r px-4 py-6 lg:flex lg:flex-col">
                <a href="{{ route('home') }}" class="mb-8 flex flex-col items-center text-center">
                    <div class="relative flex h-24 w-24 items-center justify-center overflow-hidden rounded-full border border-cyan-300/35 bg-cyan-300/[0.06] shadow-[0_0_38px_rgba(34,211,238,0.18)]">
                        <img
                            src="{{ asset('images/9c2cb117cadfb7bc8b91d332705effc4.jpg') }}"
                            alt="Herói do HabitFlow"
                            class="h-full w-full object-cover object-top"
                        >
                        <span class="absolute inset-0 rounded-full bg-[radial-gradient(circle_at_35%_20%,transparent_0%,transparent_45%,rgba(2,6,23,0.34)_100%)]"></span>
                        <span class="absolute -top-1 h-3 w-3 rotate-45 border border-cyan-200 bg-slate-950"></span>
                        <span class="absolute -bottom-1 h-3 w-3 rotate-45 border border-cyan-200 bg-slate-950"></span>
                    </div>
                    <span class="mt-3 font-['Outfit'] text-2xl font-semibold text-white">HabitFlow</span>
                    <span class="text-xs uppercase tracking-[0.36em] text-cyan-200/70">Guilda</span>
                </a>

                <nav class="space-y-2">
                    @foreach($sidebarItems as $item)
                        <a
                            href="{{ $item['href'] }}"
                            class="group flex items-center gap-3 rounded-lg border px-4 py-3 text-sm transition {{ $item['active'] ? 'border-cyan-300/60 bg-cyan-300/[0.12] text-cyan-100 shadow-[0_0_22px_rgba(34,211,238,0.14)]' : 'border-transparent text-slate-400 hover:border-cyan-300/20 hover:bg-cyan-300/[0.055] hover:text-cyan-100' }}"
                        >
                            <span class="hf-sidebar-icon {{ $item['active'] ? 'text-cyan-100' : 'text-slate-400 group-hover:text-cyan-100' }}">
                                <x-habitflow.icon :name="$item['icon']" />
                            </span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

            </aside>

            <main class="min-w-0 px-4 pb-24 pt-4 sm:px-6 lg:px-6 lg:pb-4">
                <div class="mx-auto max-w-[100rem] space-y-4">
                    <div class="flex items-center justify-between gap-3 lg:hidden">
                        <a href="{{ route('home') }}" class="flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-lg border border-cyan-300/25 bg-cyan-300/10 font-['Outfit'] font-black text-white">HF</span>
                            <span>
                                <span class="block font-['Outfit'] text-lg font-semibold text-white">HabitFlow</span>
                                <span class="block text-xs uppercase tracking-[0.24em] text-cyan-200/65">Guilda</span>
                            </span>
                        </a>
                        <a href="{{ route('home') }}" class="rounded-lg border border-cyan-300/20 px-3 py-2 text-xs font-semibold text-cyan-100">Guilda</a>
                    </div>

                    @if(session('success'))
                        <div class="hf-alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="hf-alert-error">
                            {{ session('error') }}
                        </div>
                    @endif

                    <header class="grid gap-4 xl:grid-cols-[1fr_0.92fr] xl:items-center">
                        <div>
                            <p class="text-xs uppercase tracking-[0.32em] text-cyan-200/80">Cockpit de Missão</p>
                            <h1 class="mt-1 font-['Outfit'] text-3xl font-black leading-none text-white sm:text-4xl">
                                Dashboard
                            </h1>
                            <p class="mt-1 text-sm text-slate-300">
                                Transforme sua rotina em <span class="font-semibold text-cyan-200">progresso.</span>
                            </p>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="hf-game-card hf-game-card-cyan p-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl border border-cyan-300/35 bg-cyan-300/10 font-['Outfit'] text-xl font-black text-white">
                                        {{ $game['level'] }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs uppercase tracking-[0.22em] text-cyan-200/75">Nível atual</p>
                                        <p class="truncate font-semibold text-white">{{ $game['rank'] }}</p>
                                        <div class="mt-2 flex items-center gap-3">
                                            <div class="hf-game-progress h-2 flex-1">
                                                <div class="h-full rounded-full bg-cyan-300" style="width: {{ $levelProgress }}%"></div>
                                            </div>
                                            <span class="text-xs text-slate-300">{{ $currentLevelXp }} / {{ $nextLevelXp }} XP</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="hf-game-card hf-game-card-purple p-3">
                                <p class="text-xs uppercase tracking-[0.22em] text-fuchsia-200/75">Próximo nível</p>
                                <p class="mt-1 font-semibold text-fuchsia-100">Mestre da Constância</p>
                                <p class="mt-1 text-xs text-slate-400">{{ $remainingXp }} XP restantes</p>
                            </div>

                        </div>
                    </header>

                    <section class="grid gap-4 xl:grid-cols-[1.08fr_1fr] xl:items-start">
                        <div class="hf-game-card overflow-hidden p-4">
                            <div class="grid gap-4 md:grid-cols-[1fr_10rem] md:items-center">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">Campanha de hoje</p>
                                    <h2 class="mt-2 font-['Outfit'] text-3xl font-black leading-none text-white">
                                        {{ $game['quest_done'] }} de {{ $game['quest_total'] }} missões concluídas
                                    </h2>
                                    <p class="mt-2 max-w-md text-sm leading-6 text-slate-400">
                                        Recompensas prontas, combos em risco e dados atualizados.
                                    </p>
                                </div>
                                <div class="hidden h-32 items-center justify-center rounded-full border border-cyan-300/15 bg-cyan-300/[0.035] md:flex">
                                    <div class="relative flex h-20 w-20 items-center justify-center rounded-2xl border border-cyan-300/30 bg-slate-950/80 font-['Outfit'] text-2xl font-black text-cyan-100 shadow-[0_0_34px_rgba(34,211,238,0.18)]">
                                        <span class="absolute inset-3 text-cyan-300/20">
                                            <x-habitflow.icon name="guardian" />
                                        </span>
                                        <span class="relative z-10">{{ $game['quest_done'] }}/{{ $game['quest_total'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="mb-2 flex items-center justify-between gap-3 text-xs text-slate-400">
                                    <span>Progresso das missões</span>
                                    <span>{{ $game['quest_progress_percent'] }}%</span>
                                </div>
                                <div class="hf-game-progress">
                                    <div class="hf-game-progress-fill" style="width: {{ $game['quest_progress_percent'] }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid self-start gap-3 sm:grid-cols-2">
                            <div class="hf-game-card hf-game-card-amber hf-stat-card min-h-28 p-3">
                                <span class="hf-stat-icon text-amber-200"><x-habitflow.icon name="shop" /></span>
                                <p class="text-xs uppercase tracking-[0.2em] text-amber-200/80">Moedas</p>
                                <p class="mt-1 font-['Outfit'] text-3xl font-black text-amber-300">{{ $game['coins'] }}</p>
                                <p class="mt-1 text-xs text-slate-400">para recompensas</p>
                            </div>
                            <div class="hf-game-card hf-game-card-purple hf-stat-card min-h-28 p-3">
                                <span class="hf-stat-icon text-fuchsia-200"><x-habitflow.icon name="flame" /></span>
                                <p class="text-xs uppercase tracking-[0.2em] text-fuchsia-200/80">Combo</p>
                                <p class="mt-1 font-['Outfit'] text-3xl font-black text-fuchsia-200">{{ $game['best_streak'] }}</p>
                                <p class="mt-1 text-xs text-slate-400">melhor streak</p>
                            </div>
                            <div class="hf-game-card hf-stat-card min-h-28 p-3">
                                <span class="hf-stat-icon text-emerald-200"><x-habitflow.icon name="mission" /></span>
                                <p class="text-xs uppercase tracking-wide text-slate-500">Check-ins hoje</p>
                                <p class="mt-1 font-['Outfit'] text-3xl font-black text-white">{{ $checkinsToday }}</p>
                                <p class="text-xs text-slate-400">de {{ $todayHabits->count() }} missões</p>
                            </div>
                            <div class="hf-game-card hf-stat-card min-h-28 p-3">
                                <span class="hf-stat-icon text-fuchsia-200"><x-habitflow.icon name="spark" /></span>
                                <p class="text-xs uppercase tracking-wide text-slate-500">XP total</p>
                                <p class="mt-1 font-['Outfit'] text-3xl font-black text-fuchsia-200">{{ $game['total_xp'] }}</p>
                                <p class="text-xs text-slate-400">{{ $currentLevelXp }}/{{ $nextLevelXp }} no nível</p>
                            </div>
                        </div>
                    </section>

                    <section class="grid gap-4 xl:grid-cols-[1.05fr_0.95fr] xl:items-start">
                        <div class="hf-game-card hf-missions-panel self-start p-4">
                            <div class="mb-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">Missões de hoje</p>
                                    <h2 class="font-['Outfit'] text-xl font-black text-white">Missões de hoje</h2>
                                </div>
                                <a href="{{ route('habits.index') }}" class="text-sm text-cyan-200 hover:text-cyan-100">Ver todas</a>
                            </div>

                            @if($game['quests']->isEmpty())
                                <p class="py-6 text-center text-sm text-stone-400">
                                    Nenhuma missão ativa.
                                    <a href="{{ route('habits.create') }}" class="text-cyan-200 hover:underline">Criar uma Missão</a>
                                </p>
                            @else
                                <div class="grid gap-3 md:grid-cols-2">
                                    @foreach($game['quests']->take(6) as $quest)
                                        <div class="rounded-xl border border-white/10 bg-white/[0.035] p-3 shadow-[inset_3px_0_0_0_var(--quest-color)]" style="--quest-color: {{ $quest['color'] }}">
                                            <div class="flex items-start gap-3">
                                                <div class="hf-mission-icon" style="color: {{ $quest['color'] }}">
                                                    <x-habitflow.icon :name="$quest['icon'] ?? 'mission'" />
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        <p class="truncate text-sm font-semibold text-white">{{ $quest['name'] }}</p>
                                                        <span class="rounded-md bg-slate-900 px-2 py-0.5 text-[11px] font-semibold text-slate-300">{{ $quest['difficulty'] }}</span>
                                                    </div>
                                                    <p class="mt-1 text-xs text-slate-500">{{ $quest['category'] }} · {{ $quest['frequency'] }}</p>
                                                    <p class="mt-2 text-xs font-bold text-amber-200">+{{ $quest['reward_xp'] }} XP · +{{ $quest['reward_coins'] }} moedas</p>
                                                </div>
                                            </div>

                                            <div class="mt-3 flex items-center justify-between gap-3">
                                                <span class="text-xs font-medium text-fuchsia-200">Combo: {{ $quest['streak'] }}</span>
                                                @if($quest['completed'])
                                                    <span class="rounded-full border border-emerald-300/20 bg-emerald-300/10 px-3 py-1 text-xs font-semibold text-emerald-200">Concluída</span>
                                                @else
                                                    <form action="{{ route('checkins.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="habit_id" value="{{ $quest['id'] }}">
                                                        <button type="submit" class="rounded-full bg-cyan-300 px-3 py-1 text-xs font-bold text-slate-950 transition hover:bg-cyan-200">
                                                            Concluir
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <section class="hf-boss-card overflow-hidden p-4">
                                <div class="hf-boss-illustration">
                                    <img src="{{ asset('images/the-dragon-boss-is-massive-v0-a4qhhsi7z2041.webp') }}" alt="" aria-hidden="true">
                                </div>
                                <div class="relative z-10">
                                    <div class="mb-3 flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.24em] text-fuchsia-200/80">{{ $boss['subtitle'] }}</p>
                                            <h2 class="mt-2 font-['Outfit'] text-2xl font-black leading-tight text-white">
                                                {{ $boss['name'] }}
                                            </h2>
                                        </div>
                                        <div class="hf-boss-mark">
                                            <x-habitflow.icon :name="$boss['icon'] ?? 'dragon'" />
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Objetivo</p>
                                        <p class="mt-1 text-sm leading-6 text-slate-300">{{ $boss['objective'] }}</p>
                                    </div>

                                    <div class="mt-4">
                                        <div class="mb-2 flex items-center justify-between gap-3 text-xs text-slate-400">
                                            <span>Vida do chefe</span>
                                            <span>{{ $boss['progress_percent'] }}%</span>
                                        </div>
                                        <div class="flex gap-1">
                                            @foreach(range(1, 10) as $segment)
                                                <div class="h-3 flex-1 rounded-full {{ $segment <= ceil($boss['progress_percent'] / 10) ? 'bg-fuchsia-400 shadow-[0_0_12px_rgba(217,70,239,0.45)]' : 'bg-slate-900 ring-1 ring-white/5' }}"></div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                                            <span>{{ $boss['current'] }} / {{ $boss['target'] }} check-ins</span>
                                            <span>{{ $boss['days_remaining'] }} dias restantes</span>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Recompensa</p>
                                        <div class="mt-2 flex flex-wrap gap-2 text-sm font-semibold">
                                            <span class="rounded-full border border-fuchsia-300/20 bg-fuchsia-300/10 px-3 py-1 text-fuchsia-100">+{{ $boss['reward_xp'] }} XP</span>
                                            <span class="rounded-full border border-amber-300/20 bg-amber-300/10 px-3 py-1 text-amber-200">+{{ $boss['reward_coins'] }} moedas</span>
                                            <span class="rounded-full border border-cyan-300/20 bg-cyan-300/10 px-3 py-1 text-cyan-100">{{ $boss['reward_label'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section id="recompensas" class="hf-game-card hf-reward-shop p-4">
                                <div class="mb-3 flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.24em] text-amber-200/80">Loja de Recompensas</p>
                                        <h2 class="font-['Outfit'] text-lg font-black text-white">Recompensas</h2>
                                    </div>
                                    <div class="rounded-xl border border-amber-300/25 bg-amber-300/10 px-3 py-2 text-right">
                                        <p class="text-[10px] uppercase tracking-[0.18em] text-amber-100/70">Moedas</p>
                                        <p class="font-['Outfit'] text-2xl font-black text-amber-300">{{ $game['coins'] }}</p>
                                        <p class="text-[10px] text-amber-100/55">{{ $game['spent_coins'] }} gastas</p>
                                    </div>
                                </div>

                                <div class="grid gap-3 2xl:grid-cols-2">
                                    @foreach($rewards as $reward)
                                        <div class="hf-reward-card {{ $reward['available'] ? 'hf-reward-card-ready' : '' }}">
                                            <div class="hf-reward-icon {{ $reward['available'] ? 'text-amber-200' : 'text-slate-500' }}">
                                                <x-habitflow.icon :name="$reward['icon'] ?? 'shop'" />
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <p class="truncate text-sm font-semibold text-white">{{ $reward['name'] }}</p>
                                                    <span class="rounded-md border border-white/10 bg-slate-950/70 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-400">{{ $reward['tier'] }}</span>
                                                </div>
                                                <p class="mt-1 text-xs leading-5 text-slate-500">{{ $reward['description'] }}</p>
                                                <div class="hf-reward-progress mt-3">
                                                    <div class="hf-reward-progress-fill" style="width: {{ $reward['progress_percent'] }}%"></div>
                                                </div>
                                            </div>
                                            <div class="shrink-0 text-right">
                                                <p class="font-['Outfit'] text-xl font-black text-amber-300">{{ $reward['cost'] }}</p>
                                                <p class="text-[10px] uppercase tracking-[0.16em] text-amber-100/65">moedas</p>
                                                @if($reward['available'])
                                                    <form action="{{ route('rewards.redeem') }}" method="POST" class="mt-2">
                                                        @csrf
                                                        <input type="hidden" name="reward_key" value="{{ $reward['key'] }}">
                                                        <button type="submit" class="rounded-full border border-emerald-300/25 bg-emerald-300/10 px-2.5 py-1 text-[11px] font-bold text-emerald-200 transition hover:bg-emerald-300/20">
                                                            Resgatar
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="mt-2 inline-flex rounded-full border border-slate-700 bg-slate-900/80 px-2.5 py-1 text-[11px] font-bold text-slate-500">
                                                        Faltam {{ $reward['shortfall'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        </div>
                    </section>

                    <p class="hidden pb-1 text-center font-['Outfit'] text-xs tracking-[0.12em] text-cyan-200/80 2xl:block">
                        Pequenas ações diárias. Grandes conquistas eternas.
                    </p>
                </div>
            </main>
        </div>

        <nav class="hf-mobile-questbar lg:hidden" aria-label="Navegação rápida">
            @foreach($mobileNavItems as $item)
                <a
                    href="{{ $item['href'] }}"
                    class="hf-mobile-questbar-item {{ $item['active'] ? 'text-cyan-100' : 'text-slate-400' }}"
                    aria-current="{{ $item['active'] ? 'page' : 'false' }}"
                >
                    <span class="hf-mobile-questbar-icon {{ $item['active'] ? 'border-cyan-300/55 bg-cyan-300/15 text-cyan-100 shadow-[0_0_18px_rgba(34,211,238,0.22)]' : 'border-cyan-300/15 bg-slate-950 text-slate-400' }}">
                        <x-habitflow.icon :name="$item['icon']" />
                    </span>
                    <span class="text-[10px] font-semibold leading-none">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

</x-app-layout>
