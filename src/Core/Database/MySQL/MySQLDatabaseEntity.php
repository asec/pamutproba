<?php declare(strict_types=1);

namespace PamutProba\Core\Database\MySQL;

use PamutProba\Core\Database\IDatabaseEntity;

abstract class MySQLDatabaseEntity implements IDatabaseEntity
{
    protected abstract function table(): string;
}