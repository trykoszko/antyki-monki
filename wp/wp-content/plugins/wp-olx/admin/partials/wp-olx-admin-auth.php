<?php

/**
 * Provide a admin area view for the plugin auth
 *
 * @link       https://github.com/trykoszko
 * @since      1.0.0
 *
 * @package    Wp_Olx
 * @subpackage Wp_Olx/admin/partials
 */

if ( isset( $_GET['_olx_auth'] ) ) {
    if ( Olx::first_time_auth( get_option( '_olx_authorization_token' ) ) ) {
        wp_redirect( admin_url( '/admin.php?page=wp-olx' ) );
    } else {
        echo 'Authorization error';
    }
}

$is_authorized = Olx::has_tokens();
?>

<div class="wrap wrap--wp-olx">

    <h1><?php _e( 'OLX API Auth', 'wp-olx' ); ?></h1>

    <?php if ( ! $is_authorized ) : ?>

        <h3>
            To authorize:
        </h3>
        <ol>
            <li>Insert Client ID, Client Secret and State in <a href="<?php echo admin_url(); ?>admin.php?page=wp-olx-settings">Settings</a></li>
            <li>Click <a href="https://www.olx.pl/oauth/authorize/?client_id=<?php echo get_option( '_olx_client_id' ); ?>&response_type=code&state=<?php echo get_option( '_olx_state' ); ?>&scope=read+write+v2">here</a></li>
            <li>Copy `code` URL parameter</li>
            <li>Paste it below and save settings</li>
            <li>Click Authorize</li>
        </ol>

    <?php endif; ?>

    <form method="post" action="options.php">
        <?php settings_fields( 'olx_auth_settings' ); ?>

        <table class="form-table" role="presentation">

            <tbody>

                <tr>
                    <th scope="row">
                        <label for="_olx_authorization_token">
                            <?php _e( 'Authorization code', 'wp-olx' ); ?>
                        </label>
                    </th>
                    <td>
                        <input <?php echo $is_authorized ? 'disabled' : ''; ?> name="_olx_authorization_token" type="text" id="_olx_authorization_token" value="<?php echo get_option( '_olx_authorization_token' ); ?>" class="regular-text">
                    </td>
                </tr>

            </tbody>

        </table>

        <p class="submit">
            <input <?php echo $is_authorized ? 'disabled' : ''; ?> type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'wp-olx' ); ?>">
            <a <?php echo $is_authorized ? '' : 'href="' . get_admin_url() . 'admin.php?page=wp-olx-auth&_olx_auth=true"'; ?> class="button button-secondary"><?php _e( 'Authorize', 'wp-olx' ); ?></a>
        </p>

    </form>

</div>
