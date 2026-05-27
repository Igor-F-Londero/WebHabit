<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Services\GamificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private readonly GamificationService $gamification)
    {
    }

    /** Consolida métricas de hábitos, heatmap e ranking para a página de relatórios. */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $game = $this->gamification->forUser($user);
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
        // Calcula estatísticas de check-in para cada hábito no período selecionado
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

        $topStreaks = $user->habits()
            ->where('active', true)
            ->with(['checkins'])
            ->get()
            ->sortByDesc(fn ($habit) => $habit->currentStreak())
            ->take(3)
            ->values();

        $last7 = collect(range(6, 0))->map(fn ($i) => today()->subDays($i)->toDateString());//collect é uma função do Laravel que cria uma coleção a partir de um array. Aqui, estamos criando uma coleção de datas dos últimos 7 dias, começando de hoje e indo para trás. O método range(6, 0) gera os números de 6 a 0, e para cada número, subtraímos esse número de dias da data atual (today()) e formatamos como string de data (toDateString()).
    
        $checkinsByDay = Checkin::selectRaw('DATE(checked_date) as date, COUNT(*) as total')
            ->whereHas('habit', fn ($q) => $q->where('user_id', $user->id))
            ->where('checked_date', '>=', today()->subDays(6))
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartLabels = $last7->map(fn ($date) => Carbon::parse($date)->format('d/m'))->values();
        $chartData = $last7->map(fn ($date) => $checkinsByDay->get($date, 0))->values();

        $heatmapData = Checkin::selectRaw('DATE(checked_date) as date, COUNT(*) as total')
            ->whereHas('habit', fn ($q) => $q->where('user_id', $user->id))
            ->where('checked_date', '>=', today()->subYear())
            ->groupBy('date')
            ->pluck('total', 'date');

        return view('reports.index', compact(
            'habitStats',
            'period',
            'startDate',
            'endDate',
            'totalDays',
            'totalCheckins',
            'avgRate',
            'topStreaks',
            'chartLabels',
            'chartData',
            'heatmapData',
            'game',
        ));
    }
}
