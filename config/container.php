<?php

declare(strict_types=1);

use Framework\Http\Container\Container;

$container = new Container(require_once __DIR__ . '/definitions.php');

return $container;
