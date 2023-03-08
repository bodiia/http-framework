<?php

declare(strict_types=1);

namespace App\Middlewares\ErrorHandler;

final class Utils
{
    public static function getStatusCode(\Exception $exception): int
    {
        if ($exception->getCode() >= 400 && $exception->getCode() < 600) {
            return $exception->getCode();
        }
        return 500;
    }
}
