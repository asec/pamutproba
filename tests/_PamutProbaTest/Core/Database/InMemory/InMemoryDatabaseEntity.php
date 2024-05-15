<?php declare(strict_types=1);

namespace _PamutProbaTest\Core\Database\InMemory;

use PamutProba\Core\Database\IDatabaseEntity;
use PamutProba\Core\Database\IDatabaseService;
use PamutProba\Database\DatabaseEntityType;

abstract class InMemoryDatabaseEntity implements IDatabaseEntity
{
    protected array $filter = [];

    public function __construct(
        private readonly IDatabaseService $service
    )
    {}

    public function service(): IDatabaseService
    {
        return $this->service;
    }

    protected function dbo(): Store
    {
        return $this->service->dbo();
    }

    protected abstract function entityType(): DatabaseEntityType;

    public function relation(DatabaseEntityType $entityType): null|string
    {
        return null;
    }

    public function filterByRelation(DatabaseEntityType $entityType, int $id): IDatabaseEntity
    {
        $relation = $this->relation($entityType);
        if ($relation)
        {
            $this->filter[] = [
                "relation" => $entityType,
                "id" => $id
            ];
        }

        return $this;
    }

    protected function filterEntities(array $entities): array
    {
        if ($this->filter)
        {
            foreach ($this->filter as $filter)
            {
                $relationKey = $this->relation($filter["relation"]);
                foreach ($entities as $key => $entity)
                {
                    if ($entity[$relationKey]["id"] !== $filter["id"])
                    {
                        unset($entities[$key]);
                    }
                }
            }
        }

        return $entities;
    }

    public function count(): int
    {
        $entities = $this->dbo()->get($this->entityType());
        $entities = $this->filterEntities($entities);

        return count($entities);
    }

    public function get(int $id): array|false
    {
        return $this->dbo()->get($this->entityType(), $id) ?? false;
    }

    public function getBy(string $field, mixed $value): array|false
    {
        $entities = $this->dbo()->get($this->entityType());
        $result = false;

        foreach ($entities as $id => $properties)
        {
            if ($properties[$field] === $value)
            {
                $result = $properties;
                break;
            }
        }

        return $result;
    }

    public function list(int $start, int $limit): array
    {
        $start = max(0, $start);
        $limit = max(0, $limit);

        $entities = $this->dbo()->get($this->entityType());
        $entities = $this->filterEntities($entities);

        if (!$start && !$limit)
        {
            return $entities;
        }
        else if (!$limit)
        {
            $limit = $start;
            $start = 0;
        }

        $result = [];

        $keys = array_keys($entities);
        for ($j = min($start, count($keys) - 1); $j < min($start + $limit, count($keys)); $j++)
        {
            $key = $keys[$j];
            $result[$key] = $entities[$key];
        }

        return $result;
    }

    public function save(array $properties): array
    {
        if ($properties["id"] === null)
        {
            $properties["id"] = $this->dbo()->nextId($this->entityType());
        }
        $this->dbo()->set($this->entityType(), $properties);

        return $this->dbo()->get($this->entityType(), (int) $properties["id"]);
    }

    public function delete(int $id): void
    {
        $this->dbo()->delete($this->entityType(), $id);
    }

    public function truncate(): void
    {
        $this->dbo()->truncate($this->entityType());
    }
}