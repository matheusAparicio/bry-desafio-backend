<?php

namespace App\Exceptions;

use App\ErrorCode;

class InvalidCredentialsException extends ApiException
{
    public function __construct(string $message = "Invalid login credentials.")
    {
        parent::__construct(
            message: $message,
            internalCode: ErrorCode::INVALID_CREDENTIALS->value,
            status: 401
        );
    }
}
