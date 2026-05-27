<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHabitRequest;
use App\Http\Requests\UpdateHabitRequest;
use App\Models\Category;
use App\Models\Habit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HabitController extends Controller
{
    public function index(): View
    {
        $habits = auth()->user()
            ->habits()
            ->with([
                'category',
                'checkins' => fn ($q) => $q->where('checked_date', '>=', today()->startOfWeek()->toDateString()),
            ])
            ->orderBy('name')
            ->get();

        return view('habits.index', compact('habits'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('habits.create', compact('categories')); //compact é uma função do PHP que cria um array associativo a partir de variáveis, onde as chaves são os nomes das variáveis e os valores são os valores das variáveis. Neste caso, estamos passando a variável $categories para a view, e ela estará disponível na view como uma variável chamada $categories.
    }

    public function store(StoreHabitRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        Habit::create($data);

        return redirect()->route('habits.index')
            ->with('success', 'Missão criada com sucesso!');
    }

    public function edit(Habit $habit): View
    {
        abort_if($habit->user_id !== auth()->id(), 403);// abort_if é uma função do Laravel que interrompe a execução do código e retorna uma resposta HTTP com um status code específico. Neste caso, estamos verificando se o user_id do hábito é diferente do ID do usuário autenticado. Se for diferente, significa que o usuário não tem permissão para editar esse hábito, e então a função abort_if irá interromper a execução e retornar uma resposta HTTP 403 Forbidden.

        $categories = Category::orderBy('name')->get();

        return view('habits.edit', compact('habit', 'categories'));
    }

    public function update(UpdateHabitRequest $request, Habit $habit): RedirectResponse 
    {
        abort_if($habit->user_id !== auth()->id(), 403);

        $habit->update($request->validated());

        return redirect()->route('habits.index')
            ->with('success', 'Missão atualizada com sucesso!');
    }

    public function destroy(Habit $habit): RedirectResponse
    {
        abort_if($habit->user_id !== auth()->id(), 403);

        $habit->delete();

        return redirect()->route('habits.index')
            ->with('success', 'Missão excluída com sucesso.');
    }
}
