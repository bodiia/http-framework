<?php

declare(strict_types=1);

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MiddlewareResolver
{
    public function resolve(mixed $handler): MiddlewareInterface|RequestHandlerInterface|callable
    {
        if (is_array($handler)) {
            $middlewares = new Pipeline();

            foreach ($handler as $middleware) {
                $middlewares->pipe($this->resolve($middleware));
            }

            return $middlewares;
        }

        if (is_string($handler)) {
            return function (ServerRequestInterface $request, callable $next) use ($handler) {
                return (new $handler)($request, $next);
            };
        }

        return $handler;
    }
}