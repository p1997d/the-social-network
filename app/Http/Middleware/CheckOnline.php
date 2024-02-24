<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use \Jenssegers\Agent\Agent;

class CheckOnline
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $agent = new Agent;
            $expiresAt = Carbon::now()->addMinutes(5);

            Cache::put('online-' . Auth::user()->id, true, $expiresAt);
            Cache::put('wasOnline-' . Auth::user()->id, $expiresAt);

            if ($agent->isDesktop()) {
                Cache::put('onlineMobile-' . Auth::user()->id, false, $expiresAt);
            } else {
                Cache::put('onlineMobile-' . Auth::user()->id, true, $expiresAt);
            }
        }
        return $next($request);
    }
}
