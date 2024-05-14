<?php declare(strict_types=1);

namespace PamutProba\Core\Validation;

use PamutProba\Core\Entity\Entity;
use PamutProba\Core\Exception\ValidationException;

class StringLength implements IValidator
{
    public function __construct(
        protected int $min = 0,
        protected int $max = -1
    )
    {}

    public function __invoke(string $field, Entity $entity): void
    {
        $value = $entity->$field;
        if (!is_string($value))
        {
            throw ValidationException::with("Invalid field [$field]. Needs to be a string");
        }

        if ($this->min > 0 && strlen($value) < $this->min)
        {
            throw ValidationException::with("Invalid field [$field]. Needs to be at least $this->min characters long");
        }
        if ($this->max > -1 && strlen($value) > $this->max)
        {
            throw ValidationException::with("Invalid field [$field]. Cannot be longer than $this->max characters");
        }
    }
}