<?php

declare(strict_types=1);

namespace Framework\Http\Middleware\ErrorHandler;

use Framework\Http\Logger\LogErrorHandlerInterface;
use Framework\Http\Middleware\ErrorHandler\ErrorResponseGenerator\ErrorResponseGeneratorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ErrorHandlerMiddleware implements MiddlewareInterface
{
    /**
     * @var LogErrorHandlerInterface[] $handlers
     */
    private array $handlers = [];

    public function __construct(
        private readonly ErrorResponseGeneratorInterface $responseGenerator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Exception $exception) {
            foreach ($this->handlers as $handler) {
                $handler->handle($exception, $request);
            }
            return $this->responseGenerator->generate($exception, $request);
        }
    }

    public function pushHandler(LogErrorHandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }
}
