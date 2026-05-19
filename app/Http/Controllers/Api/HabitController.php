<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class HabitController extends Controller
{
    public function index(): JsonResponse
    {
        $habits = request()->user()
            ->habits()
            ->with([
                'category:id,name',
                'checkins' => fn($query) => $query->whereDate('checked_date', today()),
            ])
            ->orderBy('name')
            ->get()
            ->map(fn($habit) => [
                'id' => $habit->id,
                'name' => $habit->name,
                'description' => $habit->description,
                'category' => $habit->category?->name,
                'frequency' => $habit->frequency,
                'color' => $habit->color,
                'active' => $habit->active,
                'current_streak' => $habit->currentStreak(),
                'completed_today' => $habit->isCompletedToday(),
            ])
            ->values();

        return response()->json([
            'data' => $habits,
        ]);
    }
}
