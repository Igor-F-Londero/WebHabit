<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-['Outfit'] text-2xl font-semibold leading-tight text-white">
                Minhas Metas
            </h2>
            <a href="{{ route('goals.create') }}"
               class="inline-flex items-center rounded-full bg-cyan-300 px-4 py-2 text-sm font-medium text-slate-950 transition hover:bg-cyan-200">
                + Nova Meta
            </a>
        </div>
    </x-slot>

    <div class="hf-page">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-cyan-300/10 border border-cyan-300/20 text-cyan-100 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-rose-400/10 border border-rose-400/20 text-rose-100 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if($goals->isEmpty())
                <div class="hf-panel-pad p-12 text-center">
                    <p class="mb-4 text-lg text-stone-400">Você ainda não tem metas cadastradas.</p>
                    <a href="{{ route('goals.create') }}"
                       class="inline-flex items-center rounded-full bg-cyan-300 px-6 py-3 font-medium text-slate-950 transition hover:bg-cyan-200">
                        Criar minha primeira meta
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
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-base font-semibold leading-tight text-white">
                                        {{ $goal->title }}
                                    </h3>
                                    <span class="ml-2 shrink-0 text-xs font-medium px-2 py-0.5 rounded-full {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <p class="mb-3 text-xs text-stone-500">
                                    🏷 {{ $goal->habit->name }}
                                </p>

                                @if($goal->description)
                                    <p class="mb-3 line-clamp-2 text-sm text-stone-400">{{ $goal->description }}</p>
                                @endif

                                <div class="mb-1 flex items-center justify-between text-xs text-stone-400">
                                    <span>{{ $checkins }} / {{ $goal->target_count }} check-ins</span>
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

                            <div class="mt-4 flex items-center justify-end gap-3">
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
