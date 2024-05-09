<?php

namespace PamutProba\App;

use PamutProba\App\Input\HeaderTransformer;
use PamutProba\App\Input\Input;
use PamutProba\App\View\HtmlView;
use PamutProba\Exception\Exception;
use PamutProba\Http\Method;
use PamutProba\Http\Status;
use PamutProba\Utility\Path;

class Client
{
    protected static Request $request;
    protected static Router $router;

    private function __construct(){}

    public static function create(array $headers, array $params, array $body): void
    {
        static::$request = new Request(
            new Input($headers, new HeaderTransformer()),
            new Input($params),
            new Input($body)
        );
        static::$router = new Router();
    }

    public static function request(): Request
    {
        return static::$request;
    }

    public static function router(): Router
    {
        return static::$router;
    }

    /**
     * @throws \Exception
     */
    public static function execute(Method $method, string $endpoint): void
    {
        $view = static::router()->execute($method, $endpoint);
        ob_start();
        $view->render();
        ob_end_flush();
        exit();
    }

    public static function exitWithError(\Exception $error, Status $code = Status::InternalServerError): void
    {
        ob_end_clean();
        http_response_code($code->value);
        $view = new HtmlView(Path::template("error.php"), [
            "title" => "Hiba",
            "error" => Exception::from($error)
        ]);
        $view->render();
        exit();
    }
}