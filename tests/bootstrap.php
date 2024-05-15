<?php declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../src/autoload.php";
require __DIR__ . "/_PamutProbaTest/autoload.php";

if (!function_exists("apache_request_headers"))
{
    function apache_request_headers(): array
    {
        return \_PamutProbaTest\ApacheHeaders::get();
    }
}