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
            case 'ajax_text':
                $content = $message;
                break;
            case 'ajax':
                $message = json_encode(json_decode($message), JSON_PRETTY_PRINT);
                $content = "```bash\n$message```";
                break;
            case 'general':
                $content = $message;
                break;
            case 'error':
            default:
                $content = "```css\n$message```\n\n";
                break;
        }

        $avatarUrl = 'https://img-resizer.prd.01.eu-west-1.eu.olx.org/img-eu-olxpl-production/921433466_1_192x192_rev001.jpg';

        $prodUrl = 'https://discord.com/api/webhooks/811711163125465188/ROyrjyFOwOyklYj7j4nkGLp0_aE0TSNwIgW6_lquJ-2a815D6JSIEQQaq64uyHzNEj1k';
        $testUrl = 'https://discord.com/api/webhooks/807987019463917658/UmVTi5zt1i2Zqws-zby7ke9dnDui1p5PkLPS0XHpRcZ_EMC_E1GD1W-QvCyZnXlc2mMS';

        $webhookUrl = getenv('WP_LOCAL_DEV') ? $testUrl : $prodUrl;

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
