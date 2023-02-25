<?php

declare(strict_types=1);

namespace Framework\App\Middlewares;

use Framework\Http\Middleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class CredentialsMiddleware
{
    public const ATTRIBUTE = 'X-Developer';

    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        return $next($request->withAttribute('X-Developer', 'Bodianskii'));
    }
}
