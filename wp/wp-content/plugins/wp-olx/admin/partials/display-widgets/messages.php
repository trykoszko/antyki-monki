<?php
    $messages = $olx->get_messages();
?>
<div id="widget_messages" class="postbox">
    <h2 class="hndle ui-sortable-handle">
        <span><?php _e( 'Wiadomości', 'wp-olx' ); ?></span>
    </h2>
    <div class="inside">
        <?php if ( $messages ) : ?>
            <table>
                <tr>
                    <td><?php _e( '#', 'wp-olx' ); ?></td>
                    <td><?php _e( 'Wiadomość do ogłoszenia', 'wp-olx' ); ?></td>
                </tr>
                <?php $i = 1; ?>
                        <?php var_dump($messages); ?>
                <?php foreach ( $messages as $message ) : ?>
                    <tr>
                        <?php
                            /**
                             * @todo
                             */
                        ?>
                        <td><?php echo $i; ?></td>
                        <td><?php echo olx_get_product_id_by_advert_id( $message->advert_id ); ?></td>
                    </tr>
                    <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <?php _e( 'Brak wiadomości', 'wp-olx' ); ?>
        <?php endif; ?>
    </div>
</div>
