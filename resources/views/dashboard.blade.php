<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">cockpit de execução</p>
            <h2 class="font-['Outfit'] text-2xl font-semibold leading-tight text-white">
                Dashboard
            </h2>
        </div>
    </x-slot>

    <div class="hf-page">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="rounded-2xl border border-cyan-300/20 bg-cyan-300/10 p-4 text-cyan-100">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-2xl border border-rose-400/20 bg-rose-400/10 p-4 text-rose-100">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Cards de resumo --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
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
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">Hábitos ativos</p>
                    <p class="text-3xl font-bold text-white">{{ $todayHabits->count() }}</p>
                    <p class="mt-1 text-xs text-stone-400">cadastrados</p>
                </div>
            </div>

            {{-- Hábitos do dia --}}
            <div class="hf-panel-pad">
                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="font-semibold text-white">Hábitos de hoje</h3>
                    <a href="{{ route('habits.index') }}" class="text-sm text-cyan-200 hover:text-cyan-100">Ver todos</a>
                </div>

                @if($todayHabits->isEmpty())
                    <p class="py-6 text-center text-sm text-stone-400">
                        Nenhum hábito ativo.
                        <a href="{{ route('habits.create') }}" class="text-cyan-200 hover:underline">Criar um hábito</a>
                    </p>
                @else
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($todayHabits as $habit)
                            <div class="hf-panel-soft flex items-center gap-3 rounded-2xl p-3">
                                <div class="w-1.5 h-10 rounded-full shrink-0" style="background-color: {{ $habit->color }}"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="truncate text-sm font-medium text-white">{{ $habit->name }}</p>
                                    <p class="text-xs text-stone-500">{{ $habit->category->name }}</p>
                                </div>
                                @if($habit->isCompletedToday())
                                    <span class="shrink-0 rounded-full bg-cyan-300/10 px-2 py-1 text-xs font-medium text-cyan-200">Feito</span>
                                @else
                                    <form action="{{ route('checkins.store') }}" method="POST" class="shrink-0">
                                        @csrf
                                        <input type="hidden" name="habit_id" value="{{ $habit->id }}">
                                        <button type="submit"
                                                class="rounded-full bg-cyan-300 px-3 py-1 text-xs font-semibold text-slate-950 transition hover:bg-cyan-200">
                                            Check-in
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Top 3 Streaks --}}
            @if($topStreaks->isNotEmpty())
                <div class="hf-panel-pad">
                    <h3 class="mb-4 font-semibold text-white">Top streaks ativos</h3>
                    <div class="grid gap-4 sm:grid-cols-3">
                        @foreach($topStreaks as $i => $habit)
                            @php $streak = $habit->currentStreak(); @endphp
                            <div class="flex items-center gap-3 rounded-2xl border border-cyan-300/10 bg-cyan-300/10 p-3">
                                <span class="text-2xl font-black text-cyan-200">#{{ $i + 1 }}</span>
                                <div class="flex-1 min-w-0">
                                    <p class="truncate text-sm font-semibold text-white">{{ $habit->name }}</p>
                                    <p class="text-xs font-medium text-cyan-300">
                                        {{ $streak }} {{ $streak === 1 ? 'dia' : 'dias' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Gráfico últimos 7 dias --}}
            <div class="hf-panel-pad">
                <h3 class="mb-4 font-semibold text-white">Check-ins — últimos 7 dias</h3>
                <canvas id="checkinChart" height="80"></canvas>
            </div>

            {{-- Heatmap --}}
            <div class="hf-panel-pad">
                <h3 class="mb-1 font-semibold text-white">Atividade — último ano</h3>
                <p class="mb-4 text-xs text-stone-400">Cada célula representa um dia; mais escuro = mais check-ins</p>

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
                    {{-- Labels dos dias --}}
                    <div class="flex flex-col gap-1 mr-1 shrink-0">
                        <div class="h-3"></div>
                        @foreach($dayLabels as $label)
                            <div class="h-3 flex items-center">
                                <span class="w-6 text-[9px] text-stone-500">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Semanas --}}
                    @foreach($weeks as $week)
                        <div class="flex flex-col gap-1 shrink-0">
                            @php $firstDay = $week->first()['date']; @endphp
                            <div class="h-3 flex items-center">
                                @if($firstDay->day <= 7)
                                    <span class="whitespace-nowrap text-[9px] text-stone-500">
                                        {{ $firstDay->translatedFormat('M') }}
                                    </span>
                                @endif
                            </div>
                            @foreach($week as $cell)
                                <div class="w-3 h-3 rounded-sm {{ $cellColor($cell['count'], $cell['future']) }}"
                                     title="{{ $cell['date']->format('d/m/Y') }}{{ $cell['count'] > 0 ? ' — ' . $cell['count'] . ' check-in(s)' : '' }}">
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                {{-- Legenda --}}
                <div class="flex items-center gap-1 mt-3">
                    <span class="mr-1 text-xs text-stone-500">Menos</span>
                    <div class="h-3 w-3 rounded-sm bg-stone-800 ring-1 ring-white/5"></div>
                    <div class="h-3 w-3 rounded-sm bg-cyan-300/50"></div>
                    <div class="h-3 w-3 rounded-sm bg-cyan-400"></div>
                    <div class="h-3 w-3 rounded-sm bg-fuchsia-400"></div>
                    <div class="h-3 w-3 rounded-sm bg-pink-400"></div>
                    <span class="ml-1 text-xs text-stone-500">Mais</span>
                </div>
            </div>

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
