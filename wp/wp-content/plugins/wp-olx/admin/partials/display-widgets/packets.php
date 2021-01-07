<?php
    $packets = $olx->get_packets();
?>
<div id="widget_packets" class="postbox">
    <h2 class="hndle ui-sortable-handle">
        <span><?php _e( 'Pakiety ogłoszeń', 'wp-olx' ); ?></span>
    </h2>
    <div class="inside">
        <?php if ( $packets ) : ?>
            <table>
                <tr>
                    <td><strong><?php _e( '#', 'wp-olx' ); ?></strong></td>
                    <td><strong><?php _e( 'Nazwa', 'wp-olx' ); ?></strong></td>
                    <td><strong><?php _e( 'Pozostało ogłoszeń', 'wp-olx' ); ?></strong></td>
                    <td><strong><?php _e( 'Aktywne do', 'wp-olx' ); ?></strong></td>
                    <td><strong><?php _e( 'Ważne w kategorii', 'wp-olx' ); ?></strong></td>
                </tr>
                <?php $i = 1; ?>
                <?php foreach ( $packets as $packet ) : ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $packet->name; ?></td>
                        <td><?php echo $packet->left; ?></td>
                        <td><?php echo $packet->is_active ? $packet->active_to : __( 'Nieaktywny', 'wp-olx' ); ?></td>
                        <td><?php echo $packet->categories_labels[0]; ?></td>
                    </tr>
                    <?php $i++; ?>
                <?php endforeach; ?>
            </table>
            <a href="https://www.olx.pl/mojolx/shoppacks/"><?php _e( 'Wszystkie pakiety', 'wp-olx' ); ?></a>
        <?php else : ?>
            <?php _e( 'Brak pakietów ogłoszeń', 'wp-olx' ); ?>
        <?php endif; ?>
    </div>
</div>
