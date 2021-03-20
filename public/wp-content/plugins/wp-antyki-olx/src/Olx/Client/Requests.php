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
            error_log(json_encode([
                'Olx->Client->Requests->getUserData error' => $e->getMessage()
            ]));
            return $e->getMessage();
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
            $messages = [];
            foreach ($data as $message) {
                $messageId = $message->id;
                $advert = RequestHelper::getWpProductByAdvertId($message->advert_id);
                $advertTitle = get_field('olx_attributes_olx_title', $advert);
                $advertOlxData = json_decode(get_field('olx_olx_data', $advert));
                $advertUrl = $advertOlxData && $advertOlxData->url;
                $messages[] = [
                    'messageId' => $messageId,
                    'advertOlxData' => $advertOlxData,
                    'advertTitle' => $advertTitle,
                    'advertUrl' => $advertUrl,
                    'isUnread' => $message->unread_count > 0
                ];
            }
            return $messages;
        } catch (RequestException $e) {
            error_log(json_encode([
                'Olx->Client->Requests->getMessages error' => $e->getMessage()
            ]));
            return $e->getMessage();
        }
    }

    public function getPackets()
    {
        try {
            $response = $this->guzzleClient->request(
                'GET',
                '/api/partner/users/me/packets',
                [
                    'headers' => [
                        'Authorization' =>
                            'Bearer ' . $this->olx->auth->olxAccessToken,
                        'Version' => '2.0',
                    ],
                ]
            );
            $data = json_decode($response->getBody())->data;
            update_option('olxPackets', $data);
            return $data;
        } catch (RequestException $e) {
            error_log(json_encode([
                'Olx->Client->Requests->getPackets error' => $e->getMessage()
            ]));
            return $e->getMessage();
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
                    $assignedWpProduct = RequestHelper::getWpProductByAdvertId($advert->id);
                    if ($assignedWpProduct) {
                        $advert->wpProduct = $assignedWpProduct;
                    }
                    $adverts[] = $advert;
                }
            }
            return $adverts;
        } catch (RequestException $e) {
            error_log(json_encode([
                'Olx->Client->Requests->getAllAdverts error' => $e->getMessage()
            ]));
            return $e->getMessage();
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
                return strcmp($a->valid_to, $b->valid_to);
            });
            return array_slice($adverts, 0, 10);
        } catch (Exception $e) {
            error_log(json_encode([
                'Olx->Client->Requests->getEndingAdverts error' => $e->getMessage()
            ]));
            return $e->getMessage();
        }
    }

    public static function prepareAdvert($productId)
    {
        $olxMaxImages = 8;

        // get product data
        $productAttributes = get_field( 'product_attributes', $productId );
        $productOlxAttributes = get_field( 'olx_attributes', $productId );
        $productImages = (array) get_field( 'product_gallery', $productId );

        error_log(json_encode([
            '$productId' => $productId,
            '$productOlxAttributes' => $productOlxAttributes
        ]));

        $maxAllowedImages = array_slice($productImages, 0, $olxMaxImages);

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

            Oferujemy antyki i starocie takie jak meble, obrazy, ozdoby, artykuły mosiężne czy żyrandole.
            Nasze produkty pochodzą głównie z Niemiec oraz Belgii.

            Na sprzedaż:

            <?php echo isset( $productAttributes['desc'] ) ? ucfirst($productAttributes['desc']) : ''; ?>

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
            <?php
                // only if it's needed
                echo isset( $productAttributes['canvas_type'] )
                    ? ($productAttributes['canvas_type'] !== 'nie dotyczy'
                        ? ('Rodzaj podobrazia: ' . $productAttributes['canvas_type']['label'] . '
                            ')
                        : '')
                    : '';
            ?>
            <?php
                // only if it's needed
                echo isset( $productAttributes['paint_type'] )
                    ? ($productAttributes['paint_type'] !== 'none'
                        ? ('Rodzaj wykończenia: ' . $productAttributes['paint_type']['label'] . '
                            ')
                        : '')
                    : '';
            ?>
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
        if ($maxAllowedImages) {
            foreach ( $maxAllowedImages as $image ) {
                if (is_numeric($image)) {
                    $image = wp_get_attachment_url($image);
                } else {
                    $image = $image['url'];
                }
                $images[] = array(
                    'url' => str_replace(
                        'antyki.sors.smarthost.pl.devlocal',
                        'antyki.sors.smarthost.pl',
                        str_replace(
                            'antyki-stage.sors.smarthost.pl',
                            'antyki.sors.smarthost.pl',
                            $image
                        )
                    )
                );
            }
        }

        // product params
        $params = array(
            'title' => $productOlxAttributes['olx_title'],
            'description' => str_replace( '↵', '', $desc ),
            'category_id' => $productOlxAttributes['cat'],
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
            return [
                'success' => true,
                'message' => ''
            ];
        } catch (RequestException $e) {
            error_log(json_encode([
                'Olx->Client->Requests->addAdvert error' => $e->getResponse()->getBody()->getContents()
            ]));
            return $e->getResponse()->getBody()->getContents();
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
            error_log(json_encode([
                'Olx->Client->Requests->updateAdvert error' => $e->getResponse()->getBody()->getContents()
            ]));
            return $e->getResponse()->getBody()->getContents();
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
                        'is_success' => true
                    ])
                ]
            );
            $data = json_decode($response->getBody());
            return $data;
        } catch (RequestException $e) {
            error_log(json_encode([
                'Olx->Client->Requests->unpublishAdvert error' => $e->getResponse()->getBody()->getContents()
            ]));
            return $e->getResponse()->getBody()->getContents();
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
                return $data;
            }
        } catch (RequestException $e) {
            error_log(json_encode([
                'Olx->Client->Requests->getAdvertStats error' => $e->getResponse()->getBody()->getContents()
            ]));
            return $e->getResponse()->getBody()->getContents();
        }
    }

    public function renewAdvert($productId)
    {
        try {
            $advertId = self::getAdvertId($productId);

            if ($advertId) {
                $response = $this->guzzleClient->request(
                    'POST',
                    "/api/partner/adverts/$advertId/commands",
                    [
                        'headers' => [
                            'Authorization' =>
                                'Bearer ' . $this->olx->auth->olxAccessToken,
                            'Version' => '2.0',
                            'Content-Type' => 'application/json',
                        ],
                        'body' => json_encode([
                            'command' => 'activate',
                            'is_success' => true
                        ])
                    ]
                );
                $data = json_decode($response->getBody());

                $this->pullAdvertDataFromOlx($productId);

                return $data;
            }
        } catch (RequestException $e) {
            error_log(json_encode([
                'Olx->Client->Requests->renewAdvert error' => $e->getResponse()->getBody()->getContents()
            ]));
        }
        return $e->getResponse()->getBody()->getContents();
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
        $soldStatusAdded = wp_update_post([
            'ID' => $productId,
            'post_status' => ANTYKI_CPT_CUSTOM_STATUS
        ]);

        $createdAt = update_field( 'olx_created_at', '', $productId );
        $validTo = update_field( 'olx_valid_to', '', $productId );
        $olxData = update_field( 'olx_olx_data', '', $productId );
        $olxStatus = update_field( 'olx_status', 'removed_by_user', $productId );

        return $soldStatusAdded && $createdAt && $validTo && $olxData && $olxStatus;
    }

    public function advertSold($productId)
    {
        $advertUnpublishedOlx = $this->unpublishAdvert($productId);
        $advertMarkedAsSoldWp = $this->markAdvertAsSold($productId);
        return [
            'advertUnpublishedOlx' => $advertUnpublishedOlx,
            'advertMarkedAsSoldWp' => $advertMarkedAsSoldWp
        ];
    }

    public function cleanupAdvert($productId)
    {
        error_log('cleanupAdverts WP ID: ' . $productId);
        update_field( 'olx_created_at', '', $productId );
        update_field( 'olx_valid_to', '', $productId );
        update_field( 'olx_olx_data', '', $productId );
        update_field( 'olx_status', '', $productId );
    }

    public function getParentCatId($catId)
    {
        try {
            $response = $this->guzzleClient->request(
                'GET',
                "/api/partner/categories/$catId",
                [
                    'headers' => [
                        'Authorization' =>
                            'Bearer ' . $this->olx->auth->olxAccessToken,
                        'Version' => '2.0',
                        'Content-Type' => 'application/json'
                    ]
                ]
            );
            $category = json_decode($response->getBody())->data;
            return $category->parent_id;
        } catch (RequestException $e) {
            error_log(json_encode([
                'Olx->Client->Requests->getAdvertStats error' => $e->getResponse()->getBody()->getContents()
            ]));
            return $e->getResponse()->getBody()->getContents();
        }
    }

    public function pullAdvertDataFromOlx($productId)
    {
        try {
            $advertId = self::getAdvertId($productId);

            $response = $this->guzzleClient->request(
                'GET',
                "/api/partner/adverts/$advertId",
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
            if ($data) {
                if (is_array($data)) {
                    $data = reset($data);
                }
                $olxId = $data->id;
                error_log('Pulling Advert data from OLX. WP ID: ' . $productId . ', OLX ID: ' . $olxId);
                $olxCreatedAt = $data->created_at;
                $olxValidTo = $data->valid_to;
                $olxStatus = $data->status;
                update_field( 'olx_created_at', $olxCreatedAt, $productId );
                update_field( 'olx_valid_to', $olxValidTo, $productId );
                update_field( 'olx_olx_data', json_encode( $data ), $productId );
                update_field( 'olx_status', $olxStatus, $productId );
            } else {
                error_log('Pulling Advert data from OLX. WP ID: ' . $productId . ', OLX ID: [NOT EXISTS]');
                update_field( 'olx_created_at', '', $productId );
                update_field( 'olx_valid_to', '', $productId );
                update_field( 'olx_olx_data', '', $productId );
                update_field( 'olx_status', 'removed_by_user', $productId );
            }
            return $data;
        } catch (RequestException $e) {
            error_log(json_encode([
                'Olx->Client->Requests->pullAdvertDataFromOlx error' => $e->getResponse()->getBody()->getContents()
            ]));
            return $e->getResponse()->getBody()->getContents();
        }
    }

}
