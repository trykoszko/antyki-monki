<?php

namespace Antyki\Olx\Client;

use GuzzleHttp\Psr7 as Psr7;
use GuzzleHttp\Exception\RequestException as RequestException;

use Antyki\Olx\Cache as AntykiCache;

class Requests
{
    protected $guzzleClient;

    public function __construct(
        \GuzzleHttp\Client $guzzleClient,
        AntykiCache $cache
    ) {
        $this->guzzleClient = $guzzleClient;
        $this->cache = $cache;
    }

    public function get_adverts()
    {
        $data = false;

        // authorize
        if ($this->auth->isAuthenticated()) {
        }

        $transient = 'olx_api/partner/adverts';
        $endpoint = 'https://www.olx.pl/api/partner/adverts';

        if (AntykiCache::get($transient)) {
            return AntykiCache::get($transient);
        }

        // request for getting tokens
        $response = $this->guzzleClient->request('GET', $endpoint, [
            'headers' => [
                'Authorization' => "Bearer $this->access_token",
                'Version' => '2.0',
            ],
        ]);

        // response
        $data = json_decode($response->getBody())->data;

        // save to cache
        AntykiCache::set($transient, $data);

        return $data;
    }

    public function get_packets()
    {
        $data = false;

        // authorize
        if (self::isAuthenticated()) {
            $transient = 'olx_api/partner/packets';
            $endpoint = 'https://www.olx.pl/api/partner/users/me/packets';

            if (AntykiCache::get($transient)) {
                return AntykiCache::get($transient);
            }

            // new Guzzle client
            $client = new Client();

            // request
            $response = $this->guzzleClient->request('GET', $endpoint, [
                'headers' => [
                    'Authorization' => "Bearer $this->access_token",
                    'Version' => '2.0',
                ],
            ]);

            $data = json_decode($response->getBody())->data;

            // save to cache
            AntykiCache::set($transient, $data);
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

            if (AntykiCache::get($transient)) {
                return AntykiCache::get($transient);
            }

            // new Guzzle client
            $client = new Client();

            // request
            $response = $this->guzzleClient->request('GET', $endpoint, [
                'headers' => [
                    'Authorization' => "Bearer $this->access_token",
                    'Version' => '2.0',
                ],
            ]);

            $data = json_decode($response->getBody())->data;

            // save to cache
            AntykiCache::set($transient, $data);
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
            $add_info = [];
            foreach ($product_attrs['additional'] as $attr) {
                $add_info[] = $attr['label'];
            }
            $additional_info = implode(', ', $add_info);
        }

        // material
        $material_info = null;
        if (isset($product_attrs['material'])) {
            $mat_info = [];
            foreach ($product_attrs['material'] as $attr) {
                $mat_info[] = $attr['label'];
            }
            $material_info = implode(', ', $mat_info);
        }

        // description field
        $desc = 'Dzień dobry.

        ';
        $desc .= isset($product_attrs['desc'])
            ? $product_attrs['desc'] .
                '

        '
            : '';
        $desc .= 'Atrybuty przedmiotu:
        ';
        $desc .= isset($product_attrs['width'])
            ? 'Szerokość: ' .
                $product_attrs['width'] .
                'cm
        '
            : '';
        $desc .= isset($product_attrs['height'])
            ? 'Wysokość: ' .
                $product_attrs['height'] .
                'cm
        '
            : '';
        $desc .= isset($product_attrs['depth'])
            ? 'Głębokość: ' .
                $product_attrs['depth'] .
                'cm
        '
            : '';
        $desc .= $material_info
            ? 'Materiał wykonania: ' .
                $material_info .
                '
        '
            : '';
        $desc .= isset($product_attrs['canvas_type'])
            ? 'Rodzaj podobrazia: ' .
                $product_attrs['canvas_type']['label'] .
                '
        '
            : '';
        $desc .= isset($product_attrs['paint_type'])
            ? 'Rodzaj wykończenia: ' .
                $product_attrs['paint_type']['label'] .
                '
        '
            : '';
        $desc .= isset($product_attrs['state'])
            ? 'Stan: ' .
                $product_attrs['state']['label'] .
                '
        '
            : '';
        $desc .= $additional_info
            ? 'Dodatkowe informacje: ' .
                $additional_info .
                '
        '
            : '';
        $desc .= '

        ';
        $desc .=
            'Po więcej antyków i staroci zapraszamy na naszą stronę internetową: antyki-monki.pl';

        // images
        $images = [];
        foreach ($product_images as $image) {
            $images[] = [
                'url' => $image['url'],
            ];
        }

        // product params
        $params = [
            'title' => $product_olx_attrs['olx_title'],
            'description' => str_replace('↵', '', $desc),
            'category_id' => $product_olx_attrs['cat']['value'],
            'advertiser_type' => 'business',
            'contact' => [
                'name' => get_field('olx_settings_person_name', 'option'),
                'phone' => get_field('olx_settings_person_phone', 'option'),
            ],
            'location' => [
                'city_id' => get_field('olx_settings_place', 'option')['value'],
            ],
            'images' => $images,
            'price' => [
                'value' => $product_olx_attrs['price'],
                'currency' => 'PLN',
                'negotiable' => !empty($product_olx_attrs['price_min']),
                'trade' => false,
            ],
            'attributes' => [
                [
                    'code' => 'state',
                    'value' => $product_olx_attrs['state'],
                ],
            ],
        ];

        // request
        $response = $this->guzzleClient->request('POST', $endpoint, [
            'headers' => [
                'Authorization' => "Bearer $this->access_token",
                'Version' => '2.0',
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($params),
        ]);

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
            update_field(
                'olx_olx_data',
                json_encode($response_data),
                $product_id
            );

            $success = true;
        }

        return [
            'success' => $success,
            'data' => $response->getBody(),
        ];
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

            if (AntykiCache::get($transient)) {
                return AntykiCache::get($transient);
            }

            // new Guzzle client
            $client = new Client();

            try {
                // request for getting tokens
                $response = $this->guzzleClient->request('GET', $endpoint, [
                    'headers' => [
                        'Authorization' => "Bearer $this->access_token",
                        'Version' => '2.0',
                    ],
                ]);
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
            AntykiCache::set($transient, $advert);

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
        $adverts = get_posts([
            'post_type' => 'product',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ]);

        if ($adverts) {
            foreach ($adverts as $advert_id) {
                $this->get_advert_data_by_post_id($advert_id);
            }
        }
    }
}
