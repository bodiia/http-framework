<?php

use App\Actions\NotFoundHandler;
use App\Application;
use App\Middlewares\CredentialsMiddleware;
use App\Middlewares\CurrentTimeMiddleware;
use App\Middlewares\ErrorHandler\ErrorHandlerMiddleware;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Container\ContainerInterface;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var ContainerInterface $container */
$container = require_once __DIR__ . '/../config/container.php';

/** @var Application $application */
$application = $container->get(Application::class);
$application->setDefaultHandler(NotFoundHandler::class);
$application
    ->pipe(ErrorHandlerMiddleware::class)
    ->pipe(CredentialsMiddleware::class)
    ->pipe(CurrentTimeMiddleware::class)
    ->pipe(RouteMiddleware::class)
    ->pipe(DispatchMiddleware::class);

$response = $application->handleRequest(ServerRequestFactory::fromGlobals());

$emitter = new SapiEmitter();
$emitter->emit($response);
