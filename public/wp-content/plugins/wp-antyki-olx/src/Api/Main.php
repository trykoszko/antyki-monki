<?php

namespace Antyki\Api;

use Antyki\Api\Controller as Controller;

class Main {

    public $apiVersion;
    public $apiUrl;

    protected $controller;

    public function __construct()
    {
        $this->apiVersion = defined('ANTYKI_API_VERSION') ? ANTYKI_API_VERSION : 'v1';
        $this->apiUrl = '/api/' . $this->apiVersion . '/';

        $this->controller = new Controller();

        $this->initRoutes();
    }

    public function initRoutes()
    {
        $routeList = [
            // $endpointName => $args
            'products' => [
                'methods' => 'GET',
                'callback' => [$this->controller, 'getAllProducts'],
            ],
            'products/(?P<id>\d+)' => [
                'methods' => 'GET',
                'callback' => [$this->controller, 'getSingleProduct'],
                // 'args' => [
                //     'id' => [
                //         'validate_callback' => function($param) {
                //             return is_numeric($param);
                //         }
                //     ]
                // ],
            ],
            // 'categories' => [],
            // 'category' => ['term_id'],
            // 'pages' => []
        ];
        foreach ($routeList as $endpoint => $args) {
            add_action('rest_api_init', function () use ($endpoint, $args) {
                register_rest_route('antyki/v1', $endpoint, $args);
            });
        }
    }

}
