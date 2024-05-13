<?php declare(strict_types=1);

namespace PamutProba\Entity\Model\Validation;

use PamutProba\Entity\Entity;
use PamutProba\Exception\ValidationException;

class IsEmail implements IValidator
{
    public function __invoke(string $field, Entity $entity): void
    {
        if (!filter_var($entity->$field, FILTER_VALIDATE_EMAIL))
        {
            throw ValidationException::with("Invalid field [$field]. Needs to be an e-mail address");
        }
    }
}