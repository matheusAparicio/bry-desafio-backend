<?php

namespace App\Exceptions;

use App\ErrorCode;
use Exception;

class ExpiredTokenException extends ApiException
{
    public function __construct(string $message = "Your token has expired.")
    {
        parent::__construct(
            message: $message,
            internalCode: ErrorCode::EXPIRED_TOKEN->value,
            status: 401
        );
    }
}
