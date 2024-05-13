<?php declare(strict_types=1);

namespace PamutProba\Entity\Model\Validation;

use PamutProba\Entity\Entity;
use PamutProba\Entity\Status;
use PamutProba\Exception\ValidationException;

class IsStatus implements IValidator
{
    /**
     * @throws ValidationException
     */
    public function __invoke(string $field, Entity $entity): void
    {
        if (!($entity instanceof Status))
        {
            throw ValidationException::with(
                "Invalid entity for validation: '" . get_class($entity) . "'. Needs: '" . Status::class . "'"
            );
        }
    }
}