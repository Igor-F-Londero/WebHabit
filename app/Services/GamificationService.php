<?php

namespace App\Services;

use App\Models\Checkin;
use App\Models\Goal;
use App\Models\Habit;
use App\Models\RewardRedemption;
use App\Models\User;
use Illuminate\Support\Collection;

class GamificationService
{
    public const CHECKIN_XP = 15;

    public const CHECKIN_COINS = 3;

    public const GOAL_XP = 80;

    public const GOAL_COINS = 25;

    /**
     * Consolida o estado gamificado do usuário para dashboard, home, perfil e relatórios.
     */
    public function forUser(User $user): array
    {
        $activeHabits = $user->habits()
            ->where('active', true)
            ->with([
                'category',
                'checkins' => fn ($query) => $query->where('checked_date', '>=', today()->subDays(30)->toDateString()),
            ])
            ->orderBy('name')
            ->get();

        $totalCheckins = Checkin::whereHas('habit', fn ($query) => $query->where('user_id', $user->id))->count();
        $checkinsToday = Checkin::whereHas('habit', fn ($query) => $query->where('user_id', $user->id))
            ->whereDate('checked_date', today())
            ->count();
        $checkinsThisWeek = Checkin::whereHas('habit', fn ($query) => $query->where('user_id', $user->id))
            ->where('checked_date', '>=', today()->startOfWeek()->toDateString())
            ->count();

        $completedGoals = $user->goals()->where('status', 'completed')->count();
        $activeGoals = $user->goals()
            ->where('status', 'active')
            ->whereDate('end_date', '>=', today())
            ->with(['habit.category'])
            ->orderBy('end_date')
            ->get();
        $bestStreak = $activeHabits->max(fn (Habit $habit) => $habit->currentStreak()) ?? 0;

        $quests = $this->quests($activeHabits);
        $completedQuests = $quests->where('completed', true)->count();
        $questTotal = $quests->count();
        $achievements = $this->achievements($totalCheckins, $checkinsThisWeek, $bestStreak, $activeHabits->count(), $completedGoals);

        $totalXp = ($totalCheckins * self::CHECKIN_XP)
            + ($checkinsThisWeek * 5)
            + ($bestStreak * 10)
            + ($completedGoals * self::GOAL_XP);

        $level = $this->levelFromXp($totalXp);
        $earnedCoins = ($totalCheckins * self::CHECKIN_COINS)
            + ($bestStreak * 2)
            + ($completedGoals * self::GOAL_COINS);
        $spentCoins = (int) $user->rewardRedemptions()->sum('cost');
        $coins = max(0, $earnedCoins - $spentCoins);
        $rewardCatalog = $this->rewardCatalog();

        return [
            'total_xp' => $totalXp,
            'coins' => $coins,
            'earned_coins' => $earnedCoins,
            'spent_coins' => $spentCoins,
            'level' => $level['level'],
            'rank' => $this->rankForLevel($level['level']),
            'current_level_xp' => $level['current_level_xp'],
            'next_level_xp' => $level['next_level_xp'],
            'level_progress_percent' => $level['progress_percent'],
            'avatar_initials' => $this->initials($user->name),
            'total_checkins' => $totalCheckins,
            'checkins_today' => $checkinsToday,
            'checkins_this_week' => $checkinsThisWeek,
            'best_streak' => $bestStreak,
            'completed_goals' => $completedGoals,
            'quests' => $quests,
            'quest_total' => $questTotal,
            'quest_done' => $completedQuests,
            'quest_progress_percent' => $questTotal > 0 ? round(($completedQuests / $questTotal) * 100) : 0,
            'weekly_boss' => $this->weeklyBoss($activeGoals, $activeHabits),
            'achievements' => $achievements,
            'recent_achievements' => $this->recentAchievements($achievements),
            'rewards' => $this->rewards($coins, $rewardCatalog),
            'recent_activity' => $this->recentActivity($user),
            'inventory' => $this->inventory($user, $rewardCatalog, $earnedCoins, $spentCoins, $coins),
        ];
    }

