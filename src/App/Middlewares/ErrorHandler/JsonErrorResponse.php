<?php

declare(strict_types=1);

namespace App\Middlewares\ErrorHandler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class JsonErrorResponse implements ErrorResponseGeneratorInterface
{
    public function __construct(private readonly bool $debug)
    {
    }

    public function generate(\Exception $exception, ServerRequestInterface $request): ResponseInterface
    {
        $responseData = [
            'status' => 'Server Error.',
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ];

        if ($this->debug) {
            $responseData['trace'] = $exception->getTrace();
        }

        return new JsonResponse($responseData, $this->getStatusCode($exception));
    }

    public function getStatusCode(\Exception $exception): int
    {
        if ($exception->getCode() >= 400 && $exception->getCode() < 600) {
            return $exception->getCode();
        }
        return 500;
    }
}
