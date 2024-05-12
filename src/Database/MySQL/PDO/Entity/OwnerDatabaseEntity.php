<?php

namespace PamutProba\Database\MySQL\PDO\Entity;

class OwnerDatabaseEntity extends DatabaseEntity
{
    protected function table(): string
    {
        return "owners";
    }
}