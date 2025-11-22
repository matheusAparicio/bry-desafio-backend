<?php

namespace App;

enum ErrorCode: int
{
    case UNAUTHORIZED = 1;
    case FORBIDDEN = 2;
    case RESOURCE_NOT_FOUND = 3;
    case ALREADY_EXISTS = 4;
    case INVALID_VALUE = 5;
    case MISSING_REQUIRED_FIELDS = 6;
    case PAYLOAD_TOO_LARGE = 7;
    case INTERNAL_ERROR = 999;
}
