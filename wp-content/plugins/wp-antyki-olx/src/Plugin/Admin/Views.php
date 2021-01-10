<?php

namespace Antyki\Plugin\Admin;

class Views
{
    public $twig;
    public $olxClient;
    public $isAuthenticated;

    public function __construct($twigInstance, $olxClient)
    {
        $this->twig = $twigInstance;
        $this->olxClient = $olxClient;

        $this->isAuthenticated = $this->olxClient->isAuthenticated();
    }

    public function renderUnauthorized()
    {
        $this->twig->render('unauthorized', []);
    }

    public function dashboardPage()
    {
        if ($this->isAuthenticated) {
            $this->twig->render('dashboard', [
                'endingAdverts' => [
                    [
                        'url' => '#',
                        'title' => 'adv1',
                        'productUrl' => '#',
                        'productTitle' => 'Sample title',
                        'validTo' => '12-01-2020'
                    ]
                ],
                'packets' => [
                    [
                        '' => ''
                    ]
                ],
                'messages' => [
                    [
                        '' => ''
                    ]
                ],
                'userInfo' => [
                    'name' => 'Michal',
                    'email' => 'email@email.co',
                    'last_login_at' => '12-10-2021'
                ]
            ]);
        } else {
            $this->renderUnauthorized();
        }
    }

    public function authPage()
    {
        $this->twig->render('auth', [
            'userInfo' => [
                'name' => 'Michal',
                'email' => 'email@email.co',
                'last_login_at' => '12-10-2021'
            ]
        ]);
    }

    public function settingsPage()
    {
        if ($this->isAuthenticated) {
            $this->twig->render('settings', [
                '_olx_client_id' => $this->olxClient->_olx_client_id,
                '_olx_client_secret' => $this->olxClient->_olx_client_secret,
                '_olx_state' => $this->olxClient->_olx_state,
                '_olx_access_token' => $this->olxClient->_olx_access_token,
                '_olx_refresh_token' => $this->olxClient->_olx_refresh_token
            ]);
        } else {
            $this->renderUnauthorized();
        }
    }
}
