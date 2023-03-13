<?php

declare(strict_types=1);

namespace Framework\Http\Logger;

use Psr\Http\Message\ServerRequestInterface;

interface LogErrorHandlerInterface
{
    public function handle(\Exception $exception, ServerRequestInterface $request);
}
