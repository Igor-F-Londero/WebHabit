<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Cards de resumo --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Consistência (30 dias)</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ $consistencyRate }}%</p>
                    <p class="text-xs text-gray-400 mt-1">hábitos diários concluídos</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Check-ins hoje</p>
                    <p class="text-3xl font-bold text-green-600">{{ $checkinsToday }}</p>
                    <p class="text-xs text-gray-400 mt-1">de {{ $todayHabits->count() }} hábito(s) ativo(s)</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Hábitos ativos</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $todayHabits->count() }}</p>
                    <p class="text-xs text-gray-400 mt-1">cadastrados</p>
                </div>
            </div>

            {{-- Hábitos do dia --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-800">Hábitos de hoje</h3>
                    <a href="{{ route('habits.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Ver todos</a>
                </div>

                @if($todayHabits->isEmpty())
                    <p class="text-gray-400 text-sm text-center py-6">
                        Nenhum hábito ativo.
                        <a href="{{ route('habits.create') }}" class="text-indigo-600 hover:underline">Criar um hábito</a>
                    </p>
                @else
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($todayHabits as $habit)
                            <div class="flex items-center gap-3 border border-gray-100 rounded-lg p-3">
                                <div class="w-1.5 h-10 rounded-full shrink-0" style="background-color: {{ $habit->color }}"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $habit->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $habit->category->name }}</p>
                                </div>
                                @if($habit->isCompletedToday())
                                    <span class="shrink-0 text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">✓ Feito</span>
                                @else
                                    <form action="{{ route('checkins.store') }}" method="POST" class="shrink-0">
                                        @csrf
                                        <input type="hidden" name="habit_id" value="{{ $habit->id }}">
                                        <button type="submit"
                                                class="text-xs bg-gray-800 text-white px-3 py-1 rounded-full hover:bg-gray-700 transition">
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
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Top streaks ativos</h3>
                    <div class="grid gap-4 sm:grid-cols-3">
                        @foreach($topStreaks as $i => $habit)
                            @php $streak = $habit->currentStreak(); @endphp
                            <div class="flex items-center gap-3 p-3 rounded-lg bg-orange-50 border border-orange-100">
                                <span class="text-2xl font-black text-orange-300">#{{ $i + 1 }}</span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $habit->name }}</p>
                                    <p class="text-xs text-orange-500 font-medium">
                                        🔥 {{ $streak }} {{ $streak === 1 ? 'dia' : 'dias' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Gráfico últimos 7 dias --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Check-ins — últimos 7 dias</h3>
                <canvas id="checkinChart" height="80"></canvas>
            </div>

            {{-- Heatmap --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-1">Atividade — último ano</h3>
                <p class="text-xs text-gray-400 mb-4">Cada célula representa um dia; mais escuro = mais check-ins</p>

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
                        if ($future)       return 'bg-gray-50';
                        if ($count === 0)  return 'bg-gray-100';
                        if ($count === 1)  return 'bg-green-200';
                        if ($count <= 3)   return 'bg-green-400';
                        if ($count <= 5)   return 'bg-green-600';
                        return 'bg-green-800';
                    };

                    $dayLabels = ['Seg', '', 'Qua', '', 'Sex', '', 'Dom'];
                @endphp

                <div class="flex gap-1 overflow-x-auto pb-2">
                    {{-- Labels dos dias --}}
                    <div class="flex flex-col gap-1 mr-1 shrink-0">
                        <div class="h-3"></div>
                        @foreach($dayLabels as $label)
                            <div class="h-3 flex items-center">
                                <span class="text-[9px] text-gray-400 w-6">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Semanas --}}
                    @foreach($weeks as $week)
                        <div class="flex flex-col gap-1 shrink-0">
                            @php $firstDay = $week->first()['date']; @endphp
                            <div class="h-3 flex items-center">
                                @if($firstDay->day <= 7)
                                    <span class="text-[9px] text-gray-400 whitespace-nowrap">
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
                    <span class="text-xs text-gray-400 mr-1">Menos</span>
                    <div class="w-3 h-3 rounded-sm bg-gray-100"></div>
                    <div class="w-3 h-3 rounded-sm bg-green-200"></div>
                    <div class="w-3 h-3 rounded-sm bg-green-400"></div>
                    <div class="w-3 h-3 rounded-sm bg-green-600"></div>
                    <div class="w-3 h-3 rounded-sm bg-green-800"></div>
                    <span class="text-xs text-gray-400 ml-1">Mais</span>
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
                    backgroundColor: 'rgba(99, 102, 241, 0.7)',
                    borderColor: 'rgba(99, 102, 241, 1)',
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
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
