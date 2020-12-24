<?php
namespace Trykoszko\Antyki;

class ProductPost extends \Timber\Post {

    var $_html;

    public static function getHtml($img) {
        echo '<figure class="c-card-gallery__img c-card-gallery__img--' . $img['orientation'] . ' c-card-gallery__img--' . $img['name'] . '">
            <img class="c-img o-object-fit-' . $img['object_fit'] . '" src="' . $img['src'] . '" alt="' . $img['alt'] . '" />
        </figure>';
    }

    public function cardImages() {
        $post_id = $this->id;

        $gallery = get_field('product_gallery', $post_id);
        $first_img = $gallery[0]['sizes']['thumbnail'];
        $second_img = $gallery[1]['sizes']['thumbnail'];

        $html = '';

        if ($first_img) {
            $first_img_aspect_ratio = round($gallery[0]['sizes']['medium-width'] / $gallery[0]['sizes']['medium-height'], 2);
            $first_img_array = [
                'exists' => true,
                'name' => 'first',
                'aspect_ratio' => $first_img_aspect_ratio,
                'object_fit' => $first_img_aspect_ratio === 1.78 || $first_img_aspect_ratio === 0.56 ? 'contain' : 'cover',
                'orientation' => $first_img_aspect_ratio > 1 ? 'landscape' : 'portrait',
                'src' => $first_img,
                'alt' => $gallery[0]['alt']
            ];
            $html .= self::getHtml($first_img_array);
        }

        if ($second_img) {
            $second_img_aspect_ratio = round($gallery[0]['sizes']['medium-width'] / $gallery[0]['sizes']['medium-height'], 2);
            $second_img_array = [
                'exists' => true,
                'name' => 'second',
                'aspect_ratio' => $second_img_aspect_ratio,
                'object_fit' => $second_img_aspect_ratio === 1.78 || $second_img_aspect_ratio === 0.56 ? 'contain' : 'cover',
                'orientation' => $second_img_aspect_ratio > 1 ? 'landscape' : 'portrait',
                'src' => $second_img,
                'alt' => $gallery[1]['alt']
            ];
            $html .= self::getHtml($second_img_array);
        }

        return $html;
    }

}
