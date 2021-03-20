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
            'advertSold',
            'sendErrorNotice',
            'refreshAdvertStats',
            'renewAdvert',
            'syncAllOlxToWp',
            'cleanupAllAdverts',
            'activateAdvert'
        ];

        foreach ($hooks as $hook) {
            add_action('wp_ajax_' . $hook, [$this, $hook]);
            add_action('wp_ajax_nopriv_' . $hook, [$this, $hook]);
        }
    }

    public function sendErrorNotice()
    {
        try {
            $args = $_REQUEST;
            $message = $args['message'];
            if (!isset($message) || !$message) {
                return;
            }
            $messageSent = Notice::send('error', $message);
            wp_send_json_success($messageSent);
            wp_die();
        } catch (Exception $e) {
            wp_send_json_error(false);
            wp_die();
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
        $isAdded = $advert && $advert['success'];

        Notice::send('ajax', json_encode([
            'Olx->Ajax->addAdvert() [' . $productId . '] - Success: ' . $isAdded
        ]));

        if ($isAdded) {
            \wp_send_json_success();
        } else {
            \wp_send_json_error();
        }

        \wp_die();
    }

    public function activateAdvert()
    {
        // arguments from AJAX request
        $args = $_REQUEST;
        $productId = $args['productId'];

        $advert = $this->olxClient->requests->renewAdvert($productId);
        $isAdded = $advert && $advert['success'];

        Notice::send('ajax', json_encode([
            'Olx->Ajax->activateAdvert() [' . $productId . '] - Success: ' . $isAdded
        ]));

        if ($isAdded) {
            \wp_send_json_success();
        } else {
            \wp_send_json_error();
        }

        \wp_die();
    }

    public function updateAdvert()
    {
        // arguments from AJAX request
        $args = $_REQUEST;
        $productId = $args['productId'];
        $advert = $this->olxClient->requests->updateAdvert($productId);

        Notice::send('ajax', json_encode([
            'Olx->Ajax->updateAdvert()' => [
                'params' => [
                    '$productId' => $productId
                ],
                'info' => [
                    'post_title' => get_the_title($productId)
                ],
                'result' => property_exists($advert, 'id')
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
        $advertSold = $this->olxClient->requests->advertSold($productId);

        Notice::send('ajax', json_encode([
            'Olx->Ajax->advertSold()' => [
                'params' => [
                    '$productId' => $productId
                ],
                'result' => $advertSold
            ]
        ]));

        // return json response
        echo \json_encode($advertSold);
        \wp_die();
    }

    public function refreshAdvertStats()
    {
        $products = get_posts([
            'post_type' => ANTYKI_CPT_PRODUCT,
            'posts_per_page' => -1,
            'fiels' => 'ids'
        ]);
        if ($products) {
            $refreshed = [];
            foreach ($products as $productId) {
                $refreshed[] = $this->olxClient->requests->refreshAdvertStats($productId);
            }
            error_log(json_encode([
                'Olx->Ajax->refreshAdvertStats' => $refreshed
            ]));
            Notice::send('ajax', json_encode([
                'Olx->Ajax->refreshAdvertStats()' => [
                    'result' => count($refreshed) > 0
                ]
            ]));
        }

        echo \json_encode(true);
        \wp_die();
    }

    public function renewAdvert()
    {
        $args = $_REQUEST;
        $productId = $args['productId'];

        if ($productId) {
            $refreshed = $this->olxClient->requests->renewAdvert($productId);
            error_log(json_encode([
                'Olx->Ajax->renewAdvert' => $refreshed
            ]));
            Notice::send('ajax', json_encode([
                'Olx->Ajax->renewAdvert()' => [
                    'result' => count($refreshed) > 0
                ]
            ]));
        }

        echo \json_encode(true);
        \wp_die();
    }

    public function syncAllOlxToWp()
    {
        $products = get_posts([
            'post_type' => ANTYKI_CPT_PRODUCT,
            'post_status' => ['publish', 'sold'],
            'posts_per_page' => -1,
            'fields' => 'ids'
        ]);
        if ($products) {
            $synced = [];
            foreach ($products as $productId) {
                $isSynced = $this->olxClient->requests->pullAdvertDataFromOlx($productId);
                if ($isSynced) {
                    $synced[] = $isSynced;
                }
            }
            error_log(json_encode([
                'Olx->Ajax->syncAllOlxToWp' => $synced
            ]));
            Notice::send('ajax', json_encode([
                'Olx->Ajax->syncAllOlxToWp()' => [
                    'result' => count($synced)
                ]
            ]));
        }

        echo \json_encode(true);
        \wp_die();
    }

    public function cleanupAllAdverts()
    {
        $products = get_posts([
            'post_type' => ANTYKI_CPT_PRODUCT,
            'post_status' => ['publish', 'sold'],
            'posts_per_page' => -1,
            'fields' => 'ids'
        ]);
        if ($products) {
            $cleaned = [];
            foreach ($products as $productId) {
                $isCleaned = $this->olxClient->requests->cleanupAdvert($productId);
                if ($isCleaned) {
                    $cleaned[] = $isCleaned;
                }
            }
            error_log(json_encode([
                'Olx->Ajax->cleanupAllAdverts' => $cleaned
            ]));
            Notice::send('ajax', json_encode([
                'Olx->Ajax->cleanupAllAdverts()' => [
                    'result' => count($cleaned)
                ]
            ]));
        }

        echo \json_encode(true);
        \wp_die();
    }

}
