<?php

namespace Antyki\Plugin;

use Antyki\Olx\Main as Olx;

class Cron {

    public $olx;

    public function __construct(
        Olx $olx
    )
    {
        $this->olx = $olx;
    }

    public function refreshAdvertStats()
    {
        $products = get_posts([
            'post_type' => ANTYKI_CPT_PRODUCT,
            'posts_per_page' => -1,
            'fiels' => 'ids'
        ]);
        if ($products) {
            foreach ($products as $productId) {
                $this->olx->requests->refreshAdvertStats($productId);
            }
        }
    }

}
