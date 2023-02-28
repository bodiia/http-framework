<?php

declare(strict_types=1);

use Aura\Router\Map;
use App\Actions\CatalogHandler;
use App\Actions\HomeHandler;

/** @var Map $routes */
$routes->get('home.index', '/', HomeHandler::class);
$routes->get('catalog.index', '/catalog', CatalogHandler::class);
