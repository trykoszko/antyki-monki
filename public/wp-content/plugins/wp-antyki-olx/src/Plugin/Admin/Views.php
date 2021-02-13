<?php

namespace Antyki\Plugin\Admin;

class Views
{
    public $twigInstance;
    public $twig;
    public $olxClient;
    public $isAuthenticated;

    public function __construct(
        \Antyki\Plugin\Twig $twig,
        \Antyki\Olx\Main $olxClient
    ) {
        $this->twig = $twig;
        $this->olxClient = $olxClient;
    }

    public function renderUnauthorized()
    {
        $this->twig->render('unauthorized', []);
    }

    public function dashboardPage()
    {

        if ($this->olxClient->auth->isAuthenticated) {
            $allPackets = $this->olxClient->requests->getPackets();
            $availablePackets = array_filter($allPackets, function ($packet) {
                return $packet->left > 0;
            });
            $this->twig->render('dashboard', [
                'endingAdverts' => $this->olxClient->requests->getEndingAdverts(),
                'packets' => $availablePackets,
                'messages' => $this->olxClient->requests->getMessages(),
                'userInfo' => $this->olxClient->requests->getUserData(),
            ]);
        } else {
            $this->renderUnauthorized();
        }
    }

    public function authPage()
    {
        $this->twig->render('auth', [
            'olxAuth' => [
                'olxCode' => $this->olxClient->getOption('olxCode'),
                'olxClientId' => $this->olxClient->getOption('olxClientId'),
                'olxClientSecret' => $this->olxClient->getOption(
                    'olxClientSecret'
                ),
                'olxState' => $this->olxClient->getOption('olxState'),
                'olxAccessToken' => $this->olxClient->getOption(
                    'olxAccessToken'
                ),
                'olxRefreshToken' => $this->olxClient->getOption(
                    'olxRefreshToken'
                ),
                'isAuthorized' =>
                    $this->olxClient->getOption('olxTokensValidUntil') >
                    date('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function settingsPage()
    {
        if ($this->olxClient->auth->isAuthenticated) {
            $this->twig->render('settings', []);
        } else {
            $this->renderUnauthorized();
        }
    }
}
