<?php

namespace MathieuTu\LVConnectSocialite;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->app['events']->listen(
            SocialiteWasCalled::class,
            function (SocialiteWasCalled $socialite) {
                $socialite->extendSocialite('lvconnect', LVConnectProvider::class);
            }
        );
    }
}
