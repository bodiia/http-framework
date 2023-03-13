<?php

declare(strict_types=1);

namespace App\Factories;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

final class LoggerFactory
{
    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        $logger = new Logger('application');
        $logger->pushHandler(
            new StreamHandler(
                __DIR__ . '/../var/app.log',
                $container->get('config')['debug'] ? Level::Debug : Level::Warning
            ),
        );
        return $logger;
    }
}