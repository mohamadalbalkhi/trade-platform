<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        if (!$user) {
            abort(403, 'Forbidden');
        }

        $role = strtolower(trim((string) $user->role));
        $status = strtolower(trim((string) ($user->status ?? 'active')));

        if ($role !== 'admin') {
            abort(403, 'Forbidden');
        }

        if ($status !== 'active') {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}