<?php

declare(strict_types=1);

namespace Framework\App;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Pipeline\Pipeline;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Application
{
    public function __construct(
        private readonly MiddlewareResolver $resolver,
        private readonly Pipeline $pipeline = new Pipeline()
    ) {
    }

    public function handleRequest(ServerRequestInterface $request, RequestHandlerInterface $default): ResponseInterface
    {
        return $this->pipeline->process($request, $default);
    }

    public function pipe(mixed $handler): void
    {
        $this->pipeline->pipe($this->resolver->resolve($handler));
    }
}