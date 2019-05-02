<?php

$container = $app->getContainer();

// Twig template engine
$container['twig'] = function ($c) {
    $twig = new \Slim\Views\Twig($c->get('settings')['twig']['tpl_path'], [
        'cache' => $c->get('settings')['mode'] == 'dev' ? false : $c->get('settings')['twig']['cache_path'],
        'debug' => $c->get('settings')['mode'] == 'dev' ? true : false,
    ]);

    $lang = $_GET['lang'];

    // First param is the "default language" to use.
    $translator = new Symfony\Component\Translation\Translator($lang, null);
    // Set a fallback language incase you don't have a translation in the default language
    $translator->setFallbackLocales(['en_US']);
    // Add a loader that will get the php files we are going to store our translations in
    $translator->addLoader('php', new Symfony\Component\Translation\Loader\PhpFileLoader());

    // Add language files here
    $translator->addResource('php', $c->get('settings')['lang_path'].'/en_US.php', 'en_US'); // English
    $translator->addResource('php', $c->get('settings')['lang_path'].'/fr_FR.php', 'fr_FR'); // FR

    // Instantiate and add Slim specific extension
    $router = $c->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $twig->addExtension(new \Slim\Views\TwigExtension($router, $uri));
    $twig->addExtension(new \Twig\Extension\DebugExtension());
    $twig->addExtension(new \Symfony\Bridge\Twig\Extension\TranslationExtension($translator));

    return $twig;
};

$container['eloquent'] = function($c) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($c->get('settings')['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

$container['monolog'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new \Monolog\Logger($settings['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
};
