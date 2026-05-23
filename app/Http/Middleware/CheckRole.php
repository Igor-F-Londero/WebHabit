<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (! $request->user() || $request->user()->role !== $role) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}
