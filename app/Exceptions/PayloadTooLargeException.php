<?php

namespace App\Exceptions;

use App\ErrorCode;

class PayloadTooLargeException extends ApiException
{
    public function __construct(string $message = "")
    {
        parent::__construct(
            message: $message,
            internalCode: ErrorCode::PAYLOAD_TOO_LARGE->value,
            status: 401
        );
    }
}
