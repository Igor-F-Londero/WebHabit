<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Outfit'] text-2xl font-semibold leading-tight text-white">
            Relatório
        </h2>
    </x-slot>

    @php
        $recentAchievements = collect($game['recent_achievements']);
        $recentActivity = collect($game['recent_activity']);
    @endphp

    <div class="hf-page">
        <div class="mx-auto max-w-[100rem] space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Filtro de período --}}
            <div class="hf-panel-pad p-5">
                <form method="GET" action="{{ route('reports.index') }}"
                      x-data="{ period: '{{ $period }}' }"
                      class="flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-end">

                    <div>
                        <label class="mb-1 block text-xs font-medium text-stone-400">Período</label>
                        <select name="period" x-model="period"
                                class="hf-select text-sm">
                            <option value="7">Últimos 7 dias</option>
                            <option value="30">Últimos 30 dias</option>
                            <option value="90">Últimos 90 dias</option>
                            <option value="custom">Intervalo customizado</option>
                        </select>
                    </div>

                    <div x-show="period === 'custom'" class="flex flex-col gap-2 sm:flex-row sm:items-end">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-stone-400">De</label>
                            <input type="date" name="start"
                                   value="{{ $period === 'custom' ? $startDate->toDateString() : '' }}"
                                   class="hf-input text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-stone-400">Até</label>
                            <input type="date" name="end"
                                   value="{{ $period === 'custom' ? $endDate->toDateString() : '' }}"
                                   class="hf-input text-sm">
                        </div>
                    </div>

                    <button type="submit"
                            class="rounded-full bg-cyan-300 px-4 py-2 text-sm text-slate-950 transition hover:bg-cyan-200">
                        Filtrar
                    </button>
                </form>
            </div>

            {{-- Cards de resumo --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="hf-panel-pad p-5">
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">Período</p>
                    <p class="text-lg font-bold text-white">{{ $totalDays }} dias</p>
                    <p class="mt-1 text-xs text-stone-400">
                        {{ $startDate->format('d/m/Y') }} → {{ $endDate->format('d/m/Y') }}
                    </p>
                </div>
                <div class="hf-panel-pad p-5">
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">Check-ins realizados</p>
                    <p class="text-3xl font-bold text-cyan-300">{{ $totalCheckins }}</p>
                </div>
                <div class="hf-panel-pad p-5">
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">Taxa média</p>
                    <p class="text-3xl font-bold {{ $avgRate >= 70 ? 'text-cyan-300' : ($avgRate >= 40 ? 'text-fuchsia-300' : 'text-rose-300') }}">
                        {{ $avgRate }}%
                    </p>
                    <p class="mt-1 text-xs text-stone-400">entre todas as missões</p>
                </div>
            </div>

            <section class="grid gap-5 xl:grid-cols-[0.85fr_1.15fr]">
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
            </section>

            <section class="grid gap-5 xl:grid-cols-2">
                <section class="hf-game-card p-5">
                    <div class="mb-4">
                        <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">Linha do tempo</p>
                        <h2 class="font-['Outfit'] text-xl font-black text-white">Atividade Recente</h2>
                    </div>

                    @if($recentActivity->isEmpty())
                        <p class="rounded-xl border border-cyan-300/10 bg-cyan-300/[0.035] p-4 text-sm text-slate-400">
                            Complete missões ou resgate recompensas para iniciar o histórico do Herói.
                        </p>
                    @else
                        <div class="space-y-3">
                            @foreach($recentActivity as $activity)
                                <div class="hf-activity-card {{ $activity['tone'] === 'amber' ? 'hf-activity-card-amber' : '' }}">
                                    <div class="hf-activity-icon {{ $activity['tone'] === 'amber' ? 'text-amber-200' : 'text-cyan-200' }}">
                                        <x-habitflow.icon :name="$activity['icon'] ?? 'mission'" />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <p class="truncate text-sm font-semibold text-white">{{ $activity['title'] }}</p>
                                            <span class="text-[11px] text-slate-500">{{ $activity['time'] }}</span>
                                        </div>
                                        <p class="mt-1 truncate text-xs text-slate-500">{{ $activity['description'] }}</p>
                                    </div>
                                    <span class="shrink-0 text-xs font-bold {{ $activity['tone'] === 'amber' ? 'text-amber-200' : 'text-cyan-200' }}">
                                        {{ $activity['meta'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                <section id="conquistas" class="hf-game-card p-5">
                    <div class="mb-4">
                        <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">Conquistas recentes</p>
                        <h2 class="font-['Outfit'] text-xl font-black text-white">Conquistas Recentes</h2>
                    </div>

                    <div class="space-y-3">
                        @foreach($recentAchievements as $achievement)
                            <div class="hf-achievement-card">
                                <div class="hf-achievement-medal {{ $achievement['unlocked'] ? 'border-cyan-300/35 bg-cyan-300/10 text-cyan-100' : 'border-slate-700 bg-slate-900 text-slate-500' }}">
                                    <x-habitflow.icon :name="$achievement['icon'] ?? 'trophy'" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-white">{{ $achievement['name'] }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $achievement['description'] }}</p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="text-sm font-bold {{ $achievement['unlocked'] ? 'text-cyan-200' : 'text-slate-500' }}">+{{ $achievement['xp'] }} XP</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $achievement['date'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
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
