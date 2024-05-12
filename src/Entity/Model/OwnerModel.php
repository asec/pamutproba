<?php

namespace PamutProba\Entity\Model;

use PamutProba\Database\DatabaseEntityType;
use PamutProba\Entity\Owner;

class OwnerModel extends Model
{
    protected static string $entityType = Owner::class;
    protected static DatabaseEntityType $dbEntityType = DatabaseEntityType::Owner;

    /**
     * @throws \Exception
     */
    public static function list(int $start = 0, int $limit = 10): array
    {
        $rawData = static::db()->entity(static::$dbEntityType)->list($start, $limit);

        $result = [];
        foreach ($rawData as $data)
        {
            $result[] = Owner::from($data);
        }

        return $result;
    }
}