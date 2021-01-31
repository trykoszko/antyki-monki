<?php

namespace Antyki\Olx;

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

        // return json response
        echo \json_encode($advert);
        \wp_die();
    }

}
