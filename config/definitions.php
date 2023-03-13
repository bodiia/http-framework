<?php

declare(strict_types=1);

use Framework\Http\Middleware\ErrorHandler\ErrorHandlerMiddleware;
use Framework\Http\Middleware\ErrorHandler\ErrorResponseGenerator\ErrorResponseGeneratorInterface;
use Framework\Http\Router\RouterInterface;
use Laminas\Diactoros\Response;
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
    RouterInterface::class => \App\Factories\RouterFactory::class,
    ErrorResponseGeneratorInterface::class => \App\Factories\ErrorResponseGeneratorFactory::class,
    ErrorHandlerMiddleware::class => \App\Factories\ErrorHandlerMiddlewareFactory::class,
    LoggerInterface::class => \App\Factories\LoggerFactory::class,
];
