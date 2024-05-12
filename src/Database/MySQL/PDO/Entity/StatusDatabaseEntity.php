<?php

namespace PamutProba\Database\MySQL\PDO\Entity;

class StatusDatabaseEntity extends DatabaseEntity
{
    protected function table(): string
    {
        return "statuses";
    }
}