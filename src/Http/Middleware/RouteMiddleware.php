<?php

declare(strict_types=1);

namespace Framework\Http\Middleware;

use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exceptions\RouteNotMatchedException;
use Framework\Http\Router\Result;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RouteMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly AuraRouterAdapter $router)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $route = $this->router->match($request);
            foreach ($route->getAttributes() as $attribute => $value) {
                $request = $request->withAttribute($attribute, $value);
            }
            return $handler->handle($request->withAttribute(Result::class, $route));
        } catch (RouteNotMatchedException) {
            return $handler->handle($request);
        }
    }
}
