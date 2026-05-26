<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Services\GamificationService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private readonly GamificationService $gamification) {}

    /** Carrega a home com os indicadores gerais da campanha do usuário. */
    public function __invoke(): View
    {
        $user = auth()->user();

        $activeHabits = $user->habits()->where('active', true)->count();
        $activeGoals = $user->goals()->where('status', 'active')->count();
        $checkinsToday = Checkin::whereHas('habit', fn ($query) => $query->where('user_id', $user->id))
            ->whereDate('checked_date', today())
            ->count();

        $bestStreak = $user->habits()
            ->where('active', true)
            ->with('checkins')
            ->get()
            ->max(fn ($habit) => $habit->currentStreak()) ?? 0;

        $game = $this->gamification->forUser($user);

        return view('home', compact(
            'activeHabits',
            'activeGoals',
            'checkinsToday',
            'bestStreak',
            'game',
        ));
    }
}
