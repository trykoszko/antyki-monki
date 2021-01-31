<?php

namespace Antyki\Olx\Client;

use \Exception as Exception;

use GuzzleHttp\Exception\RequestException as RequestException;

use Antyki\Olx\Cache as Cache;
use Antyki\Olx\Main as Olx;
use Antyki\Olx\Client\RequestHelper as RequestHelper;

class Requests
{
    protected $guzzleClient;
    protected $cache;
    protected $olx;

    public function __construct(
        \GuzzleHttp\Client $guzzleClient,
        Cache $cache,
        Olx $olx
    ) {
        $this->guzzleClient = $guzzleClient;
        $this->cache = $cache;
        $this->olx = $olx;
    }

    public function getUserData()
    {
        try {
            $response = $this->guzzleClient->request(
                'GET',
                '/api/partner/users/me',
                [
                    'headers' => [
                        'Authorization' =>
                            'Bearer ' . $this->olx->auth->olxAccessToken,
                        'Version' => '2.0',
                    ],
                ]
            );
            $data = json_decode($response->getBody())->data;
            return $data;
        } catch (RequestException $e) {
            error_log('OLX Requests->getUserData error');
            error_log($e);
            return [
                'error' => $e,
            ];
        }
    }

    public function getMessages()
    {
        try {
            $response = $this->guzzleClient->request(
                'GET',
                '/api/partner/threads',
                [
                    'headers' => [
                        'Authorization' =>
                            'Bearer ' . $this->olx->auth->olxAccessToken,
                        'Version' => '2.0',
                    ],
                ]
            );
            $data = json_decode($response->getBody())->data;
            return $data;
        } catch (RequestException $e) {
            error_log('OLX Requests->getMessages error');
            error_log($e);
            return [
                'error' => $e,
            ];
        }
    }

    public function getPackets()
    {
        try {
            $response = $this->guzzleClient->request(
                'GET',
                '/api/partner/paid-features',
                [
                    'headers' => [
                        'Authorization' =>
                            'Bearer ' . $this->olx->auth->olxAccessToken,
                        'Version' => '2.0',
                    ],
                ]
            );
            $data = json_decode($response->getBody())->data;
            return $data;
        } catch (RequestException $e) {
            error_log('OLX Requests->getPackets error');
            error_log($e);
            return [
                'error' => $e,
            ];
        }
    }

    public function getAllAdverts()
    {
        try {
            $response = $this->guzzleClient->request(
                'GET',
                '/api/partner/adverts',
                [
                    'headers' => [
                        'Authorization' =>
                            'Bearer ' . $this->olx->auth->olxAccessToken,
                        'Version' => '2.0',
                    ],
                ]
            );
            $data = json_decode($response->getBody())->data;
            $adverts = [];
            if ($data) {
                foreach ($data as $advert) {
                    $assignedWpProduct = RequestHelper::getWpProductByAdvertId(
                        $advert['id']
                    );
                    if ($assignedWpProduct) {
                        $advert['wpProduct'] = $assignedWpProduct;
                    }
                    $adverts[] = &$advert;
                }
            }
            return $adverts;
        } catch (RequestException $e) {
            error_log('OLX Requests->getAllAdverts error');
            error_log($e);
            return [
                'error' => $e,
            ];
        }
    }

    public function getEndingAdverts()
    {
        try {
            $adverts = $this->getAllAdverts();
            if (count($adverts) == 0) {
                return false;
            }
            usort($adverts, function ($a, $b) {
                return strcmp($a['valid_to'], $b['valid_to']);
            });
            return array_slice($adverts, 0, 10);
        } catch (Exception $e) {
            error_log('OLX Requests->getEndingAdverts error');
            error_log($e);
            return [
                'error' => $e,
            ];
        }
    }

