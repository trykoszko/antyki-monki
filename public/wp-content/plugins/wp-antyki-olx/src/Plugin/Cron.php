<?php

namespace Antyki\Plugin;

use Antyki\Olx\Main as Olx;

use Antyki\Olx\Client\RequestHelper as RequestHelper;

use Antyki\Notice\Main as Notice;

class Cron {

    public $olx;

    public function __construct(
        Olx $olx
    )
    {
        $this->olx = $olx;
    }

    public function run_once_a_week()
    {
        Notice::send('ajax_text', '--------------------------------
Olx->Cron->run_once_a_week() start');

        Notice::send('ajax_text', 'Olx->Cron->run_once_a_week() end');
    }

    public function run_daily_8am()
    {
        Notice::send('ajax_text', '--------------------------------
Olx->Cron->run_daily_8am() start');

        $this->refreshAdverts();

        Notice::send('ajax_text', 'Olx->Cron->run_daily_8am() end');
    }

    public function run_daily_10am()
    {
        Notice::send('ajax_text', '
        --------------------------------
Olx->Cron->run_daily_10am() start');

        $this->refreshAdvertStats();

        Notice::send('ajax_text', 'Olx->Cron->run_daily_10am() end');
    }

    public function run_every_6_hours()
    {
        Notice::send('ajax_text', '--------------------------------
Olx->Cron->run_every_6_hours() start');

        $this->refreshAdvertStats();

        Notice::send('ajax_text', 'Olx->Cron->run_every_6_hours() end');
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
            $refreshedCount = count($refreshed);

            Notice::send('ajax_text', "Olx->Cron->refreshAdvertStats(): updated $refreshedCount advert stats");
        }
    }

    public function refreshAdverts()
    {
        $allAdverts = $this->olx->requests->getAllAdverts();
        if ($allAdverts) {
            $updatedCount = 0;

            foreach ($allAdverts as $advert) {
                $advertId = $advert->id;
                $postId = RequestHelper::getWpProductByAdvertId($advertId);

                $categoryId = $advert->category_id;
                $parentCategoryId = $this->olx->requests->getParentCatId($categoryId);
                $status = $advert->status;
                $validTo = $advert->valid_to;

                if ($postId) {
                    $validToUpdated = update_field('olx_valid_to', $validTo, $postId);
                    $olxDataUpdated = update_field('olx_olx_data', json_encode($advert, JSON_UNESCAPED_UNICODE), $postId);
                    $olxStatusUpdated = update_field('olx_status', $status, $postId);
                    $olxParentCatUpdated = update_field('olx_parent_category_id', $parentCategoryId, $postId);

                    if ($validToUpdated || $olxDataUpdated || $olxStatusUpdated || $olxParentCatUpdated) {
                        $updatedCount++;
                    }
                }

            }

            Notice::send('ajax_text', "Olx->Cron->refreshAdverts(): updated $updatedCount adverts");
        }
    }

}
