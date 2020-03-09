<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Twilio\Rest\Client;

class TwilioServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Client::class, function($app){
            $twilioAccountSid = getenv('TWILIO_SID');
            $twilioAccountToken = getenv('TWILIO_ACCOUNT_TOKEN');
            return new Client($twilioAccountSid, $twilioAccountToken);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
