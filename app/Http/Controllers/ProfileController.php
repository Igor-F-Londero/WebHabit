<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\GamificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(private readonly GamificationService $gamification) {}

    /**
     * Exibe a página do perfil com o inventário e o progresso do herói.
     */
    public function edit(Request $request): View
    {
        $game = $this->gamification->forUser($request->user());

        return view('profile.edit', [
            'user' => $request->user(),
            'game' => $game,
        ]);
    }

    /**
     * Atualiza os dados básicos do perfil e reinvalida a confirmação de e-mail quando necessário.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Remove a conta do usuário e encerra a sessão com segurança.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
