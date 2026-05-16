<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Categoria: {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">

                        <div>
                            <x-input-label for="name" value="Nome *" />
                            <x-text-input id="name" name="name" type="text"
                                class="mt-1 block w-full"
                                :value="old('name', $category->name)"
                                required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Descrição" />
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $category->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="icon" value="Ícone" />
                            <x-text-input id="icon" name="icon" type="text"
                                class="mt-1 block w-full"
                                :value="old('icon', $category->icon)"
                                placeholder="Ex: ti-heart, ti-book..." />
                            <p class="mt-1 text-xs text-gray-400">Classe de ícone (Tabler Icons).</p>
                            <x-input-error :messages="$errors->get('icon')" class="mt-1" />
                        </div>

                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <x-primary-button>Atualizar</x-primary-button>
                        <a href="{{ route('admin.categories.index') }}"
                           class="text-sm text-gray-600 hover:text-gray-900">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
