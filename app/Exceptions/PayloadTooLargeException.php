<?php

namespace App\Exceptions;

use App\ErrorCode;

class PayloadTooLargeException extends ApiException
{
    public function __construct(string $message = "Payload too large, maximum size is 8mb.")
    {
        parent::__construct(
            message: $message,
            internalCode: ErrorCode::PAYLOAD_TOO_LARGE->value,
            status: 413
        );
    }
}
