<?php

declare(strict_types=1);

namespace Framework\App\Actions;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class NotFoundHandler
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse('<h1>Not Found</h1>', 404);
    }
}