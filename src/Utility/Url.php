<?php declare(strict_types=1);

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

    public static function current(array $params = []): string
    {
        $url = static::base(Client::request()->getHeader("REQUEST_URI"));
        if ($params)
        {
            foreach ($params as $key => $param)
            {
                if ($param === "")
                {
                    unset($params[$key]);
                }
            }
            $query = http_build_query($params);
            if ($query)
            {
                $url .= "?" . $query;
            }
        }
        return $url;
    }
}