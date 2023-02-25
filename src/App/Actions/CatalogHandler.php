<?php

declare(strict_types=1);

namespace Framework\App\Actions;

use Framework\App\Middlewares\CredentialsMiddleware;
use Framework\App\Middlewares\CurrentTimeMiddleware;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class CatalogHandler
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(sprintf(
            'Catalog | %s | %s',
            $request->getAttribute(CredentialsMiddleware::ATTRIBUTE),
            $request->getAttribute(CurrentTimeMiddleware::ATTRIBUTE)
        ));
    }
}
