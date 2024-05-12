<?php

namespace PamutProba\Entity\Model;

use PamutProba\Database\DatabaseEntityType;
use PamutProba\Database\IDatabaseService;
use PamutProba\Exception\Exception;

class Models
{
    protected static IDatabaseService $store;
    protected static array $classMap = [];

    public static function setStore(IDatabaseService $store): void
    {
        static::$store = $store;
    }

    /**
     * @throws Exception
     */
    public static function create(
        string $entityType,
        DatabaseEntityType $databaseEntityType,
        string $modelClass
    ): void
    {
        try
        {
            $model = new $modelClass($entityType, $databaseEntityType, static::$store);
        }
        catch (\Exception $e)
        {
            throw new Exception("Failed to instantiate model [$modelClass]", $e->getCode(), $e);
        }

        static::$classMap[$entityType] = $model;
    }

    public static function get(string $entityType): Model
    {
        return static::$classMap[$entityType];
    }
}