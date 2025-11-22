<?php

namespace App\Exceptions;

use App\ErrorCode;

class InternalErrorException extends ApiException
{
    public function __construct(string $message = "")
    {
        parent::__construct(
            message: $message,
            internalCode: ErrorCode::INTERNAL_ERROR->value,
            status: 401
        );
    }
}
