<?php declare(strict_types=1);

namespace PamutProba\Entity\Model\Validation;

use PamutProba\Entity\Entity;
use PamutProba\Exception\ValidationException;

class NotNull implements IValidator
{
    public function __invoke(string $field, Entity $entity): void
    {
        if ($entity->$field === null)
        {
            throw ValidationException::with("Invalid field [$field]. Cannot be null");
        }
    }
}