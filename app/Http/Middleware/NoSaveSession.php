<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class NoSaveSession
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        session_write_close();
        config(['session.driver' =>'array']);
        if(!Auth::user()){  
            return response()->json(['error' => 'You must be authenticated to access this route'], 401);
        }
        return $next($request);
    }
}