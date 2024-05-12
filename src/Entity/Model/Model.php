<?php

namespace PamutProba\Entity\Model;

use PamutProba\Database\DatabaseEntityType;
use PamutProba\Database\IDatabaseService;
use PamutProba\Entity\Entity;

abstract class Model
{
    protected static string $entityType = Entity::class;
    protected static DatabaseEntityType $dbEntityType;
    private static ?IDatabaseService $db = null;

    public static function setDb(IDatabaseService $db): void
    {
        static::$db = $db;
    }

    /**
     * @throws \Exception
     */
    protected static function db(): IDatabaseService
    {
        if (static::$db === null)
        {
            throw new \Exception("You need to set a database service for the models first");
        }
        return static::$db;
    }

    /**
     * @throws \Exception
     */
    public static function count(): int
    {
        return static::db()->entity(static::$dbEntityType)->count();
    }

    /**
     * @param int $start
     * @param int $limit
     * @return Entity[]
     */
    public static abstract function list(int $start = 0, int $limit = 10): array;

    /**
     * @throws \Exception
     */
    public static function get(int $id): Entity|null
    {
        $data = static::db()->entity(static::$dbEntityType)->get($id);
        if ($data === false)
        {
            return null;
        }

        return call_user_func(array(static::$entityType, "from"), $data);
    }

    public static function getBy(string $field, mixed $value): Entity|null
    {
        $data = static::db()->entity(static::$dbEntityType)->getBy($field, $value);
        if ($data === false)
        {
            return null;
        }

        return call_user_func(array(static::$entityType, "from"), $data);
    }
}