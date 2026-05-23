<?php

namespace App\Http\Controllers;

use App\Services\GamificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RewardRedemptionController extends Controller
{
    public function __construct(private readonly GamificationService $gamification) {}

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'reward_key' => ['required', 'string'],
        ]);

        $user = $request->user();
        $game = $this->gamification->forUser($user);
        $reward = collect($game['rewards'])->firstWhere('key', $data['reward_key']);

        if (! $reward) {
            return back()->with('error', 'Recompensa não encontrada na loja.');
        }

        if (! $reward['available']) {
            return back()->with('error', "Você ainda precisa de {$reward['shortfall']} moedas para resgatar {$reward['name']}.");
        }

        $user->rewardRedemptions()->create([
            'reward_key' => $reward['key'],
            'reward_name' => $reward['name'],
            'cost' => $reward['cost'],
            'redeemed_at' => now(),
        ]);

        return back()->with('success', "Recompensa resgatada: {$reward['name']} por {$reward['cost']} moedas.");
    }
}
