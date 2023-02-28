<?php

declare(strict_types=1);

namespace App\Middlewares;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
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
