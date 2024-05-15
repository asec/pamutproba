<?php declare(strict_types=1);

namespace PamutProba\Core\Database;

enum DatabaseServiceDriver: string
{
    case MySQLWithPdo = "pdo";
}
