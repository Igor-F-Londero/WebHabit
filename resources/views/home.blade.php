<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">hub de aventura</p>
                <h2 class="font-['Outfit'] text-3xl font-black leading-tight text-white">
                    Escolha sua próxima missão
                </h2>
            </div>
            <p class="text-sm text-slate-400">
                {{ now()->translatedFormat('d M Y') }}
            </p>
        </div>
    </x-slot>

    @php
        $unlockedAchievements = collect($game['achievements'])->where('unlocked', true)->count();
    @endphp

    <div class="hf-page">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="hf-alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="hf-alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <section class="grid gap-4 lg:grid-cols-[1.45fr_0.55fr]">
                <div class="hf-panel-pad overflow-hidden">
                    <div class="flex flex-col gap-6 md:flex-row md:items-center">
                        <div class="relative flex h-32 w-32 shrink-0 items-center justify-center rounded-xl border border-cyan-300/20 bg-slate-950 shadow-[0_0_36px_rgba(34,211,238,0.14)]">
                            <div class="absolute inset-3 rounded-lg bg-[linear-gradient(135deg,_rgba(34,211,238,0.18),_rgba(217,70,239,0.14))]"></div>
                            <div class="relative grid h-20 w-20 grid-cols-4 grid-rows-4 gap-1">
                                <span class="rounded-sm bg-cyan-200"></span>
                                <span class="rounded-sm bg-cyan-300"></span>
                                <span class="rounded-sm bg-cyan-300"></span>
                                <span class="rounded-sm bg-cyan-200"></span>
                                <span class="rounded-sm bg-slate-700"></span>
                                <span class="rounded-sm bg-slate-100"></span>
                                <span class="rounded-sm bg-slate-100"></span>
                                <span class="rounded-sm bg-slate-700"></span>
                                <span class="rounded-sm bg-fuchsia-300"></span>
                                <span class="rounded-sm bg-cyan-400"></span>
                                <span class="rounded-sm bg-cyan-400"></span>
                                <span class="rounded-sm bg-fuchsia-300"></span>
                                <span class="rounded-sm bg-slate-800"></span>
                                <span class="rounded-sm bg-amber-300"></span>
                                <span class="rounded-sm bg-amber-300"></span>
                                <span class="rounded-sm bg-slate-800"></span>
                            </div>
                            <span class="absolute bottom-3 rounded-md border border-white/10 bg-slate-950/90 px-2 py-1 font-['Outfit'] text-xs font-black text-white">
                                {{ $game['avatar_initials'] }}
                            </span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">status do personagem</p>
                            <h3 class="mt-3 break-words font-['Outfit'] text-3xl font-black leading-tight text-white sm:text-4xl">
                                {{ auth()->user()->name }}, Nível {{ $game['level'] }}
                            </h3>
                            <p class="mt-2 text-sm font-semibold text-fuchsia-100">{{ $game['rank'] }}</p>

                            <div class="mt-5">
                                <div class="mb-2 flex items-center justify-between gap-3 text-xs text-slate-400">
                                    <span>{{ $game['current_level_xp'] }} / {{ $game['next_level_xp'] }} XP para subir</span>
                                    <span>{{ $game['level_progress_percent'] }}%</span>
                                </div>
                                <div class="h-3 overflow-hidden rounded-full bg-slate-900 ring-1 ring-white/10">
                                    <div
                                        class="h-full rounded-full bg-[linear-gradient(90deg,_#22d3ee,_#f472b6,_#fbbf24)] shadow-[0_0_20px_rgba(34,211,238,0.32)]"
                                        style="width: {{ $game['level_progress_percent'] }}%"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                    <div class="hf-panel-pad">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">XP total</p>
                        <p class="mt-3 font-['Outfit'] text-3xl font-black text-cyan-300">{{ $game['total_xp'] }}</p>
                    </div>
                    <div class="hf-panel-pad">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">moedas</p>
                        <p class="mt-3 font-['Outfit'] text-3xl font-black text-amber-300">{{ $game['coins'] }}</p>
                    </div>
                    <div class="hf-panel-pad">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">melhor combo</p>
                        <p class="mt-3 font-['Outfit'] text-3xl font-black text-pink-300">{{ $game['best_streak'] }}</p>
                    </div>
                </div>
            </section>

            <section class="grid gap-4 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="hf-panel-pad">
                    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">quadro de missões</p>
                            <h3 class="font-['Outfit'] text-2xl font-black text-white">Missões de hoje</h3>
                        </div>
                        <span class="rounded-full border border-cyan-300/20 bg-cyan-300/10 px-3 py-1 text-xs font-semibold text-cyan-100">
                            {{ $game['quest_done'] }}/{{ $game['quest_total'] }} concluídas
                        </span>
                    </div>

                    <div class="mb-5 h-2 overflow-hidden rounded-full bg-slate-900 ring-1 ring-white/10">
                        <div
                            class="h-full rounded-full bg-cyan-300 shadow-[0_0_18px_rgba(34,211,238,0.3)]"
                            style="width: {{ $game['quest_progress_percent'] }}%"
                        ></div>
                    </div>

                    <div class="space-y-3">
                        @forelse($game['quests']->take(5) as $quest)
                            <div class="flex flex-col gap-3 rounded-xl border border-white/10 bg-white/[0.035] p-4 sm:flex-row sm:items-center">
                                <div class="flex min-w-0 flex-1 items-center gap-3">
                                    <span class="h-12 w-2 shrink-0 rounded-full" style="background-color: {{ $quest['color'] }}"></span>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="truncate text-sm font-semibold text-white">{{ $quest['name'] }}</p>
                                            <span class="rounded-full bg-slate-900 px-2 py-0.5 text-[11px] font-semibold text-slate-300">{{ $quest['difficulty'] }}</span>
                                        </div>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $quest['category'] }} · {{ $quest['frequency'] }} · +{{ $quest['reward_xp'] }} XP · +{{ $quest['reward_coins'] }} moedas
                                        </p>
                                    </div>
                                </div>

                                @if($quest['completed'])
                                    <span class="shrink-0 rounded-full bg-cyan-300/10 px-3 py-1 text-xs font-semibold text-cyan-100">
                                        Recompensa coletada
                                    </span>
                                @else
                                    <form action="{{ route('checkins.store') }}" method="POST" class="shrink-0">
                                        @csrf
                                        <input type="hidden" name="habit_id" value="{{ $quest['id'] }}">
                                        <button type="submit"
                                                class="rounded-full bg-cyan-300 px-4 py-2 text-xs font-bold text-slate-950 transition hover:bg-cyan-200">
                                            Concluir
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-xl border border-cyan-300/10 bg-cyan-300/[0.035] p-5 text-sm text-slate-300">
                                Nenhuma missão ativa. Crie uma Missão para começar sua jornada.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="hf-panel-pad">
                    <div class="mb-5 flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-[0.24em] text-amber-200/70">loja de recompensas</p>
                            <h3 class="font-['Outfit'] text-2xl font-black text-white">Inventário</h3>
                        </div>
                        <span class="rounded-full bg-amber-300/10 px-3 py-1 text-xs font-bold text-amber-200">
                            {{ $game['coins'] }} moedas
                        </span>
                    </div>

                    <div class="space-y-3">
                        @foreach($game['rewards'] as $reward)
                            <div class="flex items-center justify-between gap-3 rounded-xl border border-white/10 bg-white/[0.035] p-4">
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ $reward['name'] }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $reward['cost'] }} moedas</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $game['coins'] >= $reward['cost'] ? 'bg-amber-300/10 text-amber-200' : 'bg-slate-900 text-slate-500' }}">
                                    {{ $game['coins'] >= $reward['cost'] ? 'Disponível' : 'Bloqueada' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="hf-panel-pad">
                <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.24em] text-fuchsia-200/70">conquistas</p>
                        <h3 class="font-['Outfit'] text-2xl font-black text-white">Medalhas desbloqueadas</h3>
                    </div>
                    <span class="text-sm font-semibold text-slate-300">{{ $unlockedAchievements }}/{{ count($game['achievements']) }}</span>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($game['achievements'] as $achievement)
                        <div class="rounded-xl border p-4 {{ $achievement['unlocked'] ? 'border-fuchsia-300/20 bg-fuchsia-300/10' : 'border-white/10 bg-white/[0.025]' }}">
                            <p class="text-sm font-semibold {{ $achievement['unlocked'] ? 'text-fuchsia-100' : 'text-slate-500' }}">
                                {{ $achievement['name'] }}
                            </p>
                            <p class="mt-2 text-xs leading-5 {{ $achievement['unlocked'] ? 'text-slate-300' : 'text-slate-600' }}">
                                {{ $achievement['description'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
