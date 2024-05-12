<?php

namespace PamutProba\Entity\Model;

use PamutProba\Database\DatabaseEntityType;
use PamutProba\Entity\Entity;
use PamutProba\Entity\Status;

class StatusModel extends Model
{
    protected static string $entityType = Status::class;
    protected static DatabaseEntityType $dbEntityType = DatabaseEntityType::Status;

    /**
     * @param int $start
     * @param int $limit
     * @return Entity[]
     * @throws \Exception
     */
    public static function list(int $start = 0, int $limit = 10): array
    {
        $rawData = static::db()->entity(static::$dbEntityType)->list($start, $limit);

        $result = [];
        foreach ($rawData as $data)
        {
            $result[] = Status::from($data);
        }

        return $result;
    }
}