<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Services\GamificationService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly GamificationService $gamification) {}

    /** Monta o cockpit principal com missões de hoje e resumo da gamificação. */
    public function index(): View
    {
        $user = auth()->user();

        $todayHabits = $user->habits()
            ->where('active', true)
            ->with([
                'category',
                'checkins' => fn ($q) => $q->where('checked_date', '>=', today()->startOfWeek()->toDateString()),
            ])
            ->orderBy('name')
            ->get();

        $checkinsToday = Checkin::whereHas('habit', fn ($q) => $q->where('user_id', $user->id))
            ->whereDate('checked_date', today())
            ->count();

        $game = $this->gamification->forUser($user);

        return view('dashboard', compact(
            'todayHabits',
            'checkinsToday',
            'game',
        ));
    }
}
