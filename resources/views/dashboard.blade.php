<x-app-layout>
    <x-slot name="hideShellNav">true</x-slot>
    <x-slot name="hideShellHeader">true</x-slot>

    @php
        $nextLevelXp = $game['next_level_xp'];
        $currentLevelXp = $game['current_level_xp'];
        $levelProgress = $game['level_progress_percent'];
        $remainingXp = max(0, $nextLevelXp - $currentLevelXp);
        $sidebarItems = [
            ['label' => 'Cockpit', 'href' => route('dashboard'), 'active' => true, 'mark' => 'C'],
            ['label' => 'Missões', 'href' => route('habits.index'), 'active' => false, 'mark' => 'M'],
            ['label' => 'Chefes', 'href' => route('goals.index'), 'active' => false, 'mark' => 'B'],
            ['label' => 'Relatório', 'href' => route('reports.index'), 'active' => false, 'mark' => 'R'],
            ['label' => 'Conquistas', 'href' => '#conquistas', 'active' => false, 'mark' => 'Q'],
            ['label' => 'Herói', 'href' => route('profile.edit'), 'active' => false, 'mark' => 'H'],
            ['label' => 'Guilda', 'href' => auth()->user()?->isAdmin() ? route('admin.dashboard') : route('home'), 'active' => false, 'mark' => 'G'],
            ['label' => 'Loja', 'href' => '#recompensas', 'active' => false, 'mark' => 'L'],
            ['label' => 'Configurações', 'href' => route('profile.edit'), 'active' => false, 'mark' => 'S'],
        ];
    @endphp

    <div class="hf-game-shell">
        <div class="grid min-h-screen lg:grid-cols-[17rem_minmax(0,1fr)]">
            <aside class="hf-game-sidebar hidden border-r px-4 py-6 lg:flex lg:flex-col">
                <a href="{{ route('home') }}" class="mb-8 flex flex-col items-center text-center">
                    <div class="relative flex h-24 w-24 items-center justify-center rounded-full border border-cyan-300/35 bg-cyan-300/[0.06] shadow-[0_0_38px_rgba(34,211,238,0.18)]">
                        <span class="font-['Outfit'] text-4xl font-black text-white">HF</span>
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
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md border border-cyan-300/20 bg-slate-950/70 font-['Outfit'] text-xs font-black text-cyan-200">
                                {{ $item['mark'] }}
                            </span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="mt-auto space-y-4 pt-8">
                    <div class="hf-game-card p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-14 w-14 items-center justify-center rounded-lg border border-cyan-300/25 bg-cyan-300/10 font-['Outfit'] text-lg font-black text-white">
                                {{ $game['avatar_initials'] }}
                            </div>
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-400">Herói da Disciplina</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-xs text-slate-400">
                            <span>Nível {{ $game['level'] }}</span>
                            <span>{{ $currentLevelXp }} / {{ $nextLevelXp }} XP</span>
                        </div>
                        <div class="hf-game-progress mt-2 h-2">
                            <div class="hf-game-progress-fill" style="width: {{ $levelProgress }}%"></div>
                        </div>
                    </div>

                    <div class="hf-game-card hf-game-card-purple p-4">
                        <p class="text-xs uppercase tracking-[0.22em] text-fuchsia-200/75">Combo atual</p>
                        <p class="mt-2 font-['Outfit'] text-4xl font-black text-fuchsia-200">{{ $game['best_streak'] }}</p>
                        <p class="text-xs text-fuchsia-100/75">melhor streak</p>
                    </div>
                </div>
            </aside>

            <main class="min-w-0 px-4 py-5 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-[100rem] space-y-5">
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

                    <header class="grid gap-5 xl:grid-cols-[1fr_0.92fr] xl:items-center">
                        <div>
                            <p class="text-xs uppercase tracking-[0.32em] text-cyan-200/80">Cockpit de Missão</p>
                            <h1 class="mt-2 font-['Outfit'] text-4xl font-black leading-none text-white sm:text-5xl">
                                Dashboard
                            </h1>
                            <p class="mt-2 text-base text-slate-300">
                                Transforme sua rotina em <span class="font-semibold text-cyan-200">progresso.</span>
                            </p>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-[1fr_1fr_auto]">
                            <div class="hf-game-card hf-game-card-cyan p-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-xl border border-cyan-300/35 bg-cyan-300/10 font-['Outfit'] text-2xl font-black text-white">
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

                            <div class="hf-game-card hf-game-card-purple p-4">
                                <p class="text-xs uppercase tracking-[0.22em] text-fuchsia-200/75">Próximo nível</p>
                                <p class="mt-2 font-semibold text-fuchsia-100">Mestre da Constância</p>
                                <p class="mt-1 text-xs text-slate-400">{{ $remainingXp }} XP restantes</p>
                            </div>

                            <div class="hf-game-card hidden h-full w-20 place-items-center rounded-full p-3 sm:grid">
                                <div class="relative flex h-12 w-12 items-center justify-center rounded-full border border-cyan-300/20 bg-cyan-300/10 text-cyan-100">
                                    <span class="font-['Outfit'] text-lg font-black">3</span>
                                </div>
                            </div>
                        </div>
                    </header>

                    <section class="grid gap-5 xl:grid-cols-[1.08fr_1fr]">
                        <div class="hf-game-card overflow-hidden p-6">
                            <div class="grid gap-6 md:grid-cols-[1fr_13rem] md:items-center">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">Campanha de hoje</p>
                                    <h2 class="mt-4 font-['Outfit'] text-4xl font-black leading-none text-white">
                                        {{ $game['quest_done'] }} de {{ $game['quest_total'] }} missões concluídas
                                    </h2>
                                    <p class="mt-4 max-w-md text-sm leading-7 text-slate-400">
                                        Recompensas prontas, combos em risco e dados atualizados.
                                    </p>
                                </div>
                                <div class="hidden h-44 items-center justify-center rounded-full border border-cyan-300/15 bg-cyan-300/[0.035] md:flex">
                                    <div class="flex h-28 w-28 items-center justify-center rounded-2xl border border-cyan-300/30 bg-slate-950/80 font-['Outfit'] text-4xl font-black text-cyan-100 shadow-[0_0_34px_rgba(34,211,238,0.18)]">
                                        {{ $game['quest_done'] }}/{{ $game['quest_total'] }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <div class="mb-2 flex items-center justify-between gap-3 text-xs text-slate-400">
                                    <span>Progresso das missões</span>
                                    <span>{{ $game['quest_progress_percent'] }}%</span>
                                </div>
                                <div class="hf-game-progress">
                                    <div class="hf-game-progress-fill" style="width: {{ $game['quest_progress_percent'] }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <div class="hf-game-card hf-game-card-cyan p-5">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Nível</p>
                                <p class="mt-3 font-['Outfit'] text-4xl font-black text-white">{{ $game['level'] }}</p>
                                <p class="mt-1 text-xs font-semibold text-cyan-200">{{ $game['rank'] }}</p>
                            </div>
                            <div class="hf-game-card hf-game-card-amber p-5">
                                <p class="text-xs uppercase tracking-[0.2em] text-amber-200/80">Moedas</p>
                                <p class="mt-3 font-['Outfit'] text-4xl font-black text-amber-300">{{ $game['coins'] }}</p>
                                <p class="mt-1 text-xs text-slate-400">para recompensas</p>
                            </div>
                            <div class="hf-game-card hf-game-card-purple p-5">
                                <p class="text-xs uppercase tracking-[0.2em] text-fuchsia-200/80">Combo</p>
                                <p class="mt-3 font-['Outfit'] text-4xl font-black text-fuchsia-200">{{ $game['best_streak'] }}</p>
                                <p class="mt-1 text-xs text-slate-400">melhor streak</p>
                            </div>
                            <div class="hf-game-card p-4">
                                <p class="text-xs uppercase tracking-wide text-slate-500">Consistência</p>
                                <p class="mt-2 font-['Outfit'] text-3xl font-black text-cyan-300">{{ $consistencyRate }}%</p>
                                <p class="text-xs text-slate-400">30 dias</p>
                            </div>
                            <div class="hf-game-card p-4">
                                <p class="text-xs uppercase tracking-wide text-slate-500">Check-ins hoje</p>
                                <p class="mt-2 font-['Outfit'] text-3xl font-black text-white">{{ $checkinsToday }}</p>
                                <p class="text-xs text-slate-400">de {{ $todayHabits->count() }} missões</p>
                            </div>
                            <div class="hf-game-card p-4">
                                <p class="text-xs uppercase tracking-wide text-slate-500">XP total</p>
                                <p class="mt-2 font-['Outfit'] text-3xl font-black text-fuchsia-200">{{ $game['total_xp'] }}</p>
                                <p class="text-xs text-slate-400">{{ $currentLevelXp }}/{{ $nextLevelXp }} no nível</p>
                            </div>
                        </div>
                    </section>

                    <section class="grid gap-5 xl:grid-cols-[1.25fr_0.92fr]">
                        <div class="hf-game-card p-5">
                            <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">Missões de hoje</p>
                                    <h2 class="font-['Outfit'] text-2xl font-black text-white">Missões de hoje</h2>
                                </div>
                                <a href="{{ route('habits.index') }}" class="text-sm text-cyan-200 hover:text-cyan-100">Ver todas</a>
                            </div>

                            @if($game['quests']->isEmpty())
                                <p class="py-6 text-center text-sm text-stone-400">
                                    Nenhuma missão ativa.
                                    <a href="{{ route('habits.create') }}" class="text-cyan-200 hover:underline">Criar uma Missão</a>
                                </p>
                            @else
                                <div class="grid gap-4 md:grid-cols-2">
                                    @foreach($game['quests']->take(6) as $quest)
                                        <div class="rounded-xl border border-white/10 bg-white/[0.035] p-4 shadow-[inset_3px_0_0_0_var(--quest-color)]" style="--quest-color: {{ $quest['color'] }}">
                                            <div class="flex items-start gap-4">
                                                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl border border-cyan-300/20 bg-slate-950/70 font-['Outfit'] text-xl font-black text-cyan-100">
                                                    {{ mb_substr($quest['name'], 0, 1) }}
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

                                            <div class="mt-4 flex items-center justify-between gap-3">
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

                        <div class="space-y-5">
                            <section class="hf-game-card p-5">
                                <h2 class="font-['Outfit'] text-xl font-black text-white">Relatório dos últimos 7 dias</h2>
                                <div class="mt-4">
                                    <canvas id="checkinChart" height="120"></canvas>
                                </div>
                            </section>

                            @if($topStreaks->isNotEmpty())
                                <section class="hf-game-card p-5">
                                    <h2 class="font-['Outfit'] text-xl font-black text-white">Top combos ativos</h2>
                                    <div class="mt-4 space-y-3">
                                        @foreach($topStreaks as $i => $habit)
                                            @php $streak = $habit->currentStreak(); @endphp
                                            <div class="flex items-center gap-3 rounded-xl border border-cyan-300/10 bg-cyan-300/[0.045] p-3">
                                                <span class="text-2xl font-black text-cyan-200">#{{ $i + 1 }}</span>
                                                <div class="min-w-0 flex-1">
                                                    <p class="truncate text-sm font-semibold text-white">{{ $habit->name }}</p>
                                                    <p class="text-xs font-medium text-cyan-300">{{ $streak }} {{ $streak === 1 ? 'dia' : 'dias' }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                        </div>
                    </section>

                    <section class="hf-game-card p-5">
                        <h2 class="mb-1 font-['Outfit'] text-xl font-black text-white">Atividade do último ano</h2>
                        <p class="mb-4 text-xs text-stone-400">Cada célula representa um dia; mais escuro significa mais check-ins</p>

                        @php
                            $heatStart = today()->subWeeks(51)->startOfWeek(\Carbon\Carbon::MONDAY);
                            $weeks = collect(range(0, 51))->map(function ($w) use ($heatStart, $heatmapData) {
                                return collect(range(0, 6))->map(function ($d) use ($heatStart, $w, $heatmapData) {
                                    $date = $heatStart->copy()->addWeeks($w)->addDays($d);
                                    $count = $heatmapData->get($date->toDateString(), 0);
                                    $future = $date->isAfter(today());
                                    return compact('date', 'count', 'future');
                                });
                            });

                            $cellColor = function (int $count, bool $future): string {
                                if ($future) return 'bg-stone-900/60 ring-1 ring-white/5';
                                if ($count === 0) return 'bg-stone-800 ring-1 ring-white/5';
                                if ($count === 1) return 'bg-cyan-300/50 shadow-[0_0_10px_rgba(103,232,249,0.28)]';
                                if ($count <= 3) return 'bg-cyan-400 shadow-[0_0_12px_rgba(34,211,238,0.35)]';
                                if ($count <= 5) return 'bg-fuchsia-400 shadow-[0_0_14px_rgba(217,70,239,0.35)]';
                                return 'bg-pink-400 shadow-[0_0_16px_rgba(244,114,182,0.42)]';
                            };

                            $dayLabels = ['Seg', '', 'Qua', '', 'Sex', '', 'Dom'];
                        @endphp

                        <div class="flex gap-1 overflow-x-auto pb-2">
                            <div class="mr-1 flex shrink-0 flex-col gap-1">
                                <div class="h-3"></div>
                                @foreach($dayLabels as $label)
                                    <div class="flex h-3 items-center">
                                        <span class="w-6 text-[9px] text-stone-500">{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>

                            @foreach($weeks as $week)
                                <div class="flex shrink-0 flex-col gap-1">
                                    @php $firstDay = $week->first()['date']; @endphp
                                    <div class="flex h-3 items-center">
                                        @if($firstDay->day <= 7)
                                            <span class="whitespace-nowrap text-[9px] text-stone-500">
                                                {{ $firstDay->translatedFormat('M') }}
                                            </span>
                                        @endif
                                    </div>
                                    @foreach($week as $cell)
                                        <div class="h-3 w-3 rounded-sm {{ $cellColor($cell['count'], $cell['future']) }}"
                                             title="{{ $cell['date']->format('d/m/Y') }}{{ $cell['count'] > 0 ? ' - ' . $cell['count'] . ' check-in(s)' : '' }}">
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3 flex items-center gap-1">
                            <span class="mr-1 text-xs text-stone-500">Menos</span>
                            <div class="h-3 w-3 rounded-sm bg-stone-800 ring-1 ring-white/5"></div>
                            <div class="h-3 w-3 rounded-sm bg-cyan-300/50"></div>
                            <div class="h-3 w-3 rounded-sm bg-cyan-400"></div>
                            <div class="h-3 w-3 rounded-sm bg-fuchsia-400"></div>
                            <div class="h-3 w-3 rounded-sm bg-pink-400"></div>
                            <span class="ml-1 text-xs text-stone-500">Mais</span>
                        </div>
                    </section>

                    <p class="pb-2 text-center font-['Outfit'] text-sm tracking-[0.12em] text-cyan-200/80">
                        Pequenas ações diárias. Grandes conquistas eternas.
                    </p>
                </div>
            </main>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        new Chart(document.getElementById('checkinChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! $chartLabels->toJson() !!},
                datasets: [{
                    label: 'Check-ins',
                    data: {!! $chartData->toJson() !!},
                    backgroundColor: 'rgba(34, 211, 238, 0.72)',
                    borderColor: 'rgba(103, 232, 249, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0, color: 'rgba(203,213,225,0.72)' },
                        grid: { color: 'rgba(148, 163, 184, 0.08)' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: 'rgba(203,213,225,0.72)' }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
