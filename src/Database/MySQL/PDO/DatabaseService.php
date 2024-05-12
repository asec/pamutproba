<?php

namespace PamutProba\Database\MySQL\PDO;

use PamutProba\Database\DatabaseEntityType;
use PamutProba\Database\IDatabaseEntityClassMapper;
use PamutProba\Database\MySQL\IMySQLDatabaseService;
use PamutProba\Database\MySQL\PDO\Entity\DatabaseEntity;

final readonly class DatabaseService implements IMySQLDatabaseService
{
    private \PDO $db;
    private IDatabaseEntityClassMapper $classMapper;

    public function __construct(string $host, int $port, string $user, #[\SensitiveParameter] string $password, string $database)
    {
        $dsn = "mysql:dbname=$database;host=$host;port=$port";
        $this->db = new \PDO($dsn, $user, $password, [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ]);

        $this->classMapper = new ClassMapper();
    }

    public function dbo(): \PDO
    {
        return $this->db;
    }

    /**
     * @throws \Exception
     */
    public function entity(DatabaseEntityType $entityType): DatabaseEntity
    {
        return new ($this->classMapper->map($entityType))($this, $this->db);
    }
}