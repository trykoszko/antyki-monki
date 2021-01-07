<?php

/**
 * OLX Class
 *
 * @link       https://github.com/trykoszko
 * @since      1.0.0
 *
 * @package    Wp_Olx
 * @subpackage Wp_Olx/includes
 */

/**
 * Use Guzzle namespace
 *
 * @since 1.0.0
 */
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

/**
 * Auth, pushing, pulling adverts etc
 *
 * @since 1.0.0
 */
class Olx {

    private $access_token;
    private $refresh_token;
    private $client_id;
    private $client_secret;

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct() {

        if ( self::has_tokens() ) {

            // get credentials from options
            $this->get_credentials();

            // new Guzzle client
            $client = new Client();

            try {

                $response = $client->request(
                    'POST',
                    'https://www.olx.pl/api/open/oauth/token',
                    array(
                        'headers' => array(
                            'Authorization' => "Bearer $this->access_token",
                            'Version' => '2.0'
                        ),
                        'form_params' => array(
                            'grant_type' => 'refresh_token',
                            'client_id' => $this->client_id,
                            'client_secret' => $this->client_secret,
                            'refresh_token' => $this->refresh_token
                        )
                    )
                );

                // request body
                $body = json_decode( $response->getBody() );

                $access_token = $body->access_token;
                $refresh_token = $body->refresh_token;

                // update WordPress options
                update_option( '_olx_access_token', $access_token );
                update_option( '_olx_refresh_token', $refresh_token );

                // update options for last refresh
                update_option( '_olx_tokens_last_refresh', date( 'Y-m-d H:i:s' ) );

            } catch ( RequestException $e ) {

                if ( is_admin() ) {

                    echo Psr7\str( $e->getRequest() );

                    if ( $e->hasResponse() ) {

                        echo Psr7\str( $e->getResponse() );

                    }

                }

                // first time authorize if refreshing token failed
                self::first_time_auth();

            }

        } else {

            self::first_time_auth();

        }

    }

    /**
     * Check if Access token and Refresh tokens are available
     *
     * @since 1.0.0
     *
     * @return {Boolean} $is_authorized Authorization status
     */
    public static function has_tokens() {

        if ( empty( get_option( '_olx_client_id' ) ) || empty( get_option( '_olx_client_id' ) ) ) {

            return false;

        }

        // check if is authorized
        $is_authorized = strlen( get_option( '_olx_access_token' ) ) > 10;

        return $is_authorized;

    }

    /**
     * Get credentials from WP Options table
     *
     * @since 1.0.0
     */
    private function get_credentials() {

        $this->access_token = get_option( '_olx_access_token' );
        $this->refresh_token = get_option( '_olx_refresh_token' );
        $this->client_id = get_option( '_olx_client_id' );
        $this->client_secret = get_option( '_olx_client_secret' );

    }

    /**
     * Authorize for the first time
     * Get Access token
     * Get Refresh token
     *
     * @since 1.0.0
     */
    public static function first_time_auth() {

        // new Guzzle client
        $client = new Client();

        try {

            // request for getting tokens
            $response = $client->request(
                'POST',
                'https://www.olx.pl/api/open/oauth/token',
                array(
                    'form_params' => array(
                        'grant_type' => 'authorization_code',
                        'client_id' => get_option( '_olx_client_id' ),
                        'client_secret' => get_option( '_olx_client_secret' ),
                        'scope' => 'v2 read write',
                        'code' => get_option( '_olx_authorization_token' )
                    )
                )
            );

            // request body
            $body = json_decode( $response->getBody() );

            $access_token = $body->access_token;
            $refresh_token = $body->refresh_token;

            // update WordPress options
            if ( update_option( '_olx_access_token', $access_token ) && update_option( '_olx_refresh_token', $refresh_token ) ) {
                return true;
            } else {
                return false;
            }

        } catch ( RequestException $e ) {

            update_option( '_olx_client_id', '' );

            if ( is_admin() ) {

                wp_redirect( admin_url( '/admin.php?page=wp-olx-settings' ) );

            }

        }

    }

