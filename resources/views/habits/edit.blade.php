<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Outfit'] text-2xl font-semibold leading-tight text-white">
            Editar Hábito: {{ $habit->name }}
        </h2>
    </x-slot>

    <div class="hf-page">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="hf-panel-pad">

                <form action="{{ route('habits.update', $habit) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">

                        <div>
                            <x-input-label for="name" value="Nome *" />
                            <x-text-input id="name" name="name" type="text"
                                class="mt-1 block w-full"
                                :value="old('name', $habit->name)"
                                required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="category_id" value="Categoria *" />
                            <select id="category_id" name="category_id" required
                                class="hf-select mt-1 block w-full">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $habit->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="frequency" value="Frequência *" />
                            <select id="frequency" name="frequency" required
                                class="hf-select mt-1 block w-full">
                                <option value="daily"  {{ old('frequency', $habit->frequency) === 'daily'  ? 'selected' : '' }}>Diário</option>
                                <option value="weekly" {{ old('frequency', $habit->frequency) === 'weekly' ? 'selected' : '' }}>Semanal</option>
                            </select>
                            <x-input-error :messages="$errors->get('frequency')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="color" value="Cor de identificação *" />
                            <div class="mt-1 flex items-center gap-3">
                                <input id="color" name="color" type="color"
                                    value="{{ old('color', $habit->color) }}"
                                    class="h-10 w-16 cursor-pointer rounded-xl border border-white/10 bg-stone-950" />
                                <span class="text-sm text-stone-400">Cor atual do hábito</span>
                            </div>
                            <x-input-error :messages="$errors->get('color')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Descrição" />
                            <textarea id="description" name="description" rows="3"
                                class="hf-textarea mt-1 block w-full">{{ old('description', $habit->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-1" />
                        </div>

                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <x-primary-button>Salvar Alterações</x-primary-button>
                        <a href="{{ route('habits.index') }}"
                           class="hf-subtle-link">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
