<?php

declare(strict_types=1);

namespace Framework\Http\Middleware;

use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exceptions\RouteNotMatchedException;
use Framework\Http\Router\Result;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class RouteMiddleware
{
    public function __construct(private readonly AuraRouterAdapter $router)
    {
    }

    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        try {
            $route = $this->router->match($request);
            foreach ($route->getAttributes() as $attribute => $value) {
                $request = $request->withAttribute($attribute, $value);
            }
            return $next($request->withAttribute(Result::class, $route));
        } catch (RouteNotMatchedException) {
            return $next($request);
        }
    }
}