<?php

namespace Antyki\Plugin;

class Twig
{
    public $twig;
    public $loader;

    public function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader(ANTYKI_ROOT_DIR . 'templates');
        $twig = new \Twig\Environment($this->loader);

        $twig->addGlobal('wpData', [
            'textDomain' => TEXTDOMAIN,
            'mainAdminUrl' => admin_url('/admin.php?page=wp-antyki-olx'),
            'testonejeden' => 'testonedwa'
        ]);

        $twigTranslate = new \Twig\TwigFunction('__', function ($text) {
            return \translate($text, TEXTDOMAIN);
        });
        $twig->addFunction($twigTranslate);

        $twigDump = new \Twig\TwigFunction('dump', function ($var) {
            var_dump($var);
        });
        $twig->addFunction($twigDump);

        $twigSettingsErrors = new \Twig\TwigFunction('settingsErrors', function () {
            return \settings_errors();
        });
        $twig->addFunction($twigSettingsErrors);

        $twigSettingsFields = new \Twig\Twigfunction('settingsFields', function ($var) {
            return \settings_fields($var);
        });
        $twig->addFunction($twigSettingsFields);

        $twigSubmitButton = new \Twig\Twigfunction('submitButton', function () {
            return \submit_button();
        });
        $twig->addFunction($twigSubmitButton);

        $this->twig = $twig;
    }

    public function getRender($template, $data)
    {
        return $this->twig->render("$template.twig", $data);
    }

    public function render($template, $data)
    {
        echo $this->twig->render("$template.twig", $data);
    }
}
