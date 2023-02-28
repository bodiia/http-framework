<?php

use Aura\Router\RouterContainer;
use Framework\App\Actions\CatalogHandler;
use Framework\App\Actions\HomeHandler;
use Framework\App\Actions\NotFoundHandler;
use Framework\App\Application;
use Framework\App\Middlewares\CredentialsMiddleware;
use Framework\App\Middlewares\CurrentTimeMiddleware;
use Framework\App\Middlewares\ErrorHandlerMiddleware;
use Framework\Http\Container\Container;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Pipeline\Pipeline;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Router;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new Container();
$container->set(Application::class, function (Container $container) {
    return new Application($container->get(MiddlewareResolver::class), new Pipeline());
});
$container->set(MiddlewareResolver::class, function (Container $container) {
    return new MiddlewareResolver($container);
});
$container->set(RouteMiddleware::class, function (Container $container) {
    return new RouteMiddleware($container->get(Router::class));
});
$container->set(DispatchMiddleware::class, function (Container $container) {
    return new DispatchMiddleware($container->get(MiddlewareResolver::class));
});
$container->set(Router::class, function (Container $container) {
    $aura = new RouterContainer();
    $routes = $aura->getMap();

    $routes->get('home.index', '/', HomeHandler::class);
    $routes->get('catalog.index', '/catalog', CatalogHandler::class);

    return new AuraRouterAdapter($aura);
});

$application = $container->get(Application::class);

$application
    ->pipe(ErrorHandlerMiddleware::class)
    ->pipe(CredentialsMiddleware::class)
    ->pipe(CurrentTimeMiddleware::class)
    ->pipe(RouteMiddleware::class)
    ->pipe(DispatchMiddleware::class);

$response = $application->handleRequest(ServerRequestFactory::fromGlobals(), new NotFoundHandler());

$emitter = new SapiEmitter();
$emitter->emit($response);
