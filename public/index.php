<?php declare(strict_types=1);

require_once "../src/autoload.php";

use PamutProba\Controller\Api\ApiProjektDeleteController;
use PamutProba\Controller\Dev\DevRandomController;
use PamutProba\Controller\Dev\DevWaitController;
use PamutProba\Controller\Web\WebHomeController;
use PamutProba\Controller\Web\WebProjektController;
use PamutProba\Controller\Web\WebProjektDeleteController;
use PamutProba\Controller\Web\WebProjektSaveController;
use PamutProba\Core\App\Client\Client;
use PamutProba\Core\App\Client\Middleware\FormUrlencodedBodyParser;
use PamutProba\Core\App\Client\Middleware\HeaderNormalizeRequestUri;
use PamutProba\Core\App\Client\Middleware\HeaderParseAccept;
use PamutProba\Core\App\Client\Middleware\HeaderParseUnique;
use PamutProba\Core\App\Client\Middleware\JsonBodyParser;
use PamutProba\Core\App\Config;
use PamutProba\Core\App\Environment;
use PamutProba\Core\App\Request;
use PamutProba\Core\App\Router\RouteHandler\HtmlRouteHandler;
use PamutProba\Core\App\Router\RouteHandler\JsonRouteHandler;
use PamutProba\Core\App\Session;
use PamutProba\Core\Database\Database;
use PamutProba\Core\Database\MySQL\PDO\PdoDatabaseService;
use PamutProba\Core\Exception\HttpException;
use PamutProba\Core\Http\Method;
use PamutProba\Core\Http\MimeType;
use PamutProba\Core\Mail\Mail;
use PamutProba\Core\Mail\MailServiceDriver;
use PamutProba\Core\Mail\Services\SimpleMailService;
use PamutProba\Core\Mail\Services\VoidMailService;
use PamutProba\Core\Model\Model;
use PamutProba\Core\Utility\Development\Development;
use PamutProba\Core\Utility\Development\DevelopmentService;
use PamutProba\Core\Utility\Development\VoidDevelopmentService;
use PamutProba\Core\Utility\Path;
use PamutProba\Database\DatabaseEntityType;
use PamutProba\Entity\Owner;
use PamutProba\Entity\Project;
use PamutProba\Entity\Status;
use PamutProba\Factory\OwnerFactory;
use PamutProba\Factory\ProjectFactory;
use PamutProba\Factory\StatusFactory;

Path::setBase(__DIR__ . DIRECTORY_SEPARATOR . "..");

set_error_handler(/**
 * @throws Exception
 */ function (int $errno, string $errstr) {
    throw new \Exception($errstr, $errno);
});

try
{
    Development::setEnvironment(
        Config::get("APP_ENV") === Environment::Development
            ? new DevelopmentService()
            : new VoidDevelopmentService()
    );
    Database::set(new PdoDatabaseService(
        Config::get("MYSQL")["HOST"],
        Config::get("MYSQL")["PORT"],
        Config::get("MYSQL")["USER"],
        Config::get("MYSQL")["PASSWORD"],
        Config::get("MYSQL")["DATABASE"]
    ));
    Mail::set(
        match (Config::get("MAIL")["DRIVER"])
        {
            MailServiceDriver::Null => new VoidMailService(Config::get("MAIL")["FROM"]),
            MailServiceDriver::SimpleMail => new SimpleMailService(Config::get("MAIL")["FROM"])
        }
    );

    Model::setDefaultStore(Database::get());
    Model::bind(Owner::class, DatabaseEntityType::Owner, OwnerFactory::validators());
    Model::bind(Status::class, DatabaseEntityType::Status, StatusFactory::validators());
    Model::bind(Project::class, DatabaseEntityType::Project, ProjectFactory::validators());

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
            new ProjectFactory(),
            new StatusFactory()
        )
    );

    Client::router("web")->define(
        Method::GET,
        "/projekt",
        new WebProjektController(
            Client::request(),
            Client::session(),
            new ProjectFactory(),
            new StatusFactory()
        )
    );

    Client::router("web")->define(
        Method::POST,
        "/projekt",
        new WebProjektSaveController(
            Client::request(),
            Client::session(),
            new StatusFactory(),
            new OwnerFactory(),
            new ProjectFactory(),
            Mail::get()
        )
    );

    Client::router("web")->define(
        Method::POST,
        "/projekt/torol",
        new WebProjektDeleteController(
            Client::request(),
            Client::session(),
            new ProjectFactory()
        )
    );

    Client::router("api")->define(
        Method::DELETE,
        "/api/projekt",
        new ApiProjektDeleteController(
            Client::request(),
            new ProjectFactory()
        )
    );

    if (Development::isDev())
    {
        Client::router("web")->define(
            Method::GET,
            "/dev/random",
            new DevRandomController(
                Client::request(),
                Client::session(),
                new ProjectFactory(),
                new StatusFactory(),
                new OwnerFactory()
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
    Client::exitWithError($e, \PamutProba\Core\Http\Status::from($e->getCode()));
}
catch (\Exception $e)
{
    Client::exitWithError($e);
}
