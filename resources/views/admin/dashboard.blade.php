<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Administrativo
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Cards de métricas --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total de usuários</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
                    <p class="text-xs text-gray-400 mt-1">contas ativas</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total de hábitos</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ $totalHabits }}</p>
                    <p class="text-xs text-gray-400 mt-1">cadastrados</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Check-ins hoje</p>
                    <p class="text-3xl font-bold text-green-600">{{ $checkinsToday }}</p>
                    <p class="text-xs text-gray-400 mt-1">registrados</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Engajamento (7d)</p>
                    <p class="text-3xl font-bold text-orange-500">{{ $engagementRate }}%</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $activeUsers }} de {{ $totalUsers }} ativos</p>
                </div>
            </div>

            {{-- Gráfico de engajamento (RF17) --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-1">Check-ins por dia — últimos 30 dias</h3>
                <p class="text-xs text-gray-400 mb-4">Linha tracejada = média móvel de 7 dias</p>
                <canvas id="engagementChart" height="80"></canvas>
            </div>

            {{-- Hábitos populares por categoria (RF18) --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Categorias mais populares</h3>
                @if($popularCategories->isEmpty())
                    <p class="text-gray-400 text-sm">Nenhuma categoria com hábitos cadastrados.</p>
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
                        borderColor: 'rgba(99, 102, 241, 1)',
                        backgroundColor: 'rgba(99, 102, 241, 0.08)',
                        borderWidth: 2,
                        pointRadius: 2,
                        fill: true,
                        tension: 0.3,
                    },
                    {
                        label: 'Média 7 dias',
                        data: {!! $movingAvg->toJson() !!},
                        borderColor: 'rgba(249, 115, 22, 0.9)',
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
                        grid: { color: 'rgba(0,0,0,0.05)' }
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
                    backgroundColor: 'rgba(99, 102, 241, 0.7)',
                    borderColor: 'rgba(99, 102, 241, 1)',
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
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    y: { grid: { display: false } }
                }
            }
        });
        @endif
    </script>
    @endpush
</x-app-layout>
