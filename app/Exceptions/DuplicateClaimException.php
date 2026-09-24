<?php

namespace App\Exceptions;

use App\Models\ResidentTransaction;
use RuntimeException;

/** A program's claim limit has already been reached for this household / resident. */
class DuplicateClaimException extends RuntimeException
{
    public function __construct(public readonly ?ResidentTransaction $priorClaim, string $message)
    {
        parent::__construct($message);
    }
}
