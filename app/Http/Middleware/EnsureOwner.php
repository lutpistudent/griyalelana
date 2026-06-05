<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOwner
{
    /**
     * Ensure the authenticated user has the 'owner' role.
     * Used to protect admin-only routes like exports and reports.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isOwner()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk owner.');
        }

        return $next($request);
    }
}
