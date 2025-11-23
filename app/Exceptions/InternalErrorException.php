<?php

namespace App\Exceptions;

use App\ErrorCode;

class InternalErrorException extends ApiException
{
    public function __construct(string $message = "An internal error occurred.")
    {
        parent::__construct(
            message: $message,
            internalCode: ErrorCode::INTERNAL_ERROR->value,
            status: 500
        );
    }
}