    /**
     * Method that checks authorization and redirects to auth page if needed
     *
     * @since 1.0.0
     */
    public static function check_auth() {
        if ( ! Olx::has_tokens() ) {
            wp_redirect( admin_url( 'admin.php?page=wp-olx-auth' ) );
        }
    }

    /**
     * Method that gets adverts
     *
     * @since 1.0.0
     */
    public function get_adverts() {

        $data = false;

        // authorize
        if ( Olx::has_tokens() ) {

            $transient = 'olx_api/partner/adverts';
            $endpoint = 'https://www.olx.pl/api/partner/adverts';

            if ( Olx_Cache::get( $transient ) ) {
                return Olx_Cache::get( $transient );
            }

            // new Guzzle client
            $client = new Client();

            // request for getting tokens
            $response = $client->request(
                'GET',
                $endpoint,
                array(
                    'headers' => array(
                        'Authorization' => "Bearer $this->access_token",
                        'Version' => '2.0'
                    )
                )
            );

            // response
            $data = json_decode( $response->getBody() )->data;

            // save to cache
            Olx_Cache::set( $transient, $data );

        }

        return $data;

    }

    /**
     * Method that returns Olx user data
     *
     * @since 1.0.0
     */
    public function get_user_data() {

        $data = false;

        // authorize
        if ( Olx::has_tokens() ) {

            $transient = 'olx_api/partner/users/me';
            $endpoint = 'https://www.olx.pl/api/partner/users/me';

            if ( Olx_Cache::get( $transient ) ) {
                return Olx_Cache::get( $transient );
            }

            // new Guzzle client
            $client = new Client();

            // request
            $response = $client->request(
                'GET',
                $endpoint,
                array(
                    'headers' => array(
                        'Authorization' => "Bearer $this->access_token",
                        'Version' => '2.0'
                    )
                )
            );

            // request body
            $data = json_decode( $response->getBody() )->data;

            // save to cache
            Olx_Cache::set( $transient, $data );

        }

        return $data;

    }

    /**
     * Method that returns Olx advert packets data
     *
     * @since 1.0.0
     */
    public function get_packets() {

        $data = false;

        // authorize
        if ( Olx::has_tokens() ) {

            $transient = 'olx_api/partner/packets';
            $endpoint = 'https://www.olx.pl/api/partner/users/me/packets';

            if ( Olx_Cache::get( $transient ) ) {
                return Olx_Cache::get( $transient );
            }

            // new Guzzle client
            $client = new Client();

            // request
            $response = $client->request(
                'GET',
                $endpoint,
                array(
                    'headers' => array(
                        'Authorization' => "Bearer $this->access_token",
                        'Version' => '2.0'
                    )
                )
            );

            $data = json_decode( $response->getBody() )->data;

            // save to cache
            Olx_Cache::set( $transient, $data );

        }

        return $data;

    }

    /**
     * Method that returns Olx messages
     *
     * @since 1.0.0
     */
    public function get_messages() {

        $data = false;

        // authorize
        if ( Olx::has_tokens() ) {

            $transient = 'olx_api/partner/threads';
            $endpoint = 'https://www.olx.pl/api/partner/threads';

            if ( Olx_Cache::get( $transient ) ) {
                return Olx_Cache::get( $transient );
            }

            // new Guzzle client
            $client = new Client();

            // request
            $response = $client->request(
                'GET',
                $endpoint,
                array(
                    'headers' => array(
                        'Authorization' => "Bearer $this->access_token",
                        'Version' => '2.0'
                    )
                )
            );

            $data = json_decode( $response->getBody() )->data;

            // save to cache
            Olx_Cache::set( $transient, $data );

        }

        return $data;

    }

