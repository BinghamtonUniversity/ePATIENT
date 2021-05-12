<?php

namespace App\Http\Middleware;

use Closure;

class ForceHTTPS {

    public function handle($request, Closure $next)
    {
            // Don't worry about the site if we're just doing a simple health check
            if ($request->is('health')) {
                // TJC This is not great, and should be fixed, but leaving it here for now.
                // (This bypasses everything else and just returns OK.  We should let this call a controller with a proper health check)
                return response('OK',200)->header('Content-Type', 'text/plain');
            }
        
            if (!$request->secure() && config('app.force_https')) {
                return redirect()->secure($request->getRequestUri());
            }

            return $next($request); 
    }
}