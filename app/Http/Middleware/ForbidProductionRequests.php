<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;

class ForbidProductionRequests
{
    public function __construct(protected Application $app)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->app->isProduction()) {
            abort(response()->json(['message' => 'Forbidden!'], 403));
        }

        return $next($request);
    }
}
