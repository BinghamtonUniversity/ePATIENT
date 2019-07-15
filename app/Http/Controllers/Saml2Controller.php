<?php

namespace App\Http\Controllers;

use App\Libraries\Saml2Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Libraries\SAML2AuthWrapper;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Saml2Controller extends Controller
{

    protected $saml2Auth;

    /**
     * @param Saml2Auth $saml2Auth injected.
     */
    function __construct(Saml2Auth $saml2Auth)
    {
        $this->saml2Auth = $saml2Auth;
    }

    public function wayf($site = null) {
        if(!Auth::user()){
            if (is_null($site)) {
                $enabled_idps = explode(',',config('saml2_settings.enabled_idps'));
                return view('wayf',['enabled_idps'=>$enabled_idps]);
            } else {
                config(['saml2_settings.idp' => config('saml2_settings.idps.'.$site)]);
                $this->saml2Auth->configure();
                return $this->saml2Auth->login();
            }
        } else {
            return redirect('/');
        }  
    }

    /**
     * Generate local sp metadata
     * @return \Illuminate\Http\Response
     */
    public function metadata()
    {
        $metadata = $this->saml2Auth->getMetadata();
        return response($metadata, 200, ['Content-Type' => 'text/xml']);
    }

    /**
     * Process an incoming saml2 assertion request.
     * Fires 'Saml2LoginEvent' event if a valid user is Found
     */
    public function acs()
    {
        $site = basename(parse_url(request()->input('RelayState'), PHP_URL_PATH));
        config(['saml2_settings.idp' => config('saml2_settings.idps.'.$site)]);
        $this->saml2Auth->configure();

        try {
            $errors = $this->saml2Auth->acs();
        } catch(\Exception $e) {
            // continue?
        }

        if (!empty($errors)) {
            logger()->error('Saml2 error_detail', ['error' => $this->saml2Auth->getLastErrorReason()]);
            session()->flash('saml2_error_detail', [$this->saml2Auth->getLastErrorReason()]);

            logger()->error('Saml2 error', $errors);
            session()->flash('saml2_error', $errors);
            return redirect(config('saml2_settings.errorRoute'));
        }
        
        $saml2user = $this->saml2Auth->getSaml2User();

        $messageId = $this->saml2Auth->getLastMessageId();
        // your own code preventing reuse of a $messageId to stop replay attacks
        $saml_attributes = ['id'=>$saml2user->getUserId()];
        foreach($saml2user->getAttributesWithFriendlyName() as $attribute_name => $attribute_value) {
            if (isset($attribute_value[0])) {
                $saml_attributes[$attribute_name] = $attribute_value[0];
            }
        }
        if (env('IDP_DEBUG_'.$site,false)) {
            echo '<h3>SAML Attributes</h3>';
            echo '<pre>'.print_r($saml_attributes,true).'</pre>';
            echo '<h3>SAML2 User</h3>';
            echo '<pre>'.print_r($saml2user,true).'</pre>';
            echo '<h3>SAML Last Message</h3>';
            echo '<pre>'.print_r($messageId,true).'</pre>';
            exit();
        }
        $data_map = config('saml2_settings.idp.data_map');
        $m = new \Mustache_Engine;                                    
        $user = User::where('unique_id', '=', 
            $m->render($data_map['unique_id'], $saml_attributes))->first();
        if ($user === null) {
            $user = new User();
            $user->unique_id = $m->render($data_map['unique_id'], $saml_attributes);
        }
        $user->first_name = $m->render($data_map['first_name'], $saml_attributes);
        $user->last_name = $m->render($data_map['last_name'], $saml_attributes);
        $user->email = $m->render($data_map['email'], $saml_attributes);
        $user->save();
        Auth::login($user, true);

        $redirectUrl = $saml2user->getIntendedUrl();

        if ($redirectUrl !== null) {
            return redirect($redirectUrl);
        } else {
            return redirect(config('saml2_settings.loginRoute'));
        }
    }

    /**
     * Process an incoming saml2 logout request.
     * Fires 'saml2.logoutRequestReceived' event if its valid.
     * This means the user logged out of the SSO infrastructure, you 'should' log him out locally too.
     */
    public function sls()
    {
        $error = $this->saml2Auth->sls(config('saml2_settings.retrieveParametersFromServer'));
        if (!empty($error)) {
            throw new \Exception("Could not log out");
        }

        return redirect(config('saml2_settings.logoutRoute')); //may be set a configurable default
    }

    /**
     * This initiates a logout request across all the SSO infrastructure.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        Session::save();    
        $returnTo = $request->query('returnTo');
        $sessionIndex = $request->query('sessionIndex');
        $nameId = $request->query('nameId');
        $full_logout = true;
        try {
            $this->saml2Auth->logout($returnTo, $nameId, $sessionIndex); //will actually end up in the sls endpoint
        } catch (\Exception $e) {
            $full_logout = false;
        }
        return view('logout',['full_logout' => $full_logout]);
    }

}
