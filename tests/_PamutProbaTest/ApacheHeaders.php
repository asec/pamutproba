<?php declare(strict_types=1);

namespace _PamutProbaTest;

class ApacheHeaders
{
    protected static array $headers = [];

    public static function set(array $headers): void
    {
        static::$headers = $headers;
    }

    public static function get(): array
    {
        return static::$headers;
    }
}