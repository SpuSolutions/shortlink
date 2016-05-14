<?php
// DIC configuration
$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

    return $view;
};

// Flash messages
$container['flash'] = function ($c) {
    return new Slim\Flash\Messages;
};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

$container[App\Action\HomeAction::class] = function ($c) {
    return new App\Action\HomeAction($c->get('view'), $c->get('logger'));
};

$container[App\Action\DetailAction::class] = function ($c) {
    return new App\Action\DetailAction($c->get('view'), $c->get('logger'));
};

$container[App\Action\HomeProcessAction::class] = function ($c) {
    return new App\Action\HomeProcessAction($c->get('view'), $c->get('logger'), $c->get('router'));
};

$container[App\Action\AboutAction::class] = function ($c) {
    return new App\Action\AboutAction($c->get('view'), $c->get('logger'));
};

$container['notFoundHandler'] = function ($c) {
    return new App\Action\NotFoundHandler($c->get('view'), '404.twig', function ($request, $response) use ($c) {
        return $c['response']->withStatus(404);
    });
};