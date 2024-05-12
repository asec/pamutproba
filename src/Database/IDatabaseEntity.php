<?php

namespace PamutProba\Database;

interface IDatabaseEntity
{
    public function __construct(IDatabaseService $service);
    public function service(): IDatabaseService;
    public function count(): int;
    public function get(int $id): array|false;
    public function getBy(string $field, mixed $value): array|false;
    public function list(int $start, int $limit): array;
}