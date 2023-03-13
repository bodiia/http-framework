<?php

declare(strict_types=1);

namespace Framework\Http\Logger;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

final class LogErrorHandler implements LogErrorHandlerInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function handle(\Exception $exception, ServerRequestInterface $request)
    {
        $this->logger->error($exception->getMessage(), [
            'exception' => $exception,
            'request' => self::extractRequest($request),
        ]);
    }

    private function extractRequest(ServerRequestInterface $request): array
    {
        return [
            'method' => $request->getMethod(),
            'url' => $request->getUri(),
            'server' => $request->getServerParams(),
            'cookies' => $request->getCookieParams(),
            'body' => $request->getParsedBody(),
        ];
    }
}
