<?php

declare(strict_types=1);

namespace Framework\Http\Pipeline;

use Framework\Http\Container\Container;
use Framework\Http\Middleware\LazyMiddlewareDecorator;
use Framework\Http\Middleware\RequestHandlerMiddleware;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MiddlewareResolver
{
    public function __construct(private readonly Container $container)
    {
    }

    public function resolve(mixed $handler): MiddlewareInterface
    {
        if (is_array($handler)) {
            return $this->buildPipeline($handler);
        }

        if (is_string($handler) && $this->container->has($handler)) {
            return new LazyMiddlewareDecorator($this, $this->container, $handler);
        }

        if ($handler instanceof MiddlewareInterface) {
            return $handler;
        }

        if ($handler instanceof RequestHandlerInterface) {
            return new RequestHandlerMiddleware($handler);
        }

        throw new UnknownMiddlewareTypeException();
    }

    public function buildPipeline(array $handlers): Pipeline
    {
        return array_reduce($handlers, function (Pipeline $pipeline, $handler) {
            $pipeline->pipe($this->resolve($handler));
        }, new Pipeline());
    }
}
