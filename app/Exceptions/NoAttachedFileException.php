<?php

namespace App\Exceptions;

use App\ErrorCode;
use Exception;

class NoAttachedFileException extends ApiException
{
    public function __construct(string $message = "You don't have any file attached.")
    {
        parent::__construct(
            message: $message,
            internalCode: ErrorCode::NO_ATTACHED_FILE->value,
            status: 404
        );
    }
}
