<?php

namespace PamutProba\Entity;

abstract class Entity
{
    public static function from(array $data): static
    {
        return new static(...$data);
    }

    public static abstract function random(): Entity;
}