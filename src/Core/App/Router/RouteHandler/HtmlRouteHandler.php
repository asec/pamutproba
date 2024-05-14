<?php declare(strict_types=1);

namespace PamutProba\Core\App\Router\RouteHandler;

use PamutProba\Core\App\View\HtmlView;
use PamutProba\Core\App\View\View;
use PamutProba\Core\Exception\HttpException;
use PamutProba\Core\Utility\Path;

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