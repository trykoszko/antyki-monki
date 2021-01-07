<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/trykoszko
 * @since      1.0.0
 *
 * @package    Wp_Olx
 * @subpackage Wp_Olx/admin/partials
 */

Olx::check_auth();
$olx = new Olx();
?>

<div class="wrap wp-olx-wrap">

    <h1><?php _e( 'OLX', 'wp-olx' ); ?></h1>

    <div id="dashboard-widgets-wrap">

        <div id="dashboard-widgets" class="metabox-holder">

            <!-- Column 1 -->
            <div id="postbox-container-1" class="postbox-container">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                    <!-- Widget - Ending Adverts -->
                    <?php include __DIR__ . '/display-widgets/ending-adverts.php'; ?>
                    <!-- Widget - Packets -->
                    <?php include __DIR__ . '/display-widgets/packets.php'; ?>
                </div>
            </div>

            <!-- Column 2 -->
            <div id="postbox-container-2" class="postbox-container">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                    <!-- Widget - Messages -->
                    <?php include __DIR__ . '/display-widgets/messages.php'; ?>
                    <!-- Widget - User info -->
                    <?php include __DIR__ . '/display-widgets/user-info.php'; ?>
                </div>
            </div>

        </div>

    </div>

</div>
