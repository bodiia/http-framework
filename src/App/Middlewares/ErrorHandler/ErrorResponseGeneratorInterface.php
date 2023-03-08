<?php

declare(strict_types=1);

namespace App\Middlewares\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ErrorResponseGeneratorInterface
{
    public function generate(\Exception $exception, ServerRequestInterface $request): ResponseInterface;
}
