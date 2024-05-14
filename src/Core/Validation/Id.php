<?php declare(strict_types=1);

namespace PamutProba\Core\Validation;

use PamutProba\Core\Entity\Entity;
use PamutProba\Core\Exception\ValidationException;
use PamutProba\Core\Model\Model;

class Id implements IValidator
{
    public function __construct(
        protected string $entityType
    )
    {}

    public function __invoke(string $field, Entity $entity): void
    {
        if ($entity->$field !== null)
        {
            $model = Model::for($this->entityType);
            if ($model->get($entity->$field) === null)
            {
                throw ValidationException::with(
                    "Invalid field [$field]. This entity does not exists in the database. You need a null id for these"
                );
            }
        }
    }
}