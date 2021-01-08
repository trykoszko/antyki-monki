<?php
namespace Trykoszko\Antyki;

class ProductPost extends \Timber\Post {

    var $_images;

    public function cardImages() {

        global $site;

        $images = $site->generate_product_images_html( $this->id );

        return $images;

    }

}
