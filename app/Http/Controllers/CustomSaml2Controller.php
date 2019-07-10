<?php

namespace App\Http\Controllers;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use Aacotroneo\Saml2\Saml2Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;


class CustomSaml2Controller extends Controller
{

    protected $saml2Auth;

    /**
     * @param Saml2Auth $saml2Auth injected.
     */
    function __construct(Saml2Auth $saml2Auth)
    {
        $this->saml2Auth = $saml2Auth;
    }

    /**
     * Process an incoming saml2 assertion request.
     * Fires 'Saml2LoginEvent' event if a valid user is Found
     */
    public function hello() {
        return "hello!";
    }
    public function acs()
    {
        $site = basename(parse_url(request()->input('RelayState'), PHP_URL_PATH));
        // config(['saml2_settings.idp' => config('saml2_settings.'.$site.'_idp')]);
        $errors = $this->saml2Auth->acs();

        if (!empty($errors)) {
            logger()->error('Saml2 error_detail', ['error' => $this->saml2Auth->getLastErrorReason()]);
            session()->flash('saml2_error_detail', [$this->saml2Auth->getLastErrorReason()]);

            logger()->error('Saml2 error', $errors);
            session()->flash('saml2_error', $errors);
            return redirect(config('saml2_settings.errorRoute'));
        }
        $user = $this->saml2Auth->getSaml2User();

        event(new Saml2LoginEvent($user, $this->saml2Auth));

        $redirectUrl = $user->getIntendedUrl();

        if ($redirectUrl !== null) {
            return redirect($redirectUrl);
        } else {

            return redirect(config('saml2_settings.loginRoute'));
        }
    }

}
