<?php

namespace Antyki\Plugin;

use Antyki\Olx\Main as Olx;

use Antyki\Notice\Main as Notice;

class Cron {

    public $olx;

    public function __construct(
        Olx $olx
    )
    {
        $this->olx = $olx;
    }

    public function run_daily_8am()
    {
        error_log(json_encode([
            'Olx->Cron->run_daily_8am'
        ]));
        $this->refreshAdvertStats();
    }

    public function run_daily_10am()
    {
        error_log(json_encode([
            'Olx->Cron->run_daily_10am'
        ]));
        $this->refreshAdvertStats();
    }

    public function run_every_6_hours()
    {
        error_log(json_encode([
            'Olx->Cron->run_every_6_hours'
        ]));
        $this->refreshAdvertStats();
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
                $refreshed[] = $this->olx->requests->refreshAdvertStats($productId);
            }
            error_log(json_encode([
                'Olx->Cron->refreshAdvertStats' => $refreshed
            ]));
            Notice::send('ajax', json_encode([
                'Olx->Cron->refreshAdvertStats()' => [
                    'result' => count($refreshed) > 0
                ]
            ]));
        }
    }

}
