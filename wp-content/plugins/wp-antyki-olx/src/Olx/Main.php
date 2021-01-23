<?php

namespace Antyki\Olx;

use \Exception as Exception;

use \GuzzleHttp\Psr7 as Psr7;
use \GuzzleHttp\Exception\RequestException as RequestException;

class Main
{
    protected $guzzleClient;

    protected $isConfigured; // if client_id, client_secret, state is filled in
    public $isAuthenticated; // if OLX gives a response

    protected $olxClientId;
    protected $olxClientSecret;
    protected $olxState;
    protected $olxAccessToken;
    protected $olxRefreshToken;

    public function __construct(\GuzzleHttp\Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;

        $isConfigured = $this->configure();
        $isAuthenticated = false;
        if ($isConfigured) {
            $isAuthenticated = $this->authorize();
        }

        $this->isConfigured = $isConfigured;
        $this->isAuthenticated = $isAuthenticated;

        // try {
        //     $auth = $this->tryToAuthorize();
        //     if (!$auth) {
        //         throw new Exception('Authentication error');
        //     }
        // } catch (Exception $e) {
        //     error_log(json_encode([
        //         'error' => $e
        //     ]));
        // }

        // if (self::isAuthenticated()) {

        // get credentials from options
        // $this->getCredentials();

        // // new Guzzle client
        // $client = new Client();

        // try {

        //     $response = $client->request(
        //         'POST',
        //         'https://www.olx.pl/api/open/oauth/token',
        //         array(
        //             'headers' => array(
        //                 'Authorization' => "Bearer $this->access_token",
        //                 'Version' => '2.0'
        //             ),
        //             'form_params' => array(
        //                 'grant_type' => 'refresh_token',
        //                 'client_id' => $this->client_id,
        //                 'client_secret' => $this->client_secret,
        //                 'refresh_token' => $this->refresh_token
        //             )
        //         )
        //     );

        //     // request body
        //     $body = json_decode($response->getBody());

        //     $access_token = $body->access_token;
        //     $refresh_token = $body->refresh_token;

        //     // update WordPress options
        //     \update_option('olxAccessToken', $access_token);
        //     \update_option('olxRefreshToken', $refresh_token);

        //     // update options for last refresh
        //     \update_option('_olx_tokens_last_refresh', date('Y-m-d H:i:s'));
        // } catch (RequestException $e) {

        //     if (\is_admin()) {

        //         echo Psr7\str($e->getRequest());

        //         if ($e->hasResponse()) {

        //             echo Psr7\str($e->getResponse());
        //         }
        //     }

        //     // first time authorize if refreshing token failed
        //     self::firstTimeAuth();
        // }
        // } else {

        //     if (defined('ANTYKI_OLX_CLIENT_ID') && defined('ANTYKI_OLX_CLIENT_SECRET')) {
        //         self::firstTimeAuth();
        //     } else {
        //         echo 'Olx authorization error';
        //         wp_die();
        //     }
        // }
    }

    public function getOption($optionName)
    {
        error_log(json_encode([
            'getOption' => $optionName
        ]));

        if (defined($optionName)) {
            return constant($optionName);
        }

        if (\get_option($optionName)) {
            return \get_option($optionName);
        }

        return false;
    }

    protected function configure()
    {
        try {

            if (!$this->getOption('olxClientId')) {
                throw new Exception('olxClientId not defined');
            }
            if (!$this->getOption('olxClientSecret')) {
                throw new Exception('olxClientSecret not defined');
            }
            if (!$this->getOption('olxState')) {
                throw new Exception('olxState not defined');
            }
            if (!$this->getOption('olxAccessToken')) {
                throw new Exception('olxAccessToken not defined');
            }
            if (!$this->getOption('olxRefreshToken')) {
                throw new Exception('olxRefreshToken not defined');
            }
            if (!$this->getOption('olxCode')) {
                throw new Exception('olxCode not defined');
            }

            $this->olxClientId = $this->getOption('olxClientId');
            $this->olxClientSecret = $this->getOption('olxClientSecret');
            $this->olxState = $this->getOption('olxState');
            $this->olxAccessToken = $this->getOption('olxAccessToken');
            $this->olxRefreshToken = $this->getOption('olxRefreshToken');
            $this->olxCode = $this->getOption('olxCode');

            var_dump($this->olxCode);

            return true;
        } catch (Exception $e) {

            error_log('[ANTYKI-OLX]');
            error_log($e->getMessage());

            return false;
        }
    }

