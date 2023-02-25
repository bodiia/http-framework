<?php

declare(strict_types=1);

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Pipeline
{
    public function __construct(private array $middlewares = [])
    {
    }

    public function __invoke(ServerRequestInterface $request, callable $handler): ResponseInterface
    {
        if (! $current = array_shift($this->middlewares)) {
            return $handler($request);
        }

        return $current($request, function (ServerRequestInterface $request) use ($handler) {
            return $this($request, $handler);
        });
    }

    public function pipe($middleware): void
    {
        $this->middlewares[] = $middleware;
    }
}