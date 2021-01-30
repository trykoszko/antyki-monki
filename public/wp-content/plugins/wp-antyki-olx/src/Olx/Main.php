<?php

namespace Antyki\Olx;

use Antyki\Olx\Client\Auth as OlxClientAuth;
use Antyki\Olx\Client\Requests as OlxClientRequests;

class Main
{
    protected $guzzleClient;
    public $auth;
    protected $cache;
    public $requests;

    public function __construct(\GuzzleHttp\Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
        $this->auth = new OlxClientAuth($this->guzzleClient, $this);
        $this->cache = new Cache();
        $this->requests = new OlxClientRequests(
            $this->guzzleClient,
            $this->cache,
            $this
        );
    }

    public function getOption($optionName)
    {
        if (isset($_ENV[$optionName])) {
            return $_ENV[$optionName];
        }

        if (defined($optionName)) {
            return constant($optionName);
        }

        if (\get_option($optionName)) {
            return \get_option($optionName);
        }

        return false;
    }

    public function authTest()
    {
        return $this->auth->isAuthenticated();
    }
}
