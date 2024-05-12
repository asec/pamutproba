<?php

namespace PamutProba\Database;

class Database
{
    protected static ?IDatabaseService $service = null;

    private function __construct(){}

    /**
     * @throws \Exception
     */
    public static function set(IDatabaseService $service): void
    {
        if (static::$service !== null)
        {
            throw new \Exception("The following service has already been set: '" . static::class. "'");
        }
        static::$service = $service;
    }

    public static function get(): IDatabaseService
    {
        return static::$service;
    }
}