    public static function prepareAdvert($productId)
    {
        // get product data
        $productAttributes = get_field( 'product_attributes', $productId );
        $productOlxAttributes = get_field( 'olx_attributes', $productId );
        $productImages = get_field( 'product_gallery', $productId );

        // additional info field
        $additionalInfo = null;
        if ( isset( $productAttributes['additional'] ) ) {
            $addInfo = array();
            foreach ( $productAttributes['additional'] as $attr ) {
                $addInfo[] = $attr['label'];
            }
            $additionalInfo = implode( ', ', $addInfo );
        }

        // material
        $materialInfo = null;
        if ( isset( $productAttributes['material'] ) ) {
            $matInfo = array();
            foreach ( $productAttributes['material'] as $attr ) {
                $matInfo[] = gettype($attr) === 'string' ? $attr : $attr['label'];
            }
            $materialInfo = implode( ', ', $matInfo );
        }

        ob_start();
            ?>Dzień dobry.

            <?php echo isset( $productAttributes['desc'] ) ? $productAttributes['desc'] : ''; ?>

            <?php
                if (
                    isset($productAttributes['width']) ||
                    isset($productAttributes['height']) ||
                    isset($productAttributes['depth']) ||
                    $materialInfo ||
                    isset($productAttributes['canvas_type']) ||
                    isset($productAttributes['paint_type']) ||
                    isset($productAttributes['state']) ||
                    $additionalInfo
                ) {
                    ?>

                        Atrybuty przedmiotu:
                    <?php
                }
            ?>
            <?php echo isset( $productAttributes['width'] ) && $productAttributes['width'] != '' && $productAttributes['width'] != '0' ? 'Szerokość: ' . $productAttributes['width'] . 'cm
            ' : ''; ?>
            <?php echo isset( $productAttributes['height'] ) && $productAttributes['height'] != '' && $productAttributes['height'] != '0' ? 'Wysokość: ' . $productAttributes['height'] . 'cm
            ' : ''; ?>
            <?php echo isset( $productAttributes['depth'] ) && $productAttributes['depth'] != '' && $productAttributes['depth'] != '0' ? 'Głębokość: ' . $productAttributes['depth'] . 'cm
            ' : ''; ?>
            <?php echo $materialInfo ? 'Materiał wykonania: ' . $materialInfo . '
            ' : ''; ?>
            <?php echo isset( $productAttributes['canvas_type'] ) ? 'Rodzaj podobrazia: ' . $productAttributes['canvas_type']['label'] . '
            ' : ''; ?>
            <?php echo isset( $productAttributes['paint_type'] ) ? 'Rodzaj wykończenia: ' . $productAttributes['paint_type']['label'] . '
            ' : ''; ?>
            <?php echo isset( $productAttributes['state'] ) ? 'Stan: ' . $productAttributes['state']['label'] . '
            ' : ''; ?>
            <?php echo $additionalInfo ? 'Dodatkowe informacje: ' . $additionalInfo . '
            ' : ''; ?>
            Po więcej antyków i staroci zapraszamy na naszą stronę internetową: antyki-monki.pl
            <?php
        $desc = ob_get_contents();
        ob_end_clean();

        // images
        $images = array();
        foreach ( $productImages as $image ) {
            $images[] = array(
                'url' => str_replace(
                    'antyki.sors.smarthost.pl.devlocal',
                    'antyki.sors.smarthost.pl',
                    str_replace(
                        'antyki-stage.sors.smarthost.pl',
                        'antyki.sors.smarthost.pl',
                        $image['url']
                    )
                )
            );
        }

        // product params
        $params = array(
            'title' => $productOlxAttributes['olx_title'],
            'description' => str_replace( '↵', '', $desc ),
            'category_id' => $productOlxAttributes['cat']['value'],
            'advertiser_type' => 'business',
            'contact' => array(
                'name' => \get_field( 'olx_settings_person_name', 'option' ),
                'phone' => \get_field( 'olx_settings_person_phone', 'option' )
            ),
            'location' => array(
                'city_id' => \get_field( 'olx_settings_place', 'option' )['value']
            ),
            'images' => $images,
            'price' => array(
                'value' => $productOlxAttributes['price'],
                'currency' => 'PLN',
                'negotiable' => !empty( $productOlxAttributes['price_min'] ),
                'trade' => false
            ),
            'attributes' => array(
                array(
                    'code' => 'state',
                    'value' => $productOlxAttributes['state']
                )
            )
        );

        return $params;
    }

