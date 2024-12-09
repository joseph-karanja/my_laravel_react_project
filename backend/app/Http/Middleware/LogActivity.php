<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        // Perform action before response

        $response = $next($request);

        // Perform action after response

        return $response;
    }
}
