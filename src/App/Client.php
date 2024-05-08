<?php

namespace PamutProba\App;

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
        static::$request = new Request($headers, $params, $body);
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
        static::router()->execute($method, $endpoint);
    }

    public static function exitWithError(\Exception $error, Status $code = Status::InternalServerError): void
    {
        http_response_code($code->value);
        require Path::template("error.php");
        exit();
    }
}