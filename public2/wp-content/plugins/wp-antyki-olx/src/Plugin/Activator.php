<?php

namespace Antyki\Plugin;

class Activator
{
    public static function activate()
    {
        // CRON
        if (!wp_next_scheduled('antyki_cron_hook')) {
            wp_schedule_event(time(), 'daily', 'antyki_cron_hook');
        }
    }
}
