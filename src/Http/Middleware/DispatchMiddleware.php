<?php

declare(strict_types=1);

namespace Framework\Http\Middleware;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\Result;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class DispatchMiddleware
{
    public function __construct(private readonly MiddlewareResolver $resolver)
    {
    }

    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        /** @var Result $route */
        if ($route = $request->getAttribute(Result::class)) {
            $middleware = $this->resolver->resolve($route->getHandler());
            return $middleware($request, $next);
        }

        return $next($request);
    }
}