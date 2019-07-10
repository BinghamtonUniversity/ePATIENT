<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class SAML2Authentication
{
    protected $saml2;

    public function __construct(Guard $auth) {
    }

    public function handle($request, Closure $next)
    {
        if(!Auth::user()){    
            return redirect(URL::route('saml_wayf'));
        }
        return $next($request);
    }
}
