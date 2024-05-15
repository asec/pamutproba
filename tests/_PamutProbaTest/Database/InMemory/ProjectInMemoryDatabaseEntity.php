<?php declare(strict_types=1);

namespace _PamutProbaTest\Database\InMemory;

use _PamutProbaTest\Core\Database\InMemory\InMemoryDatabaseEntity;
use PamutProba\Database\DatabaseEntityType;

class ProjectInMemoryDatabaseEntity extends InMemoryDatabaseEntity
{
    protected function entityType(): DatabaseEntityType
    {
        return DatabaseEntityType::Project;
    }

    public function relation(DatabaseEntityType $entityType): null|string
    {
        return match ($entityType)
        {
            DatabaseEntityType::Owner => "owner",
            DatabaseEntityType::Status => "status",
            default => null
        };
    }

    public function save(array $properties): array
    {
        $properties["status"] = $this->service()
            ->entity(DatabaseEntityType::Status)
            ->save($properties["status"])
        ;
        $properties["owner"] = $this->service()
            ->entity(DatabaseEntityType::Owner)
            ->save($properties["owner"])
        ;
        return parent::save($properties);
    }
}