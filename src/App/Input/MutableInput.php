<?php

namespace PamutProba\App\Input;

class MutableInput extends ImmutableInput
{
    public function __construct(
        protected array &$data
    )
    {
        parent::__construct($this->data);
    }

    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function delete(string $key): bool
    {
        if (!$this->has($key))
        {
            return false;
        }

        unset($this->data[$key]);

        return true;
    }

    public function getCopy(string $key): mixed
    {
        return unserialize(serialize($this->data[$key]));
    }
}