<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Outfit'] text-2xl font-semibold leading-tight text-white">
            Heróis da Guilda
        </h2>
    </x-slot>

    <div class="hf-page">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="hf-alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Busca --}}
            <div class="mb-4">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col gap-2 sm:flex-row">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Buscar por nome ou e-mail..."
                           class="hf-input flex-1 text-sm">
                    <button type="submit"
                            class="rounded-full bg-cyan-300 px-4 py-2 text-sm text-slate-950 transition hover:bg-cyan-200">
                        Buscar
                    </button>
                    @if($search)
                        <a href="{{ route('admin.users.index') }}"
                           class="rounded-full border border-white/10 bg-white/[0.04] px-4 py-2 text-sm text-stone-200 transition hover:bg-white/[0.08]">
                            Limpar
                        </a>
                    @endif
                </form>
            </div>

            <div class="hf-panel overflow-hidden">
                @if($users->isEmpty())
                    <div class="p-8 text-center text-slate-400 sm:p-12">
                        Nenhum usuário encontrado.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="hf-table min-w-[760px] text-left text-sm">
                            <thead class="hf-table-head border-b border-white/10">
                                <tr>
                                    <th class="px-6 py-3 font-medium text-stone-400">Usuário</th>
                                    <th class="px-6 py-3 text-center font-medium text-stone-400">Missões</th>
                                    <th class="px-6 py-3 text-center font-medium text-stone-400">Chefes</th>
                                    <th class="px-6 py-3 text-center font-medium text-stone-400">Status</th>
                                    <th class="px-6 py-3 text-center font-medium text-stone-400">Cadastro</th>
                                    <th class="px-6 py-3 text-center font-medium text-stone-400">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="hf-table-row">
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-white">{{ $user->name }}</p>
                                            <p class="text-xs text-stone-500">{{ $user->email }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center text-stone-300">
                                            {{ $user->habits_count }}
                                        </td>
                                        <td class="px-6 py-4 text-center text-stone-300">
                                            {{ $user->goals_count }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($user->active)
                                                <span class="inline-block rounded-full bg-cyan-300/10 px-2 py-0.5 text-xs font-medium text-cyan-200">
                                                    Ativo
                                                </span>
                                            @else
                                                <span class="inline-block rounded-full bg-rose-400/10 px-2 py-0.5 text-xs font-medium text-rose-300">
                                                    Inativo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center text-xs text-stone-500">
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('admin.users.toggleActive', $user) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('{{ $user->active ? 'Desativar' : 'Reativar' }} a conta de {{ addslashes($user->name) }}?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="text-xs font-medium {{ $user->active ? 'text-rose-300 hover:text-rose-200' : 'text-cyan-200 hover:text-cyan-100' }}">
                                                    {{ $user->active ? 'Desativar' : 'Reativar' }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($users->hasPages())
                        <div class="border-t border-white/10 px-6 py-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
