<?php

use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\SerializerServiceProvider());
$app->register(new \Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../log/dev.log',
    'monolog.level' => Logger::INFO,
    'monolog.use_error_handler' => false
));
$app->register(new \Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => 'mysql',
        'user' => 'api',
        'password' => 'api',
        'dbname' => 'api',
        'charset' => 'UTF8',
    ),
));
$app->mount('/users', new Rest\Provider\UsersControllerProvider());

$app['users.repository'] = function () use ($app) {
    return new Rest\Repository\UserRepository($app['db']);
};

$app['api_user'] = function () {
    return 'username';
};
$app['api_pwd'] = function () {
    return 'password';
};

$app->before(function (Request $request) use ($app) {
    $user = $request->server->get('PHP_AUTH_USER');
    $pwd = $request->server->get('PHP_AUTH_PW');

    if ($app['api_user'] !== $user || $app['api_pwd'] !== $pwd) {
        return new Response('Unauthorized', 401);
    }
});

$app->view(function (array $controllerResult, Request $request) use ($app) {
    $format = $request->get('format');
    $format = $format ? $format : 'json';
    return new Response($app['serializer']->serialize($controllerResult, $format), 200, array(
        'Content-Type' => $request->getMimeType($format)
    ));
});

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    switch ($code) {
        case 400:
            $message = 'Bad request.';
            break;
        case 404:
            $message = 'Not found.';
            break;
        case 415:
            $message = 'Malformed data';
            break;
        case 416:
            $message = 'Duplicated email';
            break;
        default:
            $message = 'Internal Server Error';
    }
    return new Response($message, $code);
});

return $app;
