<?php

declare(strict_types=1);

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Pipeline implements MiddlewareInterface
{
    /** @param MiddlewareInterface[] $middlewares */
    public function __construct(private array $middlewares = [])
    {
    }

    public function pipe(MiddlewareInterface $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (! $current = array_shift($this->middlewares)) {
            return $handler->handle($request);
        }

        $next = new class ($this->process(...), $handler) implements RequestHandlerInterface {
            public function __construct(
                private readonly \Closure $process,
                private readonly RequestHandlerInterface $handler
            ) {
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return ($this->process)($request, $this->handler);
            }
        };

        return $current->process($request, $next);
    }
}
