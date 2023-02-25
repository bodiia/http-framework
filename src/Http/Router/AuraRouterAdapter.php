<?php

namespace Framework\Http\Router;

use Aura\Router\Exception\RouteNotFound;
use Aura\Router\RouterContainer;
use Framework\Http\Router\Exceptions\RouteNotFoundException;
use Framework\Http\Router\Exceptions\RouteNotMatchedException;
use Psr\Http\Message\ServerRequestInterface;

class AuraRouterAdapter implements Router
{
    public function __construct(private readonly RouterContainer $router)
    {
    }

    public function match(ServerRequestInterface $request): Result
    {
        if (! $route = $this->router->getMatcher()->match($request)) {
            throw new RouteNotMatchedException();
        }

        return new Result($route->name, $route->handler, $route->attributes);
    }

    public function generate(string $name, array $attributes): string
    {
        try {
            return $this->router->getGenerator()->generate($name, $attributes);
        } catch (RouteNotFound $exception) {
            throw new RouteNotFoundException(previous: $exception);
        }
    }
}
