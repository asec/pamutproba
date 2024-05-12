<?php

namespace PamutProba\Database\MySQL;

use \PamutProba\Database\IDatabaseEntity;

abstract class MySQLDatabaseEntity implements IDatabaseEntity
{
    protected abstract function table(): string;
}