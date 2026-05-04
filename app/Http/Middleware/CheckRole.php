<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        foreach ($roles as $role) {
            $allowed = match ($role) {
                'responsable_service'     => $user->isResponsableService(),
                'responsable_departement' => $user->isResponsableDepartement(),
                default                   => $user->role === $role,
            };

            if ($allowed) {
                return $next($request);
            }
        }

        abort(403, 'Accès non autorisé.');
    }
}
