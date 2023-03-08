<?php

declare(strict_types=1);

namespace App\Middlewares\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ErrorResponseGeneratorInterface $responseGenerator)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Exception $exception) {
            return $this->responseGenerator->generate($exception, $request);
        }
    }
}
