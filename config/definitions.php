<?php

declare(strict_types=1);

use Aura\Router\RouterContainer;
use Framework\Http\Logger\LogErrorHandler;
use Framework\Http\Middleware\ErrorHandler\ErrorHandlerMiddleware;
use Framework\Http\Middleware\ErrorHandler\ErrorResponseGenerator\DebugErrorResponseGenerator;
use Framework\Http\Middleware\ErrorHandler\ErrorResponseGenerator\ErrorResponseGeneratorInterface;
use Framework\Http\Middleware\ErrorHandler\ErrorResponseGenerator\JsonErrorResponseGenerator;
use Framework\Http\Router\AuraRouterAdapterInterface;
use Framework\Http\Router\RouterInterface;
use Laminas\Diactoros\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

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
    ErrorHandlerMiddleware::class => function (ContainerInterface $container) {
        $handler = new ErrorHandlerMiddleware($container->get(ErrorResponseGeneratorInterface::class));
        $handler->pushHandler($container->get(LogErrorHandler::class));
        return $handler;
    },
    LoggerInterface::class => function (ContainerInterface $container) {
        $logger = new Logger('application');
        $logger->pushHandler(
            new StreamHandler(
                __DIR__ . '/../var/app.log',
                $container->get('config')['debug'] ? Level::Debug : Level::Warning
            ),
        );
        return $logger;
    },
];
