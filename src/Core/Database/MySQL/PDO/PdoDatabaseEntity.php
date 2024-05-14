<?php declare(strict_types=1);

namespace PamutProba\Core\Database\MySQL\PDO;

use PamutProba\Core\Database\IDatabaseEntity;
use PamutProba\Core\Database\IDatabaseService;
use PamutProba\Core\Database\MySQL\MySQLDatabaseEntity;
use PamutProba\Database\DatabaseEntityType;

abstract class PdoDatabaseEntity extends MySQLDatabaseEntity
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

    protected function dbo(): \PDO
    {
        return $this->service()->dbo();
    }

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

    protected function createWhereClause(): array
    {
        return [];
    }

    protected function bindWhereClause(\PDOStatement $statement): void
    {}

    public function count(): int
    {
        $query = "SELECT COUNT(*) FROM `{$this->table()}` {$this->appendWhere()}";
        $statement = $this->dbo()->prepare($query);
        $statement->execute();

        return $statement->fetchColumn();
    }

    public function get(int $id): array|false
    {
        $query = "SELECT * FROM `{$this->table()}` WHERE `id` = :id";
        $statement = $this->dbo()->prepare($query);
        $statement->bindValue("id", $id);
        $statement->execute();

        return $statement->fetch();
    }

    public function getBy(string $field, mixed $value): array|false
    {
        $query = "SELECT * FROM `{$this->table()}` WHERE `$field` = :value";
        $statement = $this->dbo()->prepare($query);
        $statement->bindValue("value", $value);
        $statement->execute();

        return $statement->fetch();
    }

    protected function appendLimit(int $start, int $limit): string
    {
        $result = [];

        if ($limit > 0)
        {
            $result[] = $start >= 0 ? "$start" : "0";
            $result[] = "$limit";
        }

        return $result ? "LIMIT " . implode(", ", $result) : "";
    }

    protected function appendWhere(): string
    {
        $whereClause = $this->createWhereClause();
        return $whereClause ? "WHERE " . implode(" AND ", $whereClause) : "";
    }

    public function list(int $start, int $limit): array
    {
        $query = "SELECT * FROM `{$this->table()}` ORDER BY `id` ASC {$this->appendLimit($start, $limit)}";
        $statement = $this->dbo()->prepare($query);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function save(array $properties): array
    {
        if (!$properties["id"] || $this->get((int) $properties["id"]) === false)
        {
            return $this->insert($properties);
        }

        return $this->update($properties);
    }

    protected abstract function insert(array $properties): array;
    protected abstract function update(array $properties): array;

    public function delete(int $id): void
    {
        $query = "DELETE FROM `{$this->table()}` WHERE `id` = :id";
        $statement = $this->dbo()->prepare($query);
        $statement->bindValue("id", $id);
        $statement->execute();
    }
}