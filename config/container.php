<?php

declare(strict_types=1);

use Framework\Container\Container;

$container = new Container(require_once __DIR__ . '/definitions.php');
$container->set('config', require_once __DIR__ . '/config.php');

return $container;
