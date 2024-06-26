<?php declare(strict_types=1);

namespace PamutProba\Core\Utility\Input;

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
        if (!$this->has($key))
        {
            return null;
        }
        return unserialize(serialize($this->data[$key]));
    }
}