<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
   public function handle(Request $request, Closure $next): Response
{
    // On vérifie si l'utilisateur est connecté ET s'il est admin
    if ($request->user() && $request->user()->role === 'admin') {
        return $next($request);
    }

    // Sinon, on bloque l'accès
    return response()->json(['message' => 'Accès refusé. Vous devez être administrateur.'], 403);
}

}
