<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Outfit'] text-2xl font-semibold leading-tight text-white">
            Dashboard Administrativo
        </h2>
    </x-slot>

    <div class="hf-page">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Cards de métricas --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="hf-panel-pad p-5">
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">Total de usuários</p>
                    <p class="text-3xl font-bold text-white">{{ $totalUsers }}</p>
                    <p class="mt-1 text-xs text-stone-400">contas ativas</p>
                </div>
                <div class="hf-panel-pad p-5">
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">Total de hábitos</p>
                    <p class="text-3xl font-bold text-cyan-300">{{ $totalHabits }}</p>
                    <p class="mt-1 text-xs text-stone-400">cadastrados</p>
                </div>
                <div class="hf-panel-pad p-5">
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">Check-ins hoje</p>
                    <p class="text-3xl font-bold text-cyan-200">{{ $checkinsToday }}</p>
                    <p class="mt-1 text-xs text-stone-400">registrados</p>
                </div>
                <div class="hf-panel-pad p-5">
                    <p class="mb-1 text-xs uppercase tracking-wide text-stone-500">Engajamento (7d)</p>
                    <p class="text-3xl font-bold text-fuchsia-300">{{ $engagementRate }}%</p>
                    <p class="mt-1 text-xs text-stone-400">{{ $activeUsers }} de {{ $totalUsers }} ativos</p>
                </div>
            </div>

            {{-- Gráfico de engajamento (RF17) --}}
            <div class="hf-panel-pad">
                <h3 class="mb-1 font-semibold text-white">Check-ins por dia — últimos 30 dias</h3>
                <p class="mb-4 text-xs text-stone-400">Linha tracejada = média móvel de 7 dias</p>
                <canvas id="engagementChart" height="80"></canvas>
            </div>

            {{-- Hábitos populares por categoria (RF18) --}}
            <div class="hf-panel-pad">
                <h3 class="mb-4 font-semibold text-white">Categorias mais populares</h3>
                @if($popularCategories->isEmpty())
                    <p class="text-sm text-stone-400">Nenhuma categoria com hábitos cadastrados.</p>
                @else
                    <canvas id="categoriesChart" height="120"></canvas>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Gráfico de engajamento — linhas
        new Chart(document.getElementById('engagementChart').getContext('2d'), {
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
                    },
                    {
                        label: 'Média 7 dias',
                        data: {!! $movingAvg->toJson() !!},
                        borderColor: 'rgba(217, 70, 239, 0.9)',
                        borderWidth: 2,
                        borderDash: [5, 4],
                        pointRadius: 0,
                        fill: false,
                        tension: 0.3,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { color: 'rgba(148, 163, 184, 0.08)' }
                    },
                    x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } }
                }
            }
        });

        // Gráfico de categorias — barras horizontais
        @if($popularCategories->isNotEmpty())
        new Chart(document.getElementById('categoriesChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! $popularCategories->pluck('name')->toJson() !!},
                datasets: [{
                    label: 'Hábitos',
                    data: {!! $popularCategories->pluck('habits_count')->toJson() !!},
                    backgroundColor: 'rgba(34, 211, 238, 0.72)',
                    borderColor: 'rgba(103, 232, 249, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { color: 'rgba(148, 163, 184, 0.08)' }
                    },
                    y: { grid: { display: false } }
                }
            }
        });
        @endif
    </script>
    @endpush
</x-app-layout>
