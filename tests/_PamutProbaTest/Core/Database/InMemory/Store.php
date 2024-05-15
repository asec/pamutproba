<?php declare(strict_types=1);

namespace _PamutProbaTest\Core\Database\InMemory;

use PamutProba\Core\Utility\Input\MutableInput;
use PamutProba\Database\DatabaseEntityType;

class Store
{
    protected MutableInput $data;

    public function __construct()
    {
        $data = [];
        foreach (DatabaseEntityType::cases() as $entityType)
        {
            $data[$entityType->name] = [];
        }
        $this->data = new MutableInput($data);
    }

    public function get(DatabaseEntityType $type, ?int $id = null): array|null
    {
        try
        {
            $store = $this->data->get($type->name);
            if ($id)
            {
                $store = $store[$id] ?? null;
            }
        }
        catch (\Exception $e)
        {
            return null;
        }

        return $store;
    }

    public function set(DatabaseEntityType $type, array $properties): void
    {
        try
        {
            $store = $this->data->get($type->name);
        }
        catch (\Exception $e)
        {
            $store = [];
        }

        $store[(int) $properties["id"]] = $properties;
        $this->data->set($type->name, $store);
    }

    public function delete(DatabaseEntityType $type, int $id): bool
    {
        if (!$this->get($type, $id))
        {
            return false;
        }

        $store = $this->data->get($type->name);
        unset($store[$id]);
        $this->data->set($type->name, $store);

        return true;
    }

    public function truncate(DatabaseEntityType $type): void
    {
        $this->data->set($type->name, []);
    }

    public function nextId(DatabaseEntityType $type): int
    {
        $store = $this->get($type);
        if (!$store)
        {
            return 1;
        }

        return end($store)["id"] + 1;
    }
}