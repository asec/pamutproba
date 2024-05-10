<?php

namespace PamutProba\App\Input;

readonly class Input
{


    public function __construct(
        protected array $data
    )
    {}

    /**
     * @throws \Exception
     */
    public function get(string $key): mixed
    {
        if (!$this->has($key))
        {
            throw new \Exception("Missing key in the structure: [{$key}]");
        }

        return $this->data[$key];
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function all(): array
    {
        return $this->data;
    }
}