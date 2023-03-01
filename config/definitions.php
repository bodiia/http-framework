<?php

declare(strict_types=1);

use App\Application;
use App\Actions\NotFoundHandler;
use Aura\Router\RouterContainer;
use Framework\Http\Container\Container;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapterInterface;
use Framework\Http\Router\RouterInterface;
use Framework\Http\Pipeline\Pipeline;
use Psr\Container\ContainerInterface;

return [
    Application::class => function (Container $container) {
        return new Application(
            new Pipeline(),
            $container->get(MiddlewareResolver::class),
            $container->get(NotFoundHandler::class)
        );
    },
    ContainerInterface::class => function (Container $container) {
        return $container;
    },
    RouterInterface::class => function (Container $container) {
        $aura = new RouterContainer();
        $routes = $aura->getMap();

        require_once __DIR__ . '/../config/routes.php';

        return new AuraRouterAdapterInterface($aura);
    }
];
