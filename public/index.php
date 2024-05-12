<?php declare(strict_types=1);

require_once "../src/autoload.php";

use PamutProba\App\Client\Client;
use PamutProba\App\Client\Middleware\HeaderNormalizeRequestUri;
use PamutProba\App\Client\Middleware\HeaderParseAccept;
use PamutProba\App\Client\Middleware\JsonBodyParser;
use PamutProba\App\Config;
use PamutProba\App\Request;
use PamutProba\App\Router\RouteHandler\HtmlRouteHandler;
use PamutProba\App\Router\RouteHandler\JsonRouteHandler;
use PamutProba\App\View\HtmlView;
use PamutProba\App\View\JsonView;
use PamutProba\Database\Database;
use PamutProba\Database\MySQL\PDO\DatabaseService;
use PamutProba\Entity\Factory\ProjectFactory;
use PamutProba\Entity\Model\Model;
use PamutProba\Entity\Model\OwnerModel;
use PamutProba\Entity\Model\ProjectModel;
use PamutProba\Entity\Model\StatusModel;
use PamutProba\Exception\HttpException;
use PamutProba\Http\Method;
use PamutProba\Http\MimeType;
use PamutProba\Http\Status;
use PamutProba\Utility\Development\Development;
use PamutProba\Utility\Development\DevelopmentService;
use PamutProba\Utility\Development\VoidDevelopmentService;
use PamutProba\Utility\Path;
use PamutProba\Utility\Url;

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
    Database::set(new DatabaseService(
        Config::get("MYSQL")["HOST"],
        Config::get("MYSQL")["PORT"],
        Config::get("MYSQL")["USER"],
        Config::get("MYSQL")["PASSWORD"],
        Config::get("MYSQL")["DATABASE"]
    ));
    Model::setDb(Database::get());

    Client::use(
        new HeaderNormalizeRequestUri(),
        new HeaderParseAccept(),
        new JsonBodyParser()
    );
    Client::create(
        Request::from($_SERVER, $_GET, $_POST),
        [
            "api" => new JsonRouteHandler(MimeType::Json),
            "web" => new HtmlRouteHandler(MimeType::Any)
        ]
    );

    Client::router("web")->define(Method::GET, "/", function () {

        return new HtmlView(Path::template("main.php"), [
            "title" => "Projekt Lista",
            "projects" => ProjectModel::list()
        ]);

    });

    Client::router("web")->define(Method::GET, "/projekt", function () {

        $project = null;
        if ($id = (int) Client::request()->getParam("id"))
        {
            $project = ProjectModel::get($id);
            if ($project === null)
            {
                throw new HttpException("A keresett oldal nem található.", Status::NotFound);
            }
        }

        return new HtmlView(Path::template("projekt.php"), [
            "title" => "Projekt Létrehozása",
            "project" => $project,
            "statuses" => StatusModel::list()
        ]);

    });

    Client::router("web")->define(Method::POST, "/projekt", function () {
        $id = (int) Client::request()->getField("id");
        $status = StatusModel::get((int) Client::request()->getField("status"));
        $owner = OwnerModel::getBy("email", Client::request()->getField("owner_email"));
        if ($status === null)
        {
            // TODO: Hiba jelzés
            Client::redirect(Url::current() . "/?id=$id");
        }
        var_dump(Client::request()->body()->all()); die();
    });

    Client::router("api")->define(Method::GET, "/api", function () {
        return new JsonView([
            "foo" => "bar",
            "baz" => 10,
            "test" => new DateTime(),
            "project" => ProjectFactory::createMore(10)
        ]);
    });

    Client::router("api")->define(Method::POST, "/api", function () {
        return new JsonView([
            "headers" => Client::request()->headers()->all(),
            "params" => Client::request()->params()->all(),
            "body" => Client::request()->body()->all(),
            "success" => true
        ]);
    });

    Client::execute();
}
catch (HttpException $e)
{
    Client::exitWithError($e, Status::from($e->getCode()));
}
catch (\Exception $e)
{
    Client::exitWithError($e);
}
