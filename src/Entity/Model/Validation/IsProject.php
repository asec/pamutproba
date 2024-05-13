<?php declare(strict_types=1);

namespace PamutProba\Entity\Model\Validation;

use PamutProba\Entity\Entity;
use PamutProba\Entity\Project;
use PamutProba\Exception\ValidationException;

class IsProject implements IValidator
{
    /**
     * @throws ValidationException
     */
    public function __invoke(string $field, Entity $entity): void
    {
        if (!($entity instanceof Project))
        {
            throw ValidationException::with(
                "Invalid entity for validation: '" . get_class($entity) . "'. Needs: '" . Project::class . "'"
            );
        }
    }
}