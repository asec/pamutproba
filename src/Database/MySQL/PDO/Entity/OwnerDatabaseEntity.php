<?php declare(strict_types=1);

namespace PamutProba\Database\MySQL\PDO\Entity;

class OwnerDatabaseEntity extends DatabaseEntity
{
    protected function table(): string
    {
        return "owners";
    }

    protected function insert(array $properties): array
    {
        $query = "INSERT INTO `{$this->table()}` (`name`, `email`) VALUES (:name, :email)";
        $statement = $this->dbo()->prepare($query);
        $statement->bindValue("name", $properties["name"]);
        $statement->bindValue("email", $properties["email"]);
        $statement->execute();

        $id = (int) $this->dbo()->lastInsertId();
        $properties["id"] = $id;

        return $properties;
    }

    protected function update(array $properties): array
    {
        $query =  <<<EOL
        UPDATE `{$this->table()}` SET `name` = :name, `email` = :email
            WHERE `id` = :id
        EOL;

        $statement = $this->dbo()->prepare($query);
        $statement->bindValue("name", $properties["name"]);
        $statement->bindValue("email", $properties["email"]);
        $statement->bindValue("id", $properties["id"]);
        $statement->execute();

        return $properties;
    }
}