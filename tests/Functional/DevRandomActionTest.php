<?php declare(strict_types=1);

namespace Functional;

use _PamutProbaTest\Core\Database\InMemory\InMemoryDatabaseService;
use PamutProba\Controller\Dev\DevRandomController;
use PamutProba\Core\App\Client\Client;
use PamutProba\Core\App\Client\Middleware\FormUrlencodedBodyParser;
use PamutProba\Core\App\Client\Middleware\HeaderNormalizeRequestUri;
use PamutProba\Core\App\Client\Middleware\HeaderParseAccept;
use PamutProba\Core\App\Client\Middleware\HeaderParseUnique;
use PamutProba\Core\App\Client\Middleware\JsonBodyParser;
use PamutProba\Core\App\Request;
use PamutProba\Core\App\Router\RouteHandler\HtmlRouteHandler;
use PamutProba\Core\App\Session;
use PamutProba\Core\Database\IDatabaseService;
use PamutProba\Core\Http\Method;
use PamutProba\Core\Http\MimeType;
use PamutProba\Core\Mail\Mail;
use PamutProba\Core\Mail\Services\VoidMailService;
use PamutProba\Core\Model\Model;
use PamutProba\Core\Utility\Development\Development;
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

class DevRandomActionTest extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        Path::setBase(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "..");
        Development::setEnvironment(new VoidDevelopmentService());
        Mail::set(new VoidMailService("test@localhost"));

        Model::bind(Owner::class, DatabaseEntityType::Owner, OwnerFactory::validators());
        Model::bind(Status::class, DatabaseEntityType::Status, StatusFactory::validators());
        Model::bind(Project::class, DatabaseEntityType::Project, ProjectFactory::validators());
    }

    protected function createClient(int $count = null): array
    {
        $db = new InMemoryDatabaseService();
        $session = [];

        $headers = [
            "HTTP_HOST" => "localhost"
        ];
        $request = [];
        if ($count !== null)
        {
            $request["count"] = $count;
        }

        $client = new Client(
            Request::from($headers, $request, []),
            Session::from($session),
            [
                "test" => new HtmlRouteHandler(MimeType::Any)
            ]
        );
        $client->use(
            new HeaderNormalizeRequestUri(),
            new HeaderParseAccept(),
            new HeaderParseUnique(),
            new JsonBodyParser(),
            new FormUrlencodedBodyParser()
        );
        $client->applyMiddleware();
        Url::updateHeaders($client->request()->headers()->all());

        $client->router("test")->define(
            Method::GET,
            "/",
            new DevRandomController(
                $client->request(),
                $client->session(),
                new ProjectFactory($db),
                new StatusFactory($db),
                new OwnerFactory($db)
            )
        );

        return [$client, $db];
    }

    protected function createRandomStatus(IDatabaseService $db): void
    {
        $status = Status::random();
        // A factory megkerülése a túlzottan szigorú validáció miatt
        $db->entity(DatabaseEntityType::Status)->save($status->toArray());
    }

    protected function createRandomOwner(IDatabaseService $db): void
    {
        $ownerFactory = new OwnerFactory($db);
        $ownerFactory->save(Owner::random());
    }

    public function testEmpty()
    {
        [$client,] = $this->createClient();

        $this->expectExceptionMessage("státusz");
        $client->execute();
    }

    public function testEmptyOwners(): void
    {
        [$client, $db] = $this->createClient();
        $this->createRandomStatus($db);

        $this->expectExceptionMessage("kapcsolattartó");
        $client->execute();
    }

    public function testSuccess(): void
    {
        [$client, $db] = $this->createClient();
        $this->createRandomStatus($db);
        $this->createRandomOwner($db);

        $result = $client->execute();
        $this->assertEquals($result, "");

        $factory = new ProjectFactory($db);
        $this->assertEquals(10, $factory->count());

        $sessionData = $client->session()->all();
        $this->assertNotEmpty($sessionData["flashed"]["message-success"]);
    }

    public function testSuccessWithParams(): void
    {
        $count = 100;
        [$client, $db] = $this->createClient($count);
        $this->createRandomStatus($db);
        $this->createRandomOwner($db);

        $result = $client->execute();
        $this->assertEquals($result, "");

        $factory = new ProjectFactory($db);
        $this->assertEquals($count, $factory->count());

        $sessionData = $client->session()->all();
        $this->assertNotEmpty($sessionData["flashed"]["message-success"]);
    }
}