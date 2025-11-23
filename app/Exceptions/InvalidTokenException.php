<?php

namespace App\Exceptions;

use App\ErrorCode;
use Exception;

class InvalidTokenException extends ApiException
{
    public function __construct(string $message = "Your token is invalid.")
    {
        parent::__construct(
            message: $message,
            internalCode: ErrorCode::INVALID_TOKEN->value,
            status: 401
        );
    }
}
