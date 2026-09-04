<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifierMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || (!auth()->user()->isVerifier() && !auth()->user()->isAdmin())) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Acesso não autorizado.'], 403);
            }
            return redirect()->route('login')->with('error', 'Acesso restrito à equipa de Verificação e Administração.');
        }

        return $next($request);
    }
}
