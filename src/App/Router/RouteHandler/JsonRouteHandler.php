<?php declare(strict_types=1);

namespace PamutProba\App\Router\RouteHandler;

use PamutProba\App\View\JsonView;
use PamutProba\App\View\View;
use PamutProba\Exception\HttpException;

class JsonRouteHandler extends RouteHandler
{
    public function createErrorPage(\Exception $error): View
    {
        return new JsonView([
            "error" => $error instanceof HttpException ? $error : HttpException::from($error)
        ]);
    }
}