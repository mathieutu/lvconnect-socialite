<?php

namespace MathieuTu\LVConnectSocialite;

use Illuminate\Http\Request;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class LVConnectProvider extends AbstractProvider
{
    public const IDENTIFIER = 'LVCONNECT';
    protected $scopes = ['profile:get'];
    protected $scopeSeparator = ' ';

    public function __construct(Request $request, string $clientId, string $clientSecret, string $redirectUrl, array $guzzle = [])
    {
        parent::__construct($request, $clientId, $clientSecret, $redirectUrl, $guzzle);
    }

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->url('/oauth/authorize'), $state);
    }

    protected function getTokenUrl()
    {
        return $this->url('/oauth/token');
    }

    protected function getTokenFields($code)
    {
        return parent::getTokenFields($code) + ['grant_type' => 'authorization_code'];
    }

    protected function getUserByToken($token): array
    {
        $response = $this->getHttpClient()->get($this->url('/users/me'), [
            'headers' => ['Authorization' => 'Bearer ' . $token],
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user): User
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'],
            'name' => $user['firstName'] . $user['lastName'],
            'email' => $user['email'],
            'avatar' => $user['profilePictureUrl'],
        ]);
    }

    public static function additionalConfigKeys()
    {
        return ['url'];
    }

    private function url(string $path): string
    {
        $url = $this->getConfig('url', 'https://lvconnect.link-value.fr');

        return rtrim($url, '/') . $path;
    }
}
