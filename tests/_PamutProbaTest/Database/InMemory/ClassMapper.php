<?php declare(strict_types=1);

namespace _PamutProbaTest\Database\InMemory;

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
            DatabaseEntityType::Project => ProjectInMemoryDatabaseEntity::class,
            DatabaseEntityType::Owner => OwnerInMemoryDatabaseEntity::class,
            DatabaseEntityType::Status => StatusInMemoryDatabaseEntity::class,
            default => throw new \Exception("The following type needs to be mapped into a class in " . static::class . ": $entityType->name")
        };
    }
}