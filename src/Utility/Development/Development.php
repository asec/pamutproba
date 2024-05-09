<?php

namespace PamutProba\Utility\Development;

class Development
{
    protected static ?IDevelopmentService $service = null;

    private function __construct(){}

    /**
     * @throws \Exception
     */
    public static function setEnvironment(IDevelopmentService $service): void
    {
        if (static::$service !== null)
        {
            throw new \Exception("The state of the development environment has already been set");
        }

        static::$service = $service;
    }

    protected static function get(): IDevelopmentService
    {
        return static::$service;
    }

    public static function isDev(): bool
    {
        return static::get()->isDev();
    }

    public static function printTrace(array $trace): void
    {
        static::get()->printTrace($trace);
    }
}