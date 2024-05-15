<?php declare(strict_types=1);

namespace _PamutProbaTest\Database\InMemory;

use _PamutProbaTest\Core\Database\InMemory\InMemoryDatabaseEntity;
use PamutProba\Database\DatabaseEntityType;

class StatusInMemoryDatabaseEntity extends InMemoryDatabaseEntity
{
    protected function entityType(): DatabaseEntityType
    {
        return DatabaseEntityType::Status;
    }

    /**
     * @throws \Exception
     */
    public function save(array $properties): array
    {
        if ($properties["id"] === null)
        {
            $existing = $this->getBy("key", $properties["key"]);
            if ($existing !== false)
            {
                throw new \Exception(
                    "Unique constraint error. A status with this key already exists: [{$properties["key"]}]"
                );
            }
        }
        else
        {
            $existing = $this->getBy("key", $properties["key"]);
            if ($existing !== false)
            {
                $this->delete($existing["id"]);
            }
        }

        return parent::save($properties);
    }
}