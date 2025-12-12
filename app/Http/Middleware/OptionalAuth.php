<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;

class OptionalAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($token = $request->bearerToken()) {
            $model       = Sanctum::$personalAccessTokenModel;
            $accessToken = $model::findToken($token);

            if ($accessToken) {
                auth()->setUser($accessToken->tokenable);
            }
        }

        return $next($request);
    }
}
