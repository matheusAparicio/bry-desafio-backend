<?php

namespace App\Exceptions;

use App\ErrorCode;

class ForbiddenException extends ApiException
{
    public function __construct(string $message = "")
    {
        parent::__construct(
            message: $message,
            internalCode: ErrorCode::FORBIDDEN->value,
            status: 401
        );
    }
}
