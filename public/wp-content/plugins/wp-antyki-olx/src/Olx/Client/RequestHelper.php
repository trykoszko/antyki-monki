<?php

namespace Antyki\Olx\Client;

class RequestHelper
{
    public static function getWpProductByAdvertId($advertId)
    {
        $adverts = get_posts([
            'post_type' => 'product',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_key' => 'olx_id',
            'meta_value' => $advertId,
        ]);
        if ($adverts) {
            return $adverts[0];
        }
        return false;
    }
}
