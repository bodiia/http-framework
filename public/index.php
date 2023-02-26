<?php

use Aura\Router\RouterContainer;
use Framework\App\Actions\CatalogHandler;
use Framework\App\Actions\HomeHandler;
use Framework\App\Actions\NotFoundHandler;
use Framework\App\Application;
use Framework\App\Middlewares\CredentialsMiddleware;
use Framework\App\Middlewares\CurrentTimeMiddleware;
use Framework\App\Middlewares\ErrorHandlerMiddleware;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Pipeline\Pipeline;
use Framework\Http\Router\AuraRouterAdapter;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

require_once __DIR__ . '/../vendor/autoload.php';

$aura = new RouterContainer();
$routes = $aura->getMap();

$routes->get('home.index', '/', HomeHandler::class);
$routes->get('catalog.index', '/catalog', CatalogHandler::class);

$router = new AuraRouterAdapter($aura);
$resolver = new MiddlewareResolver();
$application = new Application($resolver, new Pipeline());
$application->pipe(ErrorHandlerMiddleware::class);
$application->pipe(CredentialsMiddleware::class);
$application->pipe(CurrentTimeMiddleware::class);
$application->pipe(new RouteMiddleware($router));
$application->pipe(new DispatchMiddleware($resolver));

$response = $application->handleRequest(ServerRequestFactory::fromGlobals(), new NotFoundHandler());

$emitter = new SapiEmitter();
$emitter->emit($response);
