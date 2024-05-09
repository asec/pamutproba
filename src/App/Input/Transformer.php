<?php

namespace PamutProba\App\Input;

abstract class Transformer
{

    public function __invoke(array &$data): void
    {
        foreach ($data as $key => $value)
        {
            $data[$key] = static::transform($key, $value);
        }
    }

    public abstract function transform(string $key, mixed $value): mixed;
}