<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use App\Libraries\SAML2AuthWrapper;

class CustomAuthentication
{
    protected $saml2;

    public function __construct(Guard $auth)
    {
        $this->saml2 = new SAML2AuthWrapper();
    }

    public function handle($request, Closure $next)
    {
        if(!Auth::user()){    
            return redirect('saml2/wayf');
        }
        return $next($request);
    }
}
