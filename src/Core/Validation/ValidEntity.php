<?php declare(strict_types=1);

namespace PamutProba\Core\Validation;

use PamutProba\Core\Entity\Entity;
use PamutProba\Core\Exception\ValidationException;
use PamutProba\Core\Model\Model;

class ValidEntity implements IValidator
{
    public function __construct(
        protected string $entityType
    )
    {}

    public function __invoke(string $field, Entity $entity): void
    {
        try
        {
            Model::for($this->entityType)->validate($entity->$field);
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