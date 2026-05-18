<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Meta: {{ $goal->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('goals.update', $goal) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">

                        <div>
                            <x-input-label for="title" value="Título *" />
                            <x-text-input id="title" name="title" type="text"
                                class="mt-1 block w-full"
                                :value="old('title', $goal->title)"
                                required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="habit_id" value="Hábito vinculado *" />
                            <select id="habit_id" name="habit_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($habits as $habit)
                                    <option value="{{ $habit->id }}"
                                        {{ old('habit_id', $goal->habit_id) == $habit->id ? 'selected' : '' }}>
                                        {{ $habit->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('habit_id')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="target_count" value="Meta de check-ins *" />
                            <x-text-input id="target_count" name="target_count" type="number"
                                class="mt-1 block w-full"
                                :value="old('target_count', $goal->target_count)"
                                min="1" max="9999"
                                required />
                            <x-input-error :messages="$errors->get('target_count')" class="mt-1" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="start_date" value="Data de início *" />
                                <x-text-input id="start_date" name="start_date" type="date"
                                    class="mt-1 block w-full"
                                    :value="old('start_date', $goal->start_date->format('Y-m-d'))"
                                    required />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="end_date" value="Data de término *" />
                                <x-text-input id="end_date" name="end_date" type="date"
                                    class="mt-1 block w-full"
                                    :value="old('end_date', $goal->end_date->format('Y-m-d'))"
                                    required />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-1" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description" value="Descrição" />
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $goal->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-1" />
                        </div>

                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <x-primary-button>Salvar Alterações</x-primary-button>
                        <a href="{{ route('goals.index') }}"
                           class="text-sm text-gray-600 hover:text-gray-900">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