    /** Calcula a recompensa base de um check-in, com bônus para hábitos semanais. */
    public function checkinReward(?Habit $habit = null): array
    {
        $weeklyBonus = $habit?->frequency === 'weekly' ? 5 : 0;

        return [
            'xp' => self::CHECKIN_XP + $weeklyBonus,
            'coins' => self::CHECKIN_COINS + ($weeklyBonus > 0 ? 1 : 0),
        ];
    }

    /** Monta a lista de missões ativas com progresso, dificuldade e recompensa. */
    private function quests(Collection $habits): Collection
    {
        return $habits->map(function (Habit $habit) {
            $reward = $this->checkinReward($habit);
            $streak = $habit->currentStreak();

            return [
                'id' => $habit->id,
                'name' => $habit->name,
                'category' => $habit->category?->name ?? 'Sem categoria',
                'icon' => $this->questIconFor($habit->category?->name ?? '', $habit->name),
                'color' => $habit->color,
                'frequency' => $habit->frequency === 'weekly' ? 'Semanal' : 'Diária',
                'completed' => $habit->isCompletedForCurrentCycle(),
                'streak' => $streak,
                'difficulty' => match (true) {
                    $streak >= 14 => 'Épica',
                    $streak >= 7 => 'Rara',
                    $streak >= 3 => 'Boa',
                    default => 'Inicial',
                },
                'reward_xp' => $reward['xp'],
                'reward_coins' => $reward['coins'],
            ];
        })->values();
    }

    /** Escolhe o ícone da missão com base na categoria e no nome do hábito. */
    private function questIconFor(string $category, string $habitName): string
    {
        $key = mb_strtolower($category.' '.$habitName);

        return match (true) {
            str_contains($key, 'alimenta') => 'meal',
            str_contains($key, 'hidrata') || str_contains($key, 'agua') || str_contains($key, 'água') => 'water',
            str_contains($key, 'humor') => 'mood',
            str_contains($key, 'faculdade') || str_contains($key, 'estudo') => 'study',
            str_contains($key, 'basquete') => 'basketball',
            str_contains($key, 'exercicio') || str_contains($key, 'exercício') => 'training',
            str_contains($key, 'tarefa') => 'tasks',
            default => 'mission',
        };
    }

    /** Converte XP acumulado em nível, progresso interno e XP necessário para o próximo nível. */
    private function levelFromXp(int $xp): array
    {
        $level = 1;
        $remaining = $xp;
        $needed = 100;

        while ($remaining >= $needed) {
            $remaining -= $needed;
            $level++;
            $needed = 100 + (($level - 1) * 35);
        }

        return [
            'level' => $level,
            'current_level_xp' => $remaining,
            'next_level_xp' => $needed,
            'progress_percent' => $needed > 0 ? min(100, round(($remaining / $needed) * 100)) : 0,
        ];
    }

    /** Define o título de patente do jogador de acordo com o nível atual. */
    private function rankForLevel(int $level): string
    {
        return match (true) {
            $level >= 18 => 'Lenda da rotina',
            $level >= 12 => 'Mestre de hábitos',
            $level >= 8 => 'Guardião do foco',
            $level >= 4 => 'Aventureiro constante',
            default => 'Recruta em evolução',
        };
    }

    /** Monta o chefe semanal principal da campanha com base nas metas ativas. */
    private function weeklyBoss(Collection $activeGoals, Collection $activeHabits): array
    {
        /** @var Goal|null $goal */
        $goal = $activeGoals->first();

        if ($goal) {
            $checkins = $goal->checkinCount();
            $progress = $goal->progressPercent();

            return [
                'name' => 'Dragão da Procrastinação',
                'subtitle' => 'Chefe Semanal',
                'objective' => "Concluir {$goal->target_count} check-ins em {$goal->habit->name}",
                'current' => $checkins,
                'target' => $goal->target_count,
                'progress_percent' => $progress,
                'days_remaining' => $goal->daysRemaining(),
                'reward_xp' => self::GOAL_XP,
                'reward_coins' => self::GOAL_COINS,
                'reward_label' => 'Conquista rara',
                'element' => $goal->habit->category?->name ?? 'Disciplina',
                'icon' => 'dragon',
            ];
        }

        $target = max(1, min(7, $activeHabits->count()));
        $completed = $activeHabits->filter(fn (Habit $habit) => $habit->isCompletedForCurrentCycle())->count();
        $progress = round(min(100, ($completed / $target) * 100), 1);

        return [
            'name' => 'Dragão da Procrastinação',
            'subtitle' => 'Chefe Semanal',
            'objective' => "Concluir {$target} Missões nesta semana",
            'current' => $completed,
            'target' => $target,
            'progress_percent' => $progress,
            'days_remaining' => max(0, today()->diffInDays(today()->endOfWeek(), false)),
            'reward_xp' => self::GOAL_XP,
            'reward_coins' => self::GOAL_COINS,
            'reward_label' => 'Conquista rara',
            'element' => 'Disciplina',
            'icon' => 'dragon',
        ];
    }

