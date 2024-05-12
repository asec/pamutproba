<?php

namespace PamutProba\Entity\Model;

use PamutProba\Database\DatabaseEntityType;
use PamutProba\Database\IDatabaseService;
use PamutProba\Entity\Entity;
use PamutProba\Exception\ValidationException;

interface IModel
{
    public function __construct(string $entityType, DatabaseEntityType $databaseEntityType, IDatabaseService $store);

    public function type(): string;

    /**
     * @param Entity $entity
     * @return void
     * @throws ValidationException
     */
    public function validate(Entity $entity): void;
    public function filterByRelation(DatabaseEntityType $databaseEntityType, Entity $entity): IModel;
    public function count(): int;
    /**
     * @param int $start
     * @param int $limit
     * @return Entity[]
     */
    public function list(int $start = 0, int $limit = 0): array;
    public function get(int $id): Entity|null;
    public function getBy(string $field, mixed $value): Entity|null;
    public function save(Entity $entity): Entity;
    public function delete(Entity $entity): void;
}