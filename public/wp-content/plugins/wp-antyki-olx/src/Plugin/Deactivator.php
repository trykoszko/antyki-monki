<?php

namespace Antyki\Plugin;

class Deactivator
{
    public static function deactivate()
    {
        // CRON
        $timestamp = wp_next_scheduled('wpAntykiOlxCRON');
        wp_unschedule_event($timestamp, 'wpAntykiOlxCRON');
    }
}
