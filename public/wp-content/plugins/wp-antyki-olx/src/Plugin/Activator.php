<?php

namespace Antyki\Plugin;

class Activator
{
    public static function activate()
    {
        // CRON
        if (!wp_next_scheduled('wpAntykiOlx_cron_daily_8am')) {
            wp_schedule_event(strtotime('today 8am'), 'daily', 'wpAntykiOlx_cron_daily_8am');
        }
        if (!wp_next_scheduled('wpAntykiOlx_cron_daily_10am')) {
            wp_schedule_event(strtotime('today 10am'), 'daily', 'wpAntykiOlx_cron_daily_10am');
        }
        if (!wp_next_scheduled('wpAntykiOlx_cron_every_6_hours')) {
            wp_schedule_event(strtotime('today 6am'), 'every_six_hours', 'wpAntykiOlx_cron_every_6_hours');
        }
    }
}
