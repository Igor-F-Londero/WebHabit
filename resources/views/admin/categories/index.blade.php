<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-['Outfit'] text-2xl font-semibold leading-tight text-white">
                Categorias
            </h2>
            <a href="{{ route('admin.categories.create') }}"
               class="inline-flex items-center rounded-full bg-cyan-300 px-4 py-2 text-sm font-medium text-slate-950 transition hover:bg-cyan-200">
                + Nova Categoria
            </a>
        </div>
    </x-slot>

    <div class="hf-page">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alertas de sessão --}}
            @if(session('success'))
                <div class="hf-alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="hf-alert-error mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="hf-panel overflow-hidden">
                <table class="hf-table">
                    <thead class="hf-table-head">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-stone-400">Ícone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-stone-400">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-stone-400">Descrição</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-stone-400">Hábitos</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-stone-400">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr class="hf-table-row">
                                <td class="px-6 py-4 text-sm text-stone-500">
                                    {{ $category->icon ?? '—' }}
                                </td>
                                <td class="px-6 py-4 font-medium text-white">
                                    {{ $category->name }}
                                </td>
                                <td class="max-w-xs truncate px-6 py-4 text-sm text-stone-400">
                                    {{ $category->description ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-stone-400">
                                    {{ $category->habits_count }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="text-sm font-medium text-cyan-200 hover:text-cyan-100">
                                        Editar
                                    </a>

                                    @if($category->habits_count === 0)
                                        <form action="{{ route('admin.categories.destroy', $category) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Excluir a categoria \'{{ addslashes($category->name) }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-sm font-medium text-rose-300 hover:text-rose-200">
                                                Excluir
                                            </button>
                                        </form>
                                    @else
                                        <span class="cursor-not-allowed text-sm text-stone-600" title="Possui hábitos vinculados">
                                            Excluir
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-stone-400">
                                    Nenhuma categoria cadastrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($categories->hasPages())
                    <div class="border-t border-white/10 px-6 py-4">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
