<?php

namespace App\Exceptions;

use App\ErrorCode;

class ResourceNotFoundException extends ApiException
{
    public function __construct(string $message = "Resource not found.")
    {
        parent::__construct(
            message: $message,
            internalCode: ErrorCode::RESOURCE_NOT_FOUND->value,
            status: 404
        );
    }
}
