<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Illuminate\Contracts\Auth\StatefulGuard as Auth;
use Illuminate\Contracts\Routing\ResponseFactory as Response;
use MathieuTu\LVConnectSocialite\LVConnectProvider;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use App\Models\User;


class HandleSocialiteCallback
{
    public function __invoke(Socialite $socialite, Auth $auth, Response $response)
    {
        $socialiteUser = $socialite->driver(LVConnectProvider::IDENTIFIER)->user();

        $auth->login($this->findOrCreateUser($socialiteUser), true);

        return $response->redirectToIntended();
    }

    protected function findOrCreateUser(SocialiteUser $LVConnectUser): Authenticatable
    {
        $user = User::firstOrNew(['email' => $LVConnectUser->getEmail()]);

        $user->fill([
            'first_name' => Str::title($LVConnectUser['firstName']),
            'last_name' => Str::upper($LVConnectUser['lastName']),
            'avatar' => $LVConnectUser->getAvatar(),
        ])->saveOrFail();

        return $user;
    }
}
