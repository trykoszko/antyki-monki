<?php

/**
 * Provide a admin area view for the general settings
 *
 * @link       https://github.com/trykoszko
 * @since      1.0.0
 *
 * @package    Wp_Olx
 * @subpackage Wp_Olx/admin/partials
 */

Olx::check_auth();

?>

<div class="wrap wrap--wp-olx">

    <h1><?php _e( 'Ustawienia wtyczki', 'wp-olx' ); ?></h1>

    <?php settings_errors(); ?>

    <form method="post" action="options.php">
        <?php settings_fields( 'olx_general_settings' ); ?>

        <table class="form-table" role="presentation">

            <tbody>

                <tr>
                    <th scope="row">
                        <label for="_olx_enable_cache">
                            <?php _e( 'Włączyć cache?', 'wp-olx' ); ?>
                        </label>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e( 'Włączyć cache?', 'wp-olx' ); ?></span>
                            </legend>
                            <label for="_olx_enable_cache">
                                <input name="_olx_enable_cache" type="checkbox" id="_olx_enable_cache" <?php echo get_option( '_olx_enable_cache' ) == 'on' ? 'checked' : ''; ?>>
                                <?php _e( 'Zaznacz jeżeli chcesz włączyć API cache', 'wp-olx' ); ?>
                            </label>
                        </fieldset>
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
