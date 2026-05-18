<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Minhas Metas
            </h2>
            <a href="{{ route('goals.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition">
                + Nova Meta
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if($goals->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-12 text-center">
                    <p class="text-gray-400 text-lg mb-4">Você ainda não tem metas cadastradas.</p>
                    <a href="{{ route('goals.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-gray-800 text-white font-medium rounded-md hover:bg-gray-700 transition">
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
                                'completed' => 'bg-green-100 text-green-700',
                                'expired'   => 'bg-red-100 text-red-700',
                                default     => 'bg-blue-100 text-blue-700',
                            };
                            $statusLabel = match($goal->status) {
                                'completed' => 'Concluída',
                                'expired'   => 'Expirada',
                                default     => 'Ativa',
                            };
                        @endphp

                        <div class="bg-white shadow-sm sm:rounded-lg p-5 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-semibold text-gray-900 text-base leading-tight">
                                        {{ $goal->title }}
                                    </h3>
                                    <span class="ml-2 shrink-0 text-xs font-medium px-2 py-0.5 rounded-full {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <p class="text-xs text-gray-500 mb-3">
                                    🏷 {{ $goal->habit->name }}
                                </p>

                                @if($goal->description)
                                    <p class="text-sm text-gray-400 mb-3 line-clamp-2">{{ $goal->description }}</p>
                                @endif

                                {{-- Barra de progresso --}}
                                <div class="mb-1 flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ $checkins }} / {{ $goal->target_count }} check-ins</span>
                                    <span>{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                                    <div class="h-2 rounded-full transition-all
                                        {{ $goal->status === 'completed' ? 'bg-green-500' : ($goal->status === 'expired' ? 'bg-red-400' : 'bg-indigo-500') }}"
                                        style="width: {{ $progress }}%">
                                    </div>
                                </div>

                                <p class="text-xs text-gray-400">
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
                                       class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
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
                                            class="text-red-500 hover:text-red-700 text-sm font-medium">
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
