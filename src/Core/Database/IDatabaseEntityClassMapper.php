<?php declare(strict_types=1);

namespace PamutProba\Core\Database;

use PamutProba\Database\DatabaseEntityType;

interface IDatabaseEntityClassMapper
{
    public function map(DatabaseEntityType $entityType): string;
}