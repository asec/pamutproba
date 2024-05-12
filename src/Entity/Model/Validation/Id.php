<?php

namespace PamutProba\Entity\Model\Validation;

use PamutProba\Entity\Entity;
use PamutProba\Entity\Model\Model;
use PamutProba\Exception\ValidationException;

class Id implements IValidator
{
    public function __construct(
        protected Model $model
    )
    {}

    public function __invoke(string $field, Entity $entity): void
    {
        if ($entity->$field !== null)
        {
            if ($this->model->get($entity->id) === null)
            {
                throw ValidationException::with(
                    "Invalid field [$field]. This entity does not exists in the database. You need a null id for these"
                );
            }
        }
    }
}