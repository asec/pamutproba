<?php declare(strict_types=1);

namespace PamutProba\Core\App\Client;

use PamutProba\Core\App\Client\Middleware\Middleware;
use PamutProba\Core\App\Request;
use PamutProba\Core\App\Router\RouteHandler\RouteHandler;
use PamutProba\Core\App\Router\Router;
use PamutProba\Core\App\Session;
use PamutProba\Core\Http\Method;
use PamutProba\Core\Http\Status;

class Client implements IClient
{
    protected Request $request;
    protected Session $session;
    protected Router $router;
    /**
     * @var Middleware[]
     */
    protected array $middlewares = [];

    /**
     * @param Request $request
     * @param Session $session
     * @param array<string, RouteHandler> $routeHandlers
     */
    public function __construct(Request $request, Session $session, array $routeHandlers)
    {
        $this->request = $request;
        $this->session = $session;
        $this->router = new Router($routeHandlers);
    }

    public function use(Middleware ...$middlewares): void
    {
        foreach ($middlewares as $middleware)
        {
            $this->middlewares[] = $middleware;
        }
    }

    public function applyMiddleware(): void
    {
        $next = fn(Request $request): Request => $request;
        foreach ($this->middlewares as $middleware)
        {
            $this->request = $middleware($this->request, $next);
        }
    }

    public function request(): Request
    {
        return $this->request;
    }

    public function session(): Session
    {
        return $this->session;
    }

    public function router(string $key): RouteHandler
    {
        return $this->router->get($key);
    }

    protected function getRouteData(): array
    {
        return [
            $this->request()->getHeader("APP_ACCEPT") ?? [],
            Method::tryFrom($this->request()->getHeader("REQUEST_METHOD") ?? "GET"),
            $this->request()->getHeader("REQUEST_URI") ?? "/"
        ];
    }

    public function redirect(string $url): void
    {
        header("Location: $url");
        exit();
    }

    /**
     * @throws \Exception
     */
    public function execute(): string
    {
        list($validMimes, $method, $endpoint) = static::getRouteData();
        $routeHandler = $this->router->selectRouteHandler($validMimes, $method, $endpoint);

        $view = $routeHandler->execute($method, $endpoint);
        $routeHandler->setHeaders();
        return $view->render();
    }

    public function exitWithError(\Exception $error, Status $code = Status::InternalServerError): void
    {
        list($validMimes, $method, $endpoint) = static::getRouteData();
        try
        {
            $routeHandler = $this->router->selectRouteHandler($validMimes, $method, $endpoint);
        }
        catch (\Exception $e)
        {
            $routeHandler = $this->router->selectDefaultRouteHandler();
        }

        if (ob_get_contents() !== false)
        {
            ob_end_clean();
        }
        http_response_code($code->value);
        $routeHandler->setHeaders();
        echo $routeHandler->createErrorPage($error)->render();
        exit();
    }
}