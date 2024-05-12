<?php

namespace PamutProba\Database\MySQL\PDO\Entity;

use PamutProba\Database\DatabaseEntityType;

class ProjectDatabaseEntity extends DatabaseEntity
{
    protected function table(): string
    {
        return "projects";
    }

    /**
     * @throws \Exception
     */
    protected function relation(DatabaseEntityType $entityType): string
    {
        return match ($entityType)
        {
            DatabaseEntityType::Owner => "project_owner_pivot",
            DatabaseEntityType::Status => "project_status_pivot",
            default => throw new \Exception("Invalid relation in '" . static::class . "': [{$entityType->name}]")
        };
    }

    /**
     * @throws \Exception
     */
    public function list(int $start, int $limit): array
    {
        $query = <<<EOL
        SELECT p.*, o.id AS pop_owner_id, o.name AS pop_owner_name, o.email AS pop_owner_email, s.id AS psp_status_id,
               s.key AS psp_status_key, s.name AS psp_status_name FROM `{$this->table()}` AS p
                LEFT JOIN `{$this->relation(DatabaseEntityType::Owner)}` AS pop ON pop.project_id = p.id
                LEFT JOIN `{$this->service()->entity(DatabaseEntityType::Owner)->table()}` AS o ON o.id = pop.owner_id
                LEFT JOIN `{$this->relation(DatabaseEntityType::Status)}` AS psp ON psp.project_id = p.id
                LEFT JOIN `{$this->service()->entity(DatabaseEntityType::Status)->table()}` AS s ON s.id = psp.status_id
            ORDER BY p.id ASC LIMIT $start, $limit
        EOL;
        $statement = $this->service()->dbo()->prepare($query);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @throws \Exception
     */
    public function get(int $id): array|false
    {
        $query = <<<EOL
        SELECT p.*, o.id AS pop_owner_id, o.name AS pop_owner_name, o.email AS pop_owner_email, s.id AS psp_status_id,
               s.key AS psp_status_key, s.name AS psp_status_name FROM `{$this->table()}` AS p
                LEFT JOIN `{$this->relation(DatabaseEntityType::Owner)}` AS pop ON pop.project_id = p.id
                LEFT JOIN `{$this->service()->entity(DatabaseEntityType::Owner)->table()}` AS o ON o.id = pop.owner_id
                LEFT JOIN `{$this->relation(DatabaseEntityType::Status)}` AS psp ON psp.project_id = p.id
                LEFT JOIN `{$this->service()->entity(DatabaseEntityType::Status)->table()}` AS s ON s.id = psp.status_id
            WHERE p.id = :id
        EOL;
        $statement = $this->service()->dbo()->prepare($query);
        $statement->bindValue("id", $id);
        $statement->execute();

        return $statement->fetch();
    }

    public function getBy(string $field, mixed $value): array|false
    {
        $query = <<<EOL
        SELECT p.*, o.id AS pop_owner_id, o.name AS pop_owner_name, o.email AS pop_owner_email, s.id AS psp_status_id,
               s.key AS psp_status_key, s.name AS psp_status_name FROM `{$this->table()}` AS p
                LEFT JOIN `{$this->relation(DatabaseEntityType::Owner)}` AS pop ON pop.project_id = p.id
                LEFT JOIN `{$this->service()->entity(DatabaseEntityType::Owner)->table()}` AS o ON o.id = pop.owner_id
                LEFT JOIN `{$this->relation(DatabaseEntityType::Status)}` AS psp ON psp.project_id = p.id
                LEFT JOIN `{$this->service()->entity(DatabaseEntityType::Status)->table()}` AS s ON s.id = psp.status_id
            WHERE p.$field = :value
        EOL;
        $statement = $this->service()->dbo()->prepare($query);
        $statement->bindValue("value", $value);
        $statement->execute();

        return $statement->fetch();
    }
}