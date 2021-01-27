<?php

namespace Antyki\Olx\Client;

use \Exception as Exception;

class Auth
{
    public $guzzleClient;
    public $olx;

    protected $credentials; // if client_id, client_secret, state is filled in
    public $isAuthenticated; // if OLX gives a response

    protected $olxClientId;
    protected $olxClientSecret;
    protected $olxState;
    public $olxAccessToken;
    protected $olxRefreshToken;
    protected $olxCode;

    public function __construct(
        \GuzzleHttp\Client $guzzleClient,
        \Antyki\Olx\Main $olx
    ) {
        $this->guzzleClient = $guzzleClient;
        $this->olx = $olx;

        $tokensValid = $this->getTokenValidity();
        if ($tokensValid) {
            $this->isAuthenticated = $tokensValid;
        }

        $this->getCredentials();
        $this->authenticate();
    }

    public function isAuthenticated()
    {
        return $this->isAuthenticated;
    }

    protected function getTokenValidity()
    {
        $validity = $this->olx->getOption('olxTokensValidUntil');
        if ($validity > date('Y-m-d H:i:s')) {
            $this->isAuthenticated = true;
        }
    }

    protected function getCredentials()
    {
        try {
            if (!$this->olx->getOption('olxClientId')) {
                throw new Exception('olxClientId not defined');
            }
            if (!$this->olx->getOption('olxClientSecret')) {
                throw new Exception('olxClientSecret not defined');
            }
            if (!$this->olx->getOption('olxState')) {
                throw new Exception('olxState not defined');
            }
            if (!$this->olx->getOption('olxCode')) {
                throw new Exception('olxCode not defined');
            }

            $this->olxClientId = $this->olx->getOption('olxClientId');
            $this->olxClientSecret = $this->olx->getOption('olxClientSecret');
            $this->olxState = $this->olx->getOption('olxState');
            $this->olxCode = $this->olx->getOption('olxCode');

            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());

            return false;
        }
    }

    protected function getTokens()
    {
        try {
            if (!$this->olx->getOption('olxAccessToken')) {
                throw new Exception('olxAccessToken not defined');
            }
            if (!$this->olx->getOption('olxRefreshToken')) {
                throw new Exception('olxRefreshToken not defined');
            }

            $this->olxAccessToken = $this->olx->getOption('olxAccessToken');
            $this->olxRefreshToken = $this->olx->getOption('olxRefreshToken');

            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());

            return false;
        }
    }

    protected function renewTokens()
    {
        $response = $this->guzzleClient->request(
            'POST',
            '/api/open/oauth/token',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->olxAccessToken,
                    'Version' => '2.0',
                ],
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => $this->olxClientId,
                    'client_secret' => $this->olxClientSecret,
                    'refresh_token' => $this->olxRefreshToken,
                ],
            ]
        );

        // request body
        $body = json_decode($response->getBody());

        $accessToken = $body->access_token;
        $refreshToken = $body->refresh_token;

        $optionsUpdated =
            \update_option('olxAccessToken', $accessToken) &&
            \update_option('olxRefreshToken', $refreshToken);

        $this->olxAccessToken = $accessToken;
        $this->olxRefreshToken = $refreshToken;

        $expiresIn = $body->expires_in;
        $validUntil = new \DateTime(date('Y-m-d H:i:s'));
        $validUntil->modify('+ ' . $expiresIn . ' sec');
        $validUntilDate = $validUntil->format('Y-m-d H:i:s');

        // update options for last refresh
        update_option('olxTokensLastRefresh', date('Y-m-d H:i:s'));
        update_option('olxTokensValidUntil', $validUntilDate);

        return $optionsUpdated;
    }

    protected function getNewTokens()
    {
        $response = $this->guzzleClient->request(
            'POST',
            '/api/open/oauth/token',
            [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => $this->olxClientId,
                    'client_secret' => $this->olxClientSecret,
                    'scope' => 'v2 read write',
                    'code' => $this->olxCode,
                ],
            ]
        );

        // request body
        $body = json_decode($response->getBody());

        $accessToken = $body->access_token;
        $refreshToken = $body->refresh_token;

        $optionsUpdated =
            \update_option('olxAccessToken', $accessToken) &&
            \update_option('olxRefreshToken', $refreshToken);

        return $optionsUpdated;
    }

    public function authenticate()
    {
        $tokens = $this->getTokens();
        if ($tokens) {
            $tokensValidUntil = $this->olx->getOption('olxTokensValidUntil');
            if (date('Y-m-d H:i:s') > $tokensValidUntil) {
                error_log('[OLX] Renewing tokens');
                $this->isAuthenticated = $this->renewTokens();
            } else {
                // Tokens still valid
            }
        } else {
            $this->isAuthenticated = $this->getNewTokens();
        }
        return $this->isAuthenticated;
    }
}
