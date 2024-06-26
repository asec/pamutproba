<?php declare(strict_types=1);

namespace PamutProba\Entity;

use PamutProba\Core\Entity\Entity;

class Status extends Entity
{
    public function __construct(
        public ?int $id,
        public string $key,
        public string $name
    ){}

    public static function random(): Status
    {
        return new Status(
            null,
            "test",
            "Fejlesztésre vár"
        );
    }
}