    /** Gera a lista de conquistas disponíveis e seus critérios de desbloqueio. */
    private function achievements(int $totalCheckins, int $checkinsThisWeek, int $bestStreak, int $activeHabits, int $completedGoals): array
    {
        return [
            [
                'name' => 'Primeira missão',
                'description' => 'Registrar o primeiro check-in.',
                'xp' => 50,
                'tier' => 'Inicial',
                'icon' => 'spark',
                'date' => today()->subDays(6)->format('d/m/Y'),
                'unlocked' => $totalCheckins >= 1,
            ],
            [
                'name' => 'Semana em movimento',
                'description' => 'Somar 7 check-ins na semana.',
                'xp' => 100,
                'tier' => 'Rara',
                'icon' => 'calendar',
                'date' => today()->subDays(4)->format('d/m/Y'),
                'unlocked' => $checkinsThisWeek >= 7,
            ],
            [
                'name' => 'Combo de 7 dias',
                'description' => 'Alcançar streak de 7.',
                'xp' => 100,
                'tier' => 'Rara',
                'icon' => 'flame',
                'date' => today()->subDays(3)->format('d/m/Y'),
                'unlocked' => $bestStreak >= 7,
            ],
            [
                'name' => 'Inventário cheio',
                'description' => 'Manter 5 hábitos ativos.',
                'xp' => 150,
                'tier' => 'Épica',
                'icon' => 'chest',
                'date' => today()->subDays(2)->format('d/m/Y'),
                'unlocked' => $activeHabits >= 5,
            ],
            [
                'name' => 'Chefe derrotado',
                'description' => 'Concluir uma meta.',
                'xp' => 200,
                'tier' => 'Épica',
                'icon' => 'skull',
                'date' => today()->subDay()->format('d/m/Y'),
                'unlocked' => $completedGoals >= 1,
            ],
            [
                'name' => 'Rotina lendária',
                'description' => 'Chegar a 100 check-ins.',
                'xp' => 250,
                'tier' => 'Lendária',
                'icon' => 'crown',
                'date' => today()->format('d/m/Y'),
                'unlocked' => $totalCheckins >= 100,
            ],
        ];
    }

    /** Retorna as conquistas desbloqueadas mais recentes para exibição rápida. */
    private function recentAchievements(array $achievements): array
    {
        $unlocked = collect($achievements)
            ->where('unlocked', true)
            ->sortByDesc('xp')
            ->take(3)
            ->values();

        if ($unlocked->isNotEmpty()) {
            return $unlocked->all();
        }

        return collect($achievements)->take(3)->values()->all();
    }

    /** Monta a linha do tempo do herói com check-ins e resgates recentes. */
    private function recentActivity(User $user): array
    {
        $checkins = Checkin::with(['habit.category'])
            ->whereHas('habit', fn ($query) => $query->where('user_id', $user->id))
            ->latest('checked_at')
            ->take(4)
            ->get()
            ->map(function (Checkin $checkin) {
                $habit = $checkin->habit;
                $reward = $this->checkinReward($habit);

                return [
                    'type' => 'mission',
                    'title' => 'Missão concluída',
                    'description' => $habit->name,
                    'meta' => "+{$reward['xp']} XP · +{$reward['coins']} moedas",
                    'icon' => $this->questIconFor($habit->category?->name ?? '', $habit->name),
                    'tone' => 'cyan',
                    'time' => $checkin->checked_at->diffForHumans(),
                    'timestamp' => $checkin->checked_at,
                ];
            });

        $redemptions = $user->rewardRedemptions()
            ->latest('redeemed_at')
            ->take(4)
            ->get()
            ->map(fn (RewardRedemption $redemption) => [
                'type' => 'reward',
                'title' => 'Recompensa resgatada',
                'description' => $redemption->reward_name,
                'meta' => "-{$redemption->cost} moedas",
                'icon' => 'shop',
                'tone' => 'amber',
                'time' => $redemption->redeemed_at->diffForHumans(),
                'timestamp' => $redemption->redeemed_at,
            ]);

        return $checkins
            ->concat($redemptions)
            ->sortByDesc('timestamp')
            ->take(5)
            ->map(function (array $activity) {
                unset($activity['timestamp']);

                return $activity;
            })
            ->values()
            ->all();
    }

