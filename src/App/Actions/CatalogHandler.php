<?php

declare(strict_types=1);

namespace App\Actions;

use App\Middlewares\CredentialsMiddleware;
use App\Middlewares\CurrentTimeMiddleware;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CatalogHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(
            sprintf(
                'Catalog | %s | %s',
                $request->getAttribute(CredentialsMiddleware::ATTRIBUTE),
                $request->getAttribute(CurrentTimeMiddleware::ATTRIBUTE)
            )
        );
    }
}
