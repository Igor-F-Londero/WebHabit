<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-['Outfit'] text-2xl font-semibold leading-tight text-white">
                Meus Hábitos
            </h2>
            <a href="{{ route('habits.create') }}"
               class="inline-flex items-center rounded-full bg-cyan-300 px-4 py-2 text-sm font-medium text-slate-950 transition hover:bg-cyan-200">
                + Novo Hábito
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

            @if($habits->isEmpty())
                <div class="hf-panel-pad p-12 text-center">
                    <p class="mb-4 text-lg text-stone-400">Você ainda não tem hábitos cadastrados.</p>
                    <a href="{{ route('habits.create') }}"
                       class="inline-flex items-center rounded-full bg-cyan-300 px-6 py-3 font-medium text-slate-950 transition hover:bg-cyan-200">
                        Criar meu primeiro hábito
                    </a>
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($habits as $habit)
                        <div class="hf-panel overflow-hidden flex">
                            {{-- Barra de cor lateral --}}
                            <div class="w-2 shrink-0" style="background-color: {{ $habit->color }}"></div>

                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <h3 class="text-base font-semibold text-white">{{ $habit->name }}</h3>
                                        <span class="rounded-full bg-white/[0.06] px-2 py-0.5 text-xs text-stone-400">
                                            {{ $habit->frequency === 'daily' ? 'Diário' : 'Semanal' }}
                                        </span>
                                    </div>
                                    <p class="mb-1 text-xs text-stone-500">{{ $habit->category->name }}</p>
                                    @if($habit->description)
                                        <p class="mt-1 line-clamp-2 text-sm text-stone-400">{{ $habit->description }}</p>
                                    @endif
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-xs {{ $habit->isCompletedToday() ? 'font-semibold text-cyan-300' : 'text-stone-400' }}">
                                        🔥 {{ $habit->currentStreak() }} {{ $habit->currentStreak() === 1 ? 'dia' : 'dias' }}
                                    </span>
                                    <div class="flex items-center gap-3">
                                        @if($habit->isCompletedToday())
                                            <span class="inline-flex items-center px-3 py-1 bg-cyan-300/10 text-cyan-100 text-xs font-medium rounded-full">
                                                ✓ Feito hoje
                                            </span>
                                        @else
                                            <form action="{{ route('checkins.store') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="habit_id" value="{{ $habit->id }}">
                                                <button type="submit"
                                                        class="inline-flex items-center rounded-full bg-cyan-400 px-3 py-1 text-xs font-semibold text-slate-950 shadow-[0_0_18px_rgba(34,211,238,0.25)] transition hover:bg-cyan-300">
                                                    Check-in
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
                                              onsubmit="return confirm('Excluir o hábito \'{{ addslashes($habit->name) }}\'? Esta ação não pode ser desfeita.')">
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
