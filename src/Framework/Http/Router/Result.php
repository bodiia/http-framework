<?php

namespace Framework\Http\Router;

class Result
{
    public function __construct(
        private readonly string $name,
        private readonly string|object|array $handler,
        private readonly array $attributes
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHandler(): string|object|array
    {
        return $this->handler;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
