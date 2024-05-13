<?php declare(strict_types=1);

namespace PamutProba\App\Router\RouteHandler;

use PamutProba\App\View\HtmlView;
use PamutProba\App\View\View;
use PamutProba\Exception\HttpException;
use PamutProba\Utility\Path;

class HtmlRouteHandler extends RouteHandler
{
    public function createErrorPage(\Exception $error): View
    {
        return new HtmlView(Path::template("error.php"), [
            "title" => "Hiba",
            "error" => $error instanceof HttpException ? $error : HttpException::from($error)
        ]);
    }
}