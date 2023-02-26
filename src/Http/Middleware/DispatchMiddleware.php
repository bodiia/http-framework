<?php

declare(strict_types=1);

namespace Framework\Http\Middleware;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\Result;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DispatchMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly MiddlewareResolver $resolver)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Result $route */
        if ($route = $request->getAttribute(Result::class)) {
            $middleware = $this->resolver->resolve($route->getHandler());
            return $middleware->process($request, $handler);
        }

        return $handler->handle($request);
    }
}