    public function authorize()
    {
        try {
            $response = $this->guzzleClient->request(
                'POST',
                '/open/oauth/token',
                array(
                    'form_params' => array(
                        'grant_type' => 'authorization_code',
                        'client_id' => $this->olxClientId,
                        'client_secret' => $this->olxClientSecret,
                        'scope' => 'v2 read write',
                        'code' => $this->olxCode
                    )
                )
            );

            // request body
            $body = json_decode($response->getBody());

            $accessToken = $body->access_token;
            $refreshToken = $body->refresh_token;

            $options_updated = \update_option('olxAccessToken', $accessToken) && \update_option('olxRefreshToken', $refreshToken);

            return $options_updated;
        } catch (RequestException $e) {
            return false;
        }
    }

    public function isAuthenticated()
    {
        return $this->isConfigured && $this->isAuthenticated;
    }












    public function get_adverts()
    {

        $data = false;

        // authorize
        if (self::isAuthenticated()) {

            $transient = 'olx_api/partner/adverts';
            $endpoint = 'https://www.olx.pl/api/partner/adverts';

            if (Cache::get($transient)) {
                return Cache::get($transient);
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
            $data = json_decode($response->getBody())->data;

            // save to cache
            Cache::set($transient, $data);
        }

        return $data;
    }

    public function getUserData()
    {

        $data = false;

        // authorize
        if (self::isAuthenticated()) {

            $transient = 'olx_api/partner/users/me';
            $endpoint = 'https://www.olx.pl/api/partner/users/me';

            if (Cache::get($transient)) {
                return Cache::get($transient);
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
            $data = json_decode($response->getBody())->data;

            // save to cache
            Cache::set($transient, $data);
        }

        return $data;
    }

    public function get_packets()
    {

        $data = false;

        // authorize
        if (self::isAuthenticated()) {

            $transient = 'olx_api/partner/packets';
            $endpoint = 'https://www.olx.pl/api/partner/users/me/packets';

            if (Cache::get($transient)) {
                return Cache::get($transient);
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

            $data = json_decode($response->getBody())->data;

            // save to cache
            Cache::set($transient, $data);
        }

        return $data;
    }

    public function get_messages()
    {

        $data = false;

        // authorize
        if (self::isAuthenticated()) {

            $transient = 'olx_api/partner/threads';
            $endpoint = 'https://www.olx.pl/api/partner/threads';

            if (Cache::get($transient)) {
                return Cache::get($transient);
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

            $data = json_decode($response->getBody())->data;

            // save to cache
            Cache::set($transient, $data);
        }

        return $data;
    }

    public function add_advert($product_id)
    {

        // set endpoint
        $endpoint = 'https://www.olx.pl/api/partner/adverts';

        // new Guzzle client
        $client = new Client();

        // get product data
        $product_attrs = get_field('product_attributes', $product_id);
        $product_olx_attrs = get_field('olx_attributes', $product_id);
        $product_images = get_field('product_gallery', $product_id);

        // additional info field
        $additional_info = null;
        if (isset($product_attrs['additional'])) {
            $add_info = array();
            foreach ($product_attrs['additional'] as $attr) {
                $add_info[] = $attr['label'];
            }
            $additional_info = implode(', ', $add_info);
        }

        // material
        $material_info = null;
        if (isset($product_attrs['material'])) {
            $mat_info = array();
            foreach ($product_attrs['material'] as $attr) {
                $mat_info[] = $attr['label'];
            }
            $material_info = implode(', ', $mat_info);
        }

        // description field
        $desc = 'Dzień dobry.

        ';
        $desc .= isset($product_attrs['desc']) ? $product_attrs['desc'] . '

        ' : '';
        $desc .= 'Atrybuty przedmiotu:
        ';
        $desc .= isset($product_attrs['width']) ? 'Szerokość: ' . $product_attrs['width'] . 'cm
        ' : '';
        $desc .= isset($product_attrs['height']) ? 'Wysokość: ' . $product_attrs['height'] . 'cm
        ' : '';
        $desc .= isset($product_attrs['depth']) ? 'Głębokość: ' . $product_attrs['depth'] . 'cm
        ' : '';
        $desc .= $material_info ? 'Materiał wykonania: ' . $material_info . '
        ' : '';
        $desc .= isset($product_attrs['canvas_type']) ? 'Rodzaj podobrazia: ' . $product_attrs['canvas_type']['label'] . '
        ' : '';
        $desc .= isset($product_attrs['paint_type']) ? 'Rodzaj wykończenia: ' . $product_attrs['paint_type']['label'] . '
        ' : '';
        $desc .= isset($product_attrs['state']) ? 'Stan: ' . $product_attrs['state']['label'] . '
        ' : '';
        $desc .= $additional_info ? 'Dodatkowe informacje: ' . $additional_info . '
        ' : '';
        $desc .= '

        ';
        $desc .= 'Po więcej antyków i staroci zapraszamy na naszą stronę internetową: antyki-monki.pl';

        // images
        $images = array();
        foreach ($product_images as $image) {
            $images[] = array(
                'url' => $image['url']
            );
        }

        // product params
        $params = array(
            'title' => $product_olx_attrs['olx_title'],
            'description' => str_replace('↵', '', $desc),
            'category_id' => $product_olx_attrs['cat']['value'],
            'advertiser_type' => 'business',
            'contact' => array(
                'name' => get_field('olx_settings_person_name', 'option'),
                'phone' => get_field('olx_settings_person_phone', 'option')
            ),
            'location' => array(
                'city_id' => get_field('olx_settings_place', 'option')['value']
            ),
            'images' => $images,
            'price' => array(
                'value' => $product_olx_attrs['price'],
                'currency' => 'PLN',
                'negotiable' => !empty($product_olx_attrs['price_min']),
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
                'body' => json_encode($params)
            )
        );

        // parse response
        $res = json_decode($response->getBody());
        $success = '';

        if (property_exists($res, 'error')) {
            $success = false;
        } else {
            // get data from response
            $response_data = $res->data;

            // update acf fields
            $olx_id = $response_data->id;
            $olx_created_at = $response_data->created_at;
            $olx_valid_to = $response_data->valid_to;
            update_field('olx_id', $olx_id, $product_id);
            update_field('olx_created_at', $olx_created_at, $product_id);
            update_field('olx_valid_to', $olx_valid_to, $product_id);
            update_field('olx_olx_data', json_encode($response_data), $product_id);

            $success = true;
        }

        return array(
            'success' => $success,
            'data' => $response->getBody()
        );
    }

    public function get_advert_data_by_post_id($post_id)
    {

        if (empty(get_field('olx_id', $post_id))) {
            return false;
        } else {
            $olx_id = get_field('olx_id', $post_id);
        }

        // authorize
        if (self::isAuthenticated()) {

            $transient = "olx_api/partner/advert/$olx_id";
            $endpoint = "https://www.olx.pl/api/partner/adverts/$olx_id";

            if (Cache::get($transient)) {
                return Cache::get($transient);
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
            } catch (RequestException $e) {

                if (is_admin()) {

                    echo Psr7\str($e->getRequest());

                    if ($e->hasResponse()) {

                        echo Psr7\str($e->getResponse());
                    }
                }
            }

            // response
            $response_body = $response->getBody();
            $advert = json_decode($response->getBody())->data;

            // save to cache
            Cache::set($transient, $advert);

            // save custom meta
            $olx_id = $advert->id;
            $olx_created_at = $advert->created_at;
            $olx_valid_to = $advert->valid_to;
            update_field('olx_id', $olx_id, $post_id);
            update_field('olx_created_at', $olx_created_at, $post_id);
            update_field('olx_valid_to', $olx_valid_to, $post_id);
            update_field('olx_olx_data', json_encode($advert), $post_id);
            update_field('olx_last_fetched', date('Y/m/d H:i:s'), $post_id);

            return $advert;
        } else {

            return false;
        }
    }

    public function get_advert_by_post_id($post_id)
    {

        if (empty(get_field('olx_id', $post_id))) {
            return false;
        } else {
            $olx_id = get_field('olx_id', $post_id);
        }

        // get data from db
        $advert = json_decode(get_field('olx_olx_data', $post_id));

        return $advert;
    }

    public function refresh_adverts_data()
    {

        $adverts = get_posts(array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ));

        if ($adverts) {
            foreach ($adverts as $advert_id) {
                $this->get_advert_data_by_post_id($advert_id);
            }
        }
    }
}
