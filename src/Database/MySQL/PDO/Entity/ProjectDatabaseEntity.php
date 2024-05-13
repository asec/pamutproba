<?php declare(strict_types=1);

namespace PamutProba\Database\MySQL\PDO\Entity;

use PamutProba\Database\DatabaseEntityType;
use PamutProba\Database\IDatabaseEntity;

class ProjectDatabaseEntity extends DatabaseEntity
{
    protected function table(): string
    {
        return "projects";
    }

    public function relation(DatabaseEntityType $entityType): null|string
    {
        return match ($entityType)
        {
            DatabaseEntityType::Owner => "project_owner_pivot",
            DatabaseEntityType::Status => "project_status_pivot",
            default => null
        };
    }

    protected function deconstructProperties(array $properties): array
    {
        $status = $properties["status"];
        if (isset($status["id"]))
        {
            $status["id"] = (int) $status["id"];
        }
        unset($properties["status"]);
        $owner = $properties["owner"];
        if (isset($owner["id"]))
        {
            $owner["id"] = (int) $owner["id"];
        }
        unset($properties["owner"]);

        return [$properties, $status, $owner];
    }

    protected function createWhereClause(): array
    {
        $whereClause = [];
        foreach ($this->filter as $filter)
        {
            switch ($filter["relation"])
            {
                case DatabaseEntityType::Status:
                    $whereClause[] = "psp.status_id = :pspstatusid";
                    break;
            }
        }

        return $whereClause;
    }

    protected function bindWhereClause(\PDOStatement $statement): void
    {
        foreach ($this->filter as $filter)
        {
            switch ($filter["relation"])
            {
                case DatabaseEntityType::Status:
                    $statement->bindValue(":pspstatusid", $filter["id"], \PDO::PARAM_INT);
                    break;
            }
        }
    }

    public function count(): int
    {
        $where = $this->appendWhere();
        if ($where)
        {
            $query = <<<EOL
            SELECT COUNT(*) FROM `{$this->table()}` AS p
                    LEFT JOIN `{$this->relation(DatabaseEntityType::Owner)}` AS pop ON pop.project_id = p.id
                    LEFT JOIN `{$this->service()->entity(DatabaseEntityType::Owner)->table()}` AS o ON o.id = pop.owner_id
                    LEFT JOIN `{$this->relation(DatabaseEntityType::Status)}` AS psp ON psp.project_id = p.id
                    LEFT JOIN `{$this->service()->entity(DatabaseEntityType::Status)->table()}` AS s ON s.id = psp.status_id
                    {$where}
            EOL;
            $statement = $this->dbo()->prepare($query);
            $this->bindWhereClause($statement);
            $statement->execute();

            return $statement->fetchColumn();
        }

        return parent::count();
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
                {$this->appendWhere()}
            ORDER BY p.id ASC {$this->appendLimit($start, $limit)}
        EOL;
        $statement = $this->dbo()->prepare($query);
        $this->bindWhereClause($statement);
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
        $statement = $this->dbo()->prepare($query);
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
        $statement = $this->dbo()->prepare($query);
        $statement->bindValue("value", $value);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * @throws \Exception
     */
    protected function insert(array $properties): array
    {
        return $this->recordCreationProcess($properties, function (array $properties) {

            $query = "INSERT INTO `{$this->table()}` (`title`, `description`) VALUES (:title, :description)";
            $statement = $this->dbo()->prepare($query);
            $statement->bindValue("title", $properties["title"]);
            $statement->bindValue("description", $properties["description"]);
            $statement->execute();

            return (int) $this->dbo()->lastInsertId();
        });
    }

    /**
     * @throws \Exception
     */
    protected function update(array $properties): array
    {
        return $this->recordCreationProcess($properties, function (array $properties) {

            $query = "UPDATE `{$this->table()}` SET `title` = :title, `description` = :description WHERE  `id` = :id";
            $statement = $this->dbo()->prepare($query);
            $statement->bindValue("title", $properties["title"]);
            $statement->bindValue("description", $properties["description"]);
            $statement->bindValue("id", $properties["id"]);
            $statement->execute();

            return $properties["id"];
        });
    }

    /**
     * @param array $properties
     * @param \Closure(array $properties): int $handleEntity
     * @return array
     * @throws \Exception
     */
    protected function recordCreationProcess(array $properties, \Closure $handleEntity): array
    {
        [$properties, $status, $owner] = $this->deconstructProperties($properties);

        try
        {
            $this->dbo()->beginTransaction();

            $status = $this->service()->entity(DatabaseEntityType::Status)->save($status);
            $owner = $this->service()->entity(DatabaseEntityType::Owner)->save($owner);

            $properties["id"] = $handleEntity($properties);

            $this->createRelations($properties["id"], $status["id"], $owner["id"]);

            $this->dbo()->commit();
        }
        catch (\PDOException $e)
        {
            $this->dbo()->rollBack();
            throw new \Exception("There was an error while saving the project: {$e->getMessage()}", 0, $e);
        }

        $properties["status"] = $status;
        $properties["owner"] = $owner;

        return $properties;
    }

    protected function createRelations(int $project_id, int $status_id, int $owner_id): void
    {
        $query = <<<EOL
        INSERT INTO `{$this->relation(DatabaseEntityType::Status)}` (`project_id`, `status_id`)
            VALUES (:pid, :sid)
            ON DUPLICATE KEY UPDATE `status_id` = VALUES(`status_id`)
        EOL;
        $statement = $this->dbo()->prepare($query);
        $statement->bindValue("pid", $project_id);
        $statement->bindValue("sid", $status_id);
        $statement->execute();

        $query = <<<EOL
        DELETE FROM `{$this->relation(DatabaseEntityType::Owner)}` WHERE `project_id` = :id
        EOL;
        $statement = $this->dbo()->prepare($query);
        $statement->bindValue("id", $project_id);
        $statement->execute();

        $query = <<<EOL
        INSERT INTO `{$this->relation(DatabaseEntityType::Owner)}` (`project_id`, `owner_id`)
            VALUES (:pid, :oid)
        EOL;
        $statement = $this->dbo()->prepare($query);
        $statement->bindValue("pid", $project_id);
        $statement->bindValue("oid", $owner_id);
        $statement->execute();
    }

    /**
     * @throws \Exception
     */
    public function delete(int $id): void
    {
        try
        {
            $this->dbo()->beginTransaction();

            $query = <<<EOL
            DELETE FROM `{$this->relation(DatabaseEntityType::Status)}` WHERE `project_id` = :id
            EOL;
            $statement = $this->dbo()->prepare($query);
            $statement->bindValue("id", $id);
            $statement->execute();

            $query = <<<EOL
            DELETE FROM `{$this->relation(DatabaseEntityType::Owner)}` WHERE `project_id` = :id
            EOL;
            $statement = $this->dbo()->prepare($query);
            $statement->bindValue("id", $id);
            $statement->execute();

            $query = "DELETE FROM `{$this->table()}` WHERE `id` = :id";
            $statement = $this->dbo()->prepare($query);
            $statement->bindValue("id", $id);
            $statement->execute();


            $this->dbo()->commit();
        }
        catch (\PDOException $e)
        {
            $this->dbo()->rollBack();
            throw new \Exception("There was an error while saving the project: {$e->getMessage()}", 0, $e);
        }
    }
}