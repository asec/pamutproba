<?php

namespace PamutProba\Database\MySQL\PDO;

use PamutProba\Database\DatabaseEntityType;
use PamutProba\Database\IDatabaseEntityClassMapper;
use PamutProba\Database\MySQL\PDO\Entity\OwnerDatabaseEntity;
use PamutProba\Database\MySQL\PDO\Entity\ProjectDatabaseEntity;
use PamutProba\Database\MySQL\PDO\Entity\StatusDatabaseEntity;

class ClassMapper implements IDatabaseEntityClassMapper
{
    /**
     * @throws \Exception
     */
    public function map(DatabaseEntityType $entityType): string
    {
        return match($entityType)
        {
            DatabaseEntityType::Project => ProjectDatabaseEntity::class,
            DatabaseEntityType::Owner => OwnerDatabaseEntity::class,
            DatabaseEntityType::Status => StatusDatabaseEntity::class,
            default => throw new \Exception("The following type needs to be mapped into a class in " . static::class . ": {$entityType->name}")
        };
    }
}