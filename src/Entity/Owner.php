<?php declare(strict_types=1);

namespace PamutProba\Entity;

use PamutProba\Core\Entity\Entity;

class Owner extends Entity
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
    )
    {}

    public static function random(): Owner
    {
        return new Owner(
            null,
            "Test Owner",
            "test@test.io"
        );
    }
}