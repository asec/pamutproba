<?php declare(strict_types=1);

namespace PamutProba\Core\Model;

use PamutProba\Core\Database\IDatabaseService;
use PamutProba\Core\Entity\Entity;
use PamutProba\Core\Validation\IValidator;
use PamutProba\Database\DatabaseEntityType;

interface IModel extends IEntityBuilder
{
    /**
     * @param string $entityType
     * @param DatabaseEntityType $databaseEntityType
     * @param array<string, IValidator> $validators
     * @return void
     */
    public static function bind(string $entityType, DatabaseEntityType $databaseEntityType, array $validators = []): void;

    public static function for(string $entityType, ?IDatabaseService $store = null): IModel;

    public function type(): string;

    public function filterByRelation(DatabaseEntityType $databaseEntityType, Entity $entity): IModel;
}