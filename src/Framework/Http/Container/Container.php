<?php

declare(strict_types=1);

namespace Framework\Http\Container;

final class Container
{
    private array $definitions = [];

    private array $resolves = [];

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->resolves)) {
            return $this->resolves[$id];
        }

        if (! array_key_exists($id, $this->definitions)) {
            if (class_exists($id)) {
                return new $id();
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