    public function addAdvert($productId)
    {
        try {
            $params = self::prepareAdvert($productId);

            $response = $this->guzzleClient->request(
                'POST',
                '/api/partner/adverts',
                [
                    'headers' => [
                        'Authorization' =>
                            'Bearer ' . $this->olx->auth->olxAccessToken,
                        'Version' => '2.0',
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode( $params )
                ]
            );
            $data = json_decode($response->getBody())->data;
            if ($data) {
                $olxId = $data->id;
                $olxCreatedAt = $data->created_at;
                $olxValidTo = $data->valid_to;
                update_field( 'olx_id', $olxId, $productId );
                update_field( 'olx_created_at', $olxCreatedAt, $productId );
                update_field( 'olx_valid_to', $olxValidTo, $productId );
                update_field( 'olx_olx_data', json_encode( $data ), $productId );
                update_field( 'olx_status', 'active', $productId );
            }
            return $data;
        } catch (RequestException $e) {
            error_log('OLX Requests->addAdvert error');
            error_log($e->getResponse()->getBody()->getContents());
            return [
                'error' => $e,
            ];
        }
    }

    public static function getAdvertId($productId)
    {
        return \get_field('olx_id', $productId);
    }

    public function updateAdvert($productId)
    {
        try {
            $params = self::prepareAdvert($productId);
            $advertId = self::getAdvertId($productId);

            $response = $this->guzzleClient->request(
                'PUT',
                "/api/partner/adverts/$advertId",
                [
                    'headers' => [
                        'Authorization' =>
                            'Bearer ' . $this->olx->auth->olxAccessToken,
                        'Version' => '2.0',
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode( $params )
                ]
            );
            $data = json_decode($response->getBody())->data;
            if ($data) {
                $olxId = $data->id;
                $olxCreatedAt = $data->created_at;
                $olxValidTo = $data->valid_to;
                update_field( 'olx_id', $olxId, $productId );
                update_field( 'olx_created_at', $olxCreatedAt, $productId );
                update_field( 'olx_valid_to', $olxValidTo, $productId );
                update_field( 'olx_olx_data', json_encode( $data ), $productId );
                update_field( 'olx_status', 'active', $productId );
            }
            return $data;
        } catch (RequestException $e) {
            error_log('OLX Requests->updateAdvert error');
            error_log($e->getResponse()->getBody()->getContents());
            return [
                'error' => $e,
            ];
        }
    }

    public function unpublishAdvert($productId)
    {
        try {
            $advertId = self::getAdvertId($productId);

            $response = $this->guzzleClient->request(
                'POST',
                "/api/partner/adverts/$advertId/commands",
                [
                    'headers' => [
                        'Authorization' =>
                            'Bearer ' . $this->olx->auth->olxAccessToken,
                        'Version' => '2.0',
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode([
                        'command' => 'deactivate',
                        'isSuccess' => true
                    ])
                ]
            );
            $data = json_decode($response->getBody())->data;
            $adverts = [];
            if ($data) {
                $olxId = $data->id;
                $olxCreatedAt = $data->created_at;
                $olxValidTo = $data->valid_to;
                update_field( 'olx_id', $olxId, $productId );
                update_field( 'olx_created_at', $olxCreatedAt, $productId );
                update_field( 'olx_valid_to', $olxValidTo, $productId );
                update_field( 'olx_olx_data', json_encode( $data ), $productId );
                update_field( 'olx_status', 'deactivated', $productId );
            }
            return $adverts;
        } catch (RequestException $e) {
            error_log('OLX Requests->updateAdvert error');
            error_log($e->getResponse()->getBody()->getContents());
            return [
                'error' => $e,
            ];
        }
    }

    public function refreshAdvertStats($product)
    {
        try {
            $advertId = self::getAdvertId($product);
            if ($advertId) {
                $response = $this->guzzleClient->request(
                    'GET',
                    "/api/partner/adverts/$advertId/statistics",
                    [
                        'headers' => [
                            'Authorization' =>
                                'Bearer ' . $this->olx->auth->olxAccessToken,
                            'Version' => '2.0',
                            'Content-Type' => 'application/json'
                        ]
                    ]
                );
                $data = json_decode($response->getBody())->data;
                update_field('olx_advert_stats', json_encode($data), $product);
                error_log("refreshAdvertStats id: $product->ID, success");
                return $data;
            } else {
                error_log("refreshAdvertStats id: $product->ID, no advert");
            }
        } catch (RequestException $e) {
            error_log('----------------------------------');
            error_log("refreshAdvertStats id: $product->ID, fail");
            error_log('----------------------------------');
            error_log('OLX Requests->getAdvertStats error');
            error_log($e->getResponse()->getBody()->getContents());
            return [
                'error' => $e,
            ];
        }
    }

    public function getAdvertStats($productId)
    {
        if (get_field('olx_advert_stats', $productId)) {
            return get_field('olx_advert_stats', $productId);
        }

        $stats = $this->refreshAdvertStats($productId);
        return $stats;
    }

    public function markAdvertAsSold($productId)
    {
        return wp_update_post([
            'ID' => $productId,
            'post_status' => ANTYKI_CPT_CUSTOM_STATUS
        ]);
    }

    public function advertSold($productId)
    {
        $advertUnpublishedOlx = $this->unpublishAdvert($productId);
        $advertMarkedAsSoldWp = $this->markAdvertAsSold($productId);
        if ($advertUnpublishedOlx && $advertMarkedAsSoldWp) {
            return true;
        }
    }

}
