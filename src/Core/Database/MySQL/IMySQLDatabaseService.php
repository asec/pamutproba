<?php declare(strict_types=1);

namespace PamutProba\Core\Database\MySQL;

use PamutProba\Core\Database\IDatabaseService;

interface IMySQLDatabaseService extends IDatabaseService
{
    public function __construct(
        string $host,
        int $port,
        string $user,
        #[\SensitiveParameter] string $password,
        string $database
    );
}