<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->get('period', '30');
        $days   = match ($period) {
            '7'  => 7,
            '90' => 90,
            default => 30,
        };

        $startDate = today()->subDays($days - 1);

        $newUsers    = User::where('role', 'user')->where('created_at', '>=', $startDate)->count();
        $newHabits   = Habit::where('created_at', '>=', $startDate)->count();
        $totalCheckins = Checkin::where('checked_date', '>=', $startDate)->count();

        $usersBeforePeriod = User::where('role', 'user')->where('created_at', '<', $startDate)->count();
        $retainedUsers     = User::where('role', 'user')
            ->where('created_at', '<', $startDate)
            ->whereHas('habits.checkins', fn($q) => $q->where('checked_date', '>=', $startDate))
            ->count();

        $retentionRate = $usersBeforePeriod > 0
            ? round(($retainedUsers / $usersBeforePeriod) * 100, 1)
            : 0;

        $dateRange = collect(range($days - 1, 0))->map(fn($i) => today()->subDays($i)->toDateString());

        $checkinsByDay = Checkin::selectRaw('DATE(checked_date) as date, COUNT(*) as total')
            ->where('checked_date', '>=', $startDate)
            ->groupBy('date')
            ->pluck('total', 'date');

        $usersByDay = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('role', 'user')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartLabels    = $dateRange->map(fn($d) => Carbon::parse($d)->format('d/m'))->values();
        $chartData      = $dateRange->map(fn($d) => $checkinsByDay->get($d, 0))->values();
        $usersChartData = $dateRange->map(fn($d) => $usersByDay->get($d, 0))->values();

        return view('admin.reports.index', compact(
            'newUsers',
            'newHabits',
            'totalCheckins',
            'retentionRate',
            'retainedUsers',
            'usersBeforePeriod',
            'period',
            'chartLabels',
            'chartData',
            'usersChartData',
        ));
    }
}
