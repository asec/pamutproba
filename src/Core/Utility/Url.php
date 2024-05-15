<?php declare(strict_types=1);

namespace PamutProba\Core\Utility;

class Url
{
    protected static string $base;
    protected static array $headers = [];

    public static function base(string $url = null): string
    {
        if (!isset(static::$base))
        {
            static::$base = implode("", [
                "http",
                !empty(static::$headers["HTTPS"]) ? "s" : "",
                "://",
                static::$headers["HTTP_HOST"]
            ]);
        }
        return static::$base . ($url ?? "/");
    }

    public static function updateHeaders(array $headers): void
    {
        static::$headers = $headers;
    }

    public static function current(array $params = []): string
    {
        $url = static::base(static::$headers["REQUEST_URI"]);
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