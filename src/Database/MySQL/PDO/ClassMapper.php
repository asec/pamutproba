<?php declare(strict_types=1);

namespace PamutProba\Database\MySQL\PDO;

use PamutProba\Core\Database\IDatabaseEntityClassMapper;
use PamutProba\Database\DatabaseEntityType;

class ClassMapper implements IDatabaseEntityClassMapper
{
    /**
     * @throws \Exception
     */
    public function map(DatabaseEntityType $entityType): string
    {
        return match($entityType)
        {
            DatabaseEntityType::Project => ProjectPdoDatabaseEntity::class,
            DatabaseEntityType::Owner => OwnerPdoDatabaseEntity::class,
            DatabaseEntityType::Status => StatusPdoDatabaseEntity::class,
            default => throw new \Exception("The following type needs to be mapped into a class in " . static::class . ": $entityType->name")
        };
    }
}