<?php

namespace MathieuTu\LVConnectSocialite;

use Illuminate\Http\Request;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class LVConnectProvider extends AbstractProvider
{
    const IDENTIFIER = 'LVCONNECT';
    protected $scopes = ['profile:get'];
    protected $scopeSeparator = ' ';

    protected $url = 'https://lvconnect.link-value.fr';

    public function __construct(Request $request, string $clientId, string $clientSecret, string $redirectUrl, array $guzzle = [])
    {
        parent::__construct($request, $clientId, $clientSecret, $redirectUrl, $guzzle);

        $this->url = $this->config['url'] ?? $this->url;
    }

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->url('/oauth/authorize'), $state);
    }

    protected function getTokenUrl()
    {
        return $this->url('/oauth/token');
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

    private function url(string $uri): string
    {
        return rtrim($this->url, '/') . '/' . ltrim($uri, '/');
    }

    public static function additionalConfigKeys()
    {
        return ['url'];
    }
}
