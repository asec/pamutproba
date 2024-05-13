<?php declare(strict_types=1);

namespace PamutProba\Entity;

abstract class Entity
{
    public static function from(array $data): static
    {
        return new static(...$data);
    }

    public function toArray(): array
    {
        return json_decode(json_encode($this), true);
    }

    public static abstract function random(): Entity;
}