<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');

        $users = User::where('role', 'user')
            ->withCount(['habits', 'goals'])
            ->when($search, fn ($q) => $q->where(
                fn ($q2) => $q2->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
            ))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function toggleActive(User $user): RedirectResponse
    {
        abort_if($user->isAdmin(), 403);

        $user->update(['active' => ! $user->active]);

        $status = $user->active ? 'reativada' : 'desativada';

        return back()->with('success', "Conta de {$user->name} {$status} com sucesso.");
    }
}
