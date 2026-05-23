<section class="space-y-6">
    <header>
        <h2 class="font-['Outfit'] text-xl font-semibold text-white">
            {{ __('Remover Herói') }}
        </h2>

        <p class="mt-1 text-sm text-stone-400">
            {{ __('Ao remover o Herói, todos os recursos e dados serão excluídos permanentemente.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Remover Herói') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="font-['Outfit'] text-xl font-semibold text-white">
                {{ __('Tem certeza de que deseja remover este Herói?') }}
            </h2>

            <p class="mt-1 text-sm text-stone-400">
                {{ __('Essa ação remove permanentemente seus dados. Digite sua senha para confirmar.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Senha') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Senha') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Remover Herói') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
