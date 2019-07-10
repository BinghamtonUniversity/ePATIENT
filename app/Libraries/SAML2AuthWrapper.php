<?php 

namespace App\Libraries;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Libraries\HTTPHelper;
use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use Aacotroneo\Saml2\Facades\Saml2Auth;

class SAML2AuthWrapper
{
    protected $auth;
    protected $cas;

    public function login($saml_attributes) {
        $data_map = [
            'unique_id' => '{{bnumber}}',
            'first_name' => '{{givenName}}',
            'last_name' => '{{sn}}',
            'email' => '{{mail}}',
        ];
        $m = new \Mustache_Engine;                                    
        $user = User::where('unique_id', '=', 
            $m->render($data_map['unique_id'], $saml_attributes))->first();
        if ($user === null) {
            $user = new User();
        }
        $user->first_name = $m->render($data_map['first_name'], $saml_attributes);
        $user->last_name = $m->render($data_map['last_name'], $saml_attributes);
        $user->email = $m->render($data_map['email'], $saml_attributes);
        $user->save();
        Auth::login($user, true);
    }

    public function authenticate() {
        return Saml2Auth::login();
    }
}
