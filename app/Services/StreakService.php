<?php

namespace App\Services;

use App\Models\Habit;
use Illuminate\Support\Carbon;

class StreakService
{
    public function calculate(Habit $habit): int
    {
        if ($habit->frequency === 'weekly') {
            return $this->calculateWeekly($habit);
        }

        return $this->calculateDaily($habit);
    }

    public function longestStreak(Habit $habit): int
    {
        $dates = $habit->checkins()
            ->orderBy('checked_date')
            ->pluck('checked_date')
            ->map(fn($d) => Carbon::parse($d));

        if ($dates->isEmpty()) return 0;

        $longest = 1;
        $current = 1;

        for ($i = 1; $i < $dates->count(); $i++) {
            if ($dates[$i]->isSameDay($dates[$i - 1]->copy()->addDay())) {
                $current++;
                $longest = max($longest, $current);
            } else {
                $current = 1;
            }
        }

        return $longest;
    }

    private function calculateDaily(Habit $habit): int
    {
        $dates = $habit->checkins()
            ->orderByDesc('checked_date')
            ->pluck('checked_date')
            ->map(fn($d) => Carbon::parse($d));

        if ($dates->isEmpty()) return 0;

        $mostRecent = $dates->first();

        // Se o check-in mais recente foi antes de ontem, streak quebrado
        if ($mostRecent->lt(today()->subDay())) return 0;

        // Começa a contar de hoje (se tem check-in hoje) ou de ontem
        $expected = $mostRecent->isToday() ? today() : today()->subDay();

        $streak = 0;

        foreach ($dates as $date) {
            if ($date->isSameDay($expected)) {
                $streak++;
                $expected->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }

    private function calculateWeekly(Habit $habit): int
    {
        $weeks = $habit->checkins()
            ->orderByDesc('checked_date')
            ->pluck('checked_date')
            ->map(fn($d) => Carbon::parse($d)->startOfWeek()->toDateString())
            ->unique()
            ->values();

        if ($weeks->isEmpty()) return 0;

        $currentWeekStart = Carbon::now()->startOfWeek()->toDateString();
        $lastWeekStart    = Carbon::now()->subWeek()->startOfWeek()->toDateString();

        // Streak só é válido se tem check-in na semana atual ou na anterior
        if ($weeks->first() !== $currentWeekStart && $weeks->first() !== $lastWeekStart) {
            return 0;
        }

        $streak   = 1;
        $expected = Carbon::parse($weeks->first())->subWeek()->toDateString();

        for ($i = 1; $i < $weeks->count(); $i++) {
            if ($weeks[$i] === $expected) {
                $streak++;
                $expected = Carbon::parse($expected)->subWeek()->toDateString();
            } else {
                break;
            }
        }

        return $streak;
    }
}