    /** Consolida saldo, itens resgatados e proporção de uso das moedas. */
    private function inventory(User $user, Collection $rewardCatalog, int $earnedCoins, int $spentCoins, int $coins): array
    {
        $catalogByKey = $rewardCatalog->keyBy('key');
        $redemptions = $user->rewardRedemptions()
            ->latest('redeemed_at')
            ->get();

        $items = $redemptions
            ->groupBy('reward_key')
            ->map(function (Collection $items, string $key) use ($catalogByKey) {
                /** @var RewardRedemption $latest */
                $latest = $items->first();
                $catalog = $catalogByKey->get($key, []);

                return [
                    'key' => $key,
                    'name' => $catalog['name'] ?? $latest->reward_name,
                    'tier' => $catalog['tier'] ?? 'Resgatada',
                    'icon' => $catalog['icon'] ?? 'shop',
                    'quantity' => $items->count(),
                    'total_spent' => $items->sum('cost'),
                    'last_redeemed_at' => $latest->redeemed_at->format('d/m/Y H:i'),
                    'last_redeemed_for_humans' => $latest->redeemed_at->diffForHumans(),
                ];
            })
            ->values()
            ->all();

        return [
            'balance' => $coins,
            'earned_coins' => $earnedCoins,
            'spent_coins' => $spentCoins,
            'redeemed_count' => $redemptions->count(),
            'spend_rate_percent' => $earnedCoins > 0 ? min(100, round(($spentCoins / $earnedCoins) * 100)) : 0,
            'items' => $items,
        ];
    }

    /** Expande o catálogo de recompensas disponíveis na loja do jogo. */
    private function rewards(int $coins, ?Collection $rewardCatalog = null): array
    {
        return ($rewardCatalog ?? $this->rewardCatalog())->map(fn (array $reward) => [
            ...$reward,
            'available' => $coins >= $reward['cost'],
            'shortfall' => max(0, $reward['cost'] - $coins),
            'progress_percent' => min(100, round(($coins / $reward['cost']) * 100)),
        ])->all();
    }

    /** Define o catálogo base da loja de recompensas do WebHabit. */
    private function rewardCatalog(): Collection
    {
        return collect([
            [
                'key' => 'planned_break',
                'name' => 'Pausa planejada',
                'description' => 'Recupere energia com 15 minutos fora da tela.',
                'cost' => 20,
                'tier' => 'Comum',
                'icon' => 'calendar',
            ],
            [
                'key' => 'leisure_session',
                'name' => 'Sessão de lazer',
                'description' => 'Troque constância por uma pausa maior sem culpa.',
                'cost' => 45,
                'tier' => 'Rara',
                'icon' => 'shop',
            ],
            [
                'key' => 'equipment_upgrade',
                'name' => 'Upgrade de equipamento',
                'description' => 'Invista em algo pequeno que facilite sua rotina.',
                'cost' => 70,
                'tier' => 'Épica',
                'icon' => 'chest',
            ],
            [
                'key' => 'special_reward',
                'name' => 'Recompensa especial',
                'description' => 'Um prêmio maior para fechar uma campanha difícil.',
                'cost' => 90,
                'tier' => 'Lendária',
                'icon' => 'crown',
            ],
        ]);
    }

    /** Extrai as iniciais do nome do usuário para o avatar do sistema. */
    private function initials(string $name): string
    {
        $parts = collect(preg_split('/\s+/', trim($name)) ?: [])
            ->filter()
            ->take(2)
            ->map(fn (string $part) => mb_substr($part, 0, 1));

        return $parts->isEmpty() ? 'HF' : mb_strtoupper($parts->implode(''));
    }
}
