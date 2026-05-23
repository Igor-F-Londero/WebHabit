<?php

namespace App\Services;

use App\Models\Checkin;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Support\Collection;

class GamificationService
{
    public const CHECKIN_XP = 15;

    public const CHECKIN_COINS = 3;

    public const GOAL_XP = 80;

    public const GOAL_COINS = 25;

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
        $bestStreak = $activeHabits->max(fn (Habit $habit) => $habit->currentStreak()) ?? 0;

        $quests = $this->quests($activeHabits);
        $completedQuests = $quests->where('completed', true)->count();
        $questTotal = $quests->count();

        $totalXp = ($totalCheckins * self::CHECKIN_XP)
            + ($checkinsThisWeek * 5)
            + ($bestStreak * 10)
            + ($completedGoals * self::GOAL_XP);

        $level = $this->levelFromXp($totalXp);
        $coins = ($totalCheckins * self::CHECKIN_COINS)
            + ($bestStreak * 2)
            + ($completedGoals * self::GOAL_COINS);

        return [
            'total_xp' => $totalXp,
            'coins' => $coins,
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
            'achievements' => $this->achievements($totalCheckins, $checkinsThisWeek, $bestStreak, $activeHabits->count(), $completedGoals),
            'rewards' => $this->rewards(),
        ];
    }

    public function checkinReward(?Habit $habit = null): array
    {
        $weeklyBonus = $habit?->frequency === 'weekly' ? 5 : 0;

        return [
            'xp' => self::CHECKIN_XP + $weeklyBonus,
            'coins' => self::CHECKIN_COINS + ($weeklyBonus > 0 ? 1 : 0),
        ];
    }

    private function quests(Collection $habits): Collection
    {
        return $habits->map(function (Habit $habit) {
            $reward = $this->checkinReward($habit);
            $streak = $habit->currentStreak();

            return [
                'id' => $habit->id,
                'name' => $habit->name,
                'category' => $habit->category?->name ?? 'Sem categoria',
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

    private function achievements(int $totalCheckins, int $checkinsThisWeek, int $bestStreak, int $activeHabits, int $completedGoals): array
    {
        return [
            [
                'name' => 'Primeira missão',
                'description' => 'Registrar o primeiro check-in.',
                'unlocked' => $totalCheckins >= 1,
            ],
            [
                'name' => 'Semana em movimento',
                'description' => 'Somar 7 check-ins na semana.',
                'unlocked' => $checkinsThisWeek >= 7,
            ],
            [
                'name' => 'Combo de 7 dias',
                'description' => 'Alcançar streak de 7.',
                'unlocked' => $bestStreak >= 7,
            ],
            [
                'name' => 'Inventário cheio',
                'description' => 'Manter 5 hábitos ativos.',
                'unlocked' => $activeHabits >= 5,
            ],
            [
                'name' => 'Chefe derrotado',
                'description' => 'Concluir uma meta.',
                'unlocked' => $completedGoals >= 1,
            ],
            [
                'name' => 'Rotina lendária',
                'description' => 'Chegar a 100 check-ins.',
                'unlocked' => $totalCheckins >= 100,
            ],
        ];
    }

    private function rewards(): array
    {
        return [
            ['name' => 'Pausa planejada', 'cost' => 20],
            ['name' => 'Sessão de lazer', 'cost' => 45],
            ['name' => 'Recompensa especial', 'cost' => 90],
        ];
    }

    private function initials(string $name): string
    {
        $parts = collect(preg_split('/\s+/', trim($name)) ?: [])
            ->filter()
            ->take(2)
            ->map(fn (string $part) => mb_substr($part, 0, 1));

        return $parts->isEmpty() ? 'HF' : mb_strtoupper($parts->implode(''));
    }
}
