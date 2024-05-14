<?php declare(strict_types=1);

namespace PamutProba\Validation;

use PamutProba\Core\Entity\Entity;
use PamutProba\Core\Exception\ValidationException;
use PamutProba\Core\Validation\IValidator;
use PamutProba\Entity\Status;

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