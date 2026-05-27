<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Services\GamificationService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly GamificationService $gamification) {}// injeção de dependência do serviço de gamificação

    /** Monta o cockpit principal com missões de hoje e resumo da gamificação. */
    public function index(): View
    {
        $user = auth()->user();// obtém o usuário autenticado

        $todayHabits = $user->habits()  // busca os hábitos do usuário
            ->where('active', true)// filtra apenas os hábitos ativos
            ->with([ 
                'category',
                'checkins' => fn ($q) => $q->where('checked_date', '>=', today()->startOfWeek()->toDateString()),
            ])
            ->orderBy('name')
            ->get(); 

        $checkinsToday = Checkin::whereHas('habit', fn ($q) => $q->where('user_id', $user->id))
            ->whereDate('checked_date', today())
            ->count(); 

        $game = $this->gamification->forUser($user);// obtém o estado do jogo para o usuário

        return view('dashboard', compact(
            'todayHabits',
            'checkinsToday',
            'game',
        ));
    }
}
