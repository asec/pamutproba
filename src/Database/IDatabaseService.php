<?php declare(strict_types=1);

namespace PamutProba\Database;

interface IDatabaseService
{
    public function entity(DatabaseEntityType $entityType): IDatabaseEntity;

    public function dbo(): mixed;
}