<?php

namespace PamutProba\Entity;

class Owner extends Entity
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
    )
    {}

    public static function random(): Owner
    {
        return new Owner(
            rand(1, 100),
            "Test Owner",
            "test@test.io"
        );
    }
}