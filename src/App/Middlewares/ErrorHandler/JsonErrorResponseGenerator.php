<?php

declare(strict_types=1);

namespace App\Middlewares\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class JsonErrorResponseGenerator implements ErrorResponseGeneratorInterface
{
    public function __construct(protected readonly ResponseInterface $response)
    {
    }

    public function generate(\Exception $exception, ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(Utils::getStatusCode($exception));

        $response->getBody()->write(json_encode([
            'message' => $exception->getMessage(),
        ]));

        return $response;
    }
}
