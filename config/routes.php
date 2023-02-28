<?php

declare(strict_types=1);

use Aura\Router\Map as Router;
use App\Actions\CatalogHandler;
use App\Actions\HomeHandler;

/** @var Router $routes */
$routes->get('home.index', '/', HomeHandler::class);
$routes->get('catalog.index', '/catalog', CatalogHandler::class);
