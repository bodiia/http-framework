<?php

declare(strict_types=1);

namespace Framework\Http\Container;

use Psr\Container\NotFoundExceptionInterface;

final class ServiceNotFoundException extends \Exception implements NotFoundExceptionInterface
{
}
