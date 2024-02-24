<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PjaxMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->pjax()) {
            // Set response headers for PJAX requests
            return response()
                ->header('X-PJAX-URL', $request->getRequestUri());
        }
    
        return $next($request);
    }
}
