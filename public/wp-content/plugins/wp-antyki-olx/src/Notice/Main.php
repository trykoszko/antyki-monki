<?php

namespace Antyki\Notice;

use \GuzzleHttp\Client as GuzzleClient;

class Main {

    public static function send($message)
    {
        $webhookUrl = 'https://discord.com/api/webhooks/807967332496179211/snTM_QBA4nS2I9Essg8jVN5xedzjs0V6eR4Q1BJH1tXOjUP3-odIJbgW7Wlo7udeIVsT';

        $guzzle = new GuzzleClient();
        $response = $guzzle->request(
            'POST',
            $webhookUrl,
            [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'form_params' => [
                    'username' => 'Antyki',
                    'avatar_url' => 'https://img-resizer.prd.01.eu-west-1.eu.olx.org/img-eu-olxpl-production/921433466_1_192x192_rev001.jpg',
                    'content' => 'AJAX action result: ' . PHP_EOL . '```' . $message . '```'
                ]
            ]
        );

        return $response;

    }

}
