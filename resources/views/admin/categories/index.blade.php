<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categorias
            </h2>
            <a href="{{ route('admin.categories.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition">
                + Nova Categoria
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alertas de sessão --}}
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

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ícone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hábitos</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-500 text-sm">
                                    {{ $category->icon ?? '—' }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $category->name }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-sm max-w-xs truncate">
                                    {{ $category->description ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-sm">
                                    {{ $category->habits_count }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
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
                                                    class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                Excluir
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-300 text-sm cursor-not-allowed" title="Possui hábitos vinculados">
                                            Excluir
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                    Nenhuma categoria cadastrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($categories->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
