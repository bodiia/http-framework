<?php

declare(strict_types=1);

namespace App\Factories;

use Framework\Http\Middleware\ErrorHandler\ErrorResponseGenerator\DebugErrorResponseGenerator;
use Framework\Http\Middleware\ErrorHandler\ErrorResponseGenerator\ErrorResponseGeneratorInterface;
use Framework\Http\Middleware\ErrorHandler\ErrorResponseGenerator\JsonErrorResponseGenerator;
use Psr\Container\ContainerInterface;

final class ErrorResponseGeneratorFactory
{
    public function __invoke(ContainerInterface $container): ErrorResponseGeneratorInterface
    {
        return $container->get('config')['debug']
            ? $container->get(DebugErrorResponseGenerator::class)
            : $container->get(JsonErrorResponseGenerator::class);
    }
}
