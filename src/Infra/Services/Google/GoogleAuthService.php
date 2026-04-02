<?php

namespace App\Infra\Services\Google;

use Google\Client;
use Google\Service\Oauth2;
use GuzzleHttp\Client as GuzzleClient;
use Google\Service\Oauth2\Userinfo;

class GoogleAuthService {

    private Client $client;
    private Userinfo $data;

    public function __construct() {
        $this->client = new Client();
    }

    public function init() {
        $guzzleClient = new GuzzleClient(['curl' => [CURLOPT_SSL_VERIFYPEER => false]]);
        $this->client->setHttpClient($guzzleClient);
        $this->client->setAuthConfig(__DIR__ . '/../../../../' . $_ENV['GOOGLE_CREDENTIALS']);
        $this->client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
        $this->client->addScope('email');
        $this->client->addScope('profile');
    }

    public function authorized($code) {
        if(isset($code)){
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            $this->client->setAccessToken($token['access_token']);
            $googleService = new Oauth2($this->client);

            $this->data = $googleService->userinfo->get();

            return true;
        }

        return false;
    }

    public function getClientData() : Userinfo {
        return $this->data;
    }

    public function generateAuthLink() {
        return $this->client->createAuthUrl();
    }

}