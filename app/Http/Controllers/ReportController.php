<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $period = $request->get('period', '30');

        if ($period === 'custom') {
            $startDate = Carbon::parse($request->get('start', today()->subDays(29)->toDateString()))->startOfDay();
            $endDate = Carbon::parse($request->get('end', today()->toDateString()))->endOfDay();
        } else {
            $days = (int) $period;
            $startDate = today()->subDays($days - 1)->startOfDay();
            $endDate = today()->endOfDay();
        }

        $totalDays = (int) $startDate->diffInDays($endDate) + 1;

        $habits = $user->habits()
            ->where('active', true)
            ->with(['checkins' => fn ($q) => $q->whereBetween('checked_date', [$startDate->toDateString(), $endDate->toDateString()])])
            ->orderBy('name')
            ->get();

        $habitStats = $habits->map(function ($habit) use ($totalDays) {
            $checkins = $habit->checkins->count();
            $expected = $habit->frequency === 'daily' ? $totalDays : (int) ceil($totalDays / 7);
            $rate = $expected > 0 ? round(($checkins / $expected) * 100, 1) : 0;

            return [
                'habit' => $habit,
                'checkins' => $checkins,
                'expected' => $expected,
                'rate' => min(100, $rate),
            ];
        })->sortByDesc('rate')->values();

        $totalCheckins = $habitStats->sum('checkins');
        $avgRate = $habitStats->isNotEmpty()
            ? round($habitStats->avg('rate'), 1)
            : 0;

        return view('reports.index', compact(
            'habitStats',
            'period',
            'startDate',
            'endDate',
            'totalDays',
            'totalCheckins',
            'avgRate',
        ));
    }
}
