<?php

declare(strict_types=1);

namespace App;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Pipeline\Pipeline;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Application
{
    private RequestHandlerInterface $handler;

    public function __construct(
        private readonly Pipeline $pipeline,
        private readonly MiddlewareResolver $resolver,
    ) {
    }

    public function setDefaultHandler(RequestHandlerInterface $handler): void
    {
        $this->handler = $handler;
    }

    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        return $this->pipeline->process($request, $this->handler);
    }

    public function pipe(mixed $handler): self
    {
        $this->pipeline->pipe($this->resolver->resolve($handler));

        return $this;
    }
}