    /**
     * Method that adds a product as an advert to OLX
     *
     * @since 1.0.0
     *
     * @param int $product_id Post ID of local product
     */
    public function add_advert( $product_id ) {

        // set endpoint
        $endpoint = 'https://www.olx.pl/api/partner/adverts';

        // new Guzzle client
        $client = new Client();

        // get product data
        $product_attrs = get_field( 'product_attributes', $product_id );
        $product_olx_attrs = get_field( 'olx_attributes', $product_id );
        $product_images = get_field( 'product_gallery', $product_id );

        // additional info field
        $additional_info = null;
        if ( isset( $product_attrs['additional'] ) ) {
            $add_info = array();
            foreach ( $product_attrs['additional'] as $attr ) {
                $add_info[] = $attr['label'];
            }
            $additional_info = implode( ', ', $add_info );
        }

        // material
        $material_info = null;
        if ( isset( $product_attrs['material'] ) ) {
            $mat_info = array();
            foreach ( $product_attrs['material'] as $attr ) {
                $mat_info[] = $attr['label'];
            }
            $material_info = implode( ', ', $mat_info );
        }

        // description field
        $desc = 'Dzień dobry.

        ';
        $desc .= isset( $product_attrs['desc'] ) ? $product_attrs['desc'] . '

        ' : '';
        $desc .= 'Atrybuty przedmiotu:
        ';
        $desc .= isset( $product_attrs['width'] ) ? 'Szerokość: ' . $product_attrs['width'] . 'cm
        ' : '';
        $desc .= isset( $product_attrs['height'] ) ? 'Wysokość: ' . $product_attrs['height'] . 'cm
        ' : '';
        $desc .= isset( $product_attrs['depth'] ) ? 'Głębokość: ' . $product_attrs['depth'] . 'cm
        ' : '';
        $desc .= $material_info ? 'Materiał wykonania: ' . $material_info . '
        ' : '';
        $desc .= isset( $product_attrs['canvas_type'] ) ? 'Rodzaj podobrazia: ' . $product_attrs['canvas_type']['label'] . '
        ' : '';
        $desc .= isset( $product_attrs['paint_type'] ) ? 'Rodzaj wykończenia: ' . $product_attrs['paint_type']['label'] . '
        ' : '';
        $desc .= isset( $product_attrs['state'] ) ? 'Stan: ' . $product_attrs['state']['label'] . '
        ' : '';
        $desc .= $additional_info ? 'Dodatkowe informacje: ' . $additional_info . '
        ' : '';
        $desc .= '

        ';
        $desc .= 'Po więcej antyków i staroci zapraszamy na naszą stronę internetową: antyki-monki.pl';

        // images
        $images = array();
        foreach ( $product_images as $image ) {
            $images[] = array(
                'url' => $image['url']
            );
        }

        // product params
        $params = array(
            'title' => $product_olx_attrs['olx_title'],
            'description' => str_replace( '↵', '', $desc ),
            'category_id' => $product_olx_attrs['cat']['value'],
            'advertiser_type' => 'business',
            'contact' => array(
                'name' => get_field( 'olx_settings_person_name', 'option' ),
                'phone' => get_field( 'olx_settings_person_phone', 'option' )
            ),
            'location' => array(
                'city_id' => get_field( 'olx_settings_place', 'option' )['value']
            ),
            'images' => $images,
            'price' => array(
                'value' => $product_olx_attrs['price'],
                'currency' => 'PLN',
                'negotiable' => ! empty( $product_olx_attrs['price_min'] ),
                'trade' => false
            ),
            'attributes' => array(
                array(
                    'code' => 'state',
                    'value' => $product_olx_attrs['state']
                )
            )
        );

        // request
        $response = $client->request(
            'POST',
            $endpoint,
            array(
                'headers' => array(
                    'Authorization' => "Bearer $this->access_token",
                    'Version' => '2.0',
                    'Content-Type' => 'application/json'
                ),
                'body' => json_encode( $params )
            )
        );

        // parse response
        $res = json_decode( $response->getBody() );
        $success = '';

        if ( property_exists( $res, 'error' ) ) {
            $success = false;
        } else {
            // get data from response
            $response_data = $res->data;

            // update acf fields
            $olx_id = $response_data->id;
            $olx_created_at = $response_data->created_at;
            $olx_valid_to = $response_data->valid_to;
            update_field( 'olx_id', $olx_id, $product_id );
            update_field( 'olx_created_at', $olx_created_at, $product_id );
            update_field( 'olx_valid_to', $olx_valid_to, $product_id );
            update_field( 'olx_olx_data', json_encode( $response_data ), $product_id );

            $success = true;
        }

        return array(
            'success' => $success,
            'data' => $response->getBody()
        );

    }

