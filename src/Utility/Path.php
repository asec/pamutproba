<?php declare(strict_types=1);

namespace PamutProba\Utility;

class Path
{
    protected static string $basePath;

    private function __construct(){}

    public static function setBase(string $path): void
    {
        static::$basePath = $path;
    }

    public static function absolute(string $path): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            static::$basePath,
            $path
        ]);
    }

    public static function template(string $path): string
    {
        return implode(DIRECTORY_SEPARATOR, [
                static::$basePath,
                "templates",
                $path
        ]);
    }
}