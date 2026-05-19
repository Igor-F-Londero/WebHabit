<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $todayHabits = $user->habits()
            ->where('active', true)
            ->with([
                'category',
                'checkins' => fn($q) => $q->whereDate('checked_date', today()),
            ])
            ->orderBy('name')
            ->get();

        $topStreaks = $user->habits()
            ->where('active', true)
            ->with(['checkins'])
            ->get()
            ->sortByDesc(fn($h) => $h->currentStreak())
            ->take(3)
            ->values();

        $startDate = today()->subDays(29);
        $dailyCount = $user->habits()->where('active', true)->where('frequency', 'daily')->count();
        $expectedTotal = $dailyCount * 30;

        $actualTotal = Checkin::whereHas('habit', fn($q) => $q->where('user_id', $user->id))
            ->where('checked_date', '>=', $startDate)
            ->count();

        $consistencyRate = $expectedTotal > 0
            ? round(($actualTotal / $expectedTotal) * 100, 1)
            : 0;

        $checkinsToday = Checkin::whereHas('habit', fn($q) => $q->where('user_id', $user->id))
            ->whereDate('checked_date', today())
            ->count();

        $last7 = collect(range(6, 0))->map(fn($i) => today()->subDays($i)->toDateString());

        $checkinsByDay = Checkin::selectRaw('DATE(checked_date) as date, COUNT(*) as total')
            ->whereHas('habit', fn($q) => $q->where('user_id', $user->id))
            ->where('checked_date', '>=', today()->subDays(6))
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartLabels = $last7->map(fn($d) => Carbon::parse($d)->format('d/m'))->values();
        $chartData   = $last7->map(fn($d) => $checkinsByDay->get($d, 0))->values();

        $heatmapData = Checkin::selectRaw('DATE(checked_date) as date, COUNT(*) as total')
            ->whereHas('habit', fn($q) => $q->where('user_id', $user->id))
            ->where('checked_date', '>=', today()->subYear())
            ->groupBy('date')
            ->pluck('total', 'date');

        return view('dashboard', compact(
            'todayHabits',
            'topStreaks',
            'consistencyRate',
            'checkinsToday',
            'chartLabels',
            'chartData',
            'heatmapData',
        ));
    }
}
