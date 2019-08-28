<?php

class Advert {
    public static $title;
    public static $description;
    public static $category_id;
    public static $advertiser_type;
    public static $external_url;
    public static $external_id;
    public static $contact;
    public static $location;
    public static $price;
    public static $images;
    public static $attributes;
}

class Olx {

    public $textdomain = 'olx';

    private $client_id;
    private $client_secret;
    private $scope;
    private $code;
    private $olx_url = 'https://www.olx.pl';
    private $refresh_token;
    private $access_token;

    public function __construct() {

        $this->get_credentials();
        $this->get_token();
        $this->refresh_token();

        register_activation_hook( __FILE__, array( $this, 'on_activate' ) );

        add_action( 'admin_init', array( $this, 'on_admin_init' ) );
        add_action( 'admin_menu', array( $this, 'add_to_admin_menu' ) );

    }

    private function get_credentials() {
        require 'olx-credentials.php';
        $this->client_id = $credentials['client_id'];
        $this->client_secret = $credentials['client_secret'];
        $this->scope = $credentials['scope'];
        $this->code = $credentials['code'];
        $this->refresh_token = $credentials['refresh_token'];
    }

    private function get_token() {
        require 'olx-token.php';
        $this->access_token = $access_token;
    }

    public function add_to_admin_menu() {
        add_menu_page(
            'OLX',
            'OLX',
            'manage_options',
            'olx-admin',
            array( $this, 'create_admin_page' ),
            'dashicons-media-spreadsheet',
            30
        );
    }

    public function create_admin_page() {
        $adverts = $this->get_olx_adverts();
        require('views/data-table.php');
    }

    public function on_admin_init() {

        // add_filter( 'manage_posts_columns', array( $this, 'add_push_advert_column' ) );
        // add_action( 'manage_posts_custom_column' , array( $this, 'add_data_to_push_column' ), 10, 2 );

        wp_enqueue_script('jquery');

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ));
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ));

    }

    public function enqueue_admin_styles() {
        wp_register_style( 'custom_olx_admin_css', plugins_url('admin-style.css', __FILE__) );
        wp_enqueue_style( 'custom_olx_admin_css' );
    }

    public function enqueue_admin_scripts() {
        wp_register_script( 'custom_olx_admin_js', plugins_url('admin-scripts.js', __FILE__) );
        wp_enqueue_script( 'custom_olx_admin_js' );
    }

    public function add_push_advert_column( $columns ) {
        // $columns['push_to_olx'] = 'OLX';
        // return $columns;
    }

    public function add_data_to_push_column( $column, $post_id ) {
        // $screen = get_current_screen();
		// if ( ! isset( $screen->post_type ) ) {
		// 	return $actions;
		// }
		// // Not enough rights
		// $post_type_object = get_post_type_object( $screen->post_type );
		// if ( ! current_user_can( $post_type_object->cap->delete_post, $post_id ) ) {
		// 	return $actions;
		// }
		// // Not on the right screen
		// if ( ! in_array( $screen->id, array( 'edit-product' ) ) ) {
		// 	return $actions;
        // }
        // switch ( $column ) {
        //     case 'push_to_olx' :
        //         echo '<span class="button button-primary button-large push-to-olx" data-product-id="'. $post_id .'">Wystaw na OLX</span> //todo: status (wystawione / nie)';
        //         break;
        // }
    }

    private function refresh_token() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->olx_url . '/api/open/oauth/token');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->access_token,
            'Version: 2.0'
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'grant_type'        => 'refresh_token',
            'client_id'         => $this->client_id,
            'client_secret'     => $this->client_secret,
            'refresh_token'     => $this->refresh_token
        ));
        $res = curl_exec($ch);
        $res_json = json_decode($res);
        $new_acces_token = $res_json->access_token;
        $access_token_file = fopen('olx_token.php', 'w') or die ('Unable to open file.');
        fwrite($access_token_file, '<?php $access_token = "' . $new_acces_token . '"; ?>');
        fclose($access_token_file);
        return;
    }

    private function get_olx_adverts() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->olx_url . '/api/partner/adverts');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->access_token,
            'Version: 2.0'
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $res_json = json_decode($response);
        return $res_json->data;
    }

    private function sync_ads_to_posts() {
        $adverts = $this->get_olx_adverts();
        // check if post exists
        foreach ($adverts as $ad) :
            $ad_id = $ad->id;
            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'meta_key' => 'olx_id',
                'meta_value' => $ad_id,
                'posts_per_page' => -1
            );
            get_posts($args) ? $post_exists = true : $post_exists = false;

            $ad_categories = array();
            switch ($ad->category_id) :
                case 431 :
                    $ad_categories[] = 8; // Kolekcje
                    break;
                case 575 :
                    $ad_categories[] = 6; // Dekoracje (obrazy)
                    break;
                case 584 :
                    $ad_categories[] = 10; // Ogród
                    break;
                case 717 :
                    $ad_categories[] = 12; // Biurka
                    break;
                case 718 :
                    $ad_categories[] = 13; // Fotele
                    break;
                case 719 :
                    $ad_categories[] = 14; // Łózka i materace
                    break;
                case 720 :
                    $ad_categories[] = 15; // Regaly
                    break;
                case 721 :
                    $ad_categories[] = 16; // Stoly i krzesla
                    break;
                case 722 :
                    $ad_categories[] = 17; // Sofy i kanapy
                    break;
                case 723 :
                    $ad_categories[] = 18; // Szafy i komody
                    break;
                case 724 :
                    $ad_categories[] = 19; // Komplety mebli
                    break;
                case 725 :
                    $ad_categories[] = 20; // Meble pozostale
                    break;
            endswitch;

            $cat_data = get_category($ad_categories[0]);
            if ( $cat_data->parent ) {
                $ad_categories[] = $cat_data->parent;
            }

            $postarr = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'post_title' => $ad->title,
                'post_category' => $ad_categories
            );

            $post_id = wp_insert_post($postarr);

            update_post_meta( $post_id, 'olx_attributes_cat', $ad->category_id );
            update_post_meta( $post_id, 'olx_attributes_price', $ad->price->value );
            update_post_meta( $post_id, 'olx_attributes_state', $ad->attributes[0]->value );
            update_post_meta( $post_id, 'product_attributes_desc', $ad->description );
            update_post_meta( $post_id, 'olx_id', $ad->id );

            require_once('functions/img-from-url.php');

            $ad_imgs = $ad->images;
            $ad_gallery_arr = array();
            foreach ($ad_imgs as $img) {
                $attachment_id = insert_attachment_from_url($img->url . '.jpg');
                $ad_gallery_arr[] = $attachment_id;
            }

            update_field( 'product_gallery', $ad_gallery_arr, $post_id );

            if (!$post_exists) :

                echo '<p>Zaimportowano produkt: ' . $ad->title . '<br>';
                echo '<a target="_blank" href="' . get_post_permalink($post_id) . ">Zobacz produkt</a></p>";
                echo '<br>';

            else :

                echo '<p>Zaktualizowano produkt: ' . $ad->title . '<br>';
                echo '<a target="_blank" href="' . get_post_permalink($post_id) . ">Zobacz produkt</a></p>";
                echo '<br>';

            endif;

        endforeach;
    }

}