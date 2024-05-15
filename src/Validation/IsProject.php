<?php declare(strict_types=1);

namespace PamutProba\Validation;

use PamutProba\Core\Database\IDatabaseService;
use PamutProba\Core\Entity\Entity;
use PamutProba\Core\Exception\ValidationException;
use PamutProba\Core\Validation\IValidator;
use PamutProba\Entity\Project;

class IsProject implements IValidator
{
    /**
     * @throws ValidationException
     */
    public function __invoke(string $field, Entity $entity, IDatabaseService $service): void
    {
        if (!($entity instanceof Project))
        {
            throw ValidationException::with(
                "Invalid entity for validation: '" . get_class($entity) . "'. Needs: '" . Project::class . "'"
            );
        }
    }
}