<?php declare(strict_types=1);

namespace PamutProba\Entity\Factory;

use PamutProba\Entity\Entity;

abstract class EntityFactory
{
    protected static string $entityType = Entity::class;

    public static function createOne(): Entity
    {
        return call_user_func(array(static::$entityType, "random"));
    }

    /**
     * @param int $count
     * @return Entity[]
     */
    public static function createMore(int $count): array
    {
        $result = [];
        while ($count-- > 0)
        {
            $result[] = static::createOne();
        }

        return $result;
    }
}