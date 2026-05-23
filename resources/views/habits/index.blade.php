<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-['Outfit'] text-2xl font-semibold leading-tight text-white">
                Minhas Missões
            </h2>
            <a href="{{ route('habits.create') }}"
               class="inline-flex items-center rounded-full bg-cyan-300 px-4 py-2 text-sm font-medium text-slate-950 transition hover:bg-cyan-200">
                + Nova Missão
            </a>
        </div>
    </x-slot>

    <div class="hf-page">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 rounded-2xl border border-cyan-300/20 bg-cyan-300/10 p-4 text-cyan-100">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 rounded-2xl border border-rose-400/20 bg-rose-400/10 p-4 text-rose-100">
                    {{ session('error') }}
                </div>
            @endif

            @if($habits->isEmpty())
                <div class="hf-panel-pad p-8 text-center sm:p-12">
                    <p class="mb-4 text-base text-slate-400 sm:text-lg">Você ainda não tem missões cadastradas.</p>
                    <a href="{{ route('habits.create') }}"
                       class="inline-flex items-center rounded-full bg-cyan-300 px-6 py-3 font-medium text-slate-950 transition hover:bg-cyan-200">
                        Criar minha primeira missão
                    </a>
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($habits as $habit)
                        @php
                            $completed = $habit->isCompletedForCurrentCycle();
                            $rewardXp = $habit->frequency === 'weekly' ? 20 : 15;
                            $rewardCoins = $habit->frequency === 'weekly' ? 4 : 3;
                            $streak = $habit->currentStreak();
                        @endphp
                        <div class="hf-panel flex overflow-hidden">
                            {{-- Barra de cor lateral --}}
                            <div class="w-2 shrink-0" style="background-color: {{ $habit->color }}"></div>

                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="mb-1 flex items-start justify-between gap-3">
                                        <h3 class="min-w-0 text-base font-semibold text-white">{{ $habit->name }}</h3>
                                        <span class="rounded-full bg-white/[0.06] px-2 py-0.5 text-xs text-stone-400">
                                            {{ $habit->frequency === 'daily' ? 'Diário' : 'Semanal' }}
                                        </span>
                                    </div>
                                    <p class="mb-1 text-xs text-stone-500">{{ $habit->category->name }}</p>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <span class="rounded-full bg-cyan-300/10 px-2.5 py-1 text-xs font-semibold text-cyan-100">
                                            +{{ $rewardXp }} XP
                                        </span>
                                        <span class="rounded-full bg-amber-300/10 px-2.5 py-1 text-xs font-semibold text-amber-200">
                                            +{{ $rewardCoins }} moedas
                                        </span>
                                    </div>
                                    @if($habit->description)
                                        <p class="mt-1 line-clamp-2 text-sm text-stone-400">{{ $habit->description }}</p>
                                    @endif
                                </div>

                                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <span class="text-xs {{ $completed ? 'font-semibold text-cyan-300' : 'text-stone-400' }}">
                                        Combo: {{ $streak }} {{ $streak === 1 ? 'dia' : 'dias' }}
                                    </span>
                                    <div class="flex flex-wrap items-center gap-3">
                                        @if($completed)
                                            <span class="inline-flex items-center rounded-full bg-cyan-300/10 px-3 py-1 text-xs font-medium text-cyan-100">
                                                Recompensa coletada
                                            </span>
                                        @else
                                            <form action="{{ route('checkins.store') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="habit_id" value="{{ $habit->id }}">
                                                <button type="submit"
                                                        class="inline-flex items-center rounded-full bg-cyan-400 px-3 py-1 text-xs font-semibold text-slate-950 shadow-[0_0_18px_rgba(34,211,238,0.25)] transition hover:bg-cyan-300">
                                                    Concluir
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('habits.edit', $habit) }}"
                                           class="text-sm font-medium text-cyan-200 hover:text-cyan-100">
                                            Editar
                                        </a>
                                        <form action="{{ route('habits.destroy', $habit) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Excluir a missao \'{{ addslashes($habit->name) }}\'? Esta acao nao pode ser desfeita.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-sm font-medium text-rose-300 hover:text-rose-200">
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
