<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exceptions\RouteNotFoundException;
use Framework\Http\Router\Exceptions\RouteNotMatchedException;
use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    /**
     * @throws RouteNotMatchedException
     */
    public function match(ServerRequestInterface $request): Result;

    /**
     * @throws RouteNotFoundException
     */
    public function generate(string $name, array $attributes): string;
}
