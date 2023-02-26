<?php

declare(strict_types=1);

namespace Framework\App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CredentialsMiddleware implements MiddlewareInterface
{
    public const ATTRIBUTE = 'X-Developer';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request->withAttribute('X-Developer', 'Bodianskii'));
    }
}
