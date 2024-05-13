<?php declare(strict_types=1);

namespace PamutProba\Database\MySQL\PDO\Entity;

class StatusDatabaseEntity extends DatabaseEntity
{
    protected function table(): string
    {
        return "statuses";
    }

    protected function insert(array $properties): array
    {
        $query = "INSERT INTO `{$this->table()}` (`key`, `name`) VALUES (:key, :name)";
        $statement = $this->dbo()->prepare($query);
        $statement->bindValue("key", $properties["key"]);
        $statement->bindValue("name", $properties["name"]);
        $statement->execute();

        $id = (int) $this->dbo()->lastInsertId();
        $properties["id"] = $id;

        return $properties;
    }

    protected function update(array $properties): array
    {
        $query =  <<<EOL
        UPDATE `{$this->table()}` SET `key` = :key, `name` = :name
            WHERE `id` = :id
        EOL;

        $statement = $this->dbo()->prepare($query);
        $statement->bindValue("key", $properties["key"]);
        $statement->bindValue("name", $properties["name"]);
        $statement->bindValue("id", $properties["id"]);
        $statement->execute();

        return $properties;
    }
}