<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('x-api-key');
        if (!$key || $key != config('services.api_key')) {
            return Response::json([
                'message' => 'Missing or invalid api key',
            ], 400);
        }
        return $next($request); // Continue with the next middleware
    }
}
