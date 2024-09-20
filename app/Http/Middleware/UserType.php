<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserType
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->type=="admin"){
            return $next($request);
        }
        else{
            return redirect("login")->with("error","You are not allowed to access this page");
        }
    }
}
