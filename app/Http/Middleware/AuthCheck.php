<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $accessToken = $request->header("x-access-token");
        $localAccessToken = env("ACCESS_TOKEN");
        if ($accessToken && $localAccessToken == $accessToken) {
            return $next($request);
        } else {
            return \response()->json([
                "message" => "Unauthorized"
            ], 401);
        }
    }
}
