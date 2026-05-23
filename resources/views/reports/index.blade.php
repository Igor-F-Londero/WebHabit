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

        </div>
    </div>
</x-app-layout>
