<?php

declare(strict_types=1);

namespace Framework\Container;

use Psr\Container\ContainerInterface;

final class Container implements ContainerInterface
{
    private array $resolves = [];

    public function __construct(private array $definitions = [])
    {
    }

    private function resolve(string $id): object
    {
        $reflection = new \ReflectionClass($id);

        if (! $reflectionConstructor = $reflection->getConstructor()) {
            return new $id();
        }

        $args = [];
        foreach ($reflectionConstructor->getParameters() as $parameter) {
            if ($parameter->hasType() && ! $parameter->getType()->isBuiltin()) {
                $args[$parameter->getName()] = $this->get($parameter->getType()->getName());
            } elseif ($parameter->isDefaultValueAvailable()) {
                $args[$parameter->getName()] = $parameter->getDefaultValue();
            } else {
                throw new ContainerException(sprintf(
                    "The class \"%s %s\" cannot be resolved",
                    $parameter->getType()->getName(),
                    $parameter->getName()
                ));
            }
        }
        return $reflection->newInstanceArgs($args);
    }

    /**
     * @throws ServiceNotFoundException|ContainerException
     */
    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->resolves)) {
            return $this->resolves[$id];
        }

        if (! array_key_exists($id, $this->definitions)) {
            if (class_exists($id)) {
                return $this->resolve($id);
            }
            throw new ServiceNotFoundException("Service with id: \"$id\" not found");
        }

        $definition = $this->definitions[$id];

        $this->resolves[$id] = $definition instanceof \Closure
            ? $definition($this)
            : $definition;

        return $this->resolves[$id];
    }

    public function set(string $id, mixed $value): void
    {
        if (array_key_exists($id, $this->resolves)) {
            unset($this->resolves[$id]);
        }
        $this->definitions[$id] = $value;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->definitions) || class_exists($id);
    }
}
