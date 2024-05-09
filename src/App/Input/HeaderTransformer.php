<?php

namespace PamutProba\App\Input;

class HeaderTransformer extends Transformer
{
    public function transform(string $key, mixed $value): mixed
    {
        if ($key === "REQUEST_URI")
        {
            $value = $this->transformRequestUri($value);
        }
        return $value;
    }

    protected function transformRequestUri(string $value): string
    {
        $value = strtok($value, "?");
        $valueElements = explode("/", $value);
        while (end($valueElements) === "")
        {
            array_pop($valueElements);
        }
        return count($valueElements) === 0 ? "/" : implode("/", $valueElements);
    }
}