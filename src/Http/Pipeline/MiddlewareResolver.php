<?php

declare(strict_types=1);

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ServerRequestInterface;

final class MiddlewareResolver
{
    public function resolve(mixed $handler): callable
    {
        if (is_array($handler)) {
            return $this->createPipe($handler);
        }

        if (is_string($handler)) {
            return function (ServerRequestInterface $request, callable $next) use ($handler) {
                return (new $handler)($request, $next);
            };
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