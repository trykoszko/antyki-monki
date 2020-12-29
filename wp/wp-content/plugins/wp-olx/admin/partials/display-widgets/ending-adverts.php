<?php
    $products = olx_get_products_with_ending_adverts();
?>
<div id="widget_ending_adverts" class="postbox">
    <h2 class="hndle ui-sortable-handle">
        <span><?php _e( 'Aukcje które niedługo się skończą', 'wp-olx' ); ?></span>
    </h2>
    <div class="inside">
        <table>
            <?php if ( $products ) : ?>
                <tr>
                    <td>
                        <?php _e( 'Ogłoszenie' ); ?>
                    </td>
                    <td>
                        <?php _e( 'Produkt' ); ?>
                    </td>
                    <td>
                        <?php _e( 'Ważne do' ); ?>
                    </td>
                </tr>
                <?php foreach ( $products as $product_id ) : ?>
                    <?php
                        $product_title = get_the_title( $product_id );
                        $product_url = get_permalink( $product_id );
                        $advert = json_decode( get_field( 'olx_olx_data', $product_id ) );
                        $advert_url = $advert->url;
                        $advert_title = $advert->title;
                        $advert_valid_to = $advert->valid_to
                    ?>
                    <tr>
                        <td>
                            <a href="<?php echo $advert_url; ?>">
                                <?php echo $advert_title; ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo $product_url; ?>">
                                <?php echo $product_url; ?>
                            </a>
                        </td>
                        <td>
                            <strong>
                                <?php echo $advert_valid_to; ?>
                            </strong>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div>
