<?php

namespace Antyki\Container;

use DI\ContainerBuilder;
use function DI\factory;
use Psr\Container\ContainerInterface;

class Main
{
    public $container;
    public function __construct()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions([
            'GuzzleClient' => factory(function () {
                return new \GuzzleHttp\Client([
                    'base_uri' => 'https://www.olx.pl/'
                ]);
            }),
            'Twig' => factory(function () {
                return new \Antyki\Plugin\Twig();
            }),
            'Olx' => factory(function (ContainerInterface $c) {
                $olx = null;
                try {
                    $olx = new \Antyki\Olx\Main($c->get('GuzzleClient'));
                } catch (\Exception $e) {
                    error_log(json_encode([
                        'OLX error' => $e->getMessage()
                    ]));
                }
                return $olx;
            }),
            'Ajax' => factory(function (ContainerInterface $c) {
                return new \Antyki\Olx\Ajax($c->get('Olx'));
            }),
            'Cache' => factory(function () {
                return new \Antyki\Olx\Cache();
            }),
            'AdminViews' => factory(function (ContainerInterface $c) {
                return new \Antyki\Plugin\Admin\Views($c->get('Twig'), $c->get('Olx'));
            })
        ]);
        $this->container = $containerBuilder->build();
    }

    public function getInstance()
    {
        return $this->container;
    }
}