<?php

declare(strict_types=1);

namespace App\Factories;

use Aura\Router\RouterContainer;
use Framework\Http\Router\AuraRouterAdapterInterface;
use Framework\Http\Router\RouterInterface;
use Psr\Container\ContainerInterface;

final class RouterFactory
{
    public function __invoke(ContainerInterface $container): RouterInterface
    {
        $aura = new RouterContainer();
        $routes = $aura->getMap();

        require_once __DIR__ . '/../../../config/routes.php';

        return new AuraRouterAdapterInterface($aura);
    }
}
