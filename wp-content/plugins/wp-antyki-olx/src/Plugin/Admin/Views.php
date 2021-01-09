<?php

namespace Antyki\Plugin\Admin;

class Views
{
    public $twig;

    public function __construct($twigInstance)
    {
        $this->twig = $twigInstance;
    }

    public function authPage()
    {
        $this->twig->render('admin', [
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
    }
}
