<?php declare(strict_types=1);

namespace PamutProba\Core\Entity;

use PamutProba\Core\Utility\Misc;

abstract class Entity
{
    public static function from(array $data): static
    {
        return new static(...$data);
    }

    public static function cast(Entity $entity): static|null
    {
        $className = static::class;
        if (!($entity instanceof $className))
        {
            return null;
        }

        return $entity;
    }

    public function diff(Entity $oldEntity): array
    {
        $className = static::class;
        if (!($oldEntity instanceof $className))
        {
            throw new \Exception("Invalid entity type: '" . get_class($oldEntity) . "'. Must be: '$className'");
        }

        $base = $oldEntity->toArray();
        $current = $this->toArray();

        return Misc::diffEntityArrays($base, $current);
    }

    public function toArray(): array
    {
        return json_decode(json_encode($this), true);
    }

    public static abstract function random(): Entity;
}