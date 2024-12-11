<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ResetSessionTimeout
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $timeout = config('session.lifetime') * 60; // Lifetime in seconds
            $lastActivity = session('lastActivityTime', now());
            $elapsedTime = now()->diffInSeconds($lastActivity);
            Log::info("Session timeout check: Elapsed time = {$elapsedTime}s, Timeout = {$timeout}s");
            if ($elapsedTime > $timeout) {
                Log::info("Session timed out for user: " . Auth::user()->email);
                Auth::logout();
                session()->flush();
                return redirect()->route('login')->with('message', 'Session expired due to inactivity.');
            }

            session(['lastActivityTime' => now()]);
        }

        return $next($request);
    }
}
