<?php

declare(strict_types=1);

use Aura\Router\RouterContainer;
use Framework\Http\Container\Container;
use Framework\Http\Router\AuraRouterAdapterInterface;
use Framework\Http\Router\RouterInterface;
use Psr\Container\ContainerInterface;

return [
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
