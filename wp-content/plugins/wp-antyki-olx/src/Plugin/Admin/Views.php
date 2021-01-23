<?php

namespace Antyki\Plugin\Admin;

class Views
{
    public $twigInstance;
    public $twig;
    public $olxClient;
    public $isAuthenticated;

    public function __construct(\Antyki\Plugin\Twig $twigInstance, \Antyki\Olx\Main $olxClient)
    {
        $this->twig = $twigInstance;
        $this->olxClient = $olxClient;
    }

    public function renderUnauthorized()
    {
        $this->twig->render('unauthorized', []);
    }

    public function dashboardPage()
    {
        if ($this->olxClient->isAuthenticated()) {
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
        var_dump($this->olxClient->isAuthenticated());

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
        if ($this->olxClient->isAuthenticated()) {
            $data = [
                'olxAuth' => [
                    'olxClientId' => $this->olxClient->getOption('olxClientId'),
                    'olxClientSecret' => $this->olxClient->olxClientSecret,
                    'olxState' => $this->olxClient->olxState,
                    'olxAccessToken' => $this->olxClient->olxAccessToken,
                    'olxRefreshToken' => $this->olxClient->olxRefreshToken
                ]
            ];
            $this->twig->render('settings', $data);
        } else {
            $this->renderUnauthorized();
        }
    }
}
