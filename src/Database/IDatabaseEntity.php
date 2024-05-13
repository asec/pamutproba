<?php declare(strict_types=1);

namespace PamutProba\Database;

interface IDatabaseEntity
{
    public function __construct(IDatabaseService $service);
    public function service(): IDatabaseService;
    public function relation(DatabaseEntityType $entityType): null|string;
    public function filterByRelation(DatabaseEntityType $entityType, int $id): IDatabaseEntity;
    public function count(): int;
    public function get(int $id): array|false;
    public function getBy(string $field, mixed $value): array|false;
    public function list(int $start, int $limit): array;
    public function save(array $properties): array;
    public function delete(int $id): void;
}