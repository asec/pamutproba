<?php

namespace PamutProba\Database;

interface IDatabaseEntityClassMapper
{
    public function map(DatabaseEntityType $entityType): string;
}