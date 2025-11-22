<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{
    public function __construct(
        string $message,
        public int $internalCode,
        public int $status
    ) {
        parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'code'    => $this->internalCode,
            'message' => $this->message,
        ], $this->status);
    }
}
