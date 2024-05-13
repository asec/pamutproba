<?php declare(strict_types=1);

namespace PamutProba\Database\MySQL;

use \PamutProba\Database\IDatabaseService;

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