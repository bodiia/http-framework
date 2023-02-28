<?php

use Framework\App\Actions\NotFoundHandler;
use Framework\App\Application;
use Framework\App\Middlewares\CredentialsMiddleware;
use Framework\App\Middlewares\CurrentTimeMiddleware;
use Framework\App\Middlewares\ErrorHandlerMiddleware;
use Framework\Http\Container\Container;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var Container $container */
$container = require_once __DIR__ . '/../config/definitions.php';

$application = $container->get(Application::class);

$application
    ->pipe(ErrorHandlerMiddleware::class)
    ->pipe(CredentialsMiddleware::class)
    ->pipe(CurrentTimeMiddleware::class)
    ->pipe(RouteMiddleware::class)
    ->pipe(DispatchMiddleware::class);

$response = $application->handleRequest(
    ServerRequestFactory::fromGlobals(),
    $container->get(NotFoundHandler::class)
);

$emitter = new SapiEmitter();
$emitter->emit($response);
