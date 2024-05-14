<?php declare(strict_types=1);

namespace PamutProba\Core\Exception;

use PamutProba\Core\Http\Status;

class HttpException extends Exception
{
    public static function with(
        string $message = "",
        Status $status = Status::InternalServerError,
        ?\Throwable $previous = null
    ): static
    {
        return new static($message, $status->value, $previous);
    }

    public static function from(\Exception $e): static
    {
        return static::with($e->getMessage());
    }
}