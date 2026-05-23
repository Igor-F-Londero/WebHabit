<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Outfit'] text-2xl font-semibold leading-tight text-white">
            Relatório
        </h2>
    </x-slot>

    <div class="hf-page">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">

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

            {{-- Tabela por hábito --}}
            <div class="hf-panel overflow-hidden">
                <div class="border-b border-white/10 px-6 py-4">
                    <h3 class="font-semibold text-white">Desempenho por missão</h3>
                    <p class="mt-0.5 text-xs text-stone-400">Ordenado do melhor para o pior</p>
                </div>

                @if($habitStats->isEmpty())
                    <div class="p-8 text-center text-slate-400 sm:p-12">
                        Nenhuma missão ativa para exibir.
                    </div>
                @else
                    <div class="divide-y divide-cyan-300/10">
                        @foreach($habitStats as $i => $stat)
                            @php
                                $rate = $stat['rate'];
                                $barColor = $rate >= 70 ? 'bg-cyan-300' : ($rate >= 40 ? 'bg-fuchsia-400' : 'bg-rose-400');
                                $rateColor = $rate >= 70 ? 'text-cyan-300' : ($rate >= 40 ? 'text-fuchsia-300' : 'text-rose-300');
                            @endphp
                            <div class="px-6 py-4">
                                <div class="mb-1.5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex min-w-0 items-center gap-2">
                                        <span class="w-4 shrink-0 text-xs text-stone-500">#{{ $i + 1 }}</span>
                                        <div class="w-2 h-5 rounded-sm shrink-0"
                                             style="background-color: {{ $stat['habit']->color }}"></div>
                                        <p class="truncate text-sm font-medium text-white">
                                            {{ $stat['habit']->name }}
                                        </p>
                                        <span class="shrink-0 rounded bg-white/[0.06] px-1.5 py-0.5 text-xs text-stone-400">
                                            {{ $stat['habit']->frequency === 'daily' ? 'Diário' : 'Semanal' }}
                                        </span>
                                    </div>
                                    <div class="flex shrink-0 items-center gap-3 sm:ml-4">
                                        <span class="text-xs text-stone-400">
                                            {{ $stat['checkins'] }}/{{ $stat['expected'] }}
                                        </span>
                                        <span class="text-sm font-bold {{ $rateColor }} w-12 text-right">
                                            {{ $rate }}%
                                        </span>
                                    </div>
                                </div>
                                <div class="h-1.5 w-full rounded-full bg-white/[0.08]">
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
