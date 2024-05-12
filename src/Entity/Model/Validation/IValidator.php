<?php

namespace PamutProba\Entity\Model\Validation;

use PamutProba\Entity\Entity;
use PamutProba\Exception\ValidationException;

interface IValidator
{
    /**
     * @param string $field
     * @param ValidEntity $entity
     * @return void
     * @throws ValidationException
     */
    public function __invoke(string $field, Entity $entity): void;
}