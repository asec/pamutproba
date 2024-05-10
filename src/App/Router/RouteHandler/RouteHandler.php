<?php

namespace PamutProba\App\Router\RouteHandler;

use PamutProba\App\View\View;
use PamutProba\Exception\HttpException;
use PamutProba\Http\Method;
use PamutProba\Http\MimeType;
use PamutProba\Http\Status;

abstract class RouteHandler
{
    protected array $routes = [];
    protected readonly MimeType $mime;

    public function __construct(MimeType $mime)
    {
        $this->mime = $mime;
    }

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
                "Invalid route definition [{$method->value} {$endpoint}]. This definition already exists in '" .
                static::class. "'"
            );
        }

        $this->routes[$method->value][$endpoint] = $action;
    }

    /**
     * @param MimeType[] $mimes
     * @return bool
     */
    public function isFor(array $mimes): bool
    {
        return in_array($this->mime, $mimes);
    }

    public function has(Method $method, string $endpoint): bool
    {
        return isset($this->routes[$method->value][$endpoint]);
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
            throw new \Exception(
                "The endpoint gave an invalid response: [{$method->value} {$endpoint}] in '" .
                static::class. "'"
            );
        }

        return $view;
    }

    public function setHeaders(): void
    {
        switch ($this->mime)
        {
            case MimeType::Json:
                header("Content-Type: " . MimeType::Json->value);
                break;
            default:
                header("Content-Type: " . MimeType::Html->value . "; charset=utf-8");
                break;
        }
    }

    public abstract function createErrorPage(\Exception $error): View;
}