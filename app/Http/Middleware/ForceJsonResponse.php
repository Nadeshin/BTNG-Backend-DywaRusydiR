<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Memastikan setiap request ke endpoint API selalu dianggap
     * mengharapkan balasan JSON, sehingga error validasi dibalas
     * 422 (bukan redirect 302).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
