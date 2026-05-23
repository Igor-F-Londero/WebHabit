<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Services\GamificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CheckinController extends Controller
{
    public function __construct(private readonly GamificationService $gamification) {}

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['habit_id' => 'required|exists:habits,id']);

        $habit = Habit::findOrFail($request->habit_id);
        abort_if($habit->user_id !== auth()->id(), 403);

        $already = $habit->frequency === 'weekly'
            ? $habit->checkins()
                ->whereBetween('checked_date', [
                    Carbon::today()->startOfWeek()->toDateString(),
                    Carbon::today()->endOfWeek()->toDateString(),
                ])
                ->exists()
            : $habit->checkins()
                ->whereDate('checked_date', today())
                ->exists();

        if ($already) {
            $message = $habit->frequency === 'weekly'
                ? 'Você já concluiu esta missão nesta semana!'
                : 'Você já concluiu esta missão hoje!';

            return back()->with('error', $message);
        }

        $habit->checkins()->create([
            'checked_date' => today(),
            'checked_at' => now(),
            'created_at' => now(),
        ]);

        $reward = $this->gamification->checkinReward($habit);

        return back()->with('success', "Missão concluída! +{$reward['xp']} XP e +{$reward['coins']} moedas.");
    }
}
