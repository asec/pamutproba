<?php

namespace PamutProba\Exception;

class Exception extends \Exception implements \JsonSerializable
{
    public function jsonSerialize(): array
    {
        return [
            "code" => $this->getCode(),
            "message" => $this->getMessage()
        ];
    }

    public static function from(\Exception $e): static
    {
        return new static($e->getMessage(), $e->getCode(), $e);
    }
}