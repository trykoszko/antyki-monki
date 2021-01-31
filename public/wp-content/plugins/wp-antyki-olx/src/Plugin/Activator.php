<?php

namespace Antyki\Plugin;

class Activator
{
    public static function activate()
    {
        // CRON
        if (!wp_next_scheduled('wpAntykiOlxCRON')) {
            wp_schedule_event(time(), 'daily', 'wpAntykiOlxCRON');
        }
    }
}
