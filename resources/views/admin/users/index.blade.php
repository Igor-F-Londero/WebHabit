<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gerenciar Usuários
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Busca --}}
            <div class="mb-4">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Buscar por nome ou e-mail..."
                           class="flex-1 rounded-md border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <button type="submit"
                            class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700 transition">
                        Buscar
                    </button>
                    @if($search)
                        <a href="{{ route('admin.users.index') }}"
                           class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300 transition">
                            Limpar
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                @if($users->isEmpty())
                    <div class="p-12 text-center text-gray-400">
                        Nenhum usuário encontrado.
                    </div>
                @else
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 font-medium text-gray-600">Usuário</th>
                                <th class="px-6 py-3 font-medium text-gray-600 text-center">Hábitos</th>
                                <th class="px-6 py-3 font-medium text-gray-600 text-center">Metas</th>
                                <th class="px-6 py-3 font-medium text-gray-600 text-center">Status</th>
                                <th class="px-6 py-3 font-medium text-gray-600 text-center">Cadastro</th>
                                <th class="px-6 py-3 font-medium text-gray-600 text-center">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-700">
                                        {{ $user->habits_count }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-700">
                                        {{ $user->goals_count }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($user->active)
                                            <span class="inline-block px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full">
                                                Ativo
                                            </span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded-full">
                                                Inativo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-500 text-xs">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.users.toggleActive', $user) }}"
                                              method="POST"
                                              onsubmit="return confirm('{{ $user->active ? 'Desativar' : 'Reativar' }} a conta de {{ addslashes($user->name) }}?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="text-xs font-medium {{ $user->active ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' }}">
                                                {{ $user->active ? 'Desativar' : 'Reativar' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($users->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $users->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
