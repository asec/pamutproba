<?php declare(strict_types=1);

namespace PamutProba\Core\Model;

use PamutProba\Core\Entity\Entity;
use PamutProba\Core\Exception\ValidationException;
use PamutProba\Database\DatabaseEntityType;

interface IEntityBuilder
{
    public function filterByRelation(DatabaseEntityType $databaseEntityType, Entity $entity): IEntityBuilder;
    public function count(): int;
    /**
     * @param int $start
     * @param int $limit
     * @return Entity[]
     */
    public function list(int $start = 0, int $limit = 0): array;
    public function get(int $id): Entity|null;
    public function getBy(string $field, mixed $value): Entity|null;
    /**
     * @param Entity $entity
     * @return void
     * @throws ValidationException
     */
    public function validate(Entity $entity): void;
    public function save(Entity $entity): Entity;
    public function delete(Entity $entity): void;
}