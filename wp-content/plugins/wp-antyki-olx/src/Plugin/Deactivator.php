<?php

namespace Antyki\Plugin;

class Deactivator
{
    public static function deactivate()
    {
        // CRON
        $timestamp = wp_next_scheduled('antyki_cron_hook');
        wp_unschedule_event($timestamp, 'antyki_cron_hook');
    }
}
