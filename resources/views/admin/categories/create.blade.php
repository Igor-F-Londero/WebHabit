<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Outfit'] text-2xl font-semibold leading-tight text-white">
            Nova Categoria
        </h2>
    </x-slot>

    <div class="hf-page">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="hf-panel-pad">

                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf

                    <div class="space-y-5">

                        <div>
                            <x-input-label for="name" value="Nome *" />
                            <x-text-input id="name" name="name" type="text"
                                class="mt-1 block w-full"
                                :value="old('name')"
                                placeholder="Ex: Saúde, Estudo..."
                                required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Descrição" />
                            <textarea id="description" name="description" rows="3"
                                class="hf-textarea mt-1 block w-full"
                                placeholder="Descrição opcional da categoria">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="icon" value="Ícone" />
                            <x-text-input id="icon" name="icon" type="text"
                                class="mt-1 block w-full"
                                :value="old('icon')"
                                placeholder="Ex: ti-heart, ti-book..." />
                            <p class="hf-note mt-1">Classe de ícone. Deixe em branco para não exibir.</p>
                            <x-input-error :messages="$errors->get('icon')" class="mt-1" />
                        </div>

                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <x-primary-button>Salvar</x-primary-button>
                        <a href="{{ route('admin.categories.index') }}"
                           class="hf-subtle-link">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
