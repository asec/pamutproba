<?php

namespace PamutProba\App;

class Input
{
    public function __construct(
        protected array $data
    ){}

    public function get(string $key): null|string
    {
        if (!array_key_exists($key, $this->data))
        {
            return null;
        }

        return $this->data[$key];
    }

    public function all(): array
    {
        return $this->data;
    }
}