<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Libraries\SAML2AuthWrapper;
use Aacotroneo\Saml2\Events\Saml2LoginEvent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

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
            dd($saml_attributes);
            $mySAML2Auth = new SAML2AuthWrapper();
            return $mySAML2Auth->handle($saml_attributes);
        });

        Event::listen('Aacotroneo\Saml2\Events\Saml2LogoutEvent', function ($event) {
            Auth::logout();
            Session::save();
        });
    }
}
