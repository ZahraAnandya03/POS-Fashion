<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Periksa apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Periksa apakah peran pengguna sesuai dengan yang diperbolehkan
        if (!in_array(Auth::user()->role, $roles)) {
            Log::info('Percobaan akses dengan peran tidak sah', ['role' => Auth::user()->role]);
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