    /**
     * Method that gets advert from OLX by product/post id
     *
     * @since 1.0.0
     *
     * @param int $post_id Post ID
     *
     * @return array $advert OLX Advert
     */
    public function get_advert_data_by_post_id( $post_id ) {

        if ( empty ( get_field( 'olx_id', $post_id ) ) ) {
            return false;
        } else {
            $olx_id = get_field( 'olx_id', $post_id );
        }

        // authorize
        if ( Olx::has_tokens() ) {

            $transient = "olx_api/partner/advert/$olx_id";
            $endpoint = "https://www.olx.pl/api/partner/adverts/$olx_id";

            if ( Olx_Cache::get( $transient ) ) {
                return Olx_Cache::get( $transient );
            }

            // new Guzzle client
            $client = new Client();

            try {
                // request for getting tokens
                $response = $client->request(
                    'GET',
                    $endpoint,
                    array(
                        'headers' => array(
                            'Authorization' => "Bearer $this->access_token",
                            'Version' => '2.0'
                        )
                    )
                );
            } catch ( RequestException $e ) {

                if ( is_admin() ) {

                    echo Psr7\str( $e->getRequest() );

                    if ( $e->hasResponse() ) {

                        echo Psr7\str( $e->getResponse() );

                    }

                }

            }

            // response
            $response_body = $response->getBody();
            $advert = json_decode( $response->getBody() )->data;

            // save to cache
            Olx_Cache::set( $transient, $advert );

            // save custom meta
            $olx_id = $advert->id;
            $olx_created_at = $advert->created_at;
            $olx_valid_to = $advert->valid_to;
            update_field( 'olx_id', $olx_id, $post_id );
            update_field( 'olx_created_at', $olx_created_at, $post_id );
            update_field( 'olx_valid_to', $olx_valid_to, $post_id );
            update_field( 'olx_olx_data', json_encode( $advert ), $post_id );
            update_field( 'olx_last_fetched', date( 'Y/m/d H:i:s' ), $post_id );

            return $advert;

        } else {

            return false;

        }

    }

    /**
     * Method that gets advert from database by product id
     *
     * @since 1.0.0
     *
     * @param int $post_id Post ID
     *
     * @return array $advert OLX Advert
     */
    public function get_advert_by_post_id( $post_id ) {

        if ( empty ( get_field( 'olx_id', $post_id ) ) ) {
            return false;
        } else {
            $olx_id = get_field( 'olx_id', $post_id );
        }

        // get data from db
        $advert = json_decode( get_field( 'olx_olx_data', $post_id ) );

        return $advert;

    }

    /**
     * Method that refreshes the products data from OLX
     *
     * @since 1.0.0
     *
     * @return void
     *
     * Hooked to 'wp_olx_cron_hook' action
     */
    public function refresh_adverts_data() {

        $adverts = get_posts( array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ) );

        if ( $adverts ) {
            foreach ( $adverts as $advert_id ) {
                $this->get_advert_data_by_post_id( $advert_id );
            }
        }

    }

}
