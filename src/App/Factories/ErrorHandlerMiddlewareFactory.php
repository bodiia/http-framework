<?php

declare(strict_types=1);

namespace App\Factories;

use Framework\Http\Logger\LogErrorHandler;
use Framework\Http\Middleware\ErrorHandler\ErrorHandlerMiddleware;
use Framework\Http\Middleware\ErrorHandler\ErrorResponseGenerator\ErrorResponseGeneratorInterface;
use Psr\Container\ContainerInterface;

final class ErrorHandlerMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): ErrorHandlerMiddleware
    {
        $handler = new ErrorHandlerMiddleware($container->get(ErrorResponseGeneratorInterface::class));
        $handler->pushHandler($container->get(LogErrorHandler::class));
        return $handler;
    }
}