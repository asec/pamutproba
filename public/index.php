<?php declare(strict_types=1);

require_once "../src/autoload.php";

use \PamutProba\App\Client;
use \PamutProba\Utility\Path;
use \PamutProba\Http\Method;
use \PamutProba\Exception\HttpException;
use \PamutProba\Http\Status;
use \PamutProba\App\View\HtmlView;
use \PamutProba\App\View\JsonView;

Path::setBase(__DIR__ . DIRECTORY_SEPARATOR . "..");

set_error_handler(/**
 * @throws Exception
 */ function (int $errno, string $errstr) {
    throw new Exception($errstr, $errno);
}, E_ALL);

try
{
    Client::create($_SERVER, $_GET, $_POST);
    $request = Client::request();

    Client::router()->define(Method::GET, "/", function () {
        $view = new HtmlView(
            Path::template("main.php"),
            [
                "foo" => 10,
                "bar" => "baz"
            ]
        );
        $view->render();
    });

    Client::router()->define(Method::GET, "/api", function () {
        $view = new JsonView([
            "foo" => "bar",
            "baz" => 10
        ]);
        $view->render();
    });

    Client::execute(
        Method::tryFrom($request->getHeader("REQUEST_METHOD")),
        $request->getHeader("REQUEST_URI")
    );
}
catch (HttpException $e)
{
    Client::exitWithError($e, Status::from($e->getCode()));
}
catch (Exception $e)
{
    Client::exitWithError($e);
}
