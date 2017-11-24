<?php

namespace MathieuTu\LVConnectSocialite;

use GuzzleHttp\ClientInterface;
use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class LVConnectProvider extends AbstractProvider implements ProviderInterface
{
    const IDENTIFIER = 'LVCONNECT';
    protected $scopes = ['profile:get'];
    protected $scopeSeparator = ' ';

    protected $url = 'https://lvconnect.link-value.fr/';

    public function __construct(\Illuminate\Http\Request $request, string $clientId, string $clientSecret, string $redirectUrl, array $guzzle = [])
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

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            $this->url('/users/me'),
            ['headers' => ['Authorization' => 'Bearer ' . $token]]
        );

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'     => $user['id'],
            'name'   => $user['firstName'] . $user['lastName'],
            'email'  => $user['email'],
            'avatar' => $user['profilePictureUrl'],
        ]);
    }

    public function getAccessTokenResponse($code)
    {
        $postKey = (version_compare(ClientInterface::VERSION, '6') === 1) ? 'form_params' : 'body';

        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => [
                'Accept'        => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
            ],
            $postKey  => $this->getTokenFields($code),
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function getTokenFields($code)
    {
        return [
            'code'       => $code,
            'grant_type' => 'authorization_code',
        ];
    }

    protected function url($uri)
    {
        return rtrim($this->url, '/') . '/' . ltrim($uri, '/');
    }
}
