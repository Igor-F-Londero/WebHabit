<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoalRequest;
use App\Http\Requests\UpdateGoalRequest;
use App\Models\Goal;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GoalController extends Controller
{
    /** Lista os chefes/metas do usuário e sincroniza o status de cada um. */
    public function index(): View
    {
        $goals = auth()->user()
            ->goals()
            ->with('habit')
            ->orderBy('end_date')
            ->get();

        $goals->each(fn ($g) => $g->syncStatus());

        return view('goals.index', compact('goals'));
    }

    /** Exibe o formulário para criar um novo chefe/meta. */
    public function create(): View
    {
        $habits = auth()->user()->habits()->orderBy('name')->get();

        return view('goals.create', compact('habits'));
    }

    /** Persiste um novo chefe/meta ligado a um hábito do próprio usuário. */
    public function store(StoreGoalRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        abort_if(
            ! auth()->user()->habits()->where('id', $data['habit_id'])->exists(),
            403
        );

        Goal::create($data);

        return redirect()->route('goals.index')
            ->with('success', 'Chefe criado com sucesso!');
    }

    /** Exibe a edição de um chefe/meta existente. */
    public function edit(Goal $goal): View
    {
        abort_if($goal->user_id !== auth()->id(), 403);

        $habits = auth()->user()->habits()->orderBy('name')->get();

        return view('goals.edit', compact('goal', 'habits'));
    }

    /** Atualiza os dados do chefe/meta selecionado. */
    public function update(UpdateGoalRequest $request, Goal $goal): RedirectResponse
    {
        abort_if($goal->user_id !== auth()->id(), 403);

        $goal->update($request->validated());

        return redirect()->route('goals.index')
            ->with('success', 'Chefe atualizado com sucesso!');
    }

    /** Remove um chefe/meta do usuário. */
    public function destroy(Goal $goal): RedirectResponse
    {
        abort_if($goal->user_id !== auth()->id(), 403);

        $goal->delete();

        return redirect()->route('goals.index')
            ->with('success', 'Chefe excluído.');
    }
}
