<?php

namespace PamutProba\App;

use PamutProba\Exception\HttpException;
use PamutProba\Http\Method;
use PamutProba\Http\Status;

class Router
{
    protected array $routes = [];

    /**
     * @throws \Exception
     */
    public function define(Method $method, string $endpoint, callable $action): void
    {
        if (!array_key_exists($method->value, $this->routes))
        {
            $this->routes[$method->value] = [];
        }

        if ($this->has($method, $endpoint))
        {
            throw new \Exception(
                "Invalid route definition [{$method->value} {$endpoint}]. This definition already exists"
            );
        }

        $this->routes[$method->value][$endpoint] = $action;
    }

    /**
     * @throws \Exception
     */
    public function execute(Method $method, string $endpoint): void
    {
        if (!$this->has($method, $endpoint))
        {
            throw new HttpException("Undefined route [{$method->name} {$endpoint}]", Status::NotFound);
        }

        $this->routes[$method->value][$endpoint]();
    }

    public function has(Method $method, string $endpoint): bool
    {
        return isset($this->routes[$method->value][$endpoint]);
    }
}