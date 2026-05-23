<x-app-layout>
    <x-slot name="hideShellNav">true</x-slot>
    <x-slot name="hideShellHeader">true</x-slot>

    @php
        $nextLevelXp = $game['next_level_xp'];
        $currentLevelXp = $game['current_level_xp'];
        $levelProgress = $game['level_progress_percent'];
        $remainingXp = max(0, $nextLevelXp - $currentLevelXp);
        $boss = $game['weekly_boss'];
        $recentAchievements = collect($game['recent_achievements']);
        $rewards = collect($game['rewards']);
        $recentActivity = collect($game['recent_activity']);
        $inventory = $game['inventory'];
        $inventoryItems = collect($inventory['items']);
        $sidebarItems = [
            ['label' => 'Cockpit', 'href' => route('dashboard'), 'active' => true, 'icon' => 'compass'],
            ['label' => 'Missões', 'href' => route('habits.index'), 'active' => false, 'icon' => 'mission'],
            ['label' => 'Chefes', 'href' => route('goals.index'), 'active' => false, 'icon' => 'boss'],
            ['label' => 'Relatório', 'href' => route('reports.index'), 'active' => false, 'icon' => 'chart'],
            ['label' => 'Conquistas', 'href' => '#conquistas', 'active' => false, 'icon' => 'trophy'],
            ['label' => 'Herói', 'href' => route('profile.edit'), 'active' => false, 'icon' => 'hero'],
            ['label' => 'Guilda', 'href' => auth()->user()?->isAdmin() ? route('admin.dashboard') : route('home'), 'active' => false, 'icon' => 'guild'],
            ['label' => 'Loja', 'href' => '#recompensas', 'active' => false, 'icon' => 'shop'],
            ['label' => 'Configurações', 'href' => route('profile.edit'), 'active' => false, 'icon' => 'settings'],
        ];
        $mobileNavItems = collect($sidebarItems)->only([0, 1, 2, 3, 5]);
        $gameIcons = [
            'basketball' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M4.6 8.2c4.6 1.5 9.1 6.2 11.2 11.2"/><path d="M8.2 4.6c1.5 4.6 6.2 9.1 11.2 11.2"/><path d="M3 12h18"/><path d="M12 3c2.2 2.5 3.3 5.5 3.3 9s-1.1 6.5-3.3 9"/></svg>',
            'boss' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4.5 6.5v5.6c0 4.7 3.1 7.6 7.5 8.9 4.4-1.3 7.5-4.2 7.5-8.9V6.5L12 3Z"/><path d="M8.8 11.2h.01"/><path d="M15.2 11.2h.01"/><path d="M9.5 15.2c1.7 1.1 3.3 1.1 5 0"/></svg>',
            'calendar' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="5" width="16" height="15" rx="3"/><path d="M8 3v4"/><path d="M16 3v4"/><path d="M4 10h16"/><path d="m8 15 2.2 2.2L16 12"/></svg>',
            'chart' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V5"/><path d="M4 19h16"/><path d="M8 16v-4"/><path d="M12 16V8"/><path d="M16 16v-6"/></svg>',
            'chest' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10h16v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8Z"/><path d="M6 10V8a5 5 0 0 1 5-5h2a5 5 0 0 1 5 5v2"/><path d="M4 14h16"/><path d="M12 10v6"/><path d="M10 16h4"/></svg>',
            'colossus' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 19 4.5 9.5 9 4h6l4.5 5.5L17 19H7Z"/><path d="m9 9 3 3 3-3"/><path d="M8.5 14h7"/><path d="M10 19v2"/><path d="M14 19v2"/></svg>',
            'compass' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m15.5 8.5-2 5-5 2 2-5 5-2Z"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="M2 12h2"/><path d="M20 12h2"/></svg>',
            'crown' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m4 8 4 4 4-7 4 7 4-4-2 11H6L4 8Z"/><path d="M6 19h12"/></svg>',
            'dragon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17c2.2-4.8 5.6-7.9 10.8-9.2L20 5l-1.2 4.8 2.2 2.7-4 .6-2.3 3.6-3.3-1.4L8 19l-3-2Z"/><path d="M14.5 8.5 12 4"/><path d="M10.5 11.5 6 9"/><path d="m16 13 3 4"/></svg>',
            'flame' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c4 0 7-2.7 7-6.6 0-2.7-1.5-5-4.3-7.1.2 2.2-.5 3.6-1.8 4.4.1-3.1-1.7-5.7-4.5-8.7.4 4.2-3.4 6.5-3.4 11.3C5 19.3 8 22 12 22Z"/><path d="M12 18c1.4 0 2.4-.9 2.4-2.2 0-1-.5-1.7-1.5-2.5 0 1-.4 1.6-1.1 2-.1-1.2-.8-2.2-1.8-3.4.1 1.9-1.2 2.8-1.2 4 0 1.2 1 2.1 3.2 2.1Z"/></svg>',
            'guardian' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21c-4.4-1.6-7-4.7-7-9.1V6l7-3 7 3v5.9c0 4.4-2.6 7.5-7 9.1Z"/><path d="M9 13c2.7.2 4.8-1 6-4 1.2 4.3-.8 7-3.5 7.2A4.4 4.4 0 0 1 8 14.7"/></svg>',
            'guild' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 20v-2a4 4 0 0 0-8 0v2"/><circle cx="12" cy="8" r="3"/><path d="M20 20v-2a3 3 0 0 0-2.1-2.9"/><path d="M17 5.2a3 3 0 0 1 0 5.6"/><path d="M4 20v-2a3 3 0 0 1 2.1-2.9"/><path d="M7 5.2a3 3 0 0 0 0 5.6"/></svg>',
            'hero' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 7 6v5c0 3.3 2 5.9 5 7 3-1.1 5-3.7 5-7V6l-5-3Z"/><path d="M8 21c1-1.4 2.3-2 4-2s3 .6 4 2"/><path d="M9.5 10.5h.01"/><path d="M14.5 10.5h.01"/></svg>',
            'leviathan' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3c3.4 4 5 7.1 5 10a5 5 0 0 1-10 0c0-2.9 1.6-6 5-10Z"/><path d="M8.5 16.5c2.2 1.2 4.7 1 7-.6"/><path d="M12 7v10"/></svg>',
            'meal' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6c-3.2-1.9-7 .4-7 5.1C5 16.5 8.5 21 12 19c3.5 2 7-2.5 7-7.9C19 6.4 15.2 4.1 12 6Z"/><path d="M12 6c0-2 1.2-3.4 3-4"/><path d="M14.5 4.2c1.3-.2 2.3.1 3.2.8"/></svg>',
            'mission' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 5 7v10l7 4 7-4V7l-7-4Z"/><path d="m9 12 2 2 4-5"/><path d="M5 7l7 4 7-4"/></svg>',
            'mood' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M8.5 10h.01"/><path d="M15.5 10h.01"/><path d="M8.5 14.5c2 2 5 2 7 0"/></svg>',
            'settings' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/><path d="M19.4 15a1.8 1.8 0 0 0 .4 2l.1.1-2 3.4-.2-.1a1.8 1.8 0 0 0-2.1.4l-.1.1-3.9-2.2v-.2a1.8 1.8 0 0 0-1.5-1.5h-.2L8 21H4l-.1-.2a1.8 1.8 0 0 0-.4-2.1l-.1-.1 2-3.4.2.1a1.8 1.8 0 0 0 2.1-.4l.1-.1 3.9 2.2v.2a1.8 1.8 0 0 0 1.5 1.5h.2L16 3h4l.1.2a1.8 1.8 0 0 0 .4 2.1l.1.1-2 3.4-.2-.1a1.8 1.8 0 0 0-2.1.4l-.1.1"/></svg>',
            'shadow' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3c4.5 2 7 5.2 7 9.3 0 4.2-2.8 7-7 8.7-4.2-1.7-7-4.5-7-8.7C5 8.2 7.5 5 12 3Z"/><path d="M9 10.5 7.5 9"/><path d="m15 10.5 1.5-1.5"/><path d="M8.8 15c1.9-1.2 4.5-1.2 6.4 0"/></svg>',
            'shop' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 10h14l-1.2 9H6.2L5 10Z"/><path d="M8 10V8a4 4 0 0 1 8 0v2"/><path d="M4 10h16"/><path d="M9 14h6"/></svg>',
            'skull' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3c-4.3 0-7 2.6-7 6.7 0 2.5 1.2 4.5 3 5.6V20h8v-4.7c1.8-1.1 3-3.1 3-5.6C19 5.6 16.3 3 12 3Z"/><path d="M9.2 11h.01"/><path d="M14.8 11h.01"/><path d="M10 16h4"/></svg>',
            'spark' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 14 9l7 3-7 3-2 7-2-7-7-3 7-3 2-7Z"/><path d="M19 3v4"/><path d="M21 5h-4"/></svg>',
            'study' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H7a3 3 0 0 0-3 3V5.5Z"/><path d="M4 18a3 3 0 0 1 3-3h13"/><path d="M9 7h6"/><path d="M9 11h5"/></svg>',
            'tasks' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3" width="14" height="18" rx="3"/><path d="m8.5 8.5 1.2 1.2 2.3-2.4"/><path d="M14 9h2"/><path d="m8.5 14.5 1.2 1.2 2.3-2.4"/><path d="M14 15h2"/></svg>',
            'training' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14h3l7-7 3 3-7 7v3H4v-6Z"/><path d="m14 7 3-3 3 3-3 3"/><path d="M7 14l3 3"/></svg>',
            'trophy' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 4h8v4.5a4 4 0 0 1-8 0V4Z"/><path d="M8 6H4v2a4 4 0 0 0 4 4"/><path d="M16 6h4v2a4 4 0 0 1-4 4"/><path d="M12 13v5"/><path d="M9 20h6"/></svg>',
            'water' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3c3.2 4 5 6.8 5 10a5 5 0 0 1-10 0c0-3.2 1.8-6 5-10Z"/><path d="M9.2 15.2c1.5 1.2 3.5 1.2 5.1 0"/></svg>',
        ];
        $icon = fn (string $name): string => $gameIcons[$name] ?? $gameIcons['mission'];
    @endphp

    <div class="hf-game-shell">
        <div class="grid min-h-screen lg:grid-cols-[17rem_minmax(0,1fr)]">
            <aside class="hf-game-sidebar hidden border-r px-4 py-6 lg:flex lg:flex-col">
                <a href="{{ route('home') }}" class="mb-8 flex flex-col items-center text-center">
                    <div class="relative flex h-24 w-24 items-center justify-center rounded-full border border-cyan-300/35 bg-cyan-300/[0.06] shadow-[0_0_38px_rgba(34,211,238,0.18)]">
                        <span class="font-['Outfit'] text-4xl font-black text-white">HF</span>
                        <span class="absolute -top-1 h-3 w-3 rotate-45 border border-cyan-200 bg-slate-950"></span>
                        <span class="absolute -bottom-1 h-3 w-3 rotate-45 border border-cyan-200 bg-slate-950"></span>
                    </div>
                    <span class="mt-3 font-['Outfit'] text-2xl font-semibold text-white">HabitFlow</span>
                    <span class="text-xs uppercase tracking-[0.36em] text-cyan-200/70">Guilda</span>
                </a>

                <nav class="space-y-2">
                    @foreach($sidebarItems as $item)
                        <a
                            href="{{ $item['href'] }}"
                            class="group flex items-center gap-3 rounded-lg border px-4 py-3 text-sm transition {{ $item['active'] ? 'border-cyan-300/60 bg-cyan-300/[0.12] text-cyan-100 shadow-[0_0_22px_rgba(34,211,238,0.14)]' : 'border-transparent text-slate-400 hover:border-cyan-300/20 hover:bg-cyan-300/[0.055] hover:text-cyan-100' }}"
                        >
                            <span class="hf-sidebar-icon {{ $item['active'] ? 'text-cyan-100' : 'text-slate-400 group-hover:text-cyan-100' }}">
                                {!! $icon($item['icon']) !!}
                            </span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="mt-auto space-y-4 pt-8">
                    <div class="hf-game-card p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-14 w-14 items-center justify-center rounded-lg border border-cyan-300/25 bg-cyan-300/10 font-['Outfit'] text-lg font-black text-white">
                                {{ $game['avatar_initials'] }}
                            </div>
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-400">Herói da Disciplina</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-xs text-slate-400">
                            <span>Nível {{ $game['level'] }}</span>
                            <span>{{ $currentLevelXp }} / {{ $nextLevelXp }} XP</span>
                        </div>
                        <div class="hf-game-progress mt-2 h-2">
                            <div class="hf-game-progress-fill" style="width: {{ $levelProgress }}%"></div>
                        </div>
                    </div>

                    <div class="hf-game-card hf-game-card-purple p-4">
                        <p class="text-xs uppercase tracking-[0.22em] text-fuchsia-200/75">Combo atual</p>
                        <p class="mt-2 font-['Outfit'] text-4xl font-black text-fuchsia-200">{{ $game['best_streak'] }}</p>
                        <p class="text-xs text-fuchsia-100/75">melhor streak</p>
                    </div>
                </div>
            </aside>

            <main class="min-w-0 px-4 pb-28 pt-5 sm:px-6 lg:px-8 lg:pb-5">
                <div class="mx-auto max-w-[100rem] space-y-5">
                    <div class="flex items-center justify-between gap-3 lg:hidden">
                        <a href="{{ route('home') }}" class="flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-lg border border-cyan-300/25 bg-cyan-300/10 font-['Outfit'] font-black text-white">HF</span>
                            <span>
                                <span class="block font-['Outfit'] text-lg font-semibold text-white">HabitFlow</span>
                                <span class="block text-xs uppercase tracking-[0.24em] text-cyan-200/65">Guilda</span>
                            </span>
                        </a>
                        <a href="{{ route('home') }}" class="rounded-lg border border-cyan-300/20 px-3 py-2 text-xs font-semibold text-cyan-100">Guilda</a>
                    </div>

                    @if(session('success'))
                        <div class="hf-alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="hf-alert-error">
                            {{ session('error') }}
                        </div>
                    @endif

                    <header class="grid gap-5 xl:grid-cols-[1fr_0.92fr] xl:items-center">
                        <div>
                            <p class="text-xs uppercase tracking-[0.32em] text-cyan-200/80">Cockpit de Missão</p>
                            <h1 class="mt-2 font-['Outfit'] text-4xl font-black leading-none text-white sm:text-5xl">
                                Dashboard
                            </h1>
                            <p class="mt-2 text-base text-slate-300">
                                Transforme sua rotina em <span class="font-semibold text-cyan-200">progresso.</span>
                            </p>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-[1fr_1fr_auto]">
                            <div class="hf-game-card hf-game-card-cyan p-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-xl border border-cyan-300/35 bg-cyan-300/10 font-['Outfit'] text-2xl font-black text-white">
                                        {{ $game['level'] }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs uppercase tracking-[0.22em] text-cyan-200/75">Nível atual</p>
                                        <p class="truncate font-semibold text-white">{{ $game['rank'] }}</p>
                                        <div class="mt-2 flex items-center gap-3">
                                            <div class="hf-game-progress h-2 flex-1">
                                                <div class="h-full rounded-full bg-cyan-300" style="width: {{ $levelProgress }}%"></div>
                                            </div>
                                            <span class="text-xs text-slate-300">{{ $currentLevelXp }} / {{ $nextLevelXp }} XP</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="hf-game-card hf-game-card-purple p-4">
                                <p class="text-xs uppercase tracking-[0.22em] text-fuchsia-200/75">Próximo nível</p>
                                <p class="mt-2 font-semibold text-fuchsia-100">Mestre da Constância</p>
                                <p class="mt-1 text-xs text-slate-400">{{ $remainingXp }} XP restantes</p>
                            </div>

                            <div class="hf-game-card hidden h-full w-20 place-items-center rounded-full p-3 sm:grid">
                                <div class="relative flex h-12 w-12 items-center justify-center rounded-full border border-cyan-300/20 bg-cyan-300/10 text-cyan-100">
                                    <span class="font-['Outfit'] text-lg font-black">3</span>
                                </div>
                            </div>
                        </div>
                    </header>

                    <section class="grid gap-5 xl:grid-cols-[1.08fr_1fr]">
                        <div class="hf-game-card overflow-hidden p-6">
                            <div class="grid gap-6 md:grid-cols-[1fr_13rem] md:items-center">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">Campanha de hoje</p>
                                    <h2 class="mt-4 font-['Outfit'] text-4xl font-black leading-none text-white">
                                        {{ $game['quest_done'] }} de {{ $game['quest_total'] }} missões concluídas
                                    </h2>
                                    <p class="mt-4 max-w-md text-sm leading-7 text-slate-400">
                                        Recompensas prontas, combos em risco e dados atualizados.
                                    </p>
                                </div>
                                <div class="hidden h-44 items-center justify-center rounded-full border border-cyan-300/15 bg-cyan-300/[0.035] md:flex">
                                    <div class="flex h-28 w-28 items-center justify-center rounded-2xl border border-cyan-300/30 bg-slate-950/80 font-['Outfit'] text-4xl font-black text-cyan-100 shadow-[0_0_34px_rgba(34,211,238,0.18)]">
                                        {{ $game['quest_done'] }}/{{ $game['quest_total'] }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <div class="mb-2 flex items-center justify-between gap-3 text-xs text-slate-400">
                                    <span>Progresso das missões</span>
                                    <span>{{ $game['quest_progress_percent'] }}%</span>
                                </div>
                                <div class="hf-game-progress">
                                    <div class="hf-game-progress-fill" style="width: {{ $game['quest_progress_percent'] }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <div class="hf-game-card hf-game-card-cyan hf-stat-card p-5">
                                <span class="hf-stat-icon text-cyan-200">{!! $icon('hero') !!}</span>
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Nível</p>
                                <p class="mt-3 font-['Outfit'] text-4xl font-black text-white">{{ $game['level'] }}</p>
                                <p class="mt-1 text-xs font-semibold text-cyan-200">{{ $game['rank'] }}</p>
                            </div>
                            <div class="hf-game-card hf-game-card-amber hf-stat-card p-5">
                                <span class="hf-stat-icon text-amber-200">{!! $icon('shop') !!}</span>
                                <p class="text-xs uppercase tracking-[0.2em] text-amber-200/80">Moedas</p>
                                <p class="mt-3 font-['Outfit'] text-4xl font-black text-amber-300">{{ $game['coins'] }}</p>
                                <p class="mt-1 text-xs text-slate-400">para recompensas</p>
                            </div>
                            <div class="hf-game-card hf-game-card-purple hf-stat-card p-5">
                                <span class="hf-stat-icon text-fuchsia-200">{!! $icon('flame') !!}</span>
                                <p class="text-xs uppercase tracking-[0.2em] text-fuchsia-200/80">Combo</p>
                                <p class="mt-3 font-['Outfit'] text-4xl font-black text-fuchsia-200">{{ $game['best_streak'] }}</p>
                                <p class="mt-1 text-xs text-slate-400">melhor streak</p>
                            </div>
                            <div class="hf-game-card hf-stat-card p-4">
                                <span class="hf-stat-icon text-cyan-200">{!! $icon('calendar') !!}</span>
                                <p class="text-xs uppercase tracking-wide text-slate-500">Consistência</p>
                                <p class="mt-2 font-['Outfit'] text-3xl font-black text-cyan-300">{{ $consistencyRate }}%</p>
                                <p class="text-xs text-slate-400">30 dias</p>
                            </div>
                            <div class="hf-game-card hf-stat-card p-4">
                                <span class="hf-stat-icon text-emerald-200">{!! $icon('mission') !!}</span>
                                <p class="text-xs uppercase tracking-wide text-slate-500">Check-ins hoje</p>
                                <p class="mt-2 font-['Outfit'] text-3xl font-black text-white">{{ $checkinsToday }}</p>
                                <p class="text-xs text-slate-400">de {{ $todayHabits->count() }} missões</p>
                            </div>
                            <div class="hf-game-card hf-stat-card p-4">
                                <span class="hf-stat-icon text-fuchsia-200">{!! $icon('spark') !!}</span>
                                <p class="text-xs uppercase tracking-wide text-slate-500">XP total</p>
                                <p class="mt-2 font-['Outfit'] text-3xl font-black text-fuchsia-200">{{ $game['total_xp'] }}</p>
                                <p class="text-xs text-slate-400">{{ $currentLevelXp }}/{{ $nextLevelXp }} no nível</p>
                            </div>
                        </div>
                    </section>

                    <section class="grid gap-5 xl:grid-cols-[1.25fr_0.92fr]">
                        <div class="hf-game-card p-5">
                            <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">Missões de hoje</p>
                                    <h2 class="font-['Outfit'] text-2xl font-black text-white">Missões de hoje</h2>
                                </div>
                                <a href="{{ route('habits.index') }}" class="text-sm text-cyan-200 hover:text-cyan-100">Ver todas</a>
                            </div>

                            @if($game['quests']->isEmpty())
                                <p class="py-6 text-center text-sm text-stone-400">
                                    Nenhuma missão ativa.
                                    <a href="{{ route('habits.create') }}" class="text-cyan-200 hover:underline">Criar uma Missão</a>
                                </p>
                            @else
                                <div class="grid gap-4 md:grid-cols-2">
                                    @foreach($game['quests']->take(6) as $quest)
                                        <div class="rounded-xl border border-white/10 bg-white/[0.035] p-4 shadow-[inset_3px_0_0_0_var(--quest-color)]" style="--quest-color: {{ $quest['color'] }}">
                                            <div class="flex items-start gap-4">
                                                <div class="hf-mission-icon" style="color: {{ $quest['color'] }}">
                                                    {!! $icon($quest['icon'] ?? 'mission') !!}
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        <p class="truncate text-sm font-semibold text-white">{{ $quest['name'] }}</p>
                                                        <span class="rounded-md bg-slate-900 px-2 py-0.5 text-[11px] font-semibold text-slate-300">{{ $quest['difficulty'] }}</span>
                                                    </div>
                                                    <p class="mt-1 text-xs text-slate-500">{{ $quest['category'] }} · {{ $quest['frequency'] }}</p>
                                                    <p class="mt-2 text-xs font-bold text-amber-200">+{{ $quest['reward_xp'] }} XP · +{{ $quest['reward_coins'] }} moedas</p>
                                                </div>
                                            </div>

                                            <div class="mt-4 flex items-center justify-between gap-3">
                                                <span class="text-xs font-medium text-fuchsia-200">Combo: {{ $quest['streak'] }}</span>
                                                @if($quest['completed'])
                                                    <span class="rounded-full border border-emerald-300/20 bg-emerald-300/10 px-3 py-1 text-xs font-semibold text-emerald-200">Concluída</span>
                                                @else
                                                    <form action="{{ route('checkins.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="habit_id" value="{{ $quest['id'] }}">
                                                        <button type="submit" class="rounded-full bg-cyan-300 px-3 py-1 text-xs font-bold text-slate-950 transition hover:bg-cyan-200">
                                                            Concluir
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="space-y-5">
                            <section class="hf-boss-card overflow-hidden p-5">
                                <div class="hf-boss-illustration">
                                    {!! $icon($boss['icon'] ?? 'dragon') !!}
                                </div>
                                <div class="relative z-10">
                                    <div class="mb-4 flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.24em] text-fuchsia-200/80">{{ $boss['subtitle'] }}</p>
                                            <h2 class="mt-3 font-['Outfit'] text-3xl font-black leading-tight text-white">
                                                {{ $boss['name'] }}
                                            </h2>
                                        </div>
                                        <div class="hf-boss-mark">
                                            {!! $icon($boss['icon'] ?? 'dragon') !!}
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Objetivo</p>
                                        <p class="mt-1 text-sm leading-6 text-slate-300">{{ $boss['objective'] }}</p>
                                    </div>

                                    <div class="mt-5">
                                        <div class="mb-2 flex items-center justify-between gap-3 text-xs text-slate-400">
                                            <span>Vida do chefe</span>
                                            <span>{{ $boss['progress_percent'] }}%</span>
                                        </div>
                                        <div class="flex gap-1">
                                            @foreach(range(1, 10) as $segment)
                                                <div class="h-3 flex-1 rounded-full {{ $segment <= ceil($boss['progress_percent'] / 10) ? 'bg-fuchsia-400 shadow-[0_0_12px_rgba(217,70,239,0.45)]' : 'bg-slate-900 ring-1 ring-white/5' }}"></div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                                            <span>{{ $boss['current'] }} / {{ $boss['target'] }} check-ins</span>
                                            <span>{{ $boss['days_remaining'] }} dias restantes</span>
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Recompensa</p>
                                        <div class="mt-3 flex flex-wrap gap-3 text-sm font-semibold">
                                            <span class="rounded-full border border-fuchsia-300/20 bg-fuchsia-300/10 px-3 py-1 text-fuchsia-100">+{{ $boss['reward_xp'] }} XP</span>
                                            <span class="rounded-full border border-amber-300/20 bg-amber-300/10 px-3 py-1 text-amber-200">+{{ $boss['reward_coins'] }} moedas</span>
                                            <span class="rounded-full border border-cyan-300/20 bg-cyan-300/10 px-3 py-1 text-cyan-100">{{ $boss['reward_label'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section id="recompensas" class="hf-game-card hf-reward-shop p-5">
                                <div class="mb-4 flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.24em] text-amber-200/80">Loja de Recompensas</p>
                                        <h2 class="font-['Outfit'] text-xl font-black text-white">Recompensas</h2>
                                    </div>
                                    <div class="rounded-xl border border-amber-300/25 bg-amber-300/10 px-3 py-2 text-right">
                                        <p class="text-[10px] uppercase tracking-[0.18em] text-amber-100/70">Moedas</p>
                                        <p class="font-['Outfit'] text-2xl font-black text-amber-300">{{ $game['coins'] }}</p>
                                        <p class="text-[10px] text-amber-100/55">{{ $game['spent_coins'] }} gastas</p>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    @foreach($rewards as $reward)
                                        <div class="hf-reward-card {{ $reward['available'] ? 'hf-reward-card-ready' : '' }}">
                                            <div class="hf-reward-icon {{ $reward['available'] ? 'text-amber-200' : 'text-slate-500' }}">
                                                {!! $icon($reward['icon'] ?? 'shop') !!}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <p class="truncate text-sm font-semibold text-white">{{ $reward['name'] }}</p>
                                                    <span class="rounded-md border border-white/10 bg-slate-950/70 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-400">{{ $reward['tier'] }}</span>
                                                </div>
                                                <p class="mt-1 text-xs leading-5 text-slate-500">{{ $reward['description'] }}</p>
                                                <div class="hf-reward-progress mt-3">
                                                    <div class="hf-reward-progress-fill" style="width: {{ $reward['progress_percent'] }}%"></div>
                                                </div>
                                            </div>
                                            <div class="shrink-0 text-right">
                                                <p class="font-['Outfit'] text-xl font-black text-amber-300">{{ $reward['cost'] }}</p>
                                                <p class="text-[10px] uppercase tracking-[0.16em] text-amber-100/65">moedas</p>
                                                @if($reward['available'])
                                                    <form action="{{ route('rewards.redeem') }}" method="POST" class="mt-2">
                                                        @csrf
                                                        <input type="hidden" name="reward_key" value="{{ $reward['key'] }}">
                                                        <button type="submit" class="rounded-full border border-emerald-300/25 bg-emerald-300/10 px-2.5 py-1 text-[11px] font-bold text-emerald-200 transition hover:bg-emerald-300/20">
                                                            Resgatar
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="mt-2 inline-flex rounded-full border border-slate-700 bg-slate-900/80 px-2.5 py-1 text-[11px] font-bold text-slate-500">
                                                        Faltam {{ $reward['shortfall'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </section>

                            <section class="hf-game-card hf-inventory-card p-5">
                                <div class="mb-4 flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">Inventário do Herói</p>
                                        <h2 class="font-['Outfit'] text-xl font-black text-white">Economia</h2>
                                    </div>
                                    <div class="hf-inventory-sigil text-amber-200">
                                        {!! $icon('chest') !!}
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

                                <div class="mt-4 space-y-3">
                                    @if($inventoryItems->isEmpty())
                                        <p class="rounded-xl border border-cyan-300/10 bg-cyan-300/[0.035] p-4 text-sm text-slate-400">
                                            Nenhuma recompensa resgatada ainda.
                                        </p>
                                    @else
                                        @foreach($inventoryItems as $item)
                                            <div class="hf-inventory-item">
                                                <div class="hf-inventory-item-icon text-amber-200">
                                                    {!! $icon($item['icon'] ?? 'shop') !!}
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

                            <section class="hf-game-card p-5">
                                <div class="mb-4">
                                    <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">Linha do tempo</p>
                                    <h2 class="font-['Outfit'] text-xl font-black text-white">Atividade Recente</h2>
                                </div>

                                @if($recentActivity->isEmpty())
                                    <p class="rounded-xl border border-cyan-300/10 bg-cyan-300/[0.035] p-4 text-sm text-slate-400">
                                        Complete missões ou resgate recompensas para iniciar o histórico do Herói.
                                    </p>
                                @else
                                    <div class="space-y-3">
                                        @foreach($recentActivity as $activity)
                                            <div class="hf-activity-card {{ $activity['tone'] === 'amber' ? 'hf-activity-card-amber' : '' }}">
                                                <div class="hf-activity-icon {{ $activity['tone'] === 'amber' ? 'text-amber-200' : 'text-cyan-200' }}">
                                                    {!! $icon($activity['icon'] ?? 'mission') !!}
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                                        <p class="truncate text-sm font-semibold text-white">{{ $activity['title'] }}</p>
                                                        <span class="text-[11px] text-slate-500">{{ $activity['time'] }}</span>
                                                    </div>
                                                    <p class="mt-1 truncate text-xs text-slate-500">{{ $activity['description'] }}</p>
                                                </div>
                                                <span class="shrink-0 text-xs font-bold {{ $activity['tone'] === 'amber' ? 'text-amber-200' : 'text-cyan-200' }}">
                                                    {{ $activity['meta'] }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </section>

                            <section id="conquistas" class="hf-game-card p-5">
                                <div class="mb-4 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.24em] text-cyan-200/70">Conquistas recentes</p>
                                        <h2 class="font-['Outfit'] text-xl font-black text-white">Conquistas Recentes</h2>
                                    </div>
                                    <a href="{{ route('home') }}#conquistas" class="text-sm text-cyan-200 hover:text-cyan-100">Ver todas</a>
                                </div>

                                <div class="space-y-3">
                                    @foreach($recentAchievements as $achievement)
                                        <div class="hf-achievement-card">
                                            <div class="hf-achievement-medal {{ $achievement['unlocked'] ? 'border-cyan-300/35 bg-cyan-300/10 text-cyan-100' : 'border-slate-700 bg-slate-900 text-slate-500' }}">
                                                {!! $icon($achievement['icon'] ?? 'trophy') !!}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-semibold text-white">{{ $achievement['name'] }}</p>
                                                <p class="mt-1 text-xs text-slate-500">{{ $achievement['description'] }}</p>
                                            </div>
                                            <div class="shrink-0 text-right">
                                                <p class="text-sm font-bold {{ $achievement['unlocked'] ? 'text-cyan-200' : 'text-slate-500' }}">+{{ $achievement['xp'] }} XP</p>
                                                <p class="mt-1 text-xs text-slate-500">{{ $achievement['date'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        </div>
                    </section>

                    <section class="grid gap-5 xl:grid-cols-[0.85fr_1.15fr]">
                        <div class="space-y-5">
                            <section class="hf-game-card p-5">
                                <h2 class="font-['Outfit'] text-xl font-black text-white">Relatório dos últimos 7 dias</h2>
                                <div class="mt-4">
                                    <canvas id="checkinChart" height="120"></canvas>
                                </div>
                            </section>

                            @if($topStreaks->isNotEmpty())
                                <section class="hf-game-card p-5">
                                    <h2 class="font-['Outfit'] text-xl font-black text-white">Top combos ativos</h2>
                                    <div class="mt-4 space-y-3">
                                        @foreach($topStreaks as $i => $habit)
                                            @php $streak = $habit->currentStreak(); @endphp
                                            <div class="flex items-center gap-3 rounded-xl border border-cyan-300/10 bg-cyan-300/[0.045] p-3">
                                                <span class="text-2xl font-black text-cyan-200">#{{ $i + 1 }}</span>
                                                <div class="min-w-0 flex-1">
                                                    <p class="truncate text-sm font-semibold text-white">{{ $habit->name }}</p>
                                                    <p class="text-xs font-medium text-cyan-300">{{ $streak }} {{ $streak === 1 ? 'dia' : 'dias' }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                        </div>

                        <section class="hf-game-card p-5">
                            <h2 class="mb-1 font-['Outfit'] text-xl font-black text-white">Atividade do último ano</h2>
                            <p class="mb-4 text-xs text-stone-400">Cada célula representa um dia; mais escuro significa mais check-ins</p>

                        @php
                            $heatStart = today()->subWeeks(51)->startOfWeek(\Carbon\Carbon::MONDAY);
                            $weeks = collect(range(0, 51))->map(function ($w) use ($heatStart, $heatmapData) {
                                return collect(range(0, 6))->map(function ($d) use ($heatStart, $w, $heatmapData) {
                                    $date = $heatStart->copy()->addWeeks($w)->addDays($d);
                                    $count = $heatmapData->get($date->toDateString(), 0);
                                    $future = $date->isAfter(today());
                                    return compact('date', 'count', 'future');
                                });
                            });

                            $cellColor = function (int $count, bool $future): string {
                                if ($future) return 'bg-stone-900/60 ring-1 ring-white/5';
                                if ($count === 0) return 'bg-stone-800 ring-1 ring-white/5';
                                if ($count === 1) return 'bg-cyan-300/50 shadow-[0_0_10px_rgba(103,232,249,0.28)]';
                                if ($count <= 3) return 'bg-cyan-400 shadow-[0_0_12px_rgba(34,211,238,0.35)]';
                                if ($count <= 5) return 'bg-fuchsia-400 shadow-[0_0_14px_rgba(217,70,239,0.35)]';
                                return 'bg-pink-400 shadow-[0_0_16px_rgba(244,114,182,0.42)]';
                            };

                            $dayLabels = ['Seg', '', 'Qua', '', 'Sex', '', 'Dom'];
                        @endphp

                        <div class="flex gap-1 overflow-x-auto pb-2">
                            <div class="mr-1 flex shrink-0 flex-col gap-1">
                                <div class="h-3"></div>
                                @foreach($dayLabels as $label)
                                    <div class="flex h-3 items-center">
                                        <span class="w-6 text-[9px] text-stone-500">{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>

                            @foreach($weeks as $week)
                                <div class="flex shrink-0 flex-col gap-1">
                                    @php $firstDay = $week->first()['date']; @endphp
                                    <div class="flex h-3 items-center">
                                        @if($firstDay->day <= 7)
                                            <span class="whitespace-nowrap text-[9px] text-stone-500">
                                                {{ $firstDay->translatedFormat('M') }}
                                            </span>
                                        @endif
                                    </div>
                                    @foreach($week as $cell)
                                        <div class="h-3 w-3 rounded-sm {{ $cellColor($cell['count'], $cell['future']) }}"
                                             title="{{ $cell['date']->format('d/m/Y') }}{{ $cell['count'] > 0 ? ' - ' . $cell['count'] . ' check-in(s)' : '' }}">
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3 flex items-center gap-1">
                            <span class="mr-1 text-xs text-stone-500">Menos</span>
                            <div class="h-3 w-3 rounded-sm bg-stone-800 ring-1 ring-white/5"></div>
                            <div class="h-3 w-3 rounded-sm bg-cyan-300/50"></div>
                            <div class="h-3 w-3 rounded-sm bg-cyan-400"></div>
                            <div class="h-3 w-3 rounded-sm bg-fuchsia-400"></div>
                            <div class="h-3 w-3 rounded-sm bg-pink-400"></div>
                            <span class="ml-1 text-xs text-stone-500">Mais</span>
                        </div>
                        </section>
                    </section>

                    <p class="pb-2 text-center font-['Outfit'] text-sm tracking-[0.12em] text-cyan-200/80">
                        Pequenas ações diárias. Grandes conquistas eternas.
                    </p>
                </div>
            </main>
        </div>

        <nav class="hf-mobile-questbar lg:hidden" aria-label="Navegação rápida">
            @foreach($mobileNavItems as $item)
                <a
                    href="{{ $item['href'] }}"
                    class="hf-mobile-questbar-item {{ $item['active'] ? 'text-cyan-100' : 'text-slate-400' }}"
                    aria-current="{{ $item['active'] ? 'page' : 'false' }}"
                >
                    <span class="hf-mobile-questbar-icon {{ $item['active'] ? 'border-cyan-300/55 bg-cyan-300/15 text-cyan-100 shadow-[0_0_18px_rgba(34,211,238,0.22)]' : 'border-cyan-300/15 bg-slate-950 text-slate-400' }}">
                        {!! $icon($item['icon']) !!}
                    </span>
                    <span class="text-[10px] font-semibold leading-none">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        new Chart(document.getElementById('checkinChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! $chartLabels->toJson() !!},
                datasets: [{
                    label: 'Check-ins',
                    data: {!! $chartData->toJson() !!},
                    backgroundColor: 'rgba(34, 211, 238, 0.72)',
                    borderColor: 'rgba(103, 232, 249, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0, color: 'rgba(203,213,225,0.72)' },
                        grid: { color: 'rgba(148, 163, 184, 0.08)' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: 'rgba(203,213,225,0.72)' }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
