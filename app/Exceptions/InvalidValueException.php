<?php

namespace App\Exceptions;

use App\ErrorCode;

class InvalidValueException extends ApiException
{
    public function __construct(string $message = "")
    {
        parent::__construct(
            message: $message,
            internalCode: ErrorCode::INVALID_VALUE->value,
            status: 401
        );
    }
}
