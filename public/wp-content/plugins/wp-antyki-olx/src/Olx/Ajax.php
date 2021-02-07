<?php

namespace Antyki\Olx;

use Antyki\Notice\Main as Notice;

use \Exception as Exception;

class Ajax
{
    protected $olxClient;

    public function __construct(\Antyki\Olx\Main $olxClient)
    {
        $this->olxClient = $olxClient;

        $this->loadAjaxHooks();
    }

    public function loadAjaxHooks()
    {
        $hooks = [
            'checkStatus',
            'addAdvert',
            'updateAdvert',
            'advertSold'
        ];

        foreach ($hooks as $hook) {
            add_action('wp_ajax_' . $hook, [$this, $hook]);
            add_action('wp_ajax_nopriv_' . $hook, [$this, $hook]);
        }
    }

    public function checkStatus()
    {
        try {
            $args = $_REQUEST;
            $nonce = $args['nonce'];
            if (!isset($nonce) || !$nonce) {
                throw new Exception('AJAX: Nonce validation error');
            }

            $authTest = $this->olxClient->authTest();
            $isAuth = $this->olxClient->auth->isAuthenticated;

            Notice::send(json_encode([
                'Olx->Ajax->checkStatus()' => [
                    'result' => [
                        '$authTest' => $authTest,
                        '$isAuth' => $isAuth
                    ],
                ]
            ]));

            wp_send_json_success($authTest || $isAuth);
            wp_die();
        } catch (Exception $e) {
            wp_send_json_error([
                'response' => $e->getMessage(),
            ]);
            wp_die();
        }
    }

    public function addAdvert()
    {
        // arguments from AJAX request
        $args = $_REQUEST;
        $productId = $args['productId'];
        $advert = $this->olxClient->requests->addAdvert($productId);

        Notice::send(json_encode([
            'Olx->Ajax->addAdvert()' => [
                'params' => [
                    '$productId' => $productId
                ],
                'result' => $advert
            ]
        ]));

        // return json response
        echo \json_encode($advert);
        \wp_die();
    }

    public function updateAdvert()
    {
        // arguments from AJAX request
        $args = $_REQUEST;
        $productId = $args['productId'];
        $advert = $this->olxClient->requests->updateAdvert($productId);

        Notice::send(json_encode([
            'Olx->Ajax->updateAdvert()' => [
                'params' => [
                    '$productId' => $productId
                ],
                'result' => $advert
            ]
        ]));

        // return json response
        echo \json_encode($advert);
        \wp_die();
    }

    public function advertSold()
    {
        // arguments from AJAX request
        $args = $_REQUEST;
        $productId = $args['productId'];
        $advert = $this->olxClient->requests->advertSold($productId);

        Notice::send(json_encode([
            'Olx->Ajax->advertSold()' => [
                'params' => [
                    '$productId' => $productId
                ],
                'result' => $advert
            ]
        ]));

        // return json response
        echo \json_encode($advert);
        \wp_die();
    }

}
