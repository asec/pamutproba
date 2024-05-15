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
use PamutProba\Core\App\Client\ErrorClient;
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
use PamutProba\Core\Database\DatabaseServiceDriver;
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
use PamutProba\Core\Utility\Url;
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

    Session::start();

    $client = new Client(
        Request::from($_SERVER, $_GET, $_POST),
        Session::from($_SESSION),
        [
            "api" => new JsonRouteHandler(MimeType::Json),
            "web" => new HtmlRouteHandler(MimeType::Any)
        ]
    );
    Url::updateHeaders($client->request()->headers()->all());

    $client->use(
        new HeaderNormalizeRequestUri(),
        new HeaderParseAccept(),
        new HeaderParseUnique(),
        new JsonBodyParser(),
        new FormUrlencodedBodyParser()
    );
    $client->applyMiddleware();
    Url::updateHeaders($client->request()->headers()->all());

    Mail::set(
        match (Config::get("MAIL")["DRIVER"])
        {
            MailServiceDriver::Null => new VoidMailService(Config::get("MAIL")["FROM"]),
            MailServiceDriver::SimpleMail => new SimpleMailService(Config::get("MAIL")["FROM"])
        }
    );

    Model::setDefaultStore(
        match (Config::get("MYSQL")["DRIVER"])
        {
            DatabaseServiceDriver::MySQLWithPdo => new PdoDatabaseService(
                Config::get("MYSQL")["HOST"],
                Config::get("MYSQL")["PORT"],
                Config::get("MYSQL")["USER"],
                Config::get("MYSQL")["PASSWORD"],
                Config::get("MYSQL")["DATABASE"]
            )
        }
    );
    Model::bind(Owner::class, DatabaseEntityType::Owner, OwnerFactory::validators());
    Model::bind(Status::class, DatabaseEntityType::Status, StatusFactory::validators());
    Model::bind(Project::class, DatabaseEntityType::Project, ProjectFactory::validators());

    $client->router("web")->define(
        Method::GET,
        "/",
        new WebHomeController(
            $client->request(),
            $client->session(),
            new ProjectFactory(),
            new StatusFactory()
        )
    );

    $client->router("web")->define(
        Method::GET,
        "/projekt",
        new WebProjektController(
            $client->request(),
            $client->session(),
            new ProjectFactory(),
            new StatusFactory()
        )
    );

    $client->router("web")->define(
        Method::POST,
        "/projekt",
        new WebProjektSaveController(
            $client->request(),
            $client->session(),
            new StatusFactory(),
            new OwnerFactory(),
            new ProjectFactory(),
            Mail::get()
        )
    );

    $client->router("web")->define(
        Method::POST,
        "/projekt/torol",
        new WebProjektDeleteController(
            $client->request(),
            $client->session(),
            new ProjectFactory()
        )
    );

    $client->router("api")->define(
        Method::DELETE,
        "/api/projekt",
        new ApiProjektDeleteController(
            $client->request(),
            new ProjectFactory()
        )
    );

    if (Development::isDev())
    {
        $client->router("web")->define(
            Method::GET,
            "/dev/random",
            new DevRandomController(
                $client->request(),
                $client->session(),
                new ProjectFactory(),
                new StatusFactory(),
                new OwnerFactory()
            )
        );

        $client->router("api")->define(
            Method::GET,
            "/dev/wait",
            new DevWaitController(
                $client->request()
            )
        );
    }

    echo $client->execute(); exit();
}
catch (HttpException $e)
{
    if (!isset($client))
    {
        $client = new ErrorClient();
    }

    $client->exitWithError($e, \PamutProba\Core\Http\Status::from($e->getCode()));
}
catch (\Exception $e)
{
    if (!isset($client))
    {
        $client = new ErrorClient();
    }

    $client->exitWithError($e);
}
