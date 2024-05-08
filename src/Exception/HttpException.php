<?php

namespace PamutProba\Exception;

use PamutProba\Http\Status;

class HttpException extends \Exception
{
    public function __construct(
        string $message = "",
        Status $code = Status::InternalServerError,
        ?\Throwable $previous = null
    )
    {
        parent::__construct($message, $code->value, $previous);
    }
}