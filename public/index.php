<?php declare(strict_types=1);

require_once "../src/autoload.php";

use PamutProba\App\Client\Client;
use PamutProba\App\Client\Middleware\FormUrlencodedBodyParser;
use PamutProba\App\Client\Middleware\HeaderNormalizeRequestUri;
use PamutProba\App\Client\Middleware\HeaderParseAccept;
use PamutProba\App\Client\Middleware\HeaderParseUnique;
use PamutProba\App\Client\Middleware\JsonBodyParser;
use PamutProba\App\Config;
use PamutProba\App\Controller\Api\ApiHomeController;
use PamutProba\App\Controller\Api\ApiHomePostController;
use PamutProba\App\Controller\Api\ApiProjektDeleteController;
use PamutProba\App\Controller\Dev\DevRandomController;
use PamutProba\App\Controller\Dev\DevWaitController;
use PamutProba\App\Controller\Web\WebHomeController;
use PamutProba\App\Controller\Web\WebProjektController;
use PamutProba\App\Controller\Web\WebProjektDeleteController;
use PamutProba\App\Controller\Web\WebProjektSaveController;
use PamutProba\App\Request;
use PamutProba\App\Router\RouteHandler\HtmlRouteHandler;
use PamutProba\App\Router\RouteHandler\JsonRouteHandler;
use PamutProba\App\Session;
use PamutProba\Database\Database;
use PamutProba\Database\DatabaseEntityType;
use PamutProba\Database\MySQL\PDO\DatabaseService;
use PamutProba\Entity\Model\Models;
use PamutProba\Entity\Model\OwnerModel;
use PamutProba\Entity\Model\ProjectModel;
use PamutProba\Entity\Model\StatusModel;
use PamutProba\Entity\Owner;
use PamutProba\Entity\Project;
use PamutProba\Entity\Status;
use PamutProba\Exception\HttpException;
use PamutProba\Http\Method;
use PamutProba\Http\MimeType;
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
    Database::set(new DatabaseService(
        Config::get("MYSQL")["HOST"],
        Config::get("MYSQL")["PORT"],
        Config::get("MYSQL")["USER"],
        Config::get("MYSQL")["PASSWORD"],
        Config::get("MYSQL")["DATABASE"]
    ));

    Models::setStore(Database::get());
    Models::create(
        Owner::class,
        DatabaseEntityType::Owner,
        OwnerModel::class
    );
    Models::create(
        Status::class,
        DatabaseEntityType::Status,
        StatusModel::class
    );
    Models::create(
        Project::class,
        DatabaseEntityType::Project,
        ProjectModel::class
    );

    Session::start();

    Client::use(
        new HeaderNormalizeRequestUri(),
        new HeaderParseAccept(),
        new HeaderParseUnique(),
        new JsonBodyParser(),
        new FormUrlencodedBodyParser()
    );
    Client::create(
        Request::from($_SERVER, $_GET, $_POST),
        Session::from($_SESSION),
        [
            "api" => new JsonRouteHandler(MimeType::Json),
            "web" => new HtmlRouteHandler(MimeType::Any)
        ]
    );

    Client::router("web")->define(
        Method::GET,
        "/",
        new WebHomeController(
            Client::request(),
            Models::get(Project::class),
            Models::get(Status::class)
        )
    );

    Client::router("web")->define(
        Method::GET,
        "/projekt",
        new WebProjektController(
            Client::request(),
            Client::session(),
            Models::get(Project::class),
            Models::get(Status::class)
        )
    );

    Client::router("web")->define(
        Method::POST,
        "/projekt",
        new WebProjektSaveController(
            Client::request(),
            Client::session(),
            Models::get(Status::class),
            Models::get(Owner::class),
            Models::get(Project::class)
        )
    );

    Client::router("web")->define(
        Method::POST,
        "/projekt/torol",
        new WebProjektDeleteController(
            Client::request(),
            Client::session(),
            Models::get(Project::class)
        )
    );

    Client::router("api")->define(
        Method::DELETE,
        "/api/projekt",
        new ApiProjektDeleteController(
            Client::request(),
            Models::get(Project::class)
        )
    );

    Client::router("api")->define(
        Method::GET,
        "/api",
        new ApiHomeController()
    );

    Client::router("api")->define(
        Method::POST,
        "/api",
        new ApiHomePostController(Client::request())
    );

    if (Development::isDev())
    {
        Client::router("web")->define(
            Method::GET,
            "/dev/random",
            new DevRandomController(
                Client::request(),
                Client::session(),
                Models::get(Project::class),
                Models::get(Status::class),
                Models::get(Owner::class)
            )
        );

        Client::router("api")->define(
            Method::GET,
            "/dev/wait",
            new DevWaitController(
                Client::request()
            )
        );
    }

    Client::execute();
}
catch (HttpException $e)
{
    Client::exitWithError($e, \PamutProba\Http\Status::from($e->getCode()));
}
catch (\Exception $e)
{
    Client::exitWithError($e);
}
