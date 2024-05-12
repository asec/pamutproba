<?php

namespace PamutProba\Entity\Model\Validation;

use PamutProba\Entity\Entity;
use PamutProba\Entity\Owner;
use PamutProba\Exception\ValidationException;

class IsOwner implements IValidator
{
    /**
     * @throws ValidationException
     */
    public function __invoke(string $field, Entity $entity): void
    {
        if (!($entity instanceof Owner))
        {
            throw ValidationException::with(
                "Invalid entity for validation: '" . get_class($entity) . "'. Needs: '" . Owner::class . "'"
            );
        }
    }
}