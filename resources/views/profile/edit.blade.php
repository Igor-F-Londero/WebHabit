<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Outfit'] text-2xl font-semibold leading-tight text-white">
            {{ __('Herói') }}
        </h2>
    </x-slot>

    @php
        $inventory = $game['inventory'];
        $inventoryItems = collect($inventory['items']);
        $heroIcons = [
            'calendar' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="5" width="16" height="15" rx="3"/><path d="M8 3v4"/><path d="M16 3v4"/><path d="M4 10h16"/><path d="m8 15 2.2 2.2L16 12"/></svg>',
            'chest' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10h16v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8Z"/><path d="M6 10V8a5 5 0 0 1 5-5h2a5 5 0 0 1 5 5v2"/><path d="M4 14h16"/><path d="M12 10v6"/><path d="M10 16h4"/></svg>',
            'crown' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m4 8 4 4 4-7 4 7 4-4-2 11H6L4 8Z"/><path d="M6 19h12"/></svg>',
            'shop' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 10h14l-1.2 9H6.2L5 10Z"/><path d="M8 10V8a4 4 0 0 1 8 0v2"/><path d="M4 10h16"/><path d="M9 14h6"/></svg>',
        ];
        $heroIcon = fn (string $name): string => $heroIcons[$name] ?? $heroIcons['shop'];
    @endphp

    <div class="hf-page">
        <div class="hf-container space-y-6">
            <section class="hf-game-card hf-inventory-card p-5 sm:p-6">
                <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">Inventário do Herói</p>
                        <h2 class="font-['Outfit'] text-2xl font-black text-white">Economia</h2>
                    </div>
                    <div class="hf-inventory-sigil text-amber-200">
                        {!! $heroIcon('chest') !!}
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="hf-economy-tile">
                        <p class="text-[10px] uppercase tracking-[0.16em] text-slate-500">Saldo</p>
                        <p class="font-['Outfit'] text-2xl font-black text-amber-300">{{ $inventory['balance'] }}</p>
                    </div>
                    <div class="hf-economy-tile">
                        <p class="text-[10px] uppercase tracking-[0.16em] text-slate-500">Ganhas</p>
                        <p class="font-['Outfit'] text-2xl font-black text-cyan-200">{{ $inventory['earned_coins'] }}</p>
                    </div>
                    <div class="hf-economy-tile">
                        <p class="text-[10px] uppercase tracking-[0.16em] text-slate-500">Gastas</p>
                        <p class="font-['Outfit'] text-2xl font-black text-fuchsia-200">{{ $inventory['spent_coins'] }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="mb-2 flex items-center justify-between text-xs text-slate-500">
                        <span>Uso das moedas</span>
                        <span>{{ $inventory['spend_rate_percent'] }}%</span>
                    </div>
                    <div class="hf-inventory-meter">
                        <div class="hf-inventory-meter-fill" style="width: {{ $inventory['spend_rate_percent'] }}%"></div>
                    </div>
                </div>

                <div class="mt-4 grid gap-3 lg:grid-cols-2">
                    @if($inventoryItems->isEmpty())
                        <p class="rounded-xl border border-cyan-300/10 bg-cyan-300/[0.035] p-4 text-sm text-slate-400 lg:col-span-2">
                            Nenhuma recompensa resgatada ainda.
                        </p>
                    @else
                        @foreach($inventoryItems as $item)
                            <div class="hf-inventory-item">
                                <div class="hf-inventory-item-icon text-amber-200">
                                    {!! $heroIcon($item['icon'] ?? 'shop') !!}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="truncate text-sm font-semibold text-white">{{ $item['name'] }}</p>
                                        <span class="rounded-md border border-white/10 bg-slate-950/70 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-400">{{ $item['tier'] }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500">{{ $item['last_redeemed_for_humans'] }} · {{ $item['total_spent'] }} moedas usadas</p>
                                </div>
                                <span class="rounded-full border border-amber-300/20 bg-amber-300/10 px-3 py-1 text-xs font-bold text-amber-200">
                                    x{{ $item['quantity'] }}
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>

            <div class="hf-panel p-4 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="hf-panel p-4 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="hf-panel p-4 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
