<?php declare(strict_types=1);

require_once "../src/autoload.php";

use PamutProba\App\Client;
use PamutProba\App\Config;
use PamutProba\App\View\HtmlView;
use PamutProba\App\View\JsonView;
use PamutProba\Entity\Factory\ProjectFactory;
use PamutProba\Exception\HttpException;
use PamutProba\Http\Method;
use PamutProba\Http\Status;
use PamutProba\Utility\Development\Development;
use PamutProba\Utility\Development\DevelopmentService;
use PamutProba\Utility\Development\VoidDevelopmentService;
use PamutProba\Utility\Path;

Path::setBase(__DIR__ . DIRECTORY_SEPARATOR . "..");

set_error_handler(/**
 * @throws Exception
 */ function (int $errno, string $errstr) {
    throw new \Exception($errstr, $errno);
});

try
{
    Development::setEnvironment(
        Config::get("APP_ENV") === "dev" ? new DevelopmentService() : new VoidDevelopmentService()
    );

    Client::create($_SERVER, $_GET, $_POST);
    $request = Client::request();

    Client::router()->define(Method::GET, "/", function () {
        return new HtmlView(Path::template("main.php"), [
            "title" => "Projekt Lista",
            "projects" => ProjectFactory::createMore(3)
        ]);
    });

    Client::router()->define(Method::GET, "/projekt", function () {
        return new HtmlView(Path::template("projekt.php"), [
            //"title" => "Projekt Létrehozása",
            "project" => ProjectFactory::createOne()
        ]);
    });

    Client::router()->define(Method::GET, "/api", function () {
        return new JsonView([
            "foo" => "bar",
            "baz" => 10,
            "test" => new DateTime(),
            "project" => ProjectFactory::createMore(10)
        ]);
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
