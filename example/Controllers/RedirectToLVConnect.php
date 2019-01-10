<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Contracts\Factory as Socialite;
use MathieuTu\LVConnectSocialite\LVConnectProvider;

class RedirectToLVConnect
{
    public function __invoke(Socialite $socialite)
    {
        return $socialite->driver(LVConnectProvider::IDENTIFIER)->redirect();
    }
}
