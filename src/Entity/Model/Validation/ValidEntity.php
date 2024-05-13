<?php declare(strict_types=1);

namespace PamutProba\Entity\Model\Validation;

use PamutProba\Entity\Entity;
use PamutProba\Entity\Model\Models;
use PamutProba\Exception\ValidationException;

class ValidEntity implements IValidator
{
    public function __construct(
        protected string $entityType
    )
    {}

    public function __invoke(string $field, Entity $entity): void
    {
        $model = Models::get($this->entityType);
        try
        {
            $model->validate($entity->$field);
        }
        catch (ValidationException $e)
        {
            throw ValidationException::with(
                "Invalid field [$field]. Needs to contain a valid '$this->entityType' => " . $e->getMessage(),
                null,
                $e
            );
        }
    }
}