<?php

/**
 * Provide a admin area view for the plugin settings
 *
 * @link       https://github.com/trykoszko
 * @since      1.0.0
 *
 * @package    Wp_Olx
 * @subpackage Wp_Olx/admin/partials
 */
?>

<div class="wrap wrap--wp-olx">

    <h1><?php _e( 'OLX API Settings', 'wp-olx' ); ?></h1>

    <?php settings_errors(); ?>

    <form method="post" action="options.php">
        <?php settings_fields( 'olx_settings' ); ?>

        <table class="form-table" role="presentation">

            <tbody>

                <tr>
                    <th scope="row">
                        <label for="_olx_client_id">
                            <?php _e( 'Client ID', 'wp-olx' ); ?>
                        </label>
                    </th>
                    <td>
                        <input name="_olx_client_id" type="text" id="_olx_client_id" value="<?php echo get_option( '_olx_client_id' ); ?>" class="regular-text">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="_olx_client_secret">
                            <?php _e( 'Client secret', 'wp-olx' ); ?>
                        </label>
                    </th>
                    <td>
                        <input name="_olx_client_secret" type="text" id="_olx_client_secret" value="<?php echo get_option( '_olx_client_secret' ); ?>" class="regular-text">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="_olx_state">
                            <?php _e( 'State', 'wp-olx' ); ?>
                        </label>
                    </th>
                    <td>
                        <input name="_olx_state" type="text" id="_olx_state" value="<?php echo get_option( '_olx_state' ); ?>" class="regular-text">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="_olx_access_token">
                            <?php _e( 'Access token', 'wp-olx' ); ?>
                        </label>
                    </th>
                    <td>
                        <input disabled name="_olx_access_token" type="text" id="_olx_access_token" value="<?php echo get_option( '_olx_access_token' ); ?>" class="regular-text">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="_olx_refresh_token">
                            <?php _e( 'Refresh token', 'wp-olx' ); ?>
                        </label>
                    </th>
                    <td>
                        <input disabled name="_olx_refresh_token" type="text" id="_olx_refresh_token" value="<?php echo get_option( '_olx_refresh_token' ); ?>" class="regular-text">
                    </td>
                </tr>

            </tbody>

        </table>

        <?php submit_button(); ?>

        <?php
            /**
             * @todo reset button
             */
        ?>

    </form>

</div>
