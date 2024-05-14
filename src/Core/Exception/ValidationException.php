<?php declare(strict_types=1);

namespace PamutProba\Core\Exception;

use PamutProba\Core\Http\Status;

class ValidationException extends HttpException
{
    public static function with(
        string $message = "",
        ?Status $status = Status::BadRequest,
        ?\Throwable $previous = null
    ): static
    {
        if ($status === null)
        {
            $status = Status::BadRequest;
        }
        return parent::with($message, $status, $previous);
    }
}