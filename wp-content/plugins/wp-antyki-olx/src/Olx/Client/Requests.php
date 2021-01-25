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
            if (!$response) {
                throw new RequestException('getUserData Error', $response);
            }
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
            if (!$response) {
                throw new RequestException('getMessages Error', $response);
            }
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
            if (!$response) {
                throw new RequestException('getPackets Error', $response);
            }
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
            if (!$response) {
                throw new RequestException('getAllAdverts Error', $response);
            }
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
}
