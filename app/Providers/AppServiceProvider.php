<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Dusterio\LumenPassport\LumenPassport;
use Laravel\Passport\Passport;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
    	LumenPassport::routes($this->app->router);
    	Passport::tokensExpireIn(Carbon::now()->addMinutes(3));
    	Passport::refreshTokensExpireIn(Carbon::now()->addMinutes(10));
    }
}
