<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-['Outfit'] text-2xl font-semibold leading-tight text-white">
                Chefes e Metas
            </h2>
            <a href="{{ route('goals.create') }}"
               class="inline-flex items-center rounded-full bg-cyan-300 px-4 py-2 text-sm font-medium text-slate-950 transition hover:bg-cyan-200">
                + Novo Chefe
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

            @if($goals->isEmpty())
                <div class="hf-panel-pad p-8 text-center sm:p-12">
                    <p class="mb-4 text-base text-slate-400 sm:text-lg">Você ainda não tem chefes cadastrados.</p>
                    <a href="{{ route('goals.create') }}"
                       class="inline-flex items-center rounded-full bg-cyan-300 px-6 py-3 font-medium text-slate-950 transition hover:bg-cyan-200">
                        Criar meu primeiro chefe
                    </a>
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($goals as $goal)
                        @php
                            $progress = $goal->progressPercent();
                            $checkins = $goal->checkinCount();
                            $statusColor = match($goal->status) {
                                'completed' => 'bg-cyan-300/10 text-cyan-100',
                                'expired'   => 'bg-rose-400/10 text-rose-200',
                                default     => 'bg-cyan-300/10 text-cyan-100',
                            };
                            $statusLabel = match($goal->status) {
                                'completed' => 'Concluída',
                                'expired'   => 'Expirada',
                                default     => 'Ativa',
                            };
                        @endphp

                        <div class="hf-panel-pad flex flex-col justify-between">
                            <div>
                                <div class="mb-2 flex items-start justify-between gap-3">
                                    <h3 class="min-w-0 text-base font-semibold leading-tight text-white">
                                        {{ $goal->title }}
                                    </h3>
                                    <span class="ml-2 shrink-0 text-xs font-medium px-2 py-0.5 rounded-full {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <p class="mb-3 text-xs uppercase tracking-[0.18em] text-slate-500">
                                    {{ $goal->habit->name }}
                                </p>

                                <div class="mb-3 flex flex-wrap gap-2">
                                    <span class="rounded-full bg-fuchsia-300/10 px-2.5 py-1 text-xs font-semibold text-fuchsia-100">
                                        Recompensa: +80 XP
                                    </span>
                                    <span class="rounded-full bg-amber-300/10 px-2.5 py-1 text-xs font-semibold text-amber-200">
                                        +25 moedas
                                    </span>
                                </div>

                                @if($goal->description)
                                    <p class="mb-3 line-clamp-2 text-sm text-stone-400">{{ $goal->description }}</p>
                                @endif

                                <div class="mb-1 flex items-center justify-between text-xs text-stone-400">
                                    <span>Dano: {{ $checkins }} / {{ $goal->target_count }} check-ins</span>
                                    <span>{{ $progress }}%</span>
                                </div>
                                <div class="mb-3 h-2 w-full rounded-full bg-stone-950 ring-1 ring-white/10">
                                    <div class="h-2 rounded-full transition-all shadow-[0_0_18px_rgba(34,211,238,0.35)]
                                        {{ $goal->status === 'completed' ? 'bg-cyan-300' : ($goal->status === 'expired' ? 'bg-rose-400' : 'bg-cyan-400') }}"
                                        style="width: {{ $progress }}%">
                                    </div>
                                </div>

                                <p class="text-xs text-stone-500">
                                    {{ $goal->start_date->format('d/m/Y') }} →
                                    {{ $goal->end_date->format('d/m/Y') }}
                                    @if($goal->status === 'active')
                                        · {{ $goal->daysRemaining() }} {{ $goal->daysRemaining() === 1 ? 'dia restante' : 'dias restantes' }}
                                    @endif
                                </p>
                            </div>

                            <div class="mt-4 flex flex-wrap items-center justify-end gap-3">
                                @if($goal->status !== 'completed')
                                    <a href="{{ route('goals.edit', $goal) }}"
                                       class="text-sm font-medium text-cyan-200 hover:text-cyan-100">
                                        Editar
                                    </a>
                                @endif
                                <form action="{{ route('goals.destroy', $goal) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Excluir a meta \'{{ addslashes($goal->title) }}\'?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-sm font-medium text-rose-300 hover:text-rose-200">
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
