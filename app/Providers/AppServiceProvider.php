<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Libraries\SAML2Auth;
use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Event::listen('Aacotroneo\Saml2\Events\Saml2LoginEvent', function (Saml2LoginEvent $event) {
            $messageId = $event->getSaml2Auth()->getLastMessageId();
            // your own code preventing reuse of a $messageId to stop replay attacks
            $user = $event->getSaml2User();
            $saml_attributes = ['id'=>$user->getUserId()];
            foreach($user->getAttributesWithFriendlyName() as $attribute_name => $attribute_value) {
                if (isset($attribute_value[0])) {
                    $saml_attributes[$attribute_name] = $attribute_value[0];
                }
            }
            $mySAML2Auth = new SAML2Auth();
            return $mySAML2Auth->handle($saml_attributes);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
