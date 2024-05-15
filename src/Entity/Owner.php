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
        $id = rand(0, 100);
        return new Owner(
            null,
            "Test Owner #$id",
            "test@test.io$id"
        );
    }
}