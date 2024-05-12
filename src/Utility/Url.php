<?php

namespace PamutProba\Utility;

use PamutProba\App\Client\Client;

class Url
{
    protected static string $base;

    public static function base(string $url = null): string
    {
        if (!isset(static::$base))
        {
            static::$base = implode("", [
                "http",
                !empty($_SERVER["HTTPS"]) ? "s" : "",
                "://",
                $_SERVER["HTTP_HOST"]
            ]);
        }
        return static::$base . ($url ?? "/");
    }

    public static function current(): string
    {
        return static::base(Client::request()->getHeader("REQUEST_URI"));
    }
}