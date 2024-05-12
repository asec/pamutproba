<?php

namespace PamutProba\Entity\Model;

use PamutProba\Database\DatabaseEntityType;
use PamutProba\Database\IDatabaseEntity;
use PamutProba\Database\IDatabaseService;
use PamutProba\Entity\Entity;
use PamutProba\Entity\Model\Validation\IValidator;
use PamutProba\Exception\ValidationException;

abstract class Model implements IModel
{
    protected ?DatabaseEntityType $filterType = null;
    protected ?Entity $filterValue = null;

    public function __construct(
        protected string $entityType,
        protected DatabaseEntityType $databaseEntityType,
        protected IDatabaseService $store
    )
    {}

    protected function store(): IDatabaseEntity
    {
        return $this->store->entity($this->databaseEntityType);
    }

    public function type(): string
    {
        return $this->entityType;
    }

    /**
     * @return array<string, IValidator[]>
     */
    protected function validators(): array
    {
        return [];
    }

    /**
     * @param Entity $entity
     * @return void
     * @throws ValidationException
     */
    public function validate(Entity $entity): void
    {
        foreach ($this->validators() as $field => $validators)
        {
            foreach ($validators as $validator)
            {
                $validator($field, $entity);
            }
        }
    }

    public function filterByRelation(DatabaseEntityType $databaseEntityType, Entity $entity): IModel
    {
        $this->filterType = $databaseEntityType;
        $this->filterValue = $entity;

        return $this;
    }

    public function count(): int
    {
        $store = $this->store();
        if ($this->filterType)
        {
            $store->filterByRelation($this->filterType, $this->filterValue->id);
        }
        return $store->count();
    }

    public abstract function list(int $start = 0, int $limit = 0): array;

    public function get(int $id): Entity|null
    {
        $data = $this->store()->get($id);
        if ($data === false)
        {
            return null;
        }

        return call_user_func(array($this->entityType, "from"), $data);
    }

    public function getBy(string $field, mixed $value): Entity|null
    {
        $data = $this->store()->getBy($field, $value);
        if ($data === false)
        {
            return null;
        }

        return call_user_func(array($this->entityType, "from"), $data);
    }

    /**
     * @throws ValidationException
     */
    public function save(Entity $entity): Entity
    {
        $this->validate($entity);
        $data = $this->store()->save($entity->toArray());

        return call_user_func(array($this->entityType, "from"), $data);
    }

    public function delete(Entity $entity): void
    {
        $this->store()->delete($entity->id);
    }
}