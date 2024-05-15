<?php declare(strict_types=1);

namespace _PamutProbaTest\Core\Database\InMemory;

use _PamutProbaTest\Database\InMemory\ClassMapper;
use PamutProba\Core\Database\IDatabaseEntity;
use PamutProba\Core\Database\IDatabaseEntityClassMapper;
use PamutProba\Core\Database\IDatabaseService;
use PamutProba\Database\DatabaseEntityType;

class InMemoryDatabaseService implements IDatabaseService
{
    protected Store $data;
    private IDatabaseEntityClassMapper $classMapper;
    /**
     * @var array<string, IDatabaseEntity>
     */
    protected array $entityCache = [];

    public function __construct()
    {
        $this->data = new Store();
        $this->classMapper = new ClassMapper();
    }

    /**
     * @throws \Exception
     */
    public function entity(DatabaseEntityType $entityType): IDatabaseEntity
    {
        $className = $this->classMapper->map($entityType);
        if (!isset($this->entityCache[$className]))
        {
            $this->entityCache[$className] = new $className($this, $this->dbo());
        }

        return $this->entityCache[$className];
    }

    public function dbo(): Store
    {
        return $this->data;
    }
}