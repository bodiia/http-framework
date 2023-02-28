<?php

declare(strict_types=1);

use Aura\Router\RouterContainer;
use App\Application;
use Framework\Http\Container\Container;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Router;
use Framework\Http\Pipeline\Pipeline;

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

    require_once __DIR__ . '/../config/routes.php';

    return new AuraRouterAdapter($aura);
});

return $container;
