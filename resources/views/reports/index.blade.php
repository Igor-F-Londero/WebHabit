<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Relatório Pessoal
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filtro de período --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-5">
                <form method="GET" action="{{ route('reports.index') }}"
                      x-data="{ period: '{{ $period }}' }"
                      class="flex flex-wrap items-end gap-4">

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Período</label>
                        <select name="period" x-model="period"
                                class="rounded-md border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="7">Últimos 7 dias</option>
                            <option value="30">Últimos 30 dias</option>
                            <option value="90">Últimos 90 dias</option>
                            <option value="custom">Intervalo customizado</option>
                        </select>
                    </div>

                    <div x-show="period === 'custom'" class="flex gap-2 items-end">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">De</label>
                            <input type="date" name="start"
                                   value="{{ $period === 'custom' ? $startDate->toDateString() : '' }}"
                                   class="rounded-md border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Até</label>
                            <input type="date" name="end"
                                   value="{{ $period === 'custom' ? $endDate->toDateString() : '' }}"
                                   class="rounded-md border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <button type="submit"
                            class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700 transition">
                        Filtrar
                    </button>
                </form>
            </div>

            {{-- Cards de resumo --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Período</p>
                    <p class="text-lg font-bold text-gray-800">{{ $totalDays }} dias</p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $startDate->format('d/m/Y') }} → {{ $endDate->format('d/m/Y') }}
                    </p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Check-ins realizados</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ $totalCheckins }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Taxa média</p>
                    <p class="text-3xl font-bold {{ $avgRate >= 70 ? 'text-green-600' : ($avgRate >= 40 ? 'text-yellow-500' : 'text-red-500') }}">
                        {{ $avgRate }}%
                    </p>
                    <p class="text-xs text-gray-400 mt-1">entre todos os hábitos</p>
                </div>
            </div>

            {{-- Tabela por hábito --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Desempenho por hábito</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Ordenado do melhor para o pior</p>
                </div>

                @if($habitStats->isEmpty())
                    <div class="p-12 text-center text-gray-400">
                        Nenhum hábito ativo para exibir.
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($habitStats as $i => $stat)
                            @php
                                $rate = $stat['rate'];
                                $barColor = $rate >= 70 ? 'bg-green-500' : ($rate >= 40 ? 'bg-yellow-400' : 'bg-red-400');
                                $rateColor = $rate >= 70 ? 'text-green-600' : ($rate >= 40 ? 'text-yellow-600' : 'text-red-500');
                            @endphp
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between mb-1.5">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="text-xs text-gray-400 w-4 shrink-0">#{{ $i + 1 }}</span>
                                        <div class="w-2 h-5 rounded-sm shrink-0"
                                             style="background-color: {{ $stat['habit']->color }}"></div>
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $stat['habit']->name }}
                                        </p>
                                        <span class="shrink-0 text-xs text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">
                                            {{ $stat['habit']->frequency === 'daily' ? 'Diário' : 'Semanal' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 shrink-0 ml-4">
                                        <span class="text-xs text-gray-500">
                                            {{ $stat['checkins'] }}/{{ $stat['expected'] }}
                                        </span>
                                        <span class="text-sm font-bold {{ $rateColor }} w-12 text-right">
                                            {{ $rate }}%
                                        </span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full {{ $barColor }} transition-all"
                                         style="width: {{ $rate }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
