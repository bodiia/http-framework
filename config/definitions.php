<?php

declare(strict_types=1);

use App\Application;
use App\Actions\NotFoundHandler;
use Aura\Router\RouterContainer;
use Framework\Http\Container\Container;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapterInterface;
use Framework\Http\Router\RouterInterface;
use Framework\Http\Pipeline\Pipeline;

return [
    Application::class => function (Container $container) {
        return new Application(
            new Pipeline(),
            $container->get(MiddlewareResolver::class),
            $container->get(NotFoundHandler::class)
        );
    },
    MiddlewareResolver::class => function (Container $container) {
        return new MiddlewareResolver($container);
    },
    RouteMiddleware::class => function (Container $container) {
        return new RouteMiddleware($container->get(RouterInterface::class));
    },
    DispatchMiddleware::class => function (Container $container) {
        return new DispatchMiddleware($container->get(MiddlewareResolver::class));
    },
    RouterInterface::class => function (Container $container) {
        $aura = new RouterContainer();
        $routes = $aura->getMap();

        require_once __DIR__ . '/../config/routes.php';

        return new AuraRouterAdapterInterface($aura);
    }
];
