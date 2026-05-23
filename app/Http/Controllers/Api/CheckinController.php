<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Services\GamificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CheckinController extends Controller
{
    public function __construct(private readonly GamificationService $gamification) {}

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'habit_id' => 'required|exists:habits,id',
        ]);

        $habit = Habit::findOrFail($data['habit_id']);

        abort_if($habit->user_id !== $request->user()->id, 403);

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
            return response()->json([
                'message' => $habit->frequency === 'weekly'
                    ? 'Você já concluiu esta missão nesta semana!'
                    : 'Você já concluiu esta missão hoje!',
            ], 422);
        }

        $checkin = $habit->checkins()->create([
            'checked_date' => today(),
            'checked_at' => now(),
            'created_at' => now(),
            'note' => $request->input('note'),
        ]);

        $reward = $this->gamification->checkinReward($habit);

        return response()->json([
            'message' => 'Check-in registrado com sucesso.',
            'data' => [
                'checkin_id' => $checkin->id,
                'habit_id' => $habit->id,
                'checked_date' => $checkin->checked_date->toDateString(),
                'checked_at' => $checkin->checked_at->toIso8601String(),
                'current_streak' => $habit->currentStreak(),
                'reward' => $reward,
            ],
        ], 201);
    }
}
