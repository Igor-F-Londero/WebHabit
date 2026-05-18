<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['habit_id' => 'required|exists:habits,id']);

        $habit = Habit::findOrFail($request->habit_id);
        abort_if($habit->user_id !== auth()->id(), 403);

        $already = $habit->checkins()
            ->whereDate('checked_date', today())
            ->exists();

        if ($already) {
            return back()->with('error', 'Você já fez check-in neste hábito hoje!');
        }

        $habit->checkins()->create([
            'checked_date' => today(),
            'checked_at'   => now(),
            'created_at'   => now(),
        ]);

        return back()->with('success', 'Check-in registrado! 🔥');
    }
}
