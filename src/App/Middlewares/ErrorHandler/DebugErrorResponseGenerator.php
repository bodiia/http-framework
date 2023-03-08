<?php

declare(strict_types=1);

namespace App\Middlewares\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class DebugErrorResponseGenerator implements ErrorResponseGeneratorInterface
{
    public function __construct(private readonly ResponseInterface $response)
    {
    }

    public function generate(\Exception $exception, ServerRequestInterface $request): ResponseInterface
    {
        $data = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'trace' => $exception->getTrace(),
        ];

        $response = $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(Utils::getStatusCode($exception));

        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
