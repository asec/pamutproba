<?php

namespace PamutProba\Utility;

class Path
{
    protected static string $basePath;

    private function __construct(){}

    public static function setBase(string $path): void
    {
        static::$basePath = $path;
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