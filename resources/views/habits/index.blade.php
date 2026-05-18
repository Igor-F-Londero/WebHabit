<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Meus Hábitos
            </h2>
            <a href="{{ route('habits.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition">
                + Novo Hábito
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

            @if($habits->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-12 text-center">
                    <p class="text-gray-400 text-lg mb-4">Você ainda não tem hábitos cadastrados.</p>
                    <a href="{{ route('habits.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-gray-800 text-white font-medium rounded-md hover:bg-gray-700 transition">
                        Criar meu primeiro hábito
                    </a>
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($habits as $habit)
                        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden flex">
                            {{-- Barra de cor lateral --}}
                            <div class="w-2 shrink-0" style="background-color: {{ $habit->color }}"></div>

                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <h3 class="font-semibold text-gray-900 text-base">{{ $habit->name }}</h3>
                                        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                                            {{ $habit->frequency === 'daily' ? 'Diário' : 'Semanal' }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mb-1">{{ $habit->category->name }}</p>
                                    @if($habit->description)
                                        <p class="text-sm text-gray-400 mt-1 line-clamp-2">{{ $habit->description }}</p>
                                    @endif
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-xs {{ $habit->isCompletedToday() ? 'text-orange-500 font-semibold' : 'text-gray-400' }}">
                                        🔥 {{ $habit->currentStreak() }} {{ $habit->currentStreak() === 1 ? 'dia' : 'dias' }}
                                    </span>
                                    <div class="flex items-center gap-3">
                                        @if($habit->isCompletedToday())
                                            <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                                ✓ Feito hoje
                                            </span>
                                        @else
                                            <form action="{{ route('checkins.store') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="habit_id" value="{{ $habit->id }}">
                                                <button type="submit"
                                                        class="inline-flex items-center px-3 py-1 bg-gray-800 text-white text-xs font-medium rounded-full hover:bg-gray-700 transition">
                                                    Check-in
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('habits.edit', $habit) }}"
                                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            Editar
                                        </a>
                                        <form action="{{ route('habits.destroy', $habit) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Excluir o hábito \'{{ addslashes($habit->name) }}\'? Esta ação não pode ser desfeita.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-500 hover:text-red-700 text-sm font-medium">
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
