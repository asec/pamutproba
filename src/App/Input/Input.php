<?php

namespace PamutProba\App\Input;

class Input
{
    public function __construct(
        protected array $data,
        Transformer $transformer = null
    )
    {
        if ($transformer !== null)
        {
            $transformer($this->data);
        }
    }

    /**
     * @throws \Exception
     */
    public function get(string $key): mixed
    {
        if (!array_key_exists($key, $this->data))
        {
            throw new \Exception("Hiányzó kulcs az adatszerkezetben: [{$key}]");
            //return null;
        }

        return $this->data[$key];
    }

    public function all(): array
    {
        return $this->data;
    }
}