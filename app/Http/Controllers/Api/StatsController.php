<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\User;
use App\Services\GamificationService;
use App\Services\StreakService;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function __construct(
        private readonly StreakService $streakService,
        private readonly GamificationService $gamification,
    ) {}

    public function index(): JsonResponse
    {
        /** @var User $user */
        $user = request()->user();

        $habits = $user->habits()
            ->where('active', true)
            ->withCount('checkins')
            ->with('checkins')
            ->get();

        $bestHabit = $habits
            ->sortByDesc(fn ($habit) => $this->streakService->calculate($habit))
            ->first();

        $mostCheckedHabit = $habits
            ->sortByDesc('checkins_count')
            ->first();

        return response()->json([
            'data' => [
                'total_habits' => $habits->count(),
                'completion_rate_7d' => $this->completionRate($user, 7),
                'completion_rate_30d' => $this->completionRate($user, 30),
                'best_streak' => [
                    'habit_id' => $bestHabit?->id,
                    'habit_name' => $bestHabit?->name,
                    'current_streak' => $bestHabit ? $this->streakService->calculate($bestHabit) : 0,
                ],
                'most_checkins' => [
                    'habit_id' => $mostCheckedHabit?->id,
                    'habit_name' => $mostCheckedHabit?->name,
                    'total_checkins' => $mostCheckedHabit?->checkins_count ?? 0,
                ],
                'gamification' => $this->gamification->forUser($user),
            ],
        ]);
    }

    private function completionRate(User $user, int $days): float
    {
        $habits = $user->habits()
            ->where('active', true)
            ->get(['id', 'frequency']);

        if ($habits->isEmpty()) {
            return 0;
        }

        $actual = Checkin::whereHas('habit', fn ($query) => $query->where('user_id', $user->id))
            ->where('checked_date', '>=', today()->subDays($days - 1))
            ->count();

        $expected = $habits->sum(
            fn ($habit) => $habit->frequency === 'daily' ? $days : (int) ceil($days / 7)
        );

        if ($expected === 0) {
            return 0;
        }

        return round(min(100, ($actual / $expected) * 100), 1);
    }
}
