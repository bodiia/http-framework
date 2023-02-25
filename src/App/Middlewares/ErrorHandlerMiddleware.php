<?php

declare(strict_types=1);

namespace Framework\App\Middlewares;

use Framework\Http\Middleware\MiddlewareInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ErrorHandlerMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        try {
            return $next($request);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => 'Server Error.',
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'trace' => $exception->getTrace(),
            ], 500);
        }
    }
}