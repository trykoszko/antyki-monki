<?php

namespace Antyki\Olx;

use Antyki\Olx\Client\Auth as OlxClientAuth;
use Antyki\Olx\Client\Requests as OlxClientRequests;

class Main
{
    protected $guzzleClient;
    public $auth;
    protected $cache;
    protected $requests;

    public function __construct(\GuzzleHttp\Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
        $this->auth = new OlxClientAuth($this->guzzleClient);
        $this->cache = new Cache();
        $this->requests = new OlxClientRequests(
            $this->guzzleClient,
            $this->cache
        );
    }

    public function authTest()
    {
        return $this->auth->isAuthenticated();
    }
}
