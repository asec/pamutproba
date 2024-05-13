<?php declare(strict_types=1);

namespace PamutProba\Database;

interface IDatabaseEntityClassMapper
{
    public function map(DatabaseEntityType $entityType): string;
}