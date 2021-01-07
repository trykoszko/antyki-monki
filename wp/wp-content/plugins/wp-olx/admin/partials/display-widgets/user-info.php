<?php
    $user_data = $olx->get_user_data();
?>
<div id="widget_user_info" class="postbox">
    <h2 class="hndle ui-sortable-handle">
        <span><?php _e( 'Informacje o użytkowniku', 'wp-olx' ); ?></span>
    </h2>
    <div class="inside">
        <table>
            <tr>
                <td><strong><?php _e( 'E-Mail', 'wp-olx' ); ?></strong></td>
                <td><?php echo $user_data->email; ?></td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Imię', 'wp-olx' ); ?></strong></td>
                <td><?php echo $user_data->name; ?></td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Ostatnie logowanie', 'wp-olx' ); ?></strong></td>
                <td><?php echo $user_data->last_login_at; ?></td>
            </tr>
        </table>
    </div>
</div>
