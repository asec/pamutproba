<?php

namespace PamutProba\Database;

interface IDatabaseService
{
    public function entity(DatabaseEntityType $entityType): IDatabaseEntity;

    public function dbo(): mixed;
}