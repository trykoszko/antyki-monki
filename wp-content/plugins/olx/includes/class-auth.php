<?php

class Auth {

    private $client_id;
    private $client_secret;
    private $scope = 'v2 read write';
    private $access_token;
    private $refresh_token;
    private $olx_url = 'https://www.olx.pl/api/open/oauth/token';

    private function get_client_credentials() {

        $this->client_id = get_option( '_olx_client_id' );
        $this->client_secret = get_option( '_olx_client_secret' );
        $this->code = get_option( '_olx_code' );
        $this->state = get_option( '_olx_state' );

    }

    private function get_tokens() {

        $this->access_token = get_option( '_olx_access_token' );
        $this->refresh_token = get_option( '_olx_refresh_token' );

    }

    public function authorize() {

        $this->get_client_credentials();

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_URL, $this->olx_url );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, array(
            'grant_type'        => 'authorization_code',
            'scope'             => $this->scope,
            'client_id'         => $this->client_id,
            'client_secret'     => $this->client_secret,
            'redirect_uri'      => 'https://antyki-monki.pl/auth',
            'code'              => $this->code,
            'state'             => $this->state
        ) );
        $res_json = curl_exec( $ch );
        $res = json_decode( $res_json );

        var_dump($res);

        $this->access_token = $res->access_token;
        $this->refresh_token = $res->refresh_token;

        update_option( '_olx_access_token', $res->access_token );
        update_option( '_olx_refresh_token', $res->refresh_token );

    }

    public function get_api_token() {

        $this->get_client_credentials();
        $this->get_tokens();

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_URL, $this->olx_url );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, array(
            'grant_type'        => 'refresh_token',
            'refresh_token'     => $this->refresh_token,
            'client_id'         => $this->client_id,
            'client_secret'     => $this->client_secret
        ) );
        $res_json = curl_exec( $ch );
        $res = json_decode( $res_json );

        var_dump($res);

        // $this->access_token = $res->access_token;
        // $this->refresh_token = $res->refresh_token;

        // update_option( '_olx_access_token', $this->access_token );
        // update_option( '_olx_refresh_token', $this->refresh_token );

        // return $this->access_token;

    }

}
