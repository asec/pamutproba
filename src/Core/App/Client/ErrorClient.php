<?php

namespace PamutProba\Core\App\Client;

use PamutProba\Core\App\Request;
use PamutProba\Core\App\Router\RouteHandler\HtmlRouteHandler;
use PamutProba\Core\Http\MimeType;
use PamutProba\Core\Http\Status;
use PamutProba\Core\Utility\Url;

class ErrorClient implements IClientBase
{
    public function __construct()
    {}

    public function exitWithError(\Exception $error, Status $code = Status::InternalServerError): void
    {
        $request = Request::from($_SERVER, [], []);
        Url::updateHeaders($request->headers()->all());

        $routeHandler = new HtmlRouteHandler(MimeType::Any);

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