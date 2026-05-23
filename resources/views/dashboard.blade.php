<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">cockpit de missão</p>
            <h2 class="font-['Outfit'] text-2xl font-semibold leading-tight text-white">
                Dashboard
            </h2>
        </div>
    </x-slot>

    <div class="hf-page">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

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

            <section class="hf-panel-pad overflow-hidden">
                <div class="grid gap-6 lg:grid-cols-[0.72fr_1.28fr] lg:items-center">
                    <div>
                        <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">campanha de hoje</p>
                        <h3 class="mt-3 font-['Outfit'] text-3xl font-black leading-tight text-white sm:text-4xl">
                            {{ $game['quest_done'] }} de {{ $game['quest_total'] }} missões concluídas
                        </h3>
                        <p class="mt-3 text-sm leading-7 text-slate-400">
                            Recompensas prontas, combos em risco e dados atualizados.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-xl border border-cyan-300/10 bg-cyan-300/[0.04] p-5">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">nível</p>
                            <p class="mt-3 font-['Outfit'] text-4xl font-black text-white">{{ $game['level'] }}</p>
                            <p class="mt-1 text-xs font-semibold text-cyan-200">{{ $game['rank'] }}</p>
                        </div>
                        <div class="rounded-xl border border-amber-300/10 bg-amber-300/[0.05] p-5">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">moedas</p>
                            <p class="mt-3 font-['Outfit'] text-4xl font-black text-amber-300">{{ $game['coins'] }}</p>
                            <p class="mt-1 text-xs text-slate-400">para recompensas</p>
                        </div>
                        <div class="rounded-xl border border-fuchsia-300/10 bg-fuchsia-300/[0.05] p-5">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">combo</p>
                            <p class="mt-3 font-['Outfit'] text-4xl font-black text-fuchsia-200">{{ $game['best_streak'] }}</p>
                            <p class="mt-1 text-xs text-slate-400">melhor streak</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="mb-2 flex items-center justify-between gap-3 text-xs text-slate-400">
                        <span>Progresso das missões</span>
                        <span>{{ $game['quest_progress_percent'] }}%</span>
                    </div>
                    <div class="h-3 overflow-hidden rounded-full bg-slate-900 ring-1 ring-white/10">
                        <div
                            class="h-full rounded-full bg-[linear-gradient(90deg,_#22d3ee,_#f472b6,_#fbbf24)] shadow-[0_0_20px_rgba(34,211,238,0.32)]"
                            style="width: {{ $game['quest_progress_percent'] }}%"
                        ></div>
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div class="hf-panel-pad">
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">Consistência (30 dias)</p>
                    <p class="text-3xl font-bold text-cyan-300">{{ $consistencyRate }}%</p>
                    <p class="mt-1 text-xs text-stone-400">hábitos diários concluídos</p>
                </div>
                <div class="hf-panel-pad">
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">Check-ins hoje</p>
                    <p class="text-3xl font-bold text-cyan-200">{{ $checkinsToday }}</p>
                    <p class="mt-1 text-xs text-stone-400">de {{ $todayHabits->count() }} hábito(s) ativo(s)</p>
                </div>
                <div class="hf-panel-pad">
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">XP total</p>
                    <p class="text-3xl font-bold text-white">{{ $game['total_xp'] }}</p>
                    <p class="mt-1 text-xs text-stone-400">{{ $game['current_level_xp'] }}/{{ $game['next_level_xp'] }} no nível</p>
                </div>
                <div class="hf-panel-pad">
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">Hábitos ativos</p>
                    <p class="text-3xl font-bold text-white">{{ $todayHabits->count() }}</p>
                    <p class="mt-1 text-xs text-stone-400">missões cadastradas</p>
                </div>
            </section>

            <section class="hf-panel-pad">
                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="font-semibold text-white">Missões de hoje</h3>
                    <a href="{{ route('habits.index') }}" class="text-sm text-cyan-200 hover:text-cyan-100">Ver todas</a>
                </div>

                @if($game['quests']->isEmpty())
                    <p class="py-6 text-center text-sm text-stone-400">
                        Nenhuma missão ativa.
                        <a href="{{ route('habits.create') }}" class="text-cyan-200 hover:underline">Criar um hábito</a>
                    </p>
                @else
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($game['quests'] as $quest)
                            <div class="hf-panel-soft flex min-h-[8rem] flex-col justify-between gap-4 rounded-2xl p-4">
                                <div class="flex items-start gap-3">
                                    <div class="h-12 w-2 shrink-0 rounded-full" style="background-color: {{ $quest['color'] }}"></div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="truncate text-sm font-semibold text-white">{{ $quest['name'] }}</p>
                                            <span class="rounded-full bg-slate-900 px-2 py-0.5 text-[11px] font-semibold text-slate-400">{{ $quest['difficulty'] }}</span>
                                        </div>
                                        <p class="mt-1 text-xs text-stone-500">{{ $quest['category'] }} · {{ $quest['frequency'] }}</p>
                                        <p class="mt-2 text-xs font-semibold text-amber-200">
                                            +{{ $quest['reward_xp'] }} XP · +{{ $quest['reward_coins'] }} moedas
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-xs {{ $quest['streak'] > 0 ? 'font-semibold text-fuchsia-200' : 'text-stone-500' }}">
                                        Combo: {{ $quest['streak'] }}
                                    </span>
                                    @if($quest['completed'])
                                        <span class="shrink-0 rounded-full bg-cyan-300/10 px-3 py-1 text-xs font-medium text-cyan-100">Concluída</span>
                                    @else
                                        <form action="{{ route('checkins.store') }}" method="POST" class="shrink-0">
                                            @csrf
                                            <input type="hidden" name="habit_id" value="{{ $quest['id'] }}">
                                            <button type="submit"
                                                    class="rounded-full bg-cyan-300 px-3 py-1 text-xs font-semibold text-slate-950 transition hover:bg-cyan-200">
                                                Concluir
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            @if($topStreaks->isNotEmpty())
                <section class="hf-panel-pad">
                    <h3 class="mb-4 font-semibold text-white">Top combos ativos</h3>
                    <div class="grid gap-4 sm:grid-cols-3">
                        @foreach($topStreaks as $i => $habit)
                            @php $streak = $habit->currentStreak(); @endphp
                            <div class="flex items-center gap-3 rounded-2xl border border-cyan-300/10 bg-cyan-300/10 p-3">
                                <span class="text-2xl font-black text-cyan-200">#{{ $i + 1 }}</span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-white">{{ $habit->name }}</p>
                                    <p class="text-xs font-medium text-cyan-300">
                                        {{ $streak }} {{ $streak === 1 ? 'dia' : 'dias' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="hf-panel-pad">
                <h3 class="mb-4 font-semibold text-white">Check-ins dos últimos 7 dias</h3>
                <canvas id="checkinChart" height="80"></canvas>
            </section>

            <section class="hf-panel-pad">
                <h3 class="mb-1 font-semibold text-white">Atividade do último ano</h3>
                <p class="mb-4 text-xs text-stone-400">Cada célula representa um dia; mais escuro significa mais check-ins</p>

                @php
                    $heatStart = today()->subWeeks(51)->startOfWeek(\Carbon\Carbon::MONDAY);
                    $weeks = collect(range(0, 51))->map(function ($w) use ($heatStart, $heatmapData) {
                        return collect(range(0, 6))->map(function ($d) use ($heatStart, $w, $heatmapData) {
                            $date   = $heatStart->copy()->addWeeks($w)->addDays($d);
                            $count  = $heatmapData->get($date->toDateString(), 0);
                            $future = $date->isAfter(today());
                            return compact('date', 'count', 'future');
                        });
                    });

                    $cellColor = function (int $count, bool $future): string {
                        if ($future)       return 'bg-stone-900/60 ring-1 ring-white/5';
                        if ($count === 0)  return 'bg-stone-800 ring-1 ring-white/5';
                        if ($count === 1)  return 'bg-cyan-300/50 shadow-[0_0_10px_rgba(103,232,249,0.28)]';
                        if ($count <= 3)   return 'bg-cyan-400 shadow-[0_0_12px_rgba(34,211,238,0.35)]';
                        if ($count <= 5)   return 'bg-fuchsia-400 shadow-[0_0_14px_rgba(217,70,239,0.35)]';
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
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { color: 'rgba(148, 163, 184, 0.08)' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
