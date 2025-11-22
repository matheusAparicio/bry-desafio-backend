<?php

namespace App\Exceptions;

use App\ErrorCode;

class AlreadyExistsException extends ApiException
{
    public function __construct(string $message = "")
    {
        parent::__construct(
            message: $message,
            internalCode: ErrorCode::ALREADY_EXISTS->value,
            status: 409
        );
    }
}
