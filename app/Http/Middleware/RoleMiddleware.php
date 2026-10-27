<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Akses ditolak. Peran Anda tidak memiliki izin untuk tindakan ini.'], 403);
        }

        // Redirect to user's respective homepage
        $redirectRoute = match ($user->role) {
            'kasir' => 'pos.index',
            'gudang' => 'inventory.index',
            'admin' => 'admin.dashboard',
            default => 'login',
        };

        return redirect()->route($redirectRoute)->with('error', 'Akses ditolak: Anda tidak memiliki wewenang untuk membuka halaman tersebut.');
    }
}
