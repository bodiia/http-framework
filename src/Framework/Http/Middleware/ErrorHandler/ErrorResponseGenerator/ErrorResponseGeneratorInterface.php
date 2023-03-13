<?php

declare(strict_types=1);

namespace Framework\Http\Middleware\ErrorHandler\ErrorResponseGenerator;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ErrorResponseGeneratorInterface
{
    public function generate(\Exception $exception, ServerRequestInterface $request): ResponseInterface;
}
