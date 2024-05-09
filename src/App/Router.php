<?php

namespace PamutProba\App;

use PamutProba\App\View\View;
use PamutProba\Exception\HttpException;
use PamutProba\Http\Method;
use PamutProba\Http\Status;

class Router
{
    protected array $routes = [];

    /**
     * @param Method $method
     * @param string $endpoint
     * @param \Closure(): View $action
     * @throws \Exception
     */
    public function define(Method $method, string $endpoint, \Closure $action): void
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
    public function execute(Method $method, string $endpoint): View
    {
        if (!$this->has($method, $endpoint))
        {
            throw new HttpException("Undefined route [{$method->name} {$endpoint}]", Status::NotFound);
        }

        $view = $this->routes[$method->value][$endpoint]();
        if (!($view instanceof View))
        {
            throw new \Exception("The endpoint gave an invalid response: [{$method->value} {$endpoint}]");
        }

        return $view;
    }

    public function has(Method $method, string $endpoint): bool
    {
        return isset($this->routes[$method->value][$endpoint]);
    }
}