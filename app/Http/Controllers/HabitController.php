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
                'checkins' => fn($q) => $q->whereDate('checked_date', today()),
            ])
            ->orderBy('name')
            ->get();

        return view('habits.index', compact('habits'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('habits.create', compact('categories'));
    }

    public function store(StoreHabitRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        Habit::create($data);

        return redirect()->route('habits.index')
            ->with('success', 'Hábito criado com sucesso!');
    }

    public function edit(Habit $habit): View
    {
        abort_if($habit->user_id !== auth()->id(), 403);

        $categories = Category::orderBy('name')->get();

        return view('habits.edit', compact('habit', 'categories'));
    }

    public function update(UpdateHabitRequest $request, Habit $habit): RedirectResponse
    {
        abort_if($habit->user_id !== auth()->id(), 403);

        $habit->update($request->validated());

        return redirect()->route('habits.index')
            ->with('success', 'Hábito atualizado com sucesso!');
    }

    public function destroy(Habit $habit): RedirectResponse
    {
        abort_if($habit->user_id !== auth()->id(), 403);

        $habit->delete();

        return redirect()->route('habits.index')
            ->with('success', 'Hábito excluído com sucesso.');
    }
}
