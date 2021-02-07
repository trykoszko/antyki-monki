<?php

namespace Antyki\Notice;

use \GuzzleHttp\Client as GuzzleClient;
use \GuzzleHttp\Middleware as GuzzleMiddleware;
use \GuzzleHttp\HandlerStack as GuzzleHandlerStack;
use \GuzzleHttp\Handler\CurlHandler as GuzzleCurlHandler;

class Main {

    public static function send($type, $message)
    {
        switch ($type) {
            case 'ajax':
                $webhookUrl = 'https://discord.com/api/webhooks/807987019463917658/UmVTi5zt1i2Zqws-zby7ke9dnDui1p5PkLPS0XHpRcZ_EMC_E1GD1W-QvCyZnXlc2mMS';
                break;
            case 'error':
            case 'general':
            default:
                $webhookUrl = 'https://discord.com/api/webhooks/807967332496179211/snTM_QBA4nS2I9Essg8jVN5xedzjs0V6eR4Q1BJH1tXOjUP3-odIJbgW7Wlo7udeIVsT';
            break;
        }

        $avatarUrl = 'https://img-resizer.prd.01.eu-west-1.eu.olx.org/img-eu-olxpl-production/921433466_1_192x192_rev001.jpg';

        $content =
            $type !== 'error'
            ? '```' . json_encode(json_decode($message), JSON_PRETTY_PRINT) . '```'
            : 'ERROR!
            ```' . $message . '```';

        $tapMiddleware = GuzzleMiddleware::tap(function ($request) {
            error_log($request->getHeaderLine('Content-Type'));
            error_log($request->getBody());
        });
        $handler = new GuzzleCurlHandler();
        $stack = GuzzleHandlerStack::create($handler);
        $guzzle = new GuzzleClient([
            'handler' => $stack
        ]);
        $response = $guzzle->request('POST', $webhookUrl, [
            'json' => [
                'username' => 'Antyki',
                'avatar_url' => $avatarUrl,
                'content' => $content,
                'handler' => $tapMiddleware($handler)
            ]
        ]);

        return $response;
    }

}
