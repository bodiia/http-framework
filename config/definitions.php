<?php

declare(strict_types=1);

use App\Middlewares\ErrorHandler\ErrorResponseGeneratorInterface;
use App\Middlewares\ErrorHandler\JsonErrorResponse;
use Aura\Router\RouterContainer;
use Framework\Http\Router\AuraRouterAdapterInterface;
use Framework\Http\Router\RouterInterface;
use Psr\Container\ContainerInterface;

return [
    ContainerInterface::class => function (ContainerInterface $container) {
        return $container;
    },
    RouterInterface::class => function (ContainerInterface $container) {
        $aura = new RouterContainer();
        $routes = $aura->getMap();

        require_once __DIR__ . '/../config/routes.php';

        return new AuraRouterAdapterInterface($aura);
    },
    ErrorResponseGeneratorInterface::class => function (ContainerInterface $container) {
        return new JsonErrorResponse($container->get('config')['debug']);
    },
];
