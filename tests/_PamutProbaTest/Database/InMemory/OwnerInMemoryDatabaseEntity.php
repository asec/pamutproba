<?php declare(strict_types=1);

namespace _PamutProbaTest\Database\InMemory;

use _PamutProbaTest\Core\Database\InMemory\InMemoryDatabaseEntity;
use PamutProba\Database\DatabaseEntityType;

class OwnerInMemoryDatabaseEntity extends InMemoryDatabaseEntity
{
    protected function entityType(): DatabaseEntityType
    {
        return DatabaseEntityType::Owner;
    }
}