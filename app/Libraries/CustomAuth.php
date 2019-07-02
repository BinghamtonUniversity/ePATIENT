<?php 

namespace App\Libraries;
use App\Libraries\SAML2Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;

class CustomAuth {

  public function __construct()
  {
    if (config('app.site.auth') == 'SAML2') {        
      $this->saml2 = new SAML2Auth();
    }
  }

  public function authenticate(Request $request, $skip = false) {
    if (config('app.site.auth') == 'SAML2') {        
      if(!Auth::user()){           
        return $this->saml2->authenticate($skip && !$request->is('login*'));
      }
    } else {
      if(!$skip && !$request->is('login*')){
        return redirect('/login?redirect='.urlencode(URL::full()));
      } else {
        if(!$request->is('api/usersetup*')){
          // return new Response();
          if(!count(User::get())){
            if(!$request->is('setup')){
              return redirect('/setup');
            }else{
              /* present form for creating initial site */
              return new Response(view('setup',array('mode'=>'user')));
            }
          }
        }
      }
    }
  }
}