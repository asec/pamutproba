<?php

namespace PamutProba\App;

use PamutProba\App\Input\MutableInput;

class Session
{
    protected readonly string $flashKey;
    protected MutableInput $data;
    protected MutableInput $flashed;

    public static function start(): bool
    {
        if (session_id())
        {
            return false;
        }

        session_start();
        return true;
    }

    public function __construct(MutableInput $data)
    {
        $this->flashKey = "flashed";
        $this->data = $data;
        try
        {
            $flashed = $this->data->getCopy($this->flashKey) ?? [];
            if (is_string($flashed))
            {
                $flashed = unserialize($flashed);
            }
        }
        catch (\Exception $e)
        {
            $flashed = [];
        }
        $this->flashed = new MutableInput($flashed);
        $this->clearFlash();
    }

    public static function from(array &$data): static
    {
        return new static(new MutableInput($data));
    }

    public function set(string $key, mixed $value): void
    {
        if ($key === $this->flashKey)
        {
            throw new \Exception("You cannot flash into the session usint the set method. Use the flash method instead");
        }
        $this->data->set($key, $value);
    }

    public function has(string $key): bool
    {
        return $this->data->has($key);
    }

    public function get(string $key): mixed
    {
        try
        {
            $value = $this->data->get($key);
        }
        catch (\Exception $e)
        {
            $value = null;
        }

        return $value;
    }

    public function delete(string $key): bool
    {
        if ($key === $this->flashKey)
        {
            throw new \Exception("You cannot delete the flash store. Use the clearFlash method instead.");
        }
        return $this->data->delete($key);
    }

    public function flash(string $key, mixed $value): void
    {
        $flash = $this->get($this->flashKey) ?? [];
        $flash[$key] = $value;
        $this->data->set($this->flashKey, $flash);
    }

    public function clearFlash(): void
    {
        $this->data->set($this->flashKey, []);
    }

    public function getFlashed(string $key): mixed
    {
        try
        {
            $value = $this->flashed->get($key);
            $this->flashed->delete($key);
        }
        catch (\Exception)
        {
            $value = null;
        }

        return $value;
    }

    public function all(): array
    {
        return $this->data->all();
    }
}