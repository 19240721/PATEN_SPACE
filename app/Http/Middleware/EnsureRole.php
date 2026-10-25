<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        $roleAliases = [
            'petugas' => ['petugas', 'operator', 'admin'],
        ];

        $allowedRoles = collect($roles)
            ->flatMap(fn (string $role) => $roleAliases[$role] ?? [$role])
            ->all();

        abort_unless($user && in_array($user->role, $allowedRoles, true), 403);

        return $next($request);
    }
}
