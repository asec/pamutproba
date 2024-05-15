<?php declare(strict_types=1);

namespace PamutProba\Core\Validation;

use PamutProba\Core\Database\IDatabaseService;
use PamutProba\Core\Entity\Entity;
use PamutProba\Core\Exception\ValidationException;

interface IValidator
{
    /**
     * @param string $field
     * @param Entity $entity
     * @param IDatabaseService $service
     * @return void
     * @throws ValidationException
     */
    public function __invoke(string $field, Entity $entity, IDatabaseService $service): void;
}