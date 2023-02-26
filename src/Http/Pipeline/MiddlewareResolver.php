<?php

declare(strict_types=1);

namespace Framework\Http\Pipeline;

use Framework\Http\Middleware\LazyMiddlewareDecorator;
use Framework\Http\Middleware\RequestHandlerMiddleware;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MiddlewareResolver
{
    public function resolve(mixed $handler): MiddlewareInterface
    {
        if (is_array($handler)) {
            return $this->createPipe($handler);
        }

        if (is_string($handler)) {
            return new LazyMiddlewareDecorator($this, $handler);
        }

        if ($handler instanceof MiddlewareInterface) {
            return $handler;
        }

        if ($handler instanceof RequestHandlerInterface) {
            return new RequestHandlerMiddleware($handler);
        }

        return $handler;
    }

    public function createPipe(array $handlers): Pipeline
    {
        $pipeline = new Pipeline();
        foreach ($handlers as $handler) {
            $pipeline->pipe($this->resolve($handler));
        }
        return $pipeline;
    }
}
