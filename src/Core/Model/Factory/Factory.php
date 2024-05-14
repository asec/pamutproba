<?php declare(strict_types=1);

namespace PamutProba\Core\Model\Factory;

use PamutProba\Core\Database\IDatabaseService;
use PamutProba\Core\Entity\Entity;
use PamutProba\Core\Exception\ValidationException;
use PamutProba\Core\Model\IEntityBuilder;
use PamutProba\Core\Model\Model;
use PamutProba\Core\Validation\IValidator;
use PamutProba\Database\DatabaseEntityType;

abstract class Factory implements IEntityBuilder
{
    protected Model $model;

    public abstract function __construct(?IDatabaseService $databaseService = null);

    /**
     * @return array<string, IValidator[]>
     */
    public static function validators(): array
    {
        return [];
    }

    /**
     * @throws ValidationException
     */
    public function validate(Entity $entity): void
    {
        $this->model->validate($entity);
    }

    public function filterByRelation(DatabaseEntityType $databaseEntityType, Entity $entity): static
    {
        $this->model->filterByRelation($databaseEntityType, $entity);

        return $this;
    }

    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * @param int $start
     * @param int $limit
     * @return Entity[]
     */
    public abstract function list(int $start = 0, int $limit = 0): array;

    public abstract function get(int $id): Entity|null;

    public abstract function getBy(string $field, mixed $value): Entity|null;

    /**
     * @param Entity $entity
     * @return Entity
     * @throws \Exception
     */
    public abstract function save(Entity $entity): Entity;

    public function delete(Entity $entity): void
    {
        $this->model->delete($entity);
    }
}