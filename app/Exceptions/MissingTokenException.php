<?php

namespace App\Exceptions;

use App\ErrorCode;

class MissingTokenException extends ApiException
{
    public function __construct(string $message = "Token missing.")
    {
        parent::__construct(
            message: $message,
            internalCode: ErrorCode::MISSING_TOKEN->value,
            status: 401
        );
    }
}
