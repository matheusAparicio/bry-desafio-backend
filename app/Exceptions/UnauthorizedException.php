<?php

namespace App\Exceptions;

use App\ErrorCode;

class UnauthorizedException extends ApiException
{
    public function __construct(string $message = "")
    {
        parent::__construct(
            message: $message,
            internalCode: ErrorCode::UNAUTHORIZED->value,
            status: 401
        );
    }
}
