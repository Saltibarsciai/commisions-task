<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Exceptions;

use Exception;
use Throwable;

class CommissionFeeManagerException extends Exception
{
    public function __construct(string $message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
