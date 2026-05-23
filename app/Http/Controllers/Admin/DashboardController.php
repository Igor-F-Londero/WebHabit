<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Checkin;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalHabits = Habit::count();
        $checkinsToday = Checkin::whereDate('checked_date', today())->count();

        $activeUsers = User::where('role', 'user')
            ->whereHas('habits.checkins', fn ($q) => $q->where('checked_date', '>=', today()->subDays(7)))
            ->count();

        $engagementRate = $totalUsers > 0
            ? round(($activeUsers / $totalUsers) * 100, 1)
            : 0;

        $last30 = collect(range(29, 0))->map(fn ($i) => today()->subDays($i)->toDateString());

        $checkinsByDay = Checkin::selectRaw('DATE(checked_date) as date, COUNT(*) as total')
            ->where('checked_date', '>=', today()->subDays(29))
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartLabels = $last30->map(fn ($d) => Carbon::parse($d)->format('d/m'))->values();
        $chartData = $last30->map(fn ($d) => $checkinsByDay->get($d, 0))->values();

        $movingAvg = $chartData->map(function ($v, $i) use ($chartData) {
            $window = $chartData->slice(max(0, $i - 6), min(7, $i + 1));

            return round($window->avg(), 1);
        })->values();

        $popularCategories = Category::withCount('habits')
            ->orderByDesc('habits_count')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalHabits',
            'checkinsToday',
            'engagementRate',
            'activeUsers',
            'chartLabels',
            'chartData',
            'movingAvg',
            'popularCategories',
        ));
    }
}
