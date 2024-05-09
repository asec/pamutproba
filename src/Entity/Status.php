<?php

namespace PamutProba\Entity;

class Status extends Entity
{
    public function __construct(
        public int $id,
        public string $key,
        public string $name
    ){}

    public static function random(): Status
    {
        return new Status(
            rand(1, 100),
            "test",
            "Fejlesztésre vár"
        );
    }
}