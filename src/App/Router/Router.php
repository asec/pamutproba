<?php declare(strict_types=1);

namespace PamutProba\App\Router;

use PamutProba\App\Router\RouteHandler\RouteHandler;
use PamutProba\Http\Method;
use PamutProba\Http\MimeType;

readonly class Router
{
    /**
     * @var array<string, RouteHandler>
     */
    protected array $handlers;

    /**
     * @param array<string, RouteHandler> $handlers
     * @throws \Exception
     */
    public function __construct(array $handlers)
    {
        $finalHandlers = [];
        foreach ($handlers as $key => $handler)
        {
            $finalHandlers[$key] = $handler;
        }
        $this->handlers = $finalHandlers;
    }

    public function get(string $key): RouteHandler
    {
        return $this->handlers[$key];
    }

    /**
     * @param MimeType[]|null $mimes
     * @param Method $method
     * @param string $endpoint
     * @return RouteHandler|null
     */
    public function selectRouteHandler(?array $mimes, Method $method, string $endpoint): RouteHandler|null
    {
        return $this->selectByMimeAndRoute($mimes, $method, $endpoint)
            ?? $this->selectByRoute($method, $endpoint)
            ?? $this->selectByMime($mimes)
            ?? $this->selectDefaultRouteHandler()
        ;
    }

    /**
     * @param MimeType[]|null $mimes
     * @param Method $method
     * @param string $endpoint
     * @return RouteHandler|null
     */
    protected function selectByMimeAndRoute(?array $mimes, Method $method, string $endpoint): RouteHandler|null
    {
        if ($mimes === null)
        {
            return null;
        }
        foreach ($this->handlers as $handler)
        {
            if ($handler->isFor($mimes) && $handler->has($method, $endpoint))
            {
                return $handler;
            }
        }

        return null;
    }

    protected function selectByRoute(Method $method, string $endpoint): RouteHandler|null
    {
        foreach ($this->handlers as $handler)
        {
            if ($handler->has($method, $endpoint))
            {
                return $handler;
            }
        }

        return null;
    }

    /**
     * @param MimeType[]|null $mimes
     * @return RouteHandler|null
     */
    protected function selectByMime(?array $mimes): RouteHandler|null
    {
        if ($mimes === null)
        {
            return null;
        }
        foreach ($this->handlers as $handler)
        {
            if ($handler->isFor($mimes))
            {
                return $handler;
            }
        }

        return null;
    }

    public function selectDefaultRouteHandler(): RouteHandler|null
    {
        if (count($this->handlers) === 0)
        {
            return null;
        }

        $availableKeys = array_keys($this->handlers);
        return $this->handlers[end($availableKeys)];
    }
}