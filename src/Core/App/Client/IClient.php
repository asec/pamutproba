<?php

namespace PamutProba\Core\App\Client;

use PamutProba\Core\App\Client\Middleware\IMiddleware;
use PamutProba\Core\App\Request;
use PamutProba\Core\App\Router\RouteHandler\RouteHandler;
use PamutProba\Core\App\Session;

interface IClient extends IClientBase
{
    /**
     * @param Request $request
     * @param Session $session
     * @param array<string, RouteHandler> $routeHandlers
     */
    public function __construct(Request $request, Session $session, array $routeHandlers);
    public function use(IMiddleware ...$middleware): void;
    public function applyMiddleware(): void;
    public function request(): Request;
    public function session(): Session;
    public function router(string $key): RouteHandler;
    public function redirect(string $url): void;
    public function execute(): string;
}