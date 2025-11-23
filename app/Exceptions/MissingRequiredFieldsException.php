<?php

namespace App\Exceptions;

use App\ErrorCode;

class MissingRequiredFieldsException extends ApiException
{
    public function __construct(string $message = "Missing required fields.")
    {
        parent::__construct(
            message: $message,
            internalCode: ErrorCode::MISSING_REQUIRED_FIELDS->value,
            status: 422
        );
    }
}
