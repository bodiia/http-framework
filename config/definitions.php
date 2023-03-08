<?php

declare(strict_types=1);

use App\Middlewares\ErrorHandler\DebugErrorResponseGenerator;
use App\Middlewares\ErrorHandler\ErrorResponseGeneratorInterface;
use App\Middlewares\ErrorHandler\JsonErrorResponseGenerator;
use Aura\Router\RouterContainer;
use Framework\Http\Router\AuraRouterAdapterInterface;
use Framework\Http\Router\RouterInterface;
use Laminas\Diactoros\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

return [
    ContainerInterface::class => function (ContainerInterface $container) {
        return $container;
    },
    ResponseInterface::class => function (ContainerInterface $container) {
        return new Response();
    },
    RouterInterface::class => function (ContainerInterface $container) {
        $aura = new RouterContainer();
        $routes = $aura->getMap();

        require_once __DIR__ . '/../config/routes.php';

        return new AuraRouterAdapterInterface($aura);
    },
    ErrorResponseGeneratorInterface::class => function (ContainerInterface $container) {
        return $container->get('config')['debug']
            ? $container->get(DebugErrorResponseGenerator::class)
            : $container->get(JsonErrorResponseGenerator::class);
    },
];
