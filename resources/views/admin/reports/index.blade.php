<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Outfit'] text-2xl font-semibold leading-tight text-white">
            Relatório de Uso — Admin
        </h2>
    </x-slot>

    <div class="hf-page">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Filtro de período --}}
            <div class="hf-panel-pad p-5">
                <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-end gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-stone-400">Período</label>
                        <select name="period"
                                class="hf-select text-sm">
                            <option value="7"  {{ $period === '7'  ? 'selected' : '' }}>Últimos 7 dias</option>
                            <option value="30" {{ $period === '30' ? 'selected' : '' }}>Últimos 30 dias</option>
                            <option value="90" {{ $period === '90' ? 'selected' : '' }}>Últimos 90 dias</option>
                        </select>
                    </div>
                    <button type="submit"
                            class="rounded-full bg-cyan-300 px-4 py-2 text-sm text-slate-950 transition hover:bg-cyan-200">
                        Filtrar
                    </button>
                </form>
            </div>

            {{-- Cards de métricas --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="hf-panel-pad p-5">
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">Novos usuários</p>
                    <p class="text-3xl font-bold text-cyan-300">{{ $newUsers }}</p>
                    <p class="mt-1 text-xs text-stone-400">no período</p>
                </div>
                <div class="hf-panel-pad p-5">
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">Novos hábitos</p>
                    <p class="text-3xl font-bold text-white">{{ $newHabits }}</p>
                    <p class="mt-1 text-xs text-stone-400">no período</p>
                </div>
                <div class="hf-panel-pad p-5">
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">Check-ins</p>
                    <p class="text-3xl font-bold text-cyan-200">{{ $totalCheckins }}</p>
                    <p class="mt-1 text-xs text-stone-400">no período</p>
                </div>
                <div class="hf-panel-pad p-5">
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">Retenção</p>
                    <p class="text-3xl font-bold text-fuchsia-300">{{ $retentionRate }}%</p>
                    <p class="mt-1 text-xs text-stone-400">
                        {{ $retainedUsers }}/{{ $usersBeforePeriod }} usuários antigos ativos
                    </p>
                </div>
            </div>

            {{-- Gráfico combinado --}}
            <div class="hf-panel-pad">
                <h3 class="mb-4 font-semibold text-white">Evolução no período</h3>
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
                        borderColor: 'rgba(34, 211, 238, 1)',
                        backgroundColor: 'rgba(34, 211, 238, 0.08)',
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
