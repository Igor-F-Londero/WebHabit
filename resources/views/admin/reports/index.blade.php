<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Relatório de Uso — Admin
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filtro de período --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-5">
                <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-end gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Período</label>
                        <select name="period"
                                class="rounded-md border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="7"  {{ $period === '7'  ? 'selected' : '' }}>Últimos 7 dias</option>
                            <option value="30" {{ $period === '30' ? 'selected' : '' }}>Últimos 30 dias</option>
                            <option value="90" {{ $period === '90' ? 'selected' : '' }}>Últimos 90 dias</option>
                        </select>
                    </div>
                    <button type="submit"
                            class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700 transition">
                        Filtrar
                    </button>
                </form>
            </div>

            {{-- Cards de métricas --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Novos usuários</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ $newUsers }}</p>
                    <p class="text-xs text-gray-400 mt-1">no período</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Novos hábitos</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $newHabits }}</p>
                    <p class="text-xs text-gray-400 mt-1">no período</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Check-ins</p>
                    <p class="text-3xl font-bold text-green-600">{{ $totalCheckins }}</p>
                    <p class="text-xs text-gray-400 mt-1">no período</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Retenção</p>
                    <p class="text-3xl font-bold text-orange-500">{{ $retentionRate }}%</p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $retainedUsers }}/{{ $usersBeforePeriod }} usuários antigos ativos
                    </p>
                </div>
            </div>

            {{-- Gráfico combinado --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Evolução no período</h3>
                <canvas id="reportChart" height="80"></canvas>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        new Chart(document.getElementById('reportChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! $chartLabels->toJson() !!},
                datasets: [
                    {
                        label: 'Check-ins/dia',
                        data: {!! $chartData->toJson() !!},
                        borderColor: 'rgba(99, 102, 241, 1)',
                        backgroundColor: 'rgba(99, 102, 241, 0.08)',
                        borderWidth: 2,
                        pointRadius: 2,
                        fill: true,
                        tension: 0.3,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Novos usuários/dia',
                        data: {!! $usersChartData->toJson() !!},
                        borderColor: 'rgba(249, 115, 22, 0.9)',
                        backgroundColor: 'rgba(249, 115, 22, 0.08)',
                        borderWidth: 2,
                        pointRadius: 2,
                        fill: false,
                        tension: 0.3,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        title: { display: true, text: 'Check-ins', font: { size: 10 } }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { drawOnChartArea: false },
                        title: { display: true, text: 'Usuários', font: { size: 10 } }
                    },
                    x: { grid: { display: false }, ticks: { maxTicksLimit: 12 } }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
