<?php declare(strict_types=1);

namespace PamutProba\Core\Database;

use PamutProba\Database\DatabaseEntityType;

interface IDatabaseService
{
    public function entity(DatabaseEntityType $entityType): IDatabaseEntity;

    public function dbo(): mixed;
}