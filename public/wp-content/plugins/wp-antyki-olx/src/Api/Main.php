<?php

namespace Antyki\Api;

use Antyki\Api\Controller as Controller;

use OpenApi as OpenApi;

class Main {

    public $apiVersion;
    public $apiUrl;

    protected $controller;

    public function __construct()
    {
        if (defined('WP_LOCAL_DEV') && WP_LOCAL_DEV) {
            $this->apiVersion = defined('ANTYKI_API_VERSION') ? ANTYKI_API_VERSION : 'v1';
            $this->apiUrl = '/api/' . $this->apiVersion . '/';

            $this->controller = new Controller();

            // $this->initDocs();
            $this->initRoutes();
        }
    }

    public function initDocs()
    {
        // @TODO:
        $openApi = OpenApi\scan(__DIR__);
        header('Content-Type: application/x-yaml');
        echo $openApi->toYaml();
    }

    public function initRoutes()
    {
        $routeList = [
            'products' => [
                'method' => 'GET',
                'callback' => [$this->controller, 'getAllProducts']
            ],
            'product/(?P<id>\d+)' => [
                'method' => 'GET',
                'callback' => [$this->controller, 'getSingleProduct']
            ],
            'categories' => [
                'method' => 'GET',
                'callback' => [$this->controller, 'getAllCategories']
            ],
            'category/(?P<id>\d+)' => [
                'method' => 'GET',
                'callback' => [$this->controller, 'getSingleCategory']
            ],
            'pages' => [
                'method' => 'GET',
                'callback' => [$this->controller, 'getAllPages']
            ],
            'options' => [
                'method' => 'GET',
                'callback' => [$this->controller, 'getAllOptions']
            ]
        ];
        foreach ($routeList as $endpoint => $args) {
            add_action('rest_api_init', function () use ($endpoint, $args) {
                register_rest_route('antyki/v1', $endpoint, $args);
            });
        }
    }

}
