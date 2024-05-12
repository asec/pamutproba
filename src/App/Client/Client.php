<?php

namespace PamutProba\App\Client;

use PamutProba\App\Client\Middleware\Middleware;
use PamutProba\App\Request;
use PamutProba\App\Router\RouteHandler\RouteHandler;
use PamutProba\App\Router\Router;
use PamutProba\App\Session;
use PamutProba\Http\Method;
use PamutProba\Http\Status;

class Client
{
    protected static bool $isCreated = false;
    protected static Request $request;
    protected static Session $session;
    protected static Router $router;
    /**
     * @var Middleware[]
     */
    protected static array $middlewares = [];

    private function __construct(){}

    /**
     * @param Request $request
     * @param Session $session
     * @param array<string, RouteHandler> $routeHandlers
     * @return void
     * @throws \Exception
     */
    public static function create(Request $request, Session $session, array $routeHandlers): void
    {
        if (static::$isCreated)
        {
            throw new \Exception("The Client has already been created");
        }
        static::$request = $request;
        static::$session = $session;
        static::$router = new Router($routeHandlers);

        if (count($routeHandlers) === 0)
        {
            throw new \Exception("You must specify at least one route handler for your application.");
        }

        static::$isCreated = true;
    }

    /**
     * @throws \Exception
     */
    public static function use(Middleware ...$middlewares): void
    {
        if (static::$isCreated)
        {
            throw new \Exception("Can't attach more middleware to the Client because it has already been created");
        }
        foreach ($middlewares as $middleware)
        {
            static::$middlewares[] = $middleware;
        }
    }

    protected static function applyMiddleware(): void
    {
        $next = fn(Request $request): Request => $request;
        foreach (static::$middlewares as $middleware)
        {
            static::$request = $middleware(static::request(), $next);
        }
    }

    public static function request(): Request
    {
        return static::$request;
    }

    public static function session(): Session
    {
        return static::$session;
    }

    /**
     * @throws \Exception
     */
    public static function router(string $key): RouteHandler
    {
        return static::$router->get($key);
    }

    /**
     * @throws \Exception
     */
    protected static function getRouteData(): array
    {
        return [
            static::request()->getHeader("APP_ACCEPT"),
            Method::tryFrom(static::request()->getHeader("REQUEST_METHOD")),
            static::request()->getHeader("REQUEST_URI")
        ];
    }

    public static function redirect(string $url)
    {
        header("Location: $url");
        exit();
    }

    /**
     * @throws \Exception
     */
    public static function execute(): void
    {
        static::applyMiddleware();

        list($validMimes, $method, $endpoint) = static::getRouteData();
        $routeHandler = static::$router->selectRouteHandler($validMimes, $method, $endpoint);

        $view = $routeHandler->execute($method, $endpoint);
        ob_start();
        $routeHandler->setHeaders();
        $view->render();
        ob_end_flush();
        exit();
    }

    public static function exitWithError(\Exception $error, Status $code = Status::InternalServerError): void
    {
        try
        {
            list($validMimes, $method, $endpoint) = static::getRouteData();
            $routeHandler = static::$router->selectRouteHandler($validMimes, $method, $endpoint);
        }
        catch (\Exception $e)
        {
            $routeHandler = static::$router->selectDefaultRouteHandler();
        }

        ob_end_clean();
        http_response_code($code->value);
        $routeHandler->setHeaders();
        $routeHandler->createErrorPage($error)->render();
        exit();
    }
}