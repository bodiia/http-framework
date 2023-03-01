<?php

declare(strict_types=1);

namespace Framework\Http\Container;

use Psr\Container\ContainerExceptionInterface;

final class ContainerException extends \Exception implements ContainerExceptionInterface
{
}
