<?php declare(strict_types=1);

namespace PamutProba\Core\App\Router\RouteHandler;

use PamutProba\Core\App\View\JsonView;
use PamutProba\Core\App\View\View;
use PamutProba\Core\Exception\HttpException;

class JsonRouteHandler extends RouteHandler
{
    public function createErrorPage(\Exception $error): View
    {
        return new JsonView([
            "error" => $error instanceof HttpException ? $error : HttpException::from($error)
        ]);
    }
}