<?php declare(strict_types=1);

namespace PamutProba\Core\Database\MySQL\PDO;

use PamutProba\Core\Database\IDatabaseEntityClassMapper;
use PamutProba\Core\Database\MySQL\IMySQLDatabaseService;
use PamutProba\Database\DatabaseEntityType;
use PamutProba\Database\MySQL\PDO\ClassMapper;

final readonly class PdoDatabaseService implements IMySQLDatabaseService
{
    private \PDO $db;
    private IDatabaseEntityClassMapper $classMapper;

    /**
     * @throws \Exception
     */
    public function __construct(string $host, int $port, string $user, #[\SensitiveParameter] string $password, string $database)
    {
        try
        {
            $dsn = "mysql:dbname=$database;host=$host;port=$port";
            $this->db = new \PDO($dsn, $user, $password, [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]);

            $this->classMapper = new ClassMapper();
        }
        catch (\PDOException $e)
        {
            throw new \Exception("PDO MySQL driver error => " . $e->getMessage(), 0, $e);
        }
    }

    public function dbo(): \PDO
    {
        return $this->db;
    }

    /**
     * @throws \Exception
     */
    public function entity(DatabaseEntityType $entityType): PdoDatabaseEntity
    {
        return new ($this->classMapper->map($entityType))($this, $this->db);
    }